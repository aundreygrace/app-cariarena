<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Pemesanan;
use App\Models\Review;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function notifikasi()
    {
        // Generate notifikasi untuk admin
        $this->generateAndSaveNotifications();
        
        // Ambil semua notifikasi untuk admin
        $notifications = Notification::where('user_id', Auth::id())
            ->orWhere('type', 'sistem') // Notifikasi sistem untuk semua admin
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
            $existingNotification = Notification::where('user_id', $userId)
                ->where('type', 'booking')
                ->where('message', 'like', '%' . $booking->nama_customer . '%')
                ->first();
                
            if (!$existingNotification) {
                Notification::create([
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
            $existingNotification = Notification::where('user_id', $userId)
                ->where('type', 'ulasan')
                ->where('message', 'like', '%' . $review->customer_name . '%')
                ->first();
                
            if (!$existingNotification) {
                Notification::create([
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

        // Notifikasi pendaftaran venue baru
        $newVenues = Venue::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
            
        foreach ($newVenues as $venue) {
            $existingNotification = Notification::where('user_id', $userId)
                ->where('type', 'venue')
                ->where('message', 'like', '%' . $venue->name . '%')
                ->first();
                
            if (!$existingNotification) {
                Notification::create([
                    'user_id' => $userId,
                    'type' => 'venue',
                    'title' => '🏢 Venue Baru',
                    'message' => 'Venue ' . $venue->name . ' menunggu persetujuan',
                    'is_read' => false,
                    'created_at' => $venue->created_at
                ]);
            }
        }

        // Notifikasi user baru
        $newUsers = User::where('role', 'user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        foreach ($newUsers as $user) {
            $existingNotification = Notification::where('user_id', $userId)
                ->where('type', 'user')
                ->where('message', 'like', '%' . $user->name . '%')
                ->first();
                
            if (!$existingNotification) {
                Notification::create([
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

    // Update method markAsRead
    public function markAsRead($id)
    {
        try {
            $notification = Notification::find($id);
            
            if ($notification) {
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