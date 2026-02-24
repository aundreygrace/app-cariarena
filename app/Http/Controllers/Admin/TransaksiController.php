<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        // ✅ FIX: Gunakan DB::table dengan LEFT JOIN ke booking dan venues
        // agar pengguna/nama_venue diambil dari booking jika kolom di transactions kosong/null
        $query = DB::table('transactions')
            ->leftJoin('booking', 'transactions.booking_id', '=', 'booking.id')
            ->leftJoin('venues', 'booking.venue_id', '=', 'venues.id')
            ->leftJoin('users', 'transactions.customer_id', '=', 'users.id')
            // ✅ Fallback JOIN: jika booking_id NULL, cari booking milik user ini
            // berdasarkan customer_id + tanggal yang cocok
            ->leftJoin(
                DB::raw('(SELECT DISTINCT ON (user_id, tanggal_booking) booking.user_id, booking.tanggal_booking, venues.name as venue_name
                          FROM booking
                          JOIN venues ON booking.venue_id = venues.id
                          WHERE booking.user_id IS NOT NULL
                          ORDER BY user_id, tanggal_booking, booking.id DESC) as booking_venue_fallback'),
                function($join) {
                    $join->on('transactions.customer_id', '=', 'booking_venue_fallback.user_id')
                         ->on(DB::raw('DATE(transactions.transaction_date)'), '=', 'booking_venue_fallback.tanggal_booking');
                }
            )
            ->select([
                'transactions.id',
                'transactions.transaction_number',
                'transactions.booking_id',
                'transactions.customer_id',
                'transactions.metode_pembayaran',
                'transactions.amount',
                'transactions.transaction_date',
                'transactions.status',
                'transactions.created_at',
                'transactions.updated_at',
                // 3 lapis fallback untuk pengguna
                DB::raw("COALESCE(NULLIF(transactions.pengguna, ''), booking.nama_customer, users.name) AS pengguna"),
                // 3 lapis fallback untuk nama_venue:
                // 1. kolom nama_venue di transactions
                // 2. venues.name lewat booking_id
                // 3. venue_name lewat match customer_id + tanggal
                DB::raw("COALESCE(NULLIF(transactions.nama_venue, ''), venues.name, booking_venue_fallback.venue_name) AS nama_venue"),
            ]);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('transactions.status', $request->status);
        }
        
        // Filter berdasarkan metode pembayaran
        if ($request->filled('metode_pembayaran')) {
            $query->where('transactions.metode_pembayaran', $request->metode_pembayaran);
        }
        
        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('transactions.transaction_date', '>=', $request->tanggal_mulai);
        }
        
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('transactions.transaction_date', '<=', $request->tanggal_selesai);
        }

        // Filter berdasarkan pencarian — cari di semua sumber (transactions + booking + venues)
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('transactions.transaction_number', 'like', $search)
                  ->orWhere('transactions.pengguna', 'like', $search)
                  ->orWhere('transactions.nama_venue', 'like', $search)
                  ->orWhere('booking.nama_customer', 'like', $search)
                  ->orWhere('venues.name', 'like', $search);
            });
        }
        
        $transaksis = $query->orderBy('transactions.created_at', 'desc')->paginate(10);
        
        // Statistik keseluruhan (semua venue)
        $statistik = [
            'total_hari_ini'      => Transaksi::whereDate('transaction_date', today())->count(),
            'pendapatan_hari_ini' => Transaksi::whereDate('transaction_date', today())
                ->whereIn('status', ['completed', 'paid'])
                ->sum('amount'),
            'pending_count'       => Transaksi::where('status', 'pending')->count(),
            'success_count'       => Transaksi::whereIn('status', ['completed', 'paid'])->count(),
        ];
        
        return view('admin.transaksi', compact('transaksis', 'statistik'));
    }

    /**
     * ✅ TAMBAHAN: Method show yang hilang (dipanggil di web.php)
     */
    public function show($id)
    {
        try {
            // ✅ FIX: JOIN ke booking dan venues agar pengguna/nama_venue tampil
            $transaksi = DB::table('transactions')
                ->leftJoin('booking', 'transactions.booking_id', '=', 'booking.id')
                ->leftJoin('venues', 'booking.venue_id', '=', 'venues.id')
                ->leftJoin('users', 'transactions.customer_id', '=', 'users.id')
                ->leftJoin(
                    DB::raw('(SELECT DISTINCT ON (user_id, tanggal_booking) booking.user_id, booking.tanggal_booking, venues.name as venue_name
                              FROM booking
                              JOIN venues ON booking.venue_id = venues.id
                              WHERE booking.user_id IS NOT NULL
                              ORDER BY user_id, tanggal_booking, booking.id DESC) as booking_venue_fallback'),
                    function($join) {
                        $join->on('transactions.customer_id', '=', 'booking_venue_fallback.user_id')
                             ->on(DB::raw('DATE(transactions.transaction_date)'), '=', 'booking_venue_fallback.tanggal_booking');
                    }
                )
                ->select([
                    'transactions.*',
                    DB::raw("COALESCE(NULLIF(transactions.pengguna, ''), booking.nama_customer, users.name) AS pengguna"),
                    DB::raw("COALESCE(NULLIF(transactions.nama_venue, ''), venues.name, booking_venue_fallback.venue_name) AS nama_venue"),
                    'booking.booking_code',
                    'booking.tanggal_booking',
                    'booking.waktu_booking',
                    'booking.end_time',
                    'booking.durasi',
                    DB::raw("COALESCE(booking.customer_phone, users.phone) AS customer_phone"),
                ])
                ->where('transactions.id', $id)
                ->first();

            if (!$transaksi) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Transaksi tidak ditemukan'
                    ], 404);
                }
                return redirect()->route('admin.transaksi.index')
                    ->with('error', 'Transaksi tidak ditemukan.');
            }

            // Pastikan nilai tidak null untuk JS
            $transaksi->pengguna   = $transaksi->pengguna   ?? '-';
            $transaksi->nama_venue = $transaksi->nama_venue ?? '-';
            $transaksi->metode_pembayaran = $transaksi->metode_pembayaran ?? '-';

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data'    => $transaksi
                ]);
            }

            return view('admin.transaksi.show', compact('transaksi'));
            
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('admin.transaksi.index')
                ->with('error', 'Terjadi kesalahan saat memuat transaksi.');
        }
    }

    /**
     * ✅ TAMBAHAN: Method filter (dipanggil di web.php via POST /filter)
     * Redirect ke index dengan parameter filter sebagai query string
     */
    public function filter(Request $request)
    {
        $params = $request->only([
            'status',
            'metode_pembayaran',
            'tanggal_mulai',
            'tanggal_selesai',
            'search',
        ]);

        // Hapus nilai kosong agar URL bersih
        $params = array_filter($params, fn($v) => $v !== null && $v !== '');

        return redirect()->route('admin.transaksi.index', $params);
    }
    
    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        
        $request->validate([
            'pengguna'          => 'required|string',
            'nama_venue'        => 'required|string',
            'metode_pembayaran' => 'required|string',
            'amount'            => 'required|numeric|min:0',
            // ✅ FIX: Status yang valid sesuai model Transaksi
            'status'            => 'required|in:pending,paid,failed,cancelled,refunded,completed,processing',
        ]);
        
        $transaksi->update([
            'pengguna'          => $request->pengguna,
            'nama_venue'        => $request->nama_venue,
            'metode_pembayaran' => $request->metode_pembayaran,
            'amount'            => $request->amount,
            'status'            => $request->status,
        ]);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diperbarui'
            ]);
        }

        return redirect()->route('admin.transaksi.index')
            ->with('success', 'Transaksi berhasil diperbarui');
    }
    
    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dihapus'
            ]);
        }

        return redirect()->route('admin.transaksi.index')
            ->with('success', 'Transaksi berhasil dihapus');
    }

    /**
     * ✅ FIX: Di web.php route namanya 'confirm' → method confirmPayment
     * Sesuai: Route::post('/{transaksi}/confirm', [AdminTransaksiController::class, 'confirmPayment'])
     */
    public function confirmPayment($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update(['status' => 'completed']);
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dikonfirmasi'
            ]);
        }

        return redirect()->route('admin.transaksi.index')
            ->with('success', 'Transaksi berhasil dikonfirmasi');
    }
    
    /**
     * ✅ FIX: Di web.php route namanya 'reject' → method rejectPayment
     * Sesuai: Route::post('/{transaksi}/reject', [AdminTransaksiController::class, 'rejectPayment'])
     */
    public function rejectPayment($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update(['status' => 'cancelled']);
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil ditolak'
            ]);
        }

        return redirect()->route('admin.transaksi.index')
            ->with('success', 'Transaksi berhasil ditolak');
    }

    // Alias untuk kompatibilitas jika ada kode lama yang memanggil confirm/reject langsung
    public function confirm($id) { return $this->confirmPayment($id); }
    public function reject($id)  { return $this->rejectPayment($id); }
}