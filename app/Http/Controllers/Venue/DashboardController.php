<?php

namespace App\Http\Controllers\Venue;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Venue;
use App\Models\Review;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        // Dapatkan venue yang dimiliki oleh user
        $venues = Venue::where('user_id', $userId)->pluck('id');
        
        // Data untuk cards
        $todayBookingsCount = Pemesanan::whereIn('venue_id', $venues)
            ->whereDate('tanggal_booking', Carbon::today())
            ->count();
            
        $weeklyRevenue = Pemesanan::whereIn('venue_id', $venues)
            ->whereIn('status', ['Terkonfirmasi', 'Selesai'])
            ->whereBetween('tanggal_booking', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('total_biaya');
            
        // Hitung rating rata-rata dan total reviews
        $averageRating = Review::whereIn('venue_id', $venues)->avg('rating') ?? 4.8;
        $averageRating = round($averageRating, 1);
        $totalReviews = Review::whereIn('venue_id', $venues)->count();
        
        // Hitung occupancy rate
        $occupancyRate = $this->hitungOccupancyRate($venues);

        // Booking terbaru
        $recentBookings = Pemesanan::whereIn('venue_id', $venues)
            ->with('venue')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Notifikasi
        $notifications = $this->generateNotifications($venues);

        return view('venue.dashboard.index', compact(
            'todayBookingsCount',
            'weeklyRevenue', 
            'averageRating',
            'totalReviews',
            'occupancyRate',
            'recentBookings',
            'notifications'
        ));
    }

    // METHOD UNTUK HALAMAN NOTIFIKASI
    public function notifikasi()
    {
        $userId = Auth::id();
        $venues = Venue::where('user_id', $userId)->pluck('id');
        
        // Generate notifikasi lengkap untuk halaman notifikasi
        $notifications = $this->generateDetailedNotifications($venues);

        return view('venue.dashboard.notifikasi', compact('notifications'));
    }

    // METHOD UNTUK MENANDAI NOTIFIKASI SUDAH DIBACA
    public function markAsRead($id)
    {
        // Untuk sementara return success, nanti bisa diintegrasikan dengan database
        return response()->json(['success' => true]);
    }

    // METHOD UNTUK MENGHAPUS NOTIFIKASI
    public function destroyNotifikasi($id)
    {
        // Untuk sementara return success, nanti bisa diintegrasikan dengan database
        return response()->json(['success' => true]);
    }

    // METHOD UNTUK HALAMAN LIHAT REVIEW
    public function lihatReview()
    {
        $userId = Auth::id();
        $venues = Venue::where('user_id', $userId)->pluck('id');
        
        $reviews = Review::whereIn('venue_id', $venues)
            ->with('venue')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('venue.dashboard.lihat-review', compact('reviews'));
    }

    private function hitungOccupancyRate($venues)
    {
        // Hitung total booking yang confirmed/selesai bulan ini
        $bookedThisMonth = Pemesanan::whereIn('venue_id', $venues)
            ->whereIn('status', ['Terkonfirmasi', 'Selesai'])
            ->whereMonth('tanggal_booking', now()->month)
            ->whereYear('tanggal_booking', now()->year)
            ->count();
            
        // Asumsi kapasitas maksimal 90 booking per bulan
        $maxCapacity = 90;
        
        return $maxCapacity > 0 ? min(round(($bookedThisMonth / $maxCapacity) * 100), 100) : 0;
    }

    // METHOD UNTUK NOTIFIKASI DI DASHBOARD (SIMPLE)
    private function generateNotifications($venues)
    {
        $notifications = collect();
        
        // Notifikasi dari booking baru
        $newBookings = Pemesanan::whereIn('venue_id', $venues)
            ->where('status', 'Menunggu')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
            
        foreach ($newBookings as $booking) {
            $notifications->push((object)[
                'title' => '📋 Booking Baru',
                'message' => $booking->nama_customer . ' ingin membooking ' . ($booking->venue->name ?? 'venue Anda'),
                'created_at' => $booking->created_at
            ]);
        }
        
        // Notifikasi dari review baru
        $newReviews = Review::whereIn('venue_id', $venues)
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();
            
        foreach ($newReviews as $review) {
            $notifications->push((object)[
                'title' => '⭐ Review Baru',
                'message' => $review->customer_name . ' memberi rating ' . $review->rating . '/5',
                'created_at' => $review->created_at
            ]);
        }
        
        // Notifikasi default jika tidak ada notifikasi
        if ($notifications->isEmpty()) {
            $notifications->push((object)[
                'title' => '👋 Selamat Datang',
                'message' => 'Kelola venue Anda dari dashboard ini',
                'created_at' => now()
            ]);
        }
        
        // Urutkan notifikasi berdasarkan created_at terbaru
        return $notifications->sortByDesc('created_at')->take(5);
    }

    // METHOD UNTUK NOTIFIKASI DI HALAMAN NOTIFIKASI (DETAILED)
    private function generateDetailedNotifications($venues)
    {
        $notifications = collect();
        
        // Notifikasi dari booking baru
        $newBookings = Pemesanan::whereIn('venue_id', $venues)
            ->where('status', 'Menunggu')
            ->orderBy('created_at', 'desc')
            ->get();
            
        foreach ($newBookings as $booking) {
            $notifications->push((object)[
                'id' => $booking->id,
                'title' => '📋 Booking Baru',
                'message' => $booking->nama_customer . ' ingin membooking ' . ($booking->venue->name ?? 'venue Anda'),
                'type' => 'booking',
                'is_read' => false,
                'created_at' => $booking->created_at
            ]);
        }
        
        // Notifikasi dari review baru
        $newReviews = Review::whereIn('venue_id', $venues)
            ->orderBy('created_at', 'desc')
            ->get();
            
        foreach ($newReviews as $review) {
            $notifications->push((object)[
                'id' => $review->id + 1000, // ID berbeda dari booking
                'title' => '⭐ Review Baru',
                'message' => $review->customer_name . ' memberi rating ' . $review->rating . '/5 untuk ' . ($review->venue->name ?? 'venue Anda'),
                'type' => 'ulasan',
                'is_read' => false,
                'created_at' => $review->created_at
            ]);
        }
        
        // Notifikasi pembayaran diterima
        $confirmedBookings = Pemesanan::whereIn('venue_id', $venues)
            ->where('status', 'Terkonfirmasi')
            ->orderBy('created_at', 'desc')
            ->get();
            
        foreach ($confirmedBookings as $booking) {
            $notifications->push((object)[
                'id' => $booking->id + 2000,
                'title' => '💰 Pembayaran Diterima',
                'message' => 'Pembayaran dari ' . $booking->nama_customer . ' sebesar Rp ' . number_format($booking->total_biaya, 0, ',', '.'),
                'type' => 'pembayaran',
                'is_read' => false,
                'created_at' => $booking->created_at
            ]);
        }
        
        // Notifikasi booking selesai
        $completedBookings = Pemesanan::whereIn('venue_id', $venues)
            ->where('status', 'Selesai')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        foreach ($completedBookings as $booking) {
            $notifications->push((object)[
                'id' => $booking->id + 3000,
                'title' => '✅ Booking Selesai',
                'message' => 'Booking ' . $booking->nama_customer . ' telah selesai',
                'type' => 'booking',
                'is_read' => true,
                'created_at' => $booking->created_at
            ]);
        }
        
        // Notifikasi sistem
        $notifications->push((object)[
            'id' => 1,
            'title' => '🔄 Pembaruan Sistem',
            'message' => 'Sistem CariArena telah diperbarui dengan fitur terbaru',
            'type' => 'sistem',
            'is_read' => true,
            'created_at' => now()->subDays(1)
        ]);
        
        // Notifikasi default jika tidak ada notifikasi
        if ($notifications->isEmpty()) {
            $notifications->push((object)[
                'id' => 999,
                'title' => '👋 Selamat Datang',
                'message' => 'Belum ada notifikasi baru untuk venue Anda',
                'type' => 'sistem',
                'is_read' => false,
                'created_at' => now()
            ]);
        }

        // Urutkan notifikasi berdasarkan created_at terbaru
        return $notifications->sortByDesc('created_at');
    }
}