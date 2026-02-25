<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotifikasiController extends Controller
{
    public function notifikasi()
    {
        $this->generateAndSaveNotifications();

        // ✅ FIX #1: Pakai DB::table karena tabel 'notifications' tidak punya updated_at
        //   Eloquent akan error saat insert/update jika tidak set $timestamps = false
        // ✅ FIX #2: Hapus orWhere('type', 'sistem') — celah keamanan, bisa tampilkan
        //   notifikasi milik user lain yang bertipe 'sistem'
        $notifications = DB::table('notifications')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.notifikasi', compact('notifications'));
    }

    private function generateAndSaveNotifications()
    {
        $userId = Auth::id();

        // ✅ FIX #3: Model Pemesanan ada (tabelnya 'booking'), pakai status konstanta
        //   'Menunggu' adalah label tampilan — status DB aslinya 'pending'
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

        // ✅ FIX #4: Review — JOIN ke venues langsung (hindari N+1 dari $review->venue->name)
        //   Model Review tidak diupload sehingga tidak diketahui apakah punya relasi venue
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

        // ✅ FIX #5: Status 'confirmed' bukan 'Terkonfirmasi' (sesuai DB constraint)
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

        // ✅ FIX #6: DIHAPUS — Venue::where('status', 'pending') tidak pernah return row
        //   karena DB constraint venues hanya terima: 'Aktif', 'Maintenance', 'Tidak Aktif'

        // ✅ FIX #7: User baru — pakai Spatie roles, bukan User::where('role', 'user')
        //   Kolom 'role' tidak ada di tabel users (roles dikelola tabel model_has_roles)
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

    // ✅ FIX #8: Semua method CRUD pakai DB::table — konsisten dengan generateAndSaveNotifications
    //   dan menghindari error Eloquent karena tidak ada kolom updated_at

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
}