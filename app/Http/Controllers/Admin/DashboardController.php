<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Venue;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ✅ FIX #1: totalPengguna pakai Spatie roles (bukan kolom 'role' yang tidak ada di tabel)
        $totalPengguna = User::whereHas('roles', fn($q) => $q->where('name', 'user'))->count();

        // ✅ FIX #2: Status venue di DB adalah 'Aktif', bukan 'active'
        $totalVenue = Venue::where('status', 'Aktif')->count();

        // Model Pemesanan sudah benar (protected $table = 'booking')
        $totalPemesanan = Pemesanan::count();

        // Peningkatan pemesanan — dihitung real, bukan hardcode
        $pemesananKemarin = Pemesanan::whereDate('created_at', Carbon::yesterday())->count();
        $pemesananHariIni  = Pemesanan::whereDate('created_at', Carbon::today())->count();
        $peningkatanPemesanan = $pemesananKemarin > 0
            ? round((($pemesananHariIni - $pemesananKemarin) / $pemesananKemarin) * 100, 2)
            : 0;

        // Tingkat okupansi
        $tingkatOkupansi = $totalVenue > 0
            ? round(($totalPemesanan / max($totalVenue * 30, 1)) * 100, 2)
            : 0;

        // ✅ FIX #3: Pemesanan Terbaru — eager load relasi venue untuk hindari N+1
        $pemesananTerbaru = Pemesanan::with('venue')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // ✅ FIX #4: Venue Populer — relasi 'pemesanans' sudah ada di Venue model
        $venuePopuler = Venue::withCount(['pemesanans as total_pemesanans'])
            ->orderBy('total_pemesanans', 'desc')
            ->take(5)
            ->get();

        // ✅ FIX #5: Notifikasi — pakai DB::table langsung karena:
        //   - Tabel 'notifications' TIDAK punya kolom 'updated_at'
        //   - Model Eloquent default membutuhkan updated_at → akan error saat insert/update
        //   - Hapus orWhere('type', 'sistem') → celah keamanan, bisa tampilkan notif user lain
        $notifications = DB::table('notifications')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Statistik Minggu Ini
        $startOfWeek   = Carbon::now()->startOfWeek();
        $endOfWeek     = Carbon::now()->endOfWeek();
        $startLastWeek = Carbon::now()->subWeek()->startOfWeek();
        $endLastWeek   = Carbon::now()->subWeek()->endOfWeek();

        $pemesananMingguIni   = Pemesanan::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
        $pendapatanMingguIni  = Pemesanan::whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('total_biaya');
        $pemesananMingguLalu  = Pemesanan::whereBetween('created_at', [$startLastWeek, $endLastWeek])->count();
        $pendapatanMingguLalu = Pemesanan::whereBetween('created_at', [$startLastWeek, $endLastWeek])->sum('total_biaya');

        $penggunaBaruMingguIni  = User::whereHas('roles', fn($q) => $q->where('name', 'user'))
                                       ->whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
        $penggunaBaruMingguLalu = User::whereHas('roles', fn($q) => $q->where('name', 'user'))
                                       ->whereBetween('created_at', [$startLastWeek, $endLastWeek])->count();

        // ✅ FIX #6: Persentase perubahan dihitung real, BUKAN angka hardcode (12.5, 8.3, dll)
        $pctBooking    = $pemesananMingguLalu  > 0 ? round((($pemesananMingguIni  - $pemesananMingguLalu)  / $pemesananMingguLalu)  * 100, 1) : 0;
        $pctPendapatan = $pendapatanMingguLalu > 0 ? round((($pendapatanMingguIni - $pendapatanMingguLalu) / $pendapatanMingguLalu) * 100, 1) : 0;
        $pctPengguna   = $penggunaBaruMingguLalu > 0 ? round((($penggunaBaruMingguIni - $penggunaBaruMingguLalu) / $penggunaBaruMingguLalu) * 100, 1) : 0;

        $statistikMingguIni = [
            'pemesanan'              => $pemesananMingguIni,
            'pendapatan'             => $pendapatanMingguIni,
            'okupansi'               => $totalVenue > 0 ? round(($pemesananMingguIni / max($totalVenue * 7, 1)) * 100, 2) : 0,
            'pengguna_baru'          => $penggunaBaruMingguIni,
            'peningkatan_pemesanan'  => $pctBooking,
            'peningkatan_pendapatan' => $pctPendapatan,
            'peningkatan_okupansi'   => 0,
            'peningkatan_pengguna'   => $pctPengguna,
        ];

        return view('admin.dashboard', compact(
            'totalPengguna',
            'totalVenue',
            'totalPemesanan',
            'peningkatanPemesanan',
            'tingkatOkupansi',
            'pemesananTerbaru',
            'venuePopuler',
            'notifications',
            'statistikMingguIni'
        ));
    }

    public function notifikasi()
    {
        $this->generateAndSaveNotifications();

        // ✅ FIX #7: pakai DB::table (bukan model Notifikasi/Notification — berbeda nama,
        //   dan tabel tidak punya updated_at yang dibutuhkan Eloquent)
        //   Hapus orWhere('type', 'sistem') — celah keamanan
        $notifications = DB::table('notifications')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.notifikasi', compact('notifications'));
    }

    private function generateAndSaveNotifications()
    {
        $userId = Auth::id();

        // ✅ FIX #8: Status 'pending' bukan 'Menunggu' (sesuai konstanta di model Pemesanan)
        //   Eager load venue untuk hindari N+1 query
        $newBookings = Pemesanan::with('venue')
            ->where('status', Pemesanan::STATUS_PENDING)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($newBookings as $booking) {
            $exists = DB::table('notifications')
                ->where('user_id', $userId)
                ->where('type', 'booking')
                ->where('message', 'like', '%' . $booking->nama_customer . '%')
                ->exists();

            if (!$exists) {
                DB::table('notifications')->insert([
                    'user_id'    => $userId,
                    'type'       => 'booking',
                    'title'      => 'Booking Baru',
                    'message'    => $booking->nama_customer . ' ingin membooking ' . ($booking->venue->name ?? 'sebuah venue'),
                    'is_read'    => false,
                    'created_at' => $booking->created_at ?? now(),
                ]);
            }
        }

        // ✅ FIX #9: Review — JOIN langsung ke venues (hindari N+1, dan hindari akses
        //   $review->venue->name yang akan null jika venue dihapus)
        $newReviews = DB::table('reviews')
            ->leftJoin('venues', 'reviews.venue_id', '=', 'venues.id')
            ->select('reviews.customer_name', 'reviews.rating', 'reviews.created_at', 'venues.name as venue_name')
            ->orderBy('reviews.created_at', 'desc')
            ->get();

        foreach ($newReviews as $review) {
            $exists = DB::table('notifications')
                ->where('user_id', $userId)
                ->where('type', 'ulasan')
                ->where('message', 'like', '%' . $review->customer_name . '%')
                ->exists();

            if (!$exists) {
                DB::table('notifications')->insert([
                    'user_id'    => $userId,
                    'type'       => 'ulasan',
                    'title'      => 'Review Baru',
                    'message'    => $review->customer_name . ' memberi rating ' . $review->rating . '/5 untuk ' . ($review->venue_name ?? 'sebuah venue'),
                    'is_read'    => false,
                    'created_at' => $review->created_at ?? now(),
                ]);
            }
        }

        // ✅ FIX #10: Status 'confirmed' bukan 'Terkonfirmasi' (sesuai DB constraint & konstanta model)
        $confirmedBookings = Pemesanan::with('venue')
            ->where('status', Pemesanan::STATUS_CONFIRMED)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($confirmedBookings as $booking) {
            $exists = DB::table('notifications')
                ->where('user_id', $userId)
                ->where('type', 'pembayaran')
                ->where('message', 'like', '%' . $booking->nama_customer . '%')
                ->exists();

            if (!$exists) {
                DB::table('notifications')->insert([
                    'user_id'    => $userId,
                    'type'       => 'pembayaran',
                    'title'      => 'Pembayaran Diterima',
                    'message'    => 'Pembayaran dari ' . $booking->nama_customer . ' sebesar Rp ' . number_format($booking->total_biaya, 0, ',', '.'),
                    'is_read'    => false,
                    'created_at' => $booking->created_at ?? now(),
                ]);
            }
        }

        // ✅ FIX #11: DIHAPUS — Venue tidak punya status 'pending'
        //   DB constraint hanya: 'Aktif', 'Maintenance', 'Tidak Aktif'
        //   Query ini sebelumnya selalu return 0 row dan sia-sia

        // ✅ FIX #12: User baru — pakai Spatie roles, bukan kolom 'role' yang tidak ada di tabel users
        $newUsers = User::whereHas('roles', fn($q) => $q->where('name', 'user'))
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        foreach ($newUsers as $user) {
            $exists = DB::table('notifications')
                ->where('user_id', $userId)
                ->where('type', 'user')
                ->where('message', 'like', '%' . $user->name . '%')
                ->exists();

            if (!$exists) {
                DB::table('notifications')->insert([
                    'user_id'    => $userId,
                    'type'       => 'user',
                    'title'      => 'User Baru',
                    'message'    => $user->name . ' bergabung sebagai user baru',
                    'is_read'    => false,
                    'created_at' => $user->created_at ?? now(),
                ]);
            }
        }
    }

    // ✅ FIX #13: Semua CRUD notifikasi pakai DB::table (bukan model Eloquent)
    //   karena tabel 'notifications' tidak punya updated_at

    public function markAsRead($id)
    {
        try {
            $updated = DB::table('notifications')
                ->where('id', $id)
                ->where('user_id', Auth::id()) // Security: hanya boleh update milik sendiri
                ->update(['is_read' => true]);

            return response()->json(['success' => (bool) $updated]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan'], 500);
        }
    }

    public function markAllAsRead()
    {
        try {
            DB::table('notifications')
                ->where('user_id', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan'], 500);
        }
    }

    public function destroyNotifikasi($id)
    {
        try {
            $deleted = DB::table('notifications')
                ->where('id', $id)
                ->where('user_id', Auth::id()) // Security: hanya boleh hapus milik sendiri
                ->delete();

            return $deleted
                ? response()->json(['success' => true])
                : response()->json(['success' => false, 'message' => 'Notifikasi tidak ditemukan'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan'], 500);
        }
    }

    public function destroyAll()
    {
        try {
            DB::table('notifications')
                ->where('user_id', Auth::id())
                ->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan'], 500);
        }
    }

    public function catatanAktivitas()
    {
        return view('admin.catatan-aktivitas');
    }

    public function jadwalLapangan()
    {
        return view('admin.jadwal-lapangan');
    }
}