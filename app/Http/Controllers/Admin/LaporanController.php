<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Pemesanan;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Excel;
use App\Exports\AdminReportExport;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman laporan utama
     */
    public function index(Request $request)
    {
        try {
            // Ambil parameter filter
            $periode = $request->get('periode', 'minggu-ini');
            $jenisLaporan = $request->get('jenis_laporan', 'semua');
            $status = $request->get('status', '');
            
            Log::info('LaporanController@index - Filter:', [
                'periode' => $periode,
                'jenis_laporan' => $jenisLaporan,
                'status' => $status
            ]);
            
            // ========== STATISTIK DARI GABUNGAN DATA ==========
            
            // **1. Total Pemesanan** - dari gabungan booking dan transactions
            $totalPemesanan = $this->getTotalPemesanan($request);
            
            // **2. Total Pendapatan** - dari gabungan booking (Terkonfirmasi) dan transactions (completed)
            $totalPendapatan = $this->getTotalPendapatan($request);
            
            // **3. Rata-rata pemesanan per hari**
            $rataPemesananPerHari = $this->getRataPemesananPerHari($request, $totalPemesanan);
            
            // **4. Venue terpopuler** - dari booking
            $venueTerpopulerData = $this->getVenueTerpopuler($request);
            $venueTerpopuler = $venueTerpopulerData['name'] ?? 'Venue Umum';
            $jumlahPemesananVenue = $venueTerpopulerData['count'] ?? 0;
            
            // ========== CHART DATA ==========
            
            // Data untuk grafik pendapatan bulanan
            $monthlyRevenue = $this->getMonthlyRevenue($request);
            
            // Data distribusi booking berdasarkan venue
            $venueDistribution = $this->getBookingDistribution($request);
            
            // ========== TABEL DATA ==========
            
            // Data gabungan untuk tabel
            $transactions = $this->getCombinedData($request)->paginate(10);
            
            // Tambahkan parameter filter ke pagination links
            $transactions->appends([
                'periode' => $periode,
                'jenis_laporan' => $jenisLaporan,
                'status' => $status
            ]);
            
            Log::info('LaporanController@index - Data ditemukan:', [
                'total_transactions' => $transactions->total(),
                'total_pemesanan' => $totalPemesanan,
                'total_pendapatan' => $totalPendapatan
            ]);
            
            return view('admin.laporan', compact(
                'totalPemesanan',
                'totalPendapatan',
                'rataPemesananPerHari',
                'venueTerpopuler',
                'jumlahPemesananVenue',
                'monthlyRevenue',
                'venueDistribution',
                'transactions',
                'periode',
                'jenisLaporan',
                'status'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error in LaporanController@index: ' . $e->getMessage() . ' - Trace: ' . $e->getTraceAsString());
            return redirect()->route('admin.dashboard')->with('error', 'Gagal memuat laporan: ' . $e->getMessage());
        }
    }
    
    /**
     * Method untuk mendapatkan data gabungan dari booking dan transactions
     */
    private function getCombinedData(Request $request)
    {
        Log::debug('getCombinedData - Mulai menggabungkan data');
        
        // Query untuk data booking
        $bookingQuery = DB::table('booking')
            ->select(
                'booking.id',
                'booking.booking_code as transaction_number',
                'booking.nama_customer as pengguna',
                DB::raw('venues.name as nama_venue'),
                DB::raw("'Booking System' as metode_pembayaran"),
                'booking.total_biaya as amount',
                'booking.tanggal_booking as transaction_date',
                'booking.durasi',
                'booking.catatan',
                DB::raw("CASE 
                    WHEN booking.status = 'Terkonfirmasi' THEN 'selesai'
                    WHEN booking.status = 'Menunggu' THEN 'pending'
                    WHEN booking.status = 'Dibatalkan' THEN 'dibatalkan'
                    ELSE booking.status 
                END as status"),
                'booking.created_at',
                'booking.updated_at'
            )
            ->leftJoin('venues', 'booking.venue_id', '=', 'venues.id');
        
        // Query untuk data transactions
        $transactionQuery = DB::table('transactions')
            ->select(
                'transactions.id',
                'transactions.transaction_number',
                'transactions.pengguna',
                'transactions.nama_venue',
                'transactions.metode_pembayaran',
                'transactions.amount',
                'transactions.transaction_date',
                DB::raw('2 as durasi'),
                DB::raw('NULL as catatan'),
                DB::raw("CASE 
                    WHEN transactions.status = 'completed' THEN 'selesai'
                    WHEN transactions.status = 'pending' THEN 'pending'
                    WHEN transactions.status = 'cancelled' THEN 'dibatalkan'
                    WHEN transactions.status = 'refunded' THEN 'dikembalikan'
                    ELSE transactions.status 
                END as status"),
                'transactions.created_at',
                'transactions.updated_at'
            );
        
        // Terapkan filter periode
        $this->applyPeriodFilter($bookingQuery, $request, 'booking.tanggal_booking');
        $this->applyPeriodFilter($transactionQuery, $request, 'transactions.transaction_date');
        
        Log::debug('getCombinedData - Filter periode diterapkan');
        
        // Filter status
        if ($request->filled('status') && $request->status != '') {
            $status = $request->status;
            
            // Mapping status untuk booking
            $bookingStatus = $this->mapStatusToBooking($status);
            if ($bookingStatus) {
                $bookingQuery->where('booking.status', $bookingStatus);
            }
            
            // Mapping status untuk transactions
            $transactionStatus = $this->mapStatusToTransaction($status);
            if ($transactionStatus) {
                $transactionQuery->where('transactions.status', $transactionStatus);
            }
            
            Log::debug('getCombinedData - Filter status diterapkan:', [
                'status' => $status,
                'bookingStatus' => $bookingStatus,
                'transactionStatus' => $transactionStatus
            ]);
        }
        
        // Filter jenis laporan
        if ($request->filled('jenis_laporan') && $request->jenis_laporan != 'semua') {
            $jenisLaporan = $request->jenis_laporan;
            
            switch ($jenisLaporan) {
                case 'pendapatan':
                    $bookingQuery->where('booking.status', 'Terkonfirmasi');
                    $transactionQuery->whereIn('transactions.status', ['completed', 'selesai']);
                    Log::debug('getCombinedData - Filter jenis: pendapatan');
                    break;
                case 'venue':
                    $bookingQuery->whereNotNull('booking.venue_id');
                    $transactionQuery->whereNotNull('transactions.nama_venue');
                    Log::debug('getCombinedData - Filter jenis: venue');
                    break;
                case 'pengguna':
                    $bookingQuery->whereNotNull('booking.nama_customer');
                    $transactionQuery->whereNotNull('transactions.pengguna');
                    Log::debug('getCombinedData - Filter jenis: pengguna');
                    break;
                case 'pemesanan':
                    // Tidak ada filter khusus untuk pemesanan
                    Log::debug('getCombinedData - Filter jenis: pemesanan');
                    break;
            }
        }
        
        // Gabungkan kedua query
        $unionQuery = $bookingQuery->unionAll($transactionQuery);
        
        // Hitung total data sebelum pagination (untuk debug)
        $totalData = DB::query()->fromSub($unionQuery, 'temp_count')->count();
        Log::debug('getCombinedData - Total data gabungan:', ['total' => $totalData]);
        
        // Buat subquery untuk ordering dan pagination
        $combinedQuery = DB::query()->fromSub($unionQuery, 'combined_data')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc');
            
        return $combinedQuery;
    }
    
    /**
     * Method untuk mendapatkan total pemesanan dari gabungan data
     */
    private function getTotalPemesanan(Request $request)
    {
        Log::debug('getTotalPemesanan - Mulai menghitung total pemesanan');
        
        // Hitung dari booking
        $bookingCountQuery = DB::table('booking');
        $this->applyPeriodFilter($bookingCountQuery, $request, 'tanggal_booking');
        
        if ($request->filled('status') && $request->status != '') {
            $status = $request->status;
            $bookingStatus = $this->mapStatusToBooking($status);
            if ($bookingStatus) {
                $bookingCountQuery->where('status', $bookingStatus);
            }
        }
        
        $bookingCount = $bookingCountQuery->count();
        Log::debug('getTotalPemesanan - Total booking:', ['count' => $bookingCount]);
        
        // Hitung dari transactions
        $transactionCountQuery = DB::table('transactions');
        $this->applyPeriodFilter($transactionCountQuery, $request, 'transaction_date');
        
        if ($request->filled('status') && $request->status != '') {
            $status = $request->status;
            $transactionStatus = $this->mapStatusToTransaction($status);
            if ($transactionStatus) {
                $transactionCountQuery->where('status', $transactionStatus);
            }
        }
        
        $transactionCount = $transactionCountQuery->count();
        Log::debug('getTotalPemesanan - Total transactions:', ['count' => $transactionCount]);
        
        $total = $bookingCount + $transactionCount;
        Log::debug('getTotalPemesanan - Total pemesanan:', ['total' => $total]);
        
        return $total;
    }
    
    /**
     * Method untuk mendapatkan total pendapatan dari gabungan data
     */
    private function getTotalPendapatan(Request $request)
    {
        Log::debug('getTotalPendapatan - Mulai menghitung total pendapatan');
        
        // Pendapatan dari booking (status Terkonfirmasi)
        $bookingRevenueQuery = DB::table('booking')
            ->where('status', 'Terkonfirmasi');
        $this->applyPeriodFilter($bookingRevenueQuery, $request, 'tanggal_booking');
        
        // Filter status untuk booking
        if ($request->filled('status') && $request->status != '') {
            $status = $request->status;
            if ($status == 'selesai') {
                // Hanya hitung jika filter status adalah selesai
                $bookingRevenue = $bookingRevenueQuery->sum('total_biaya');
            } else {
                $bookingRevenue = 0;
            }
        } else {
            $bookingRevenue = $bookingRevenueQuery->sum('total_biaya');
        }
        
        Log::debug('getTotalPendapatan - Revenue dari booking:', ['revenue' => $bookingRevenue]);
        
        // Pendapatan dari transactions (status completed/selesai)
        $transactionRevenueQuery = DB::table('transactions')
            ->whereIn('status', ['completed', 'selesai']);
        $this->applyPeriodFilter($transactionRevenueQuery, $request, 'transaction_date');
        
        // Filter status untuk transactions
        if ($request->filled('status') && $request->status != '') {
            $status = $request->status;
            if ($status == 'selesai') {
                $transactionRevenue = $transactionRevenueQuery->sum('amount');
            } else {
                $transactionRevenue = 0;
            }
        } else {
            $transactionRevenue = $transactionRevenueQuery->sum('amount');
        }
        
        Log::debug('getTotalPendapatan - Revenue dari transactions:', ['revenue' => $transactionRevenue]);
        
        $totalPendapatan = $bookingRevenue + $transactionRevenue;
        Log::debug('getTotalPendapatan - Total pendapatan:', ['total' => $totalPendapatan]);
        
        return $totalPendapatan;
    }
    
    /**
     * Method untuk mendapatkan rata-rata pemesanan per hari
     */
    private function getRataPemesananPerHari(Request $request, $totalPemesanan)
    {
        $daysInPeriod = $this->getDaysInPeriod($request);
        
        Log::debug('getRataPemesananPerHari - Perhitungan:', [
            'total_pemesanan' => $totalPemesanan,
            'days_in_period' => $daysInPeriod
        ]);
        
        if ($daysInPeriod > 0 && $totalPemesanan > 0) {
            $rata = round($totalPemesanan / $daysInPeriod, 1);
            Log::debug('getRataPemesananPerHari - Rata-rata:', ['rata' => $rata]);
            return $rata;
        }
        
        Log::debug('getRataPemesananPerHari - Tidak ada data atau periode 0 hari');
        return 0;
    }
    
    /**
     * Method untuk mendapatkan venue terpopuler
     */
    private function getVenueTerpopuler(Request $request)
    {
        Log::debug('getVenueTerpopuler - Mencari venue terpopuler');
        
        $venuePopularity = DB::table('booking')
            ->select('venues.name', DB::raw('COUNT(*) as count'))
            ->join('venues', 'booking.venue_id', '=', 'venues.id')
            ->groupBy('venues.name')
            ->orderBy('count', 'desc');
        
        $this->applyPeriodFilter($venuePopularity, $request, 'booking.tanggal_booking');
        
        if ($request->filled('status') && $request->status != '') {
            $status = $request->status;
            $bookingStatus = $this->mapStatusToBooking($status);
            if ($bookingStatus) {
                $venuePopularity->where('booking.status', $bookingStatus);
            }
        }
        
        $popularVenue = $venuePopularity->first();
        
        if ($popularVenue) {
            Log::debug('getVenueTerpopuler - Venue ditemukan:', [
                'name' => $popularVenue->name,
                'count' => $popularVenue->count
            ]);
            return [
                'name' => $popularVenue->name,
                'count' => $popularVenue->count
            ];
        }
        
        Log::debug('getVenueTerpopuler - Tidak ada venue ditemukan');
        return [
            'name' => 'Belum ada data',
            'count' => 0
        ];
    }
    
    /**
     * Method untuk mendapatkan data pendapatan bulanan
     */
    private function getMonthlyRevenue(Request $request)
    {
        Log::debug('getMonthlyRevenue - Mengambil data pendapatan bulanan');
        
        $currentYear = Carbon::now()->year;
        $monthlyRevenue = array_fill(0, 12, 0);
        
        // Data dari booking (status Terkonfirmasi)
        $bookingRevenue = DB::table('booking')
            ->select(
                DB::raw('EXTRACT(MONTH FROM tanggal_booking) as month'),
                DB::raw('SUM(total_biaya) as total')
            )
            ->where('status', 'Terkonfirmasi')
            ->whereYear('tanggal_booking', $currentYear)
            ->groupBy(DB::raw('EXTRACT(MONTH FROM tanggal_booking)'))
            ->orderBy('month')
            ->get();
        
        foreach ($bookingRevenue as $data) {
            $monthIndex = (int)$data->month - 1;
            $monthlyRevenue[$monthIndex] += round($data->total / 1000000, 2);
        }
        
        // Data dari transactions (status completed/selesai)
        $transactionRevenue = DB::table('transactions')
            ->select(
                DB::raw('EXTRACT(MONTH FROM transaction_date) as month'),
                DB::raw('SUM(amount) as total')
            )
            ->whereIn('status', ['completed', 'selesai'])
            ->whereYear('transaction_date', $currentYear)
            ->groupBy(DB::raw('EXTRACT(MONTH FROM transaction_date)'))
            ->orderBy('month')
            ->get();
        
        foreach ($transactionRevenue as $data) {
            $monthIndex = (int)$data->month - 1;
            $monthlyRevenue[$monthIndex] += round($data->total / 1000000, 2);
        }
        
        Log::debug('getMonthlyRevenue - Data pendapatan bulanan:', ['data' => $monthlyRevenue]);
        
        return $monthlyRevenue;
    }
    
    /**
     * Method untuk mendapatkan distribusi booking
     */
    private function getBookingDistribution(Request $request)
    {
        Log::debug('getBookingDistribution - Mengambil distribusi booking');
        
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        // Distribusi berdasarkan venue
        $venueDistribution = DB::table('booking')
            ->select('venues.name', DB::raw('COUNT(*) as total'))
            ->join('venues', 'booking.venue_id', '=', 'venues.id')
            ->whereYear('booking.created_at', $currentYear)
            ->whereMonth('booking.created_at', $currentMonth)
            ->whereNotNull('booking.venue_id')
            ->groupBy('venues.name')
            ->orderBy('total', 'desc')
            ->limit(7)
            ->get();
        
        $labels = [];
        $data = [];
        
        foreach ($venueDistribution as $item) {
            $labels[] = $item->name;
            $data[] = $item->total;
        }
        
        // Jika tidak ada data, gunakan distribusi status booking
        if (empty($labels)) {
            Log::debug('getBookingDistribution - Tidak ada data venue, menggunakan distribusi status');
            
            $statusDistribution = DB::table('booking')
                ->select('status', DB::raw('COUNT(*) as total'))
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->groupBy('status')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get();
            
            foreach ($statusDistribution as $item) {
                $labels[] = $this->mapBookingStatusLabel($item->status);
                $data[] = $item->total;
            }
        }
        
        // Jika masih kosong, beri data default
        if (empty($labels)) {
            Log::debug('getBookingDistribution - Tidak ada data sama sekali, menggunakan data default');
            $labels = ['Belum ada data'];
            $data = [100];
        }
        
        Log::debug('getBookingDistribution - Distribusi data:', [
            'labels' => $labels,
            'data' => $data
        ]);
        
        return [
            'data' => $data,
            'labels' => $labels
        ];
    }
    
    /**
     * Method untuk menerapkan filter periode
     */
    private function applyPeriodFilter(&$query, Request $request, $dateColumn)
    {
        $periode = $request->periode ?? 'minggu-ini';
        $now = Carbon::now();
        
        Log::debug('applyPeriodFilter - Menerapkan filter periode:', [
            'periode' => $periode,
            'dateColumn' => $dateColumn
        ]);
        
        switch ($periode) {
            case 'hari-ini':
                $query->whereDate($dateColumn, $now->toDateString());
                Log::debug('applyPeriodFilter - Filter: hari ini', ['date' => $now->toDateString()]);
                break;
            case 'minggu-ini':
                $query->whereBetween($dateColumn, [
                    $now->startOfWeek()->toDateString(),
                    $now->endOfWeek()->toDateString()
                ]);
                Log::debug('applyPeriodFilter - Filter: minggu ini', [
                    'start' => $now->startOfWeek()->toDateString(),
                    'end' => $now->endOfWeek()->toDateString()
                ]);
                break;
            case 'bulan-ini':
                $query->whereMonth($dateColumn, $now->month)
                      ->whereYear($dateColumn, $now->year);
                Log::debug('applyPeriodFilter - Filter: bulan ini', [
                    'month' => $now->month,
                    'year' => $now->year
                ]);
                break;
            case 'tahun-ini':
                $query->whereYear($dateColumn, $now->year);
                Log::debug('applyPeriodFilter - Filter: tahun ini', ['year' => $now->year]);
                break;
            case 'custom':
                if ($request->start_date && $request->end_date) {
                    $query->whereBetween($dateColumn, [
                        $request->start_date,
                        $request->end_date
                    ]);
                    Log::debug('applyPeriodFilter - Filter: custom', [
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date
                    ]);
                } else {
                    Log::debug('applyPeriodFilter - Filter: custom (tanpa tanggal)');
                }
                break;
            default:
                Log::debug('applyPeriodFilter - Filter: default (tanpa filter)');
        }
    }
    
    /**
     * Method untuk menghitung hari dalam periode
     */
    private function getDaysInPeriod(Request $request)
    {
        $periode = $request->periode ?? 'minggu-ini';
        $now = Carbon::now();
        
        Log::debug('getDaysInPeriod - Menghitung hari dalam periode:', ['periode' => $periode]);
        
        switch ($periode) {
            case 'hari-ini':
                return 1;
            case 'minggu-ini':
                return 7;
            case 'bulan-ini':
                return $now->daysInMonth;
            case 'tahun-ini':
                return $now->isLeapYear() ? 366 : 365;
            case 'custom':
                if ($request->start_date && $request->end_date) {
                    $start = Carbon::parse($request->start_date);
                    $end = Carbon::parse($request->end_date);
                    $days = $start->diffInDays($end) + 1;
                    Log::debug('getDaysInPeriod - Periode custom:', [
                        'start' => $request->start_date,
                        'end' => $request->end_date,
                        'days' => $days
                    ]);
                    return $days;
                }
                return 30; // default 30 hari
            default:
                return 30;
        }
    }
    
    /**
     * Mapping status untuk booking
     */
    private function mapStatusToBooking($status)
    {
        $statusMap = [
            'selesai' => 'Terkonfirmasi',
            'pending' => 'Menunggu',
            'dibatalkan' => 'Dibatalkan'
        ];
        
        $mapped = $statusMap[$status] ?? null;
        Log::debug('mapStatusToBooking - Mapping:', [
            'input' => $status,
            'output' => $mapped
        ]);
        
        return $mapped;
    }
    
    /**
     * Mapping status untuk transactions
     */
    private function mapStatusToTransaction($status)
    {
        $statusMap = [
            'selesai' => 'completed',
            'pending' => 'pending',
            'dibatalkan' => 'cancelled'
        ];
        
        $mapped = $statusMap[$status] ?? $status;
        Log::debug('mapStatusToTransaction - Mapping:', [
            'input' => $status,
            'output' => $mapped
        ]);
        
        return $mapped;
    }
    
    /**
     * Mapping label status booking
     */
    private function mapBookingStatusLabel($status)
    {
        $labelMap = [
            'Menunggu' => 'Pending',
            'Terkonfirmasi' => 'Selesai',
            'Dibatalkan' => 'Dibatalkan',
            'Selesai' => 'Selesai'
        ];
        
        $mapped = $labelMap[$status] ?? $status;
        Log::debug('mapBookingStatusLabel - Mapping:', [
            'input' => $status,
            'output' => $mapped
        ]);
        
        return $mapped;
    }
    
    /**
     * Menampilkan detail transaksi (AJAX)
     */
    public function detail($id)
    {
        try {
            Log::info('LaporanController@detail - Mencari detail transaksi ID: ' . $id);
            
            // Cari di tabel booking terlebih dahulu
            $booking = DB::table('booking')
                ->select(
                    'booking.id',
                    'booking.booking_code as transaction_number',
                    'booking.nama_customer as pengguna',
                    DB::raw('venues.name as nama_venue'),
                    DB::raw("'Booking System' as metode_pembayaran"),
                    'booking.total_biaya as amount',
                    'booking.tanggal_booking as transaction_date',
                    'booking.durasi',
                    'booking.catatan',
                    DB::raw("CASE 
                        WHEN booking.status = 'Terkonfirmasi' THEN 'selesai'
                        WHEN booking.status = 'Menunggu' THEN 'pending'
                        WHEN booking.status = 'Dibatalkan' THEN 'dibatalkan'
                        ELSE booking.status 
                    END as status"),
                    'booking.created_at'
                )
                ->leftJoin('venues', 'booking.venue_id', '=', 'venues.id')
                ->where('booking.id', $id)
                ->first();
            
            if ($booking) {
                Log::info('LaporanController@detail - Data ditemukan di booking:', [
                    'id' => $booking->id,
                    'transaction_number' => $booking->transaction_number
                ]);
                
                return response()->json([
                    'success' => true,
                    'data' => $booking,
                    'message' => 'Data detail berhasil diambil'
                ]);
            }
            
            Log::debug('LaporanController@detail - Tidak ditemukan di booking, mencari di transactions');
            
            // Jika tidak ditemukan di booking, cari di transactions
            $transaction = DB::table('transactions')
                ->select(
                    'transactions.id',
                    'transactions.transaction_number',
                    'transactions.pengguna',
                    'transactions.nama_venue',
                    'transactions.metode_pembayaran',
                    'transactions.amount',
                    'transactions.transaction_date',
                    DB::raw('2 as durasi'),
                    DB::raw('NULL as catatan'),
                    DB::raw("CASE 
                        WHEN transactions.status = 'completed' THEN 'selesai'
                        WHEN transactions.status = 'pending' THEN 'pending'
                        WHEN transactions.status = 'cancelled' THEN 'dibatalkan'
                        WHEN transactions.status = 'refunded' THEN 'dikembalikan'
                        ELSE transactions.status 
                    END as status"),
                    'transactions.created_at'
                )
                ->where('transactions.id', $id)
                ->first();
            
            if ($transaction) {
                Log::info('LaporanController@detail - Data ditemukan di transactions:', [
                    'id' => $transaction->id,
                    'transaction_number' => $transaction->transaction_number
                ]);
                
                return response()->json([
                    'success' => true,
                    'data' => $transaction,
                    'message' => 'Data detail berhasil diambil'
                ]);
            }
            
            Log::warning('LaporanController@detail - Data tidak ditemukan untuk ID: ' . $id);
            
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
            
        } catch (\Exception $e) {
            Log::error('Error in LaporanController@detail: ' . $e->getMessage() . ' - Trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function exportPdf(Request $request)
    {
        try {
            // ===============================
            // Ambil filter
            // ===============================
            $filterRequest = new Request([
                'periode'       => $request->query('periode', 'minggu-ini'),
                'jenis_laporan' => $request->query('jenis_laporan', 'semua'),
                'status'        => $request->query('status', ''),
                'start_date'    => $request->query('start_date'),
                'end_date'      => $request->query('end_date'),
            ]);
    
            // ===============================
            // Ambil data
            // ===============================
            $data = $this->getCombinedData($filterRequest)
                ->orderBy('transaction_date', 'desc')
                ->limit(100)
                ->get();
    
            if ($data->isEmpty()) {
                return redirect()
                    ->route('admin.laporan.index')
                    ->with('error', 'Tidak ada data untuk diexport');
            }
    
            // ===============================
            // Statistik
            // ===============================
            $totalPendapatan = $this->getTotalPendapatan($filterRequest);
            $totalTransaksi  = $data->count();
            $days            = $this->getDaysInPeriod($filterRequest);
            $rataRata        = $days > 0 ? round($totalTransaksi / $days, 1) : 0;
    
            $venueTerpopulerData = $this->getVenueTerpopuler($filterRequest);

            // ===============================
            // GENERATE CHART IMAGE (WAJIB)
            // ===============================

            // Revenue chart
            $monthlyRevenue = $this->getMonthlyRevenue($filterRequest);

            $revenueChartImg = $this->generateChartImage(
                $monthlyRevenue,
                ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
                'Pendapatan Bulanan',
                'bar'
            );

            // Distribusi booking per venue
            $distribution = $this->getBookingDistribution($filterRequest);

            $venueChartImg = $this->generateChartImage(
                $distribution['data'],
                $distribution['labels'],
                'Distribusi Booking per Venue',
                'doughnut'
            );


    
            $pdf = Pdf::loadView('venue.exports.pdf', [
                'data'                 => $data,
                'user'                 => auth()->user(),
                'isAdmin'              => true,
            
                'periode'              => $this->getPeriodeText($filterRequest->periode),
                'tanggalExport'        => now()->format('d/m/Y H:i'),
            
                'totalPendapatan'      => $totalPendapatan,
                'totalTransaksi'       => $totalTransaksi,
                'rataRata'             => $rataRata,
            
                'venueTerpopuler'      => $venueTerpopulerData['name'] ?? 'Semua Venue',
                'jumlahPemesananVenue' => $venueTerpopulerData['count'] ?? 0,
            
                // 🔥 INI YANG HILANG SELAMA INI
                'revenueChartImg'      => $revenueChartImg,
                'venueChartImg'        => $venueChartImg,
            ])
            ->setPaper('A4', 'landscape')
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
            ]);
            
            
    
            // ===============================
            // BERSIHKAN OUTPUT BUFFER
            // ===============================
            if (ob_get_length()) {
                ob_end_clean();
            }
    
            return $pdf->download(
                'laporan-admin-' . now()->format('Ymd-His') . '.pdf'
            );
    
        } catch (\Throwable $e) {
            \Log::error('Export PDF Error', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString()
            ]);
    
            return redirect()
                ->route('admin.laporan.index')
                ->with('error', 'Gagal export PDF');
        }
    }    

    /**
     * Export Excel via GET request (UNTUK DOWNLOAD LANGSUNG DARI BROWSER)
     */
    public function exportExcel(Request $request)
    {
        try {
            Log::info('LaporanController@exportExcel - Export via GET', $request->all());
            
            // Ambil parameter dari query string (GET)
            $periode = $request->query('periode', 'minggu-ini');
            $jenisLaporan = $request->query('jenis_laporan', 'semua');
            $status = $request->query('status', '');
            
            // Buat Request object untuk filter
            $filterRequest = new Request([
                'periode' => $periode,
                'jenis_laporan' => $jenisLaporan,
                'status' => $status,
                'start_date' => $request->query('start_date'),
                'end_date' => $request->query('end_date')
            ]);
            
            // Ambil data dengan filter yang sama
            $query = $this->getCombinedData($filterRequest);
            $data = $query->get();
            
            Log::info('LaporanController@exportExcel - Data untuk export:', [
                'total_records' => $data->count(),
                'filters' => $filterRequest->all()
            ]);
            
            if ($data->isEmpty()) {
                Log::warning('LaporanController@exportExcel - Tidak ada data untuk diexport');
                return redirect()->back()->with('error', 'Tidak ada data untuk diexport');
            }
            
            // Hitung total
            $totalPendapatan = $this->getTotalPendapatan($filterRequest);
            $totalTransaksi = $data->count();
            
            $filters = [
                'periode' => $periode,
                'status' => $status,
                'jenis_laporan' => $jenisLaporan
            ];
            
            $filename = 'laporan-admin-' . Carbon::now()->format('Y-m-d-H-i-s') . '.xlsx';
            
            Log::info('LaporanController@exportExcel - Excel berhasil dibuat: ' . $filename);
            
            return Excel::download(
                new AdminReportExport($data, $totalPendapatan, $totalTransaksi, $filters),
                $filename
            );
            
        } catch (\Exception $e) {
            Log::error('Error in LaporanController@exportExcel: ' . $e->getMessage() . ' - Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Gagal mengexport Excel: ' . $e->getMessage());
        }
    }
    
    /**
     * Export PDF via POST request (untuk compatibility dengan route POST yang sudah ada)
     */
    public function exportPdfPost(Request $request)
    {
        try {
            Log::info('LaporanController@exportPdfPost - Export via POST', $request->all());
            
            // Redirect ke GET version dengan parameter yang sama
            $queryParams = http_build_query($request->except('_token'));
            $redirectUrl = route('admin.laporan.export-pdf') . '?' . $queryParams;
            
            Log::info('Redirecting to: ' . $redirectUrl);
            
            return redirect($redirectUrl);
            
        } catch (\Exception $e) {
            Log::error('Error in LaporanController@exportPdfPost: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengexport PDF: ' . $e->getMessage());
        }
    }
    
    /**
     * Export Excel via POST request (untuk compatibility dengan route POST yang sudah ada)
     */
    public function exportExcelPost(Request $request)
    {
        try {
            Log::info('LaporanController@exportExcelPost - Export via POST', $request->all());
            
            // Redirect ke GET version dengan parameter yang sama
            $queryParams = http_build_query($request->except('_token'));
            $redirectUrl = route('admin.laporan.export-excel') . '?' . $queryParams;
            
            Log::info('Redirecting to: ' . $redirectUrl);
            
            return redirect($redirectUrl);
            
        } catch (\Exception $e) {
            Log::error('Error in LaporanController@exportExcelPost: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengexport Excel: ' . $e->getMessage());
        }
    }
    
    /**
     * Mendapatkan data chart (AJAX)
     */
    public function getChartData(Request $request)
    {
        try {
            Log::debug('LaporanController@getChartData - Memulai pengambilan data chart');
            
            $period = $request->get('period', 'bulanan');
            
            if ($period === 'mingguan') {
                $revenueData = $this->getWeeklyRevenue($request);
                $labels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
                Log::debug('LaporanController@getChartData - Data mingguan:', [
                    'data' => $revenueData,
                    'labels' => $labels
                ]);
            } else {
                $revenueData = $this->getMonthlyRevenue($request);
                $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                Log::debug('LaporanController@getChartData - Data bulanan:', [
                    'data' => $revenueData,
                    'labels' => $labels
                ]);
            }
            
            $distribution = $this->getBookingDistribution($request);
            
            Log::debug('LaporanController@getChartData - Data distribusi:', $distribution);
            
            return response()->json([
                'success' => true,
                'revenueData' => $revenueData,
                'revenueLabels' => $labels,
                'venueData' => $distribution['data'],
                'venueLabels' => $distribution['labels']
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in LaporanController@getChartData: ' . $e->getMessage() . ' - Trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data chart'
            ], 500);
        }
    }
    
    /**
     * Mendapatkan data pendapatan mingguan
     */
    private function getWeeklyRevenue(Request $request)
    {
        Log::debug('getWeeklyRevenue - Mengambil data pendapatan mingguan');
        
        $weeklyData = [];
        $today = Carbon::now();
        
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $dateStr = $date->toDateString();
            
            // Revenue dari booking
            $revenueBooking = DB::table('booking')
                ->where('status', 'Terkonfirmasi')
                ->whereDate('tanggal_booking', $dateStr)
                ->sum('total_biaya');
            
            // Revenue dari transactions
            $revenueTransaction = DB::table('transactions')
                ->whereIn('status', ['completed', 'selesai'])
                ->whereDate('transaction_date', $dateStr)
                ->sum('amount');
            
            $totalRevenue = $revenueBooking + $revenueTransaction;
            $weeklyData[] = round($totalRevenue / 1000, 0); // Dalam ribuan
            
            Log::debug('getWeeklyRevenue - Data harian:', [
                'date' => $dateStr,
                'booking' => $revenueBooking,
                'transaction' => $revenueTransaction,
                'total' => $totalRevenue
            ]);
        }
        
        Log::debug('getWeeklyRevenue - Data mingguan lengkap:', $weeklyData);
        
        return $weeklyData;
    }
    
    /**
     * Mendapatkan teks periode
     */
    private function getPeriodeText($periode)
    {
        $periodeTexts = [
            'hari-ini' => 'Hari Ini',
            'minggu-ini' => 'Minggu Ini',
            'bulan-ini' => 'Bulan Ini',
            'tahun-ini' => 'Tahun Ini',
            'custom' => 'Kustom'
        ];
        
        $text = $periodeTexts[$periode] ?? 'Minggu Ini';
        Log::debug('getPeriodeText - Mapping:', [
            'input' => $periode,
            'output' => $text
        ]);
        
        return $text;
    }
    
    /**
     * Generate gambar chart untuk PDF
     */
    private function generateChartImage($data, $labels, $title, $type = 'bar', $width = 500, $height = 250)
    {
        Log::debug('generateChartImage - Membuat gambar chart:', [
            'title' => $title,
            'type' => $type,
            'width' => $width,
            'height' => $height
        ]);
        
        if ($type === 'doughnut') {
            $width = 350;
            $height = 250;
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
                            'font' => ['size' => 9]
                        ]
                    ]
                ]
            ]
        ];
        
        if ($type === 'bar') {
            $chartConfig['data']['datasets'][0]['backgroundColor'] = 'rgba(99, 179, 237, 0.6)';
            $chartConfig['data']['datasets'][0]['borderColor'] = 'rgba(99, 179, 237, 1)';
            $chartConfig['options']['scales'] = [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { return "Rp " + value + " jt"; }',
                        'font' => ['size' => 8]
                    ]
                ],
                'x' => [
                    'ticks' => [
                        'font' => ['size' => 8]
                    ]
                ]
            ];
        } else {
            $chartConfig['data']['datasets'][0]['backgroundColor'] = [
                'rgba(99, 179, 237, 0.6)',
                'rgba(34, 197, 94, 0.6)',
                'rgba(59, 130, 246, 0.6)',
                'rgba(245, 158, 11, 0.6)',
                'rgba(139, 92, 246, 0.6)',
                'rgba(236, 72, 153, 0.6)',
                'rgba(16, 185, 129, 0.6)',
            ];
            $chartConfig['options']['aspectRatio'] = 1.4;
        }
        
        $url = 'https://quickchart.io/chart?width=' . $width . '&height=' . $height . '&c=' . urlencode(json_encode($chartConfig));
        
        Log::debug('generateChartImage - URL chart:', ['url' => $url]);
        
        try {
            $image = file_get_contents($url, false, stream_context_create([
                'ssl' => ['verify_peer' => false, 'verify_peer_name' => false],
                'http' => ['timeout' => 30]
            ]));
            
            if ($image !== false) {
                Log::debug('generateChartImage - Gambar chart berhasil dibuat');
                return 'data:image/png;base64,' . base64_encode($image);
            }
            
            Log::warning('generateChartImage - Gagal mengambil gambar chart');
        } catch (\Exception $e) {
            Log::error('Error generating chart image: ' . $e->getMessage());
        }
        
        // Return placeholder jika error
        Log::debug('generateChartImage - Menggunakan placeholder untuk chart');
        return 'data:image/svg+xml;base64,' . base64_encode('
            <svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '">
                <rect width="100%" height="100%" fill="#f8f9fa"/>
                <text x="50%" y="50%" text-anchor="middle" fill="#6c757d" font-family="Arial" font-size="12">
                    Chart: ' . htmlspecialchars($title) . '
                </text>
            </svg>
        ');
    }
    
}