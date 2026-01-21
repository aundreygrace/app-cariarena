<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Venue;
use App\Models\User;
use App\Models\Notifikasi;
use App\Models\Review;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Pengguna - PERBAIKI DENGAN SPATIE PERMISSION
        $totalPengguna = User::whereHas('roles', function($query) {
            $query->where('name', 'user');
        })->count();
        
        // Total Venue
        $totalVenue = Venue::where('status', 'active')->count();
        
        // Total Pemesanan
        $totalPemesanan = Pemesanan::count();
        
        // Peningkatan pemesanan dari kemarin
        $pemesananKemarin = Pemesanan::whereDate('created_at', Carbon::yesterday())->count();
        $pemesananHariIni = Pemesanan::whereDate('created_at', Carbon::today())->count();
        $peningkatanPemesanan = $pemesananKemarin > 0 ? 
            round((($pemesananHariIni - $pemesananKemarin) / $pemesananKemarin) * 100, 2) : 0;

        // Tingkat okupansi (contoh sederhana)
        $tingkatOkupansi = round(($totalPemesanan / max($totalVenue * 30, 1)) * 100, 2);

        // Pemesanan Terbaru
        $pemesananTerbaru = Pemesanan::with('venue')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Venue Populer
        $venuePopuler = Venue::withCount(['pemesanans as total_pemesanans'])
            ->orderBy('total_pemesanans', 'desc')
            ->take(5)
            ->get();

        // Notifikasi Terbaru dari database
        $notifications = Notifikasi::where('user_id', Auth::id())
            ->orWhere('type', 'sistem')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Statistik Minggu Ini
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $pemesananMingguIni = Pemesanan::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
        $pendapatanMingguIni = Pemesanan::whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('total_biaya');
        
        // PERBAIKI: Pengguna baru minggu ini dengan Spatie Permission
        $penggunaBaruMingguIni = User::whereHas('roles', function($query) {
            $query->where('name', 'user');
        })->whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();

        $statistikMingguIni = [
            'pemesanan' => $pemesananMingguIni,
            'pendapatan' => $pendapatanMingguIni,
            'okupansi' => round(($pemesananMingguIni / max($totalVenue * 7, 1)) * 100, 2),
            'pengguna_baru' => $penggunaBaruMingguIni,
            'peningkatan_pemesanan' => 12.5,
            'peningkatan_pendapatan' => 8.3,
            'peningkatan_okupansi' => 5.2,
            'peningkatan_pengguna' => 3.1
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
        // Generate notifikasi untuk admin
        $this->generateAndSaveNotifications();
        
        // Ambil semua notifikasi untuk admin
        $notifications = Notifikasi::where('user_id', Auth::id())
            ->orWhere('type', 'sistem')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.notifikasi', compact('notifications'));
    }

    // Method untuk generate notifikasi admin
    private function generateAndSaveNotifications()
    {
        $userId = Auth::id();

        // Notifikasi dari booking baru
        $newBookings = Pemesanan::where('status', 'Menunggu')
            ->orderBy('created_at', 'desc')
            ->get();
            
        foreach ($newBookings as $booking) {
            $existingNotification = Notifikasi::where('user_id', $userId)
                ->where('type', 'booking')
                ->where('message', 'like', '%' . $booking->nama_customer . '%')
                ->first();
                
            if (!$existingNotification) {
                Notifikasi::create([
                    'user_id' => $userId,
                    'type' => 'booking',
                    'title' => '📋 Booking Baru',
                    'message' => $booking->nama_customer . ' ingin membooking ' . ($booking->venue->name ?? 'sebuah venue'),
                    'is_read' => false,
                    'created_at' => $booking->created_at
                ]);
            }
        }
        
        // Notifikasi dari review baru
        $newReviews = Review::orderBy('created_at', 'desc')
            ->get();
            
        foreach ($newReviews as $review) {
            $existingNotification = Notifikasi::where('user_id', $userId)
                ->where('type', 'ulasan')
                ->where('message', 'like', '%' . $review->customer_name . '%')
                ->first();
                
            if (!$existingNotification) {
                Notifikasi::create([
                    'user_id' => $userId,
                    'type' => 'ulasan',
                    'title' => '⭐ Review Baru',
                    'message' => $review->customer_name . ' memberi rating ' . $review->rating . '/5 untuk ' . ($review->venue->name ?? 'sebuah venue'),
                    'is_read' => false,
                    'created_at' => $review->created_at
                ]);
            }
        }

        // Notifikasi pembayaran
        $confirmedBookings = Pemesanan::where('status', 'Terkonfirmasi')
            ->orderBy('created_at', 'desc')
            ->get();
            
        foreach ($confirmedBookings as $booking) {
            $existingNotification = Notifikasi::where('user_id', $userId)
                ->where('type', 'pembayaran')
                ->where('message', 'like', '%' . $booking->nama_customer . '%')
                ->first();
                
            if (!$existingNotification) {
                Notifikasi::create([
                    'user_id' => $userId,
                    'type' => 'pembayaran',
                    'title' => '💰 Pembayaran Diterima',
                    'message' => 'Pembayaran dari ' . $booking->nama_customer . ' sebesar Rp ' . number_format($booking->total_biaya, 0, ',', '.'),
                    'is_read' => false,
                    'created_at' => $booking->created_at
                ]);
            }
        }

        // Notifikasi pendaftaran venue baru
        $newVenues = Venue::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
            
        foreach ($newVenues as $venue) {
            $existingNotification = Notifikasi::where('user_id', $userId)
                ->where('type', 'venue')
                ->where('message', 'like', '%' . $venue->name . '%')
                ->first();
                
            if (!$existingNotification) {
                Notifikasi::create([
                    'user_id' => $userId,
                    'type' => 'venue',
                    'title' => '🏢 Venue Baru',
                    'message' => 'Venue ' . $venue->name . ' menunggu persetujuan',
                    'is_read' => false,
                    'created_at' => $venue->created_at
                ]);
            }
        }

        // Notifikasi user baru - PERBAIKI DENGAN SPATIE PERMISSION
        $newUsers = User::whereHas('roles', function($query) {
            $query->where('name', 'user');
        })
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();
            
        foreach ($newUsers as $user) {
            $existingNotification = Notifikasi::where('user_id', $userId)
                ->where('type', 'user')
                ->where('message', 'like', '%' . $user->name . '%')
                ->first();
                
            if (!$existingNotification) {
                Notifikasi::create([
                    'user_id' => $userId,
                    'type' => 'user',
                    'title' => '👤 User Baru',
                    'message' => $user->name . ' bergabung sebagai user',
                    'is_read' => false,
                    'created_at' => $user->created_at
                ]);
            }
        }
    }

    // Method untuk menandai notifikasi sebagai sudah dibaca
    public function markAsRead($id)
    {
        try {
            $notification = Notifikasi::find($id);
            
            if ($notification) {
                $notification->update(['is_read' => true]);
                return response()->json(['success' => true]);
            }
            
            return response()->json(['success' => false, 'message' => 'Notifikasi tidak ditemukan'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan'], 500);
        }
    }

    // Method untuk menandai semua notifikasi sebagai sudah dibaca
    public function markAllAsRead()
    {
        try {
            $userId = Auth::id();
            Notifikasi::where('user_id', $userId)
                ->where('is_read', false)
                ->update(['is_read' => true]);
                
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan'], 500);
        }
    }

    // Method untuk menghapus notifikasi
    public function destroyNotifikasi($id)
    {
        try {
            $notification = Notifikasi::find($id);
            
            if ($notification && $notification->user_id == Auth::id()) {
                $notification->delete();
                return response()->json(['success' => true]);
            }
            
            return response()->json(['success' => false, 'message' => 'Notifikasi tidak ditemukan'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan'], 500);
        }
    }

    // Method untuk menghapus semua notifikasi
    public function destroyAll()
    {
        try {
            $userId = Auth::id();
            Notifikasi::where('user_id', $userId)->delete();
                
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