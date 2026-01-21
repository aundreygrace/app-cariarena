<?php

namespace App\Http\Controllers\Venue;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\VenueReportExport;
use Maatwebsite\Excel\Facades\Excel;


class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Dapatkan venue yang dimiliki oleh user ini berdasarkan user_id
        $userVenues = DB::table('venues')
            ->where('user_id', $user->id)
            ->get(['id', 'name']);
        
        // 1. Jika user tidak memiliki venue, kembalikan data kosong
        if ($userVenues->isEmpty()) {
            return view('venue.report', [
                'transactions' => collect([]),
                'totalPemesanan' => 0,
                'totalPendapatan' => 0,
                'rataPemesananPerHari' => 0,
                'venueTerpopuler' => 'Tidak ada venue',
                'jumlahPemesananVenue' => 0,
                'monthlyRevenue' => array_fill(0, 12, 0),
                'venueDistribution' => [
                    'labels' => ['Belum ada data'],
                    'data' => [100]
                ]
            ]);
        }
        
        // Dapatkan daftar nama venue untuk filtering
        $venueNames = $userVenues->pluck('name')->toArray();
        $venueIds = $userVenues->pluck('id')->toArray();
        
        // 2. Ambil data transaksi dari booking yang terkait dengan venue user
        $transactions = $this->getTransactions($request, $venueIds, $venueNames);
        
        // 3. Hitung statistik berdasarkan booking
        $stats = $this->getStatistics($request, $venueIds, $venueNames);
        
        // 4. Data untuk chart
        $chartData = $this->getChartData($request, $venueIds, $venueNames);
        
        // 5. Gabungkan semua data
        $data = array_merge(['transactions' => $transactions], $stats, $chartData);

        
        return view('venue.report', $data);
    }
    
    private function getTransactions(Request $request, array $venueIds, array $venueNames)
    {
        $query = DB::table('booking')
            ->select(
                'booking.id',
                'booking.booking_code as transaction_number',
                'booking.user_id as customer_id',
                'booking.nama_customer as pengguna',
                'venues.name as nama_venue',
                DB::raw("CASE 
                    WHEN booking.status = 'Terkonfirmasi' THEN 'completed'
                    WHEN booking.status = 'Menunggu' THEN 'pending'
                    WHEN booking.status = 'Dibatalkan' THEN 'cancelled'
                    ELSE booking.status 
                END as status"),
                'booking.total_biaya as amount',
                'booking.tanggal_booking as transaction_date',
                'booking.durasi',
                'booking.created_at',
                'booking.updated_at'
            )
            ->join('venues', 'booking.venue_id', '=', 'venues.id')
            ->whereIn('booking.venue_id', $venueIds);
        
        // Filter periode
        $this->applyDateFilter($query, $request, 'booking.tanggal_booking');
        
        // Filter status - konversi status booking ke status transaksi
        if ($request->has('status') && $request->status != '') {
            $status = $request->status;
            $bookingStatus = $this->convertToBookingStatus($status);
            
            if ($bookingStatus) {
                $query->where('booking.status', $bookingStatus);
            }
        }
        
        // Tambahkan data dari tabel transactions jika ada
        $transactionsQuery = DB::table('transactions')
            ->select(
                'transactions.id',
                'transactions.transaction_number',
                'transactions.customer_id',
                'transactions.pengguna',
                'transactions.nama_venue',
                'transactions.status',
                'transactions.amount',
                'transactions.transaction_date',
                DB::raw('2 as durasi'), // Default durasi untuk transaksi
                'transactions.created_at',
                'transactions.updated_at'
            )
            ->whereIn('transactions.nama_venue', $venueNames);
        
        // Filter periode untuk transactions
        if ($request->has('periode') || $request->has('status')) {
            $this->applyDateFilter($transactionsQuery, $request, 'transactions.transaction_date');
            
            if ($request->has('status') && $request->status != '') {
                $transactionsQuery->where('transactions.status', $request->status);
            }
        }
        
        // Gabungkan hasil dari booking dan transactions
        $unionQuery = $query->union($transactionsQuery);
        
        // Dapatkan query union dengan pagination
        $transactions = DB::query()->fromSub($unionQuery, 'combined')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();
            
        return $transactions;
    }
    
    private function convertToBookingStatus($transactionStatus)
    {
        $statusMap = [
            'completed' => 'Terkonfirmasi',
            'pending' => 'Menunggu',
            'cancelled' => 'Dibatalkan',
            'refunded' => 'Dibatalkan' // Asumsi: refunded dianggap cancelled di booking
        ];
        
        return $statusMap[$transactionStatus] ?? null;
    }
    
    private function getStatistics(Request $request, array $venueIds, array $venueNames)
    {
        // Total booking dari tabel booking
        $totalBookingQuery = DB::table('booking')
            ->whereIn('venue_id', $venueIds);
        $this->applyDateFilter($totalBookingQuery, $request, 'tanggal_booking');
        $totalBooking = $totalBookingQuery->count();
        
        // Total booking dari tabel transactions
        $totalTransactionQuery = DB::table('transactions')
            ->whereIn('nama_venue', $venueNames);
        $this->applyDateFilter($totalTransactionQuery, $request, 'transaction_date');
        $totalTransaction = $totalTransactionQuery->count();
        
        $totalPemesanan = $totalBooking + $totalTransaction;
        
        // Total pendapatan dari booking (status Terkonfirmasi)
        $revenueBookingQuery = DB::table('booking')
            ->whereIn('venue_id', $venueIds)
            ->where('booking.status', 'Terkonfirmasi'); // PERBAIKAN: tambah prefix tabel
        $this->applyDateFilter($revenueBookingQuery, $request, 'tanggal_booking');
        $totalPendapatanBooking = $revenueBookingQuery->sum('total_biaya');
        
        // Total pendapatan dari transactions (status completed)
        $revenueTransactionQuery = DB::table('transactions')
            ->whereIn('nama_venue', $venueNames)
            ->where('status', 'completed');
        $this->applyDateFilter($revenueTransactionQuery, $request, 'transaction_date');
        $totalPendapatanTransaction = $revenueTransactionQuery->sum('amount');
        
        $totalPendapatan = $totalPendapatanBooking + $totalPendapatanTransaction;
        
        // Rata-rata pemesanan per hari
        $daysInPeriod = $this->getDaysInPeriod($request);
        $rataPemesananPerHari = $daysInPeriod > 0 ? 
            round($totalPemesanan / $daysInPeriod, 1) : 0;
        
        // Venue terpopuler berdasarkan jumlah booking (tanpa filter status)
        $venuePopularity = DB::table('booking')
            ->select('venues.name as nama_venue', DB::raw('COUNT(*) as total'))
            ->join('venues', 'booking.venue_id', '=', 'venues.id')
            ->whereIn('booking.venue_id', $venueIds);
        $this->applyDateFilter($venuePopularity, $request, 'booking.tanggal_booking');
        
        $popularVenue = $venuePopularity
            ->groupBy('venues.name')
            ->orderBy('total', 'desc')
            ->first();
            
        $jumlahPemesananVenue = $popularVenue->total ?? 0;
        
        return [
            'totalPemesanan' => $totalPemesanan,
            'totalPendapatan' => $totalPendapatan,
            'rataPemesananPerHari' => $rataPemesananPerHari,
            'venueTerpopuler' => $popularVenue->nama_venue ?? 'Tidak ada data',
            'jumlahPemesananVenue' => $jumlahPemesananVenue
        ];
    }
    
    private function getChartData(Request $request, array $venueIds, array $venueNames)
    {
        $currentYear = Carbon::now()->year;
        $monthlyRevenue = [];
        
        for ($month = 1; $month <= 12; $month++) {
            // Pendapatan dari booking
            $revenueBooking = DB::table('booking')
                ->whereIn('venue_id', $venueIds)
                ->where('booking.status', 'Terkonfirmasi') // PERBAIKAN: tambah prefix tabel
                ->whereYear('tanggal_booking', $currentYear)
                ->whereMonth('tanggal_booking', $month)
                ->sum('total_biaya');
            
            // Pendapatan dari transactions
            $revenueTransaction = DB::table('transactions')
                ->whereIn('nama_venue', $venueNames)
                ->where('status', 'completed')
                ->whereYear('transaction_date', $currentYear)
                ->whereMonth('transaction_date', $month)
                ->sum('amount');
            
            $totalRevenue = $revenueBooking + $revenueTransaction;
            
            // Konversi ke juta untuk tampilan chart
            $monthlyRevenue[] = round($totalRevenue / 1000000, 1);
        }
        
        // Data distribusi venue untuk periode filter dari booking
        $venueDistributionBooking = DB::table('booking')
            ->select('venues.name as nama_venue', DB::raw('COUNT(*) as total'))
            ->join('venues', 'booking.venue_id', '=', 'venues.id')
            ->whereIn('booking.venue_id', $venueIds)
            ->where('booking.status', 'Terkonfirmasi'); // PERBAIKAN: tambah prefix tabel
        
        $this->applyDateFilter($venueDistributionBooking, $request, 'booking.tanggal_booking');
        
        $bookingDistribution = $venueDistributionBooking
            ->groupBy('venues.name')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
        
        // Data distribusi venue dari transactions
        $transactionDistribution = DB::table('transactions')
            ->select('nama_venue', DB::raw('COUNT(*) as total'))
            ->whereIn('nama_venue', $venueNames)
            ->where('status', 'completed');
        
        $this->applyDateFilter($transactionDistribution, $request, 'transaction_date');
        
        $transDistribution = $transactionDistribution
            ->groupBy('nama_venue')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
        
        // Gabungkan dan hitung total per venue
        $venueTotals = [];
        
        foreach ($bookingDistribution as $item) {
            $venueName = $item->nama_venue;
            $venueTotals[$venueName] = ($venueTotals[$venueName] ?? 0) + $item->total;
        }
        
        foreach ($transDistribution as $item) {
            $venueName = $item->nama_venue;
            $venueTotals[$venueName] = ($venueTotals[$venueName] ?? 0) + $item->total;
        }
        
        // Urutkan berdasarkan total
        arsort($venueTotals);
        
        $venueLabels = [];
        $venueData = [];
        
        foreach ($venueTotals as $venueName => $total) {
            $venueLabels[] = $venueName;
            $venueData[] = $total;
            
            if (count($venueLabels) >= 5) {
                break;
            }
        }
        
        // Jika tidak ada data, beri nilai default
        if (empty($venueLabels)) {
            $venueLabels = ['Belum ada data'];
            $venueData = [100];
        }
        
        return [
            'monthlyRevenue' => $monthlyRevenue,
            'venueDistribution' => [
                'labels' => $venueLabels,
                'data' => $venueData
            ]
        ];
    }
    
    private function applyDateFilter(&$query, Request $request, $dateColumn)
    {
        if (!$request->has('periode') || $request->periode == '') {
            $request->periode = 'minggu-ini';
        }
        
        $today = Carbon::now();
        
        switch ($request->periode) {
            case 'hari-ini':
                $query->whereDate($dateColumn, $today->toDateString());
                break;
            case 'minggu-ini':
                $query->whereBetween($dateColumn, [
                    $today->copy()->startOfWeek()->toDateString(),
                    $today->copy()->endOfWeek()->toDateString()
                ]);
                break;
            case 'bulan-ini':
                $query->whereMonth($dateColumn, $today->month)
                    ->whereYear($dateColumn, $today->year);
                break;
            case 'tahun-ini':
                $query->whereYear($dateColumn, $today->year);
                break;
            case 'custom':
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $query->whereBetween($dateColumn, [
                        $request->start_date,
                        $request->end_date
                    ]);
                }
                break;
        }
    }
    
    private function getDaysInPeriod(Request $request)
    {
        if (!$request->has('periode') || $request->periode == '') {
            $request->periode = 'minggu-ini';
        }
        
        $today = Carbon::now();
        
        switch ($request->periode) {
            case 'hari-ini':
                return 1;
            case 'minggu-ini':
                return 7;
            case 'bulan-ini':
                return $today->daysInMonth;
            case 'tahun-ini':
                return $today->isLeapYear() ? 366 : 365;
            case 'custom':
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $start = Carbon::parse($request->start_date);
                    $end = Carbon::parse($request->end_date);
                    return $start->diffInDays($end) + 1;
                }
                return 7;
            default:
                return 7;
        }
    }
    
    public function show($id)
    {
        $user = Auth::user();
        
        // Dapatkan venue user
        $userVenues = DB::table('venues')
            ->where('user_id', $user->id)
            ->get(['id', 'name']);
        
        if ($userVenues->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki venue'
            ], 404);
        }
        
        $venueIds = $userVenues->pluck('id')->toArray();
        $venueNames = $userVenues->pluck('name')->toArray();
        
        // Cari data di booking terlebih dahulu
        $bookingData = DB::table('booking')
            ->select(
                'booking.id',
                'booking.booking_code as transaction_number',
                'booking.nama_customer as pengguna',
                'venues.name as nama_venue',
                'booking.total_biaya as amount',
                'booking.tanggal_booking as transaction_date',
                'booking.durasi',
                'booking.catatan',
                DB::raw("CASE 
                    WHEN booking.status = 'Terkonfirmasi' THEN 'completed'
                    WHEN booking.status = 'Menunggu' THEN 'pending'
                    WHEN booking.status = 'Dibatalkan' THEN 'cancelled'
                    ELSE booking.status 
                END as status"),
                DB::raw("'Booking System' as metode_pembayaran"),
                'booking.created_at',
                'booking.updated_at'
            )
            ->join('venues', 'booking.venue_id', '=', 'venues.id')
            ->where('booking.id', $id)
            ->whereIn('booking.venue_id', $venueIds)
            ->first();
        
        if ($bookingData) {
            return response()->json([
                'success' => true,
                'data' => $bookingData
            ]);
        }
        
        // Jika tidak ditemukan di booking, cari di transactions
        $transactionData = DB::table('transactions')
            ->select(
                'transactions.*',
                DB::raw('2 as durasi')
            )
            ->where('transactions.id', $id)
            ->whereIn('transactions.nama_venue', $venueNames)
            ->first();
        
        if ($transactionData) {
            return response()->json([
                'success' => true,
                'data' => $transactionData
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Transaksi tidak ditemukan atau tidak memiliki akses'
        ], 404);
    }
    
    public function chartData(Request $request)
    {
        $user = Auth::user();
        
        $userVenues = DB::table('venues')
            ->where('user_id', $user->id)
            ->get(['id', 'name']);
        
        if ($userVenues->isEmpty()) {
            return response()->json([
                'success' => true,
                'revenueData' => array_fill(0, 12, 0),
                'revenueLabels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                'venueLabels' => ['Belum ada data'],
                'venueData' => [100]
            ]);
        }
        
        $venueIds = $userVenues->pluck('id')->toArray();
        $venueNames = $userVenues->pluck('name')->toArray();
        
        $period = $request->get('period', 'bulanan');
        
        if ($period === 'mingguan') {
            // Data mingguan untuk 7 hari terakhir dari booking dan transactions
            $weeklyData = [];
            $weeklyLabels = [];
            
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $dateStr = $date->toDateString();
                
                // Revenue dari booking
                $revenueBooking = DB::table('booking')
                    ->whereIn('venue_id', $venueIds)
                    ->where('booking.status', 'Terkonfirmasi') // PERBAIKAN: tambah prefix tabel
                    ->whereDate('tanggal_booking', $dateStr)
                    ->sum('total_biaya');
                
                // Revenue dari transactions
                $revenueTransaction = DB::table('transactions')
                    ->whereIn('nama_venue', $venueNames)
                    ->where('status', 'completed')
                    ->whereDate('transaction_date', $dateStr)
                    ->sum('amount');
                
                $totalRevenue = $revenueBooking + $revenueTransaction;
                
                $weeklyData[] = round($totalRevenue / 1000, 0); // Dalam ribuan
                $weeklyLabels[] = $date->translatedFormat('D');
            }
            
            return response()->json([
                'success' => true,
                'revenueData' => $weeklyData,
                'revenueLabels' => $weeklyLabels,
                'venueLabels' => [],
                'venueData' => []
            ]);
        } else {
            // Data bulanan untuk tahun ini
            $currentYear = Carbon::now()->year;
            $monthlyData = [];
            
            for ($month = 1; $month <= 12; $month++) {
                // Revenue dari booking
                $revenueBooking = DB::table('booking')
                    ->whereIn('venue_id', $venueIds)
                    ->where('booking.status', 'Terkonfirmasi') // PERBAIKAN: tambah prefix tabel
                    ->whereYear('tanggal_booking', $currentYear)
                    ->whereMonth('tanggal_booking', $month)
                    ->sum('total_biaya');
                
                // Revenue dari transactions
                $revenueTransaction = DB::table('transactions')
                    ->whereIn('nama_venue', $venueNames)
                    ->where('status', 'completed')
                    ->whereYear('transaction_date', $currentYear)
                    ->whereMonth('transaction_date', $month)
                    ->sum('amount');
                
                $totalRevenue = $revenueBooking + $revenueTransaction;
                
                $monthlyData[] = round($totalRevenue / 1000000, 1); // Dalam juta
            }
            
            // Data distribusi venue dari booking
            $bookingDistribution = DB::table('booking')
                ->select('venues.name as nama_venue', DB::raw('COUNT(*) as total'))
                ->join('venues', 'booking.venue_id', '=', 'venues.id')
                ->whereIn('booking.venue_id', $venueIds)
                ->where('booking.status', 'Terkonfirmasi') // PERBAIKAN: tambah prefix tabel
                ->whereYear('booking.tanggal_booking', $currentYear)
                ->groupBy('venues.name')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get();
            
            // Data distribusi venue dari transactions
            $transactionDistribution = DB::table('transactions')
                ->select('nama_venue', DB::raw('COUNT(*) as total'))
                ->whereIn('nama_venue', $venueNames)
                ->where('status', 'completed')
                ->whereYear('transaction_date', $currentYear)
                ->groupBy('nama_venue')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get();
            
            // Gabungkan dan hitung total per venue
            $venueTotals = [];
            
            foreach ($bookingDistribution as $item) {
                $venueName = $item->nama_venue;
                $venueTotals[$venueName] = ($venueTotals[$venueName] ?? 0) + $item->total;
            }
            
            foreach ($transactionDistribution as $item) {
                $venueName = $item->nama_venue;
                $venueTotals[$venueName] = ($venueTotals[$venueName] ?? 0) + $item->total;
            }
            
            arsort($venueTotals);
            
            $venueLabels = [];
            $venueData = [];
            
            foreach ($venueTotals as $venueName => $total) {
                $venueLabels[] = $venueName;
                $venueData[] = $total;
                
                if (count($venueLabels) >= 5) {
                    break;
                }
            }
            
            if (empty($venueLabels)) {
                $venueLabels = ['Belum ada data'];
                $venueData = [100];
            }
            
            return response()->json([
                'success' => true,
                'revenueData' => $monthlyData,
                'revenueLabels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                'venueLabels' => $venueLabels,
                'venueData' => $venueData
            ]);
        }
    }
    
    public function exportExcel(Request $request)
    {
        $user = Auth::user();
    
        $userVenues = DB::table('venues')
            ->where('user_id', $user->id)
            ->get(['id', 'name']);
    
        if ($userVenues->isEmpty()) {
            return back()->with('error', 'Venue tidak ditemukan');
        }
    
        $venueIds   = $userVenues->pluck('id')->toArray();
        $venueNames = $userVenues->pluck('name')->toArray();
    
        $query = $this->baseExportQuery($request, $venueIds, $venueNames);
    
        $rows = $query
            ->orderBy('tanggal', 'desc')
            ->get();
    
        $data = [];
        $no = 1;
    
        foreach ($rows as $row) {
            $data[] = [
                $no++,
                $row->kode,
                $row->customer,
                $row->venue,
                $row->metode ?? '-',
                number_format($row->jumlah, 0, ',', '.'),
                $row->tanggal,
                ($row->durasi ?? 0) . ' jam',
                ucfirst($row->status)
            ];
        }
    
        $totalPendapatan = $rows->sum('jumlah');
        $totalTransaksi  = $rows->count();
    
        return Excel::download(
            new \App\Exports\VenueReportExport($data, $totalPendapatan, $totalTransaksi),
            'laporan_venue.xlsx'
        );
    }
    

    
    public function exportPdf(Request $request)
{
    $user = Auth::user();
    $periode = $request->query('periode', 'Semua Periode');

    $userVenues = DB::table('venues')
        ->where('user_id', $user->id)
        ->get(['id', 'name']);

    if ($userVenues->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'Anda tidak memiliki venue'
        ]);
    }

    $venueIds   = $userVenues->pluck('id')->toArray();
    $venueNames = $userVenues->pluck('name')->toArray();

    // Query booking data WITH metode field
    $bookingQuery = DB::table('booking')
        ->select(
            'booking.booking_code as kode',
            'booking.nama_customer as pengguna',
            'venues.name as nama_venue',
            DB::raw("'Booking System' as metode"), // <-- ADD THIS
            DB::raw("
                CASE
                    WHEN booking.status IN ('Selesai','Terkonfirmasi')
                        THEN 'completed'
                    WHEN booking.status = 'Menunggu'
                        THEN 'pending'
                    WHEN booking.status = 'Dibatalkan'
                        THEN 'cancelled'
                    ELSE LOWER(booking.status)
                END AS status
            "),
            'booking.total_biaya as amount',
            'booking.tanggal_booking as transaction_date',
            'booking.durasi'
        )
        ->join('venues', 'booking.venue_id', '=', 'venues.id')
        ->whereIn('booking.venue_id', $venueIds);

    $this->applyDateFilter($bookingQuery, $request, 'booking.tanggal_booking');

    // Query transaction data
    $transactionQuery = DB::table('transactions')
        ->select(
            'transaction_number as kode',
            'pengguna',
            'nama_venue',
            'metode_pembayaran as metode', // <-- This already exists
            'amount',
            'transaction_date',
            DB::raw('2 as durasi'),
            DB::raw("CASE 
                WHEN status = 'completed' THEN 'Selesai'
                WHEN status = 'pending' THEN 'Pending'
                WHEN status = 'cancelled' THEN 'Dibatalkan'
                WHEN status = 'refunded' THEN 'Dikembalikan'
                ELSE status 
            END as status")
        )
        ->whereIn('nama_venue', $venueNames);

    $this->applyDateFilter($transactionQuery, $request, 'transaction_date');

    $bookingData = $bookingQuery->get();
    $transactionData = $transactionQuery->get();
    
    // Combine and rename columns for consistency
    $allData = $bookingData->merge($transactionData)->map(function($item) {
        // Standardize field names
        return (object)[
            'kode' => $item->kode ?? null,
            'pengguna' => $item->pengguna ?? null,
            'nama_venue' => $item->nama_venue ?? null,
            'metode' => $item->metode ?? '-', // Ensure metode exists
            'status' => $item->status ?? null,
            'amount' => $item->amount ?? 0,
            'transaction_date' => $item->transaction_date ?? null,
            'durasi' => $item->durasi ?? 0
        ];
    });

    if ($allData->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'Tidak ada data untuk diexport'
        ]);
    }

    $tanggalExport = now()->format('d-m-Y H:i');
    $totalPendapatan = $allData->sum('amount');
    $totalTransaksi  = $allData->count();
    $days = $this->getDaysInPeriod($request);
    $rataPerHari = $days > 0 ? round($totalTransaksi / $days, 1) : 0;

    $chart = $this->getChartData($request, $venueIds, $venueNames);

    // Generate chart images
    $revenueChartImg = $this->generateChartImage(
        $chart['monthlyRevenue'],
        ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
        'Pendapatan Bulanan (Juta Rp)',
        'bar',
        500,
        250
    );

    $venueChartImg = $this->generateChartImage(
        $chart['venueDistribution']['data'],
        $chart['venueDistribution']['labels'],
        'Distribusi Venue',
        'doughnut',
        350,
        250
    );

    // Ambil venue terpopuler
    $venuePop = DB::table('booking')
        ->select('venues.name as nama_venue', DB::raw('COUNT(*) as total'))
        ->join('venues', 'booking.venue_id', '=', 'venues.id')
        ->whereIn('booking.venue_id', $venueIds)
        ->groupBy('venues.name')
        ->orderByDesc('total')
        ->first();

    $venueTerpopuler = $venuePop->nama_venue ?? '-';
    $jumlahPemesananVenue = $venuePop->total ?? 0;

    // Render PDF
    $html = view('venue.exports.pdf', [
        'data'                  => $allData,
        'user'                  => $user,
        'periode'               => $periode,
        'tanggalExport'         => $tanggalExport,
        'totalPendapatan'       => $totalPendapatan,
        'totalTransaksi'        => $totalTransaksi,
        'revenueChartImg'       => $revenueChartImg,
        'venueChartImg'         => $venueChartImg,
        'rataRata'              => $rataPerHari,
        'venueTerpopuler'       => $venueTerpopuler,
        'jumlahPemesananVenue'  => $jumlahPemesananVenue
    ])->render();

    return Pdf::loadHTML($html)
        ->setPaper('A4', 'portrait')
        ->setOption('isHtml5ParserEnabled', true)
        ->setOption('isRemoteEnabled', true)
        ->setOption('isPhpEnabled', true)
        ->setOption('defaultFont', 'DejaVu Sans')
        ->setOption('chroot', public_path())
        ->setOption('enable_css_float', true)
        ->setOption('enable_flexbox', true)
        ->download('laporan_venue_' . date('Ymd_His') . '.pdf');
}
    


    public function downloadFile($filename)
    {
        $filePath = storage_path("app/public/exports/{$filename}");
    
        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }
    
        return response()->download($filePath);
    }
    


    public function cleanupExport(Request $request)
    {
        $files = Storage::disk('public')->files('exports');
        $now = time();
        
        foreach ($files as $file) {
            $fileTime = Storage::disk('public')->lastModified($file);
            // Hapus file yang lebih dari 1 jam
            if (($now - $fileTime) > 3600) {
                Storage::delete($file);
            }
        }
        
        return response()->json(['success' => true]);
    }



private function baseExportQuery(Request $request, array $venueIds, array $venueNames)
{
    $booking = DB::table('booking')
        ->select(
            'booking.booking_code as kode',
            'booking.nama_customer as customer',
            'venues.name as venue',
            DB::raw("'Booking System' as metode"),
            'booking.total_biaya as jumlah',
            'booking.tanggal_booking as tanggal',
            'booking.durasi',
            DB::raw("
                CASE 
                    WHEN booking.status = 'Terkonfirmasi' THEN 'completed'
                    WHEN booking.status = 'Menunggu' THEN 'pending'
                    WHEN booking.status = 'Dibatalkan' THEN 'cancelled'
                    ELSE booking.status 
                END as status
            ")
        )
        ->join('venues', 'booking.venue_id', '=', 'venues.id')
        ->whereIn('booking.venue_id', $venueIds);

    $this->applyDateFilter($booking, $request, 'booking.tanggal_booking');

    if ($request->filled('status')) {
        $map = $this->convertToBookingStatus($request->status);
        if ($map) {
            $booking->where('booking.status', $map);
        }
    }

    $transactions = DB::table('transactions')
        ->select(
            'transaction_number as kode',
            'pengguna as customer',
            'nama_venue as venue',
            'metode_pembayaran as metode',
            'amount as jumlah',
            'transaction_date as tanggal',
            DB::raw('2 as durasi'),
            'status'
        )
        ->whereIn('nama_venue', $venueNames);

    $this->applyDateFilter($transactions, $request, 'transaction_date');

    if ($request->filled('status')) {
        $transactions->where('status', $request->status);
    }

    return DB::query()->fromSub(
        $booking->unionAll($transactions),
        'all_data'
    );
}


private function generateChartImage($data, $labels, $title, $type = 'bar', $width = 500, $height = 250)
{
    // Untuk doughnut chart, pastikan aspect ratio 1:1 agar tidak gepeng
    if ($type === 'doughnut') {
        $width = 350;  // Lebar lebih kecil untuk doughnut
        $height = 250; // Tinggi disesuaikan
    }
    
    $chartConfig = [
        'type' => $type,
        'data' => [
            'labels' => $labels,
            'datasets' => [[
                'label' => $title,
                'data' => $data,
                'borderWidth' => 1
            ]]
        ],
        'options' => [
            'responsive' => false,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    'labels' => [
                        'boxWidth' => 10,
                        'font' => [
                            'size' => 9
                        ]
                    ]
                ]
            ]
        ]
    ];

    // Berikan warna berdasarkan tipe chart
    if ($type === 'bar') {
        $chartConfig['data']['datasets'][0]['backgroundColor'] = 'rgba(99, 179, 237, 0.6)';
        $chartConfig['data']['datasets'][0]['borderColor'] = 'rgba(99, 179, 237, 1)';
        $chartConfig['options']['scales'] = [
            'y' => [
                'beginAtZero' => true,
                'ticks' => [
                    'callback' => 'function(value) { return "Rp " + value + " jt"; }',
                    'font' => [
                        'size' => 8
                    ]
                ]
            ],
            'x' => [
                'ticks' => [
                    'font' => [
                        'size' => 8
                    ]
                ]
            ]
        ];
    } else { // doughnut/pie
        $chartConfig['data']['datasets'][0]['backgroundColor'] = [
            'rgba(99, 179, 237, 0.6)',     // Biru
            'rgba(34, 197, 94, 0.6)',      // Hijau
            'rgba(59, 130, 246, 0.6)',     // Biru tua
            'rgba(245, 158, 11, 0.6)',     // Kuning
            'rgba(139, 92, 246, 0.6)',     // Ungu
            'rgba(236, 72, 153, 0.6)',     // Pink
            'rgba(16, 185, 129, 0.6)',     // Hijau muda
        ];
        
        // Untuk doughnut, tambahkan aspect ratio yang benar
        $chartConfig['options']['aspectRatio'] = 1.4; // Lebih proporsional
    }

    $url = 'https://quickchart.io/chart?width=' . $width . '&height=' . $height . '&c=' . urlencode(json_encode($chartConfig));

    try {
        $image = file_get_contents($url, false, stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
            'http' => [
                'timeout' => 30
            ]
        ]));

        if ($image === false) {
            // Fallback: create a simple placeholder
            return 'data:image/svg+xml;base64,' . base64_encode('
                <svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '">
                    <rect width="100%" height="100%" fill="#f8f9fa"/>
                    <text x="50%" y="50%" text-anchor="middle" fill="#6c757d" font-family="Arial" font-size="12">
                        Chart: ' . htmlspecialchars($title) . '
                    </text>
                </svg>
            ');
        }

        return 'data:image/png;base64,' . base64_encode($image);
    } catch (\Exception $e) {
        // Return placeholder jika error
        return 'data:image/svg+xml;base64,' . base64_encode('
            <svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '">
                <rect width="100%" height="100%" fill="#f8f9fa"/>
                <text x="50%" y="50%" text-anchor="middle" fill="#6c757d" font-family="Arial" font-size="12">
                    Chart tidak tersedia
                </text>
            </svg>
        ');
    }
}

}