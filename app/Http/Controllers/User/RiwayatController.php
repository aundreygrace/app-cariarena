<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pemesanan;
use App\Models\Venue;
use App\Models\Review;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RiwayatController extends Controller
{
    /**
     * Menampilkan daftar riwayat booking pengguna
     */
    public function index()
    {
        // DEBUG: Cek waktu server
        \Log::info('=== RIWAYAT PAGE LOADED ===');
        \Log::info('Server Time: ' . Carbon::now()->format('Y-m-d H:i:s'));
        \Log::info('User: ' . Auth::user()->name);

        $user = Auth::user();
        $userNameQuoted = '"' . $user->name . '"';
        
        // Ambil pemesanan user
        $pemesanans = Pemesanan::with(['venue'])
            ->where(function($query) use ($user, $userNameQuoted) {
                $query->where('user_id', $user->id)
                      ->orWhere('nama_customer', $user->name)
                      ->orWhere('nama_customer', $userNameQuoted);
            })
            ->orderBy('tanggal_booking', 'desc')
            ->orderBy('waktu_booking', 'desc')
            ->get();
        
        // Jika tidak ada pemesanan, return empty
        if ($pemesanans->isEmpty()) {
            return view('user.riwayat', [
                'orders' => collect(),
                'title' => 'Riwayat Pesanan'
            ]);
        }
        
        // Format data
        $formattedPemesanans = $pemesanans->map(function ($pemesanan) use ($user, $userNameQuoted) {
            // CARI REVIEW untuk pemesanan ini
            $review = Review::where('venue_id', $pemesanan->venue_id)
                ->where(function($query) use ($pemesanan, $user, $userNameQuoted) {
                    $customerNameQuoted = '"' . $pemesanan->nama_customer . '"';
                    
                    $query->where('customer_name', $pemesanan->nama_customer)
                          ->orWhere('customer_name', $customerNameQuoted)
                          ->orWhere('customer_name', $user->name)
                          ->orWhere('customer_name', $userNameQuoted);
                })
                ->first();
            
            // Tambahkan data review
            $pemesanan->review_id = $review ? $review->id : null;
            $pemesanan->review_rating = $review ? $review->rating : null;
            $pemesanan->review_comment = $review ? $review->comment : null;
            
            return $this->formatPemesananData($pemesanan);
        });

        return view('user.riwayat', [
            'orders' => $formattedPemesanans,
            'title' => 'Riwayat Pesanan'
        ]);
    }
    
    /**
     * Format data pemesanan untuk view
     */
    private function formatPemesananData($pemesanan)
    {

        // DEBUG LOGGING
    \Log::info('=== FORMATTING PEMESANAN ===');
    \Log::info('Booking ID: ' . $pemesanan->id);
    \Log::info('Status DB: ' . $pemesanan->status);
    \Log::info('Tanggal: ' . $pemesanan->tanggal_booking);
    \Log::info('Waktu: ' . $pemesanan->waktu_booking);

        // Tentukan status untuk frontend
        $statusInfo = $this->getStatusInfo($pemesanan);
        
        // Format tanggal dan waktu
        $dateTimeInfo = $this->getDateTimeInfo($pemesanan);
        
        // Data venue (dengan gambar)
        $venueInfo = $this->getVenueInfo($pemesanan);
        
        // Cek review yang sudah ada
        $hasReview = !empty($pemesanan->review_id);
        $rating = $pemesanan->review_rating ?? 0;
        $reviewComment = $pemesanan->review_comment ?? '';
        
        // Cek apakah bisa dibatalkan - PERHITUNGAN DIPERBAIKI
        $canCancel = $this->canCancelBooking($pemesanan, $dateTimeInfo['bookingDateTime']);
        
        // Cek apakah bisa direview
        $canReview = $this->canReviewBooking($pemesanan, $dateTimeInfo['bookingDateTime'], $hasReview);

        // Tentukan filter status untuk frontend
        $filterStatus = $this->getFilterStatus($pemesanan, $dateTimeInfo['isPast'], $statusInfo['class']);

        // DEBUG: Log hasil can_cancel dan can_review
    \Log::info('Can Cancel: ' . ($canCancel ? 'YES' : 'NO'));
    \Log::info('Can Review: ' . ($canReview ? 'YES' : 'NO'));
    \Log::info('Has Review: ' . ($hasReview ? 'YES' : 'NO'));
    \Log::info('===========================');
    
        return (object)[
            'id' => $pemesanan->id,
            'booking_code' => $pemesanan->booking_code,
            'nama_customer' => $pemesanan->nama_customer,
            'tanggal_booking' => $pemesanan->tanggal_booking,
            'date_formatted' => $dateTimeInfo['dateFormatted'],
            'waktu_booking' => $pemesanan->waktu_booking,
            'time_range' => $dateTimeInfo['timeRange'],
            'end_time' => $pemesanan->end_time,
            'durasi' => $pemesanan->durasi,
            'total_price' => $this->getTotalPrice($pemesanan),
            'total_biaya' => $pemesanan->total_biaya,
            'status' => $pemesanan->status,
            'status_class' => $statusInfo['class'],
            'status_text' => $statusInfo['text'],
            'filter_status' => $filterStatus,
            'venue_name' => $venueInfo['name'],
            'venue_image' => $venueInfo['image'],
            'location' => $venueInfo['location'],
            'category' => $venueInfo['category'],
            'customer_phone' => $pemesanan->customer_phone,
            'catatan' => $pemesanan->catatan,
            'created_at' => $pemesanan->created_at,
            'can_cancel' => $canCancel,
            'can_review' => $canReview,
            'has_rating' => $hasReview,
            'rating' => $rating,
            'review' => $reviewComment,
            'venue_id' => $pemesanan->venue_id,
            'booking_date_time' => $dateTimeInfo['bookingDateTime'],
            'is_past' => $dateTimeInfo['isPast'],
            'is_future' => $dateTimeInfo['isFuture']
        ];
    }

    /**
     * Get filter status untuk frontend
     */
    private function getFilterStatus($pemesanan, $isPast, $statusClass)
    {
        if ($pemesanan->status === 'Dibatalkan') {
            return 'cancelled';
        }
        
        if ($pemesanan->status === 'Selesai') {
            return 'completed';
        }
        
        // Jika status Menunggu/Terkonfirmasi/Dibayar
        if (in_array($pemesanan->status, ['Menunggu', 'Terkonfirmasi', 'Dibayar'])) {
            if ($isPast) {
                return 'completed';
            } else {
                return 'upcoming';
            }
        }
        
        return 'upcoming';
    }

    /**
     * Get status information - LOGIKA DIPERBAIKI
     */
    private function getStatusInfo($pemesanan)
    {
        $status = $pemesanan->status;
        
        // 1. Jika status Dibatalkan
        if ($status === 'Dibatalkan') {
            return ['class' => 'badge-cancelled', 'text' => 'Dibatalkan'];
        }
        
        // 2. Jika status Selesai (dari database)
        if ($status === 'Selesai') {
            return ['class' => 'badge-completed', 'text' => 'Selesai'];
        }
        
        // 3. Untuk status Menunggu/Terkonfirmasi/Dibayar
        if (in_array($status, ['Menunggu', 'Terkonfirmasi', 'Dibayar'])) {
            try {
                $bookingDateTime = Carbon::parse($pemesanan->tanggal_booking . ' ' . $pemesanan->waktu_booking);
                
                // DEBUG: Cek waktu
                \Log::info('=== STATUS CHECK ===');
                \Log::info('Booking ID: ' . $pemesanan->id);
                \Log::info('Tanggal: ' . $pemesanan->tanggal_booking);
                \Log::info('Waktu: ' . $pemesanan->waktu_booking);
                \Log::info('Booking DateTime: ' . $bookingDateTime->format('Y-m-d H:i:s'));
                \Log::info('Now: ' . Carbon::now()->format('Y-m-d H:i:s'));
                \Log::info('Is Past: ' . ($bookingDateTime->isPast() ? 'YES' : 'NO'));
                
                if ($bookingDateTime->isPast()) {
                    // Sudah lewat waktu -> Selesai
                    \Log::info('Result: Selesai (badge hijau)');
                    return ['class' => 'badge-completed', 'text' => 'Selesai'];
                } else {
                    // Masih akan datang -> Tampilkan status asli
                    $displayText = $status;
                    if ($status === 'Dibayar') {
                        $displayText = 'Terkonfirmasi';
                    }
                    \Log::info('Result: ' . $displayText . ' (badge biru)');
                    return ['class' => 'badge-upcoming', 'text' => $displayText];
                }
            } catch (\Exception $e) {
                \Log::error('Error parsing booking time: ' . $e->getMessage());
                return ['class' => 'badge-upcoming', 'text' => $status];
            }
        }
        
        return ['class' => 'badge-upcoming', 'text' => $status];
    }

    /**
     * Get date and time information
     */
    private function getDateTimeInfo($pemesanan)
    {
        try {
            // Format tanggal
            $tanggal = Carbon::parse($pemesanan->tanggal_booking);
            $dateFormatted = $tanggal->locale('id')->isoFormat('dddd, D MMMM YYYY');
        } catch (\Exception $e) {
            $dateFormatted = $pemesanan->tanggal_booking;
        }
        
        // Format waktu
        $timeRange = $this->formatTimeRange($pemesanan);
        
        // Booking datetime untuk logika lainnya
        $bookingDateTime = null;
        $isPast = false;
        $isFuture = false;
        
        try {
            if ($pemesanan->waktu_booking) {
                $bookingDateTime = Carbon::createFromFormat(
                    'Y-m-d H:i:s',
                    $pemesanan->tanggal_booking . ' ' . $pemesanan->waktu_booking . ':00'
                );
                $isPast = $bookingDateTime->isPast();
                $isFuture = $bookingDateTime->isFuture();
                
                // DEBUG
                \Log::info('DateTimeInfo for Booking ' . $pemesanan->id . ':');
                \Log::info('  - Booking DateTime: ' . $bookingDateTime->format('Y-m-d H:i:s'));
                \Log::info('  - Is Past: ' . ($isPast ? 'YES' : 'NO'));
                \Log::info('  - Is Future: ' . ($isFuture ? 'YES' : 'NO'));
            }
        } catch (\Exception $e) {
            \Log::error('Error creating booking datetime: ' . $e->getMessage());
        }
        
        return [
            'dateFormatted' => $dateFormatted,
            'timeRange' => $timeRange,
            'bookingDateTime' => $bookingDateTime,
            'isPast' => $isPast,
            'isFuture' => $isFuture
        ];
    }

    /**
     * Format time range
     */
    private function formatTimeRange($pemesanan)
    {
        try {
            $startTime = Carbon::parse($pemesanan->waktu_booking);
            $endTime = null;
            
            if ($pemesanan->end_time) {
                $endTime = Carbon::parse($pemesanan->end_time);
            } elseif ($pemesanan->durasi) {
                $endTime = $startTime->copy()->addHours($pemesanan->durasi);
            } else {
                $endTime = $startTime->copy()->addHour();
            }
            
            return $startTime->format('H:i') . ' - ' . $endTime->format('H:i');
        } catch (\Exception $e) {
            return $pemesanan->waktu_booking . ' - ' . ($pemesanan->end_time ?? '');
        }
    }

    /**
     * Get venue information
     */
    private function getVenueInfo($pemesanan)
    {
        $venue = $pemesanan->venue;
        
        if (!$venue) {
            return [
                'name' => 'Venue Tidak Diketahui',
                'image' => '',
                'location' => 'Alamat tidak tersedia',
                'category' => 'Futsal'
            ];
        }
        
        // Ambil gambar venue
        $venueImage = '';
        if (!empty($venue->photo)) {
            if (filter_var($venue->photo, FILTER_VALIDATE_URL)) {
                $venueImage = $venue->photo;
            } else {
                $venueImage = asset('storage/' . $venue->photo);
            }
        }
        
        return [
            'name' => $venue->name,
            'image' => $venueImage,
            'location' => $venue->address ?? 'Alamat tidak tersedia',
            'category' => $venue->category ?? 'Futsal'
        ];
    }

    /**
     * Get total price
     */
    private function getTotalPrice($pemesanan)
    {
        if ($pemesanan->total_biaya > 0) {
            return $pemesanan->total_biaya;
        }
        
        $transaksi = Transaksi::where('transaction_number', $pemesanan->booking_code)
            ->orWhere('pemesanan_id', $pemesanan->id)
            ->first();
            
        if ($transaksi && $transaksi->amount > 0) {
            return $transaksi->amount;
        }
        
        return 0;
    }

/**
 * Check if booking can be cancelled - LOGIKA DIPERBAIKI
 */
private function canCancelBooking($pemesanan, $bookingDateTime)
{
    // Status yang bisa dibatalkan
    $cancelableStatus = ['Menunggu', 'Terkonfirmasi', 'Dibayar'];
    
    if (!in_array($pemesanan->status, $cancelableStatus)) {
        \Log::info('Cancel Check ' . $pemesanan->id . ': Status tidak bisa dibatalkan (' . $pemesanan->status . ')');
        return false;
    }
    
    // Periksa waktu booking
    if (!$bookingDateTime instanceof Carbon) {
        \Log::info('Cancel Check ' . $pemesanan->id . ': bookingDateTime bukan Carbon instance');
        return false;
    }
    
    $now = Carbon::now();
    
    // Booking sudah lewat -> tidak bisa cancel
    if ($bookingDateTime->isPast()) {
        \Log::info('Cancel Check ' . $pemesanan->id . ': Booking sudah lewat');
        return false;
    }
    
    // PERHITUNGAN YANG BENAR:
    // Hitung selisih menit hingga booking
    $minutesDifference = $now->diffInMinutes($bookingDateTime, false);
    
    // DEBUG DETAIL
    \Log::info('=== CANCEL CHECK DETAIL ===');
    \Log::info('Booking ID: ' . $pemesanan->id);
    \Log::info('Booking Time: ' . $bookingDateTime->format('Y-m-d H:i:s'));
    \Log::info('Current Time: ' . $now->format('Y-m-d H:i:s'));
    \Log::info('Minutes Difference: ' . $minutesDifference);
    \Log::info('Status: ' . $pemesanan->status);
    \Log::info('==========================');
    
    // LOGIKA DIPERBAIKI: 
    // Bisa dibatalkan jika booking dalam 120 menit ke depan (belum lewat) 
    // dan lebih dari 0 menit (masih akan datang)
    // $minutesDifference positif jika booking masih akan datang
    $canCancel = $minutesDifference > 0 && $minutesDifference <= 120;
    
    \Log::info('Can Cancel: ' . ($canCancel ? 'YES' : 'NO'));
    return $canCancel;
}

  /**
 * Check if booking can be reviewed - LOGIKA DIPERBAIKI
 */
private function canReviewBooking($pemesanan, $bookingDateTime, $hasReview)
{
    // Jika sudah ada review, tidak bisa rating lagi
    if ($hasReview) {
        \Log::info('Review Check ' . $pemesanan->id . ': Sudah ada review');
        return false;
    }
    
    \Log::info('Review Check ' . $pemesanan->id . ': Status=' . $pemesanan->status);
    
    // Jika status Selesai di database -> bisa review
    if ($pemesanan->status === 'Selesai') {
        \Log::info('Review Check ' . $pemesanan->id . ': Status Selesai -> BISA REVIEW');
        return true;
    }
    
    // LOGIKA DIPERBAIKI: Jika waktu booking sudah lewat dan status bukan Dibatalkan -> bisa review
    if (in_array($pemesanan->status, ['Terkonfirmasi', 'Menunggu', 'Dibayar', 'Selesai'])) {
        if ($bookingDateTime instanceof Carbon) {
            $isPast = $bookingDateTime->isPast();
            \Log::info('Review Check ' . $pemesanan->id . ': Is Past=' . ($isPast ? 'YES' : 'NO'));
            
            if ($isPast) {
                \Log::info('Review Check ' . $pemesanan->id . ': Waktu sudah lewat -> BISA REVIEW');
                return true;
            }
        }
    }
    
    \Log::info('Review Check ' . $pemesanan->id . ': TIDAK BISA REVIEW');
    return false;
}

    /**
     * Submit review untuk pemesanan
     */
    public function submitReview(Request $request)
    {
        $request->validate([
            'pemesanan_id' => 'required|exists:booking,id',
            'venue_id' => 'required|exists:venues,id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:500'
        ]);

        $user = Auth::user();
        
        // Cari pemesanan
        $pemesanan = Pemesanan::findOrFail($request->pemesanan_id);
        
        // Validasi kepemilikan
        if (!$this->validasiKepemilikanPemesanan($pemesanan->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Pemesanan tidak ditemukan atau bukan milik Anda'
            ], 403);
        }
        
        // Cek apakah sudah ada review untuk venue ini
        $existingReview = Review::where('venue_id', $request->venue_id)
            ->where('customer_name', $pemesanan->nama_customer)
            ->first();
        
        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah memberikan review untuk venue ini'
            ], 400);
        }
        
        // Buat review baru
        try {
            $review = Review::create([
                'venue_id' => $request->venue_id,
                'customer_name' => $pemesanan->nama_customer,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'created_at' => now()
            ]);
            
            // Update rating venue
            $this->updateVenueRating($request->venue_id);
            
            return response()->json([
                'success' => true,
                'message' => 'Terima kasih! Rating dan ulasan Anda telah berhasil dikirim.',
                'rating' => $request->rating,
                'review' => $request->comment
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error creating review:', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'pemesanan_id' => $request->pemesanan_id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim review. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Batalkan pemesanan
     */
    public function cancelBooking(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:booking,id'
        ]);

        $user = Auth::user();
        
        $pemesanan = Pemesanan::findOrFail($request->order_id);
        
        // Validasi kepemilikan
        if (!$this->validasiKepemilikanPemesanan($pemesanan->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Pemesanan tidak ditemukan atau bukan milik Anda'
            ], 403);
        }
        
        // Cek status
        $cancelableStatus = ['Menunggu', 'Terkonfirmasi', 'Dibayar'];
        if (!in_array($pemesanan->status, $cancelableStatus)) {
            return response()->json([
                'success' => false,
                'message' => 'Pemesanan tidak dapat dibatalkan karena status sudah ' . $pemesanan->status
            ], 400);
        }
        
        // Cek waktu booking
        try {
            $bookingTime = Carbon::parse($pemesanan->tanggal_booking . ' ' . $pemesanan->waktu_booking);
            $now = Carbon::now();
            
            if ($bookingTime->isPast()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pemesanan tidak dapat dibatalkan karena waktu booking sudah lewat'
                ], 400);
            }
            
            // Hitung selisih waktu dalam detik lalu konversi ke menit
            $secondsDifference = $bookingTime->timestamp - $now->timestamp;
            $minutesDifference = floor($secondsDifference / 60);
            
            // DEBUG
            \Log::info('Cancel Validation for Booking ID ' . $pemesanan->id . ':');
            \Log::info('Booking Time: ' . $bookingTime->format('Y-m-d H:i:s'));
            \Log::info('Current Time: ' . $now->format('Y-m-d H:i:s'));
            \Log::info('Seconds Difference: ' . $secondsDifference);
            \Log::info('Minutes Difference: ' . $minutesDifference);
            
            // Validasi: Hanya bisa cancel jika <= 120 menit (2 jam) sebelum booking dan > 0 (belum lewat)
            if ($minutesDifference <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pemesanan tidak dapat dibatalkan karena waktu booking sudah lewat'
                ], 400);
            }
            
            if ($minutesDifference > 120) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pemesanan hanya dapat dibatalkan maksimal 2 jam sebelum waktu booking'
                ], 400);
            }
            
        } catch (\Exception $e) {
            \Log::error('Error checking booking time for cancellation:', [
                'pemesanan_id' => $pemesanan->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses pembatalan'
            ], 500);
        }
        
        // Update status pemesanan
        try {
            $pemesanan->update([
                'status' => 'Dibatalkan',
                'updated_at' => now()
            ]);
            
            // Update transaksi jika ada
            $transaksi = Transaksi::where('transaction_number', $pemesanan->booking_code)
                ->orWhere('booking_id', $pemesanan->id)
                ->first();
                
            if ($transaksi) {
                $transaksi->update([
                    'status' => 'cancelled',
                    'updated_at' => now()
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Pemesanan berhasil dibatalkan! Dana akan dikembalikan dalam 1-3 hari kerja.'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error cancelling booking:', [
                'pemesanan_id' => $pemesanan->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membatalkan pemesanan. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Update rating venue
     */
    private function updateVenueRating($venueId)
    {
        try {
            $reviews = Review::where('venue_id', $venueId)->get();
            
            if ($reviews->count() > 0) {
                $averageRating = round($reviews->avg('rating'), 1);
                $reviewsCount = $reviews->count();
                
                Venue::where('id', $venueId)->update([
                    'rating' => $averageRating,
                    'reviews_count' => $reviewsCount,
                    'updated_at' => now()
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error updating venue rating:', [
                'venue_id' => $venueId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Validasi kepemilikan pemesanan
     */
    private function validasiKepemilikanPemesanan($pemesananId)
    {
        $user = Auth::user();
        
        $pemesanan = Pemesanan::find($pemesananId);
        
        if (!$pemesanan) {
            return false;
        }
        
        return ($pemesanan->user_id == $user->id) || 
               ($pemesanan->nama_customer == $user->name);
    }
}