<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        // Data notifikasi user (dalam aplikasi nyata, ini akan diambil dari database)
        $notifications = [
            [
                'id' => 1,
                'title' => 'Booking Dikonfirmasi',
                'content' => 'Booking Anda di Corner Futsal untuk 2 jam telah dikonfirmasi. Silakan datang sesuai jadwal.',
                'time' => '5 menit yang lalu',
                'read' => false,
                'type' => 'booking_success',
                'icon' => 'fa-calendar-check'
            ],
            [
                'id' => 2,
                'title' => 'Pembayaran Berhasil', 
                'content' => 'Pembayaran untuk booking GOR Badminton Senayan sebesar Rp 240.000 telah berhasil.',
                'time' => '1 jam yang lalu',
                'read' => false,
                'type' => 'payment_success',
                'icon' => 'fa-credit-card'
            ],
            [
                'id' => 3,
                'title' => 'Reminder Booking',
                'content' => 'Ingat! Booking Anda di Lapangan Futsal Merdeka besok pukul 14:00 - 16:00.',
                'time' => '2 jam yang lalu',
                'read' => true,
                'type' => 'reminder',
                'icon' => 'fa-bell'
            ],
            [
                'id' => 4,
                'title' => 'Venue Baru Tersedia',
                'content' => 'Lapangan Basket Sport Center baru saja buka di daerah Anda!',
                'time' => '1 hari yang lalu',
                'read' => true,
                'type' => 'promo',
                'icon' => 'fa-volleyball'
            ],
            [
                'id' => 5,
                'title' => 'Review Ditambahkan',
                'content' => 'Review Anda untuk Corner Futsal telah dipublikasikan. Terima kasih!',
                'time' => '2 hari yang lalu',
                'read' => true,
                'type' => 'review',
                'icon' => 'fa-star'
            ]
        ];

        $unreadCount = collect($notifications)->where('read', false)->count();

        return view('user.notifikasi', compact('notifications', 'unreadCount'));
    }

    public function markAsRead($id)
    {
        // Dalam aplikasi nyata, ini akan update status di database
        \Log::info("Notification {$id} marked as read");
        
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        // Dalam aplikasi nyata, ini akan update semua notifikasi user
        \Log::info("All notifications marked as read");
        
        return response()->json(['success' => true]);
    }

    public function delete($id)
    {
        // Dalam aplikasi nyata, ini akan hapus notifikasi dari database
        \Log::info("Notification {$id} deleted");
        
        return response()->json(['success' => true]);
    }
}