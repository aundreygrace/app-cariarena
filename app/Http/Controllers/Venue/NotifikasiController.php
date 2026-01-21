<?php

namespace App\Http\Controllers\Venue;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Pemesanan;
use App\Models\Review;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function notifikasi()
    {
        $userId = Auth::id();
        
        // Generate notifikasi dari data booking dan review terlebih dahulu
        $venues = Venue::where('user_id', $userId)->pluck('id');
        $this->generateAndSaveNotifications($venues);
        
        // Gunakan tabel notifications dengan pagination bawaan Laravel
        $notifications = Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('venue.dashboard.notifikasi', compact('notifications'));
    }

    // Method untuk generate notifikasi (untuk keperluan testing/data awal)
    private function generateAndSaveNotifications($venues)
    {
        $userId = Auth::id();
        
        // Hapus notifikasi lama (opsional)
        // Notification::where('user_id', $userId)->delete();

        // Generate notifikasi dari booking baru
        $newBookings = Pemesanan::whereIn('venue_id', $venues)
            ->where('status', 'Menunggu')
            ->orderBy('created_at', 'desc')
            ->get();
            
        foreach ($newBookings as $booking) {
            // Cek apakah notifikasi sudah ada untuk menghindari duplikasi
            $existingNotification = Notification::where('user_id', $userId)
                ->where('type', 'booking')
                ->where('message', 'like', '%' . $booking->nama_customer . '%')
                ->first();
                
            if (!$existingNotification) {
                Notification::create([
                    'user_id' => $userId,
                    'type' => 'booking',
                    'title' => '📋 Booking Baru',
                    'message' => $booking->nama_customer . ' ingin membooking ' . ($booking->venue->name ?? 'venue Anda'),
                    'is_read' => false,
                    'created_at' => $booking->created_at
                ]);
            }
        }
        
        // Generate notifikasi dari review baru
        $newReviews = Review::whereIn('venue_id', $venues)
            ->orderBy('created_at', 'desc')
            ->get();
            
        foreach ($newReviews as $review) {
            // Cek apakah notifikasi sudah ada untuk menghindari duplikasi
            $existingNotification = Notification::where('user_id', $userId)
                ->where('type', 'ulasan')
                ->where('message', 'like', '%' . $review->customer_name . '%')
                ->first();
                
            if (!$existingNotification) {
                Notification::create([
                    'user_id' => $userId,
                    'type' => 'ulasan',
                    'title' => '⭐ Review Baru',
                    'message' => $review->customer_name . ' memberi rating ' . $review->rating . '/5 untuk ' . ($review->venue->name ?? 'venue Anda'),
                    'is_read' => false,
                    'created_at' => $review->created_at
                ]);
            }
        }

        // Notifikasi pembayaran
        $confirmedBookings = Pemesanan::whereIn('venue_id', $venues)
            ->where('status', 'Terkonfirmasi')
            ->orderBy('created_at', 'desc')
            ->get();
            
        foreach ($confirmedBookings as $booking) {
            // Cek apakah notifikasi sudah ada untuk menghindari duplikasi
            $existingNotification = Notification::where('user_id', $userId)
                ->where('type', 'pembayaran')
                ->where('message', 'like', '%' . $booking->nama_customer . '%')
                ->first();
                
            if (!$existingNotification) {
                Notification::create([
                    'user_id' => $userId,
                    'type' => 'pembayaran',
                    'title' => '💰 Pembayaran Diterima',
                    'message' => 'Pembayaran dari ' . $booking->nama_customer . ' sebesar Rp ' . number_format($booking->total_biaya, 0, ',', '.'),
                    'is_read' => false,
                    'created_at' => $booking->created_at
                ]);
            }
        }
    }

    // Update method markAsRead
    public function markAsRead($id)
    {
        try {
            $notification = Notification::find($id);
            
            if ($notification && $notification->user_id == Auth::id()) {
                $notification->update(['is_read' => true]);
                return response()->json(['success' => true]);
            }
            
            return response()->json(['success' => false, 'message' => 'Notifikasi tidak ditemukan'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan'], 500);
        }
    }

    // Update method destroyNotifikasi
    public function destroyNotifikasi($id)
    {
        try {
            $notification = Notification::find($id);
            
            if ($notification && $notification->user_id == Auth::id()) {
                $notification->delete();
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
            Notification::where('user_id', $userId)
                ->where('is_read', false)
                ->update(['is_read' => true]);
                
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan'], 500);
        }
    }

    // Method untuk menghapus semua notifikasi
    public function destroyAll()
    {
        try {
            $userId = Auth::id();
            Notification::where('user_id', $userId)->delete();
                
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan'], 500);
        }
    }
}