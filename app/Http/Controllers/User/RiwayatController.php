<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Pemesanan;
use App\Models\Venue;
use App\Models\Review;
use App\Models\Jadwal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class RiwayatController extends Controller
{
    /**
     * ✅ Display riwayat booking dengan state management
     */
    public function index()
    {
        \Log::info("=== RIWAYAT PAGE ===");
        \Log::info("User ID: " . Auth::id());
        \Log::info("Server Time: " . Carbon::now()->format('Y-m-d H:i:s'));
        
        $user = Auth::user();
        
        // Get all bookings untuk user ini
        $bookings = Pemesanan::with(['venue', 'jadwal'])
            ->where('user_id', $user->id)
            ->whereIn('status', ['confirmed', 'completed', 'cancelled'])
            ->orderBy('tanggal_booking', 'desc')
            ->orderBy('waktu_booking', 'desc')
            ->get();
        
        // Format data dengan state logic
        $orders = $bookings->map(function ($booking) {
            return $this->formatBookingWithState($booking);
        });
        
        \Log::info("Total bookings: " . $orders->count());
        
        return view('user.riwayat', [
            'orders' => $orders,
            'title' => 'Riwayat Booking'
        ]);
    }
    
    /**
     * ✅ FORMAT BOOKING DENGAN STATE LOGIC
     * State logic lengkap untuk UI
     */
    private function formatBookingWithState($booking)
    {
        $now = Carbon::now();
        
        // Parse booking datetime
        $bookingDate = Carbon::parse($booking->tanggal_booking)->format('Y-m-d');

        $bookingDateTime = Carbon::parse($bookingDate . ' ' . $booking->waktu_booking);
        $endDateTime = Carbon::parse($bookingDate . ' ' . $booking->end_time);
        
        // Time until booking starts (in hours)
        $hoursUntilStart = $now->diffInHours($bookingDateTime, false);
        
        // Check if booking has started
        $hasStarted = $now->gte($bookingDateTime);
        
        // Check if booking has ended
        $hasEnded = $now->gte($endDateTime);
        
        // Check if review exists
        $review = Review::where('venue_id', $booking->venue_id)
            ->where('user_id', Auth::id())
            ->where('booking_id', $booking->id)
            ->first();
        
        $hasReview = !is_null($review);
        
        \Log::info("=== BOOKING #{$booking->id} STATE ===");
        \Log::info("Status: {$booking->status}");
        \Log::info("Booking Time: " . $bookingDateTime->format('Y-m-d H:i:s'));
        \Log::info("End Time: " . $endDateTime->format('Y-m-d H:i:s'));
        \Log::info("Now: " . $now->format('Y-m-d H:i:s'));
        \Log::info("Hours Until Start: " . $hoursUntilStart);
        \Log::info("Has Started: " . ($hasStarted ? 'YES' : 'NO'));
        \Log::info("Has Ended: " . ($hasEnded ? 'YES' : 'NO'));
        \Log::info("Has Review: " . ($hasReview ? 'YES' : 'NO'));
        
        // ========== STATE LOGIC ==========
        
        // 1. CANCELLED - Booking dibatalkan
        if ($booking->status === 'cancelled') {
            $state = [
                'status' => 'cancelled',
                'statusText' => 'Dibatalkan',
                'statusBadge' => 'badge-cancelled', // Abu-abu
                'canCancel' => false,
                'canReview' => false,
                'showReview' => false,
                'buttonText' => null,
                'buttonAction' => null,
                'buttonClass' => null,
                'buttonDisabled' => true,
            ];
        }
        // 2. CONFIRMED - Belum mulai, bisa cancel (> 2 jam sebelum)
        elseif ($booking->status === 'confirmed' && !$hasStarted && $hoursUntilStart > 2) {
            $state = [
                'status' => 'upcoming',
                'statusText' => 'Akan Datang',
                'statusBadge' => 'badge-upcoming', // Biru
                'canCancel' => true,
                'canReview' => false,
                'showReview' => false,
                'buttonText' => 'Batalkan Booking',
                'buttonAction' => 'cancel',
                'buttonClass' => 'btn-danger',
                'buttonDisabled' => false,
            ];
        }
        // 3. CONFIRMED - Kurang dari 2 jam, tidak bisa cancel
        elseif ($booking->status === 'confirmed' && !$hasStarted && $hoursUntilStart <= 2) {
            $state = [
                'status' => 'upcoming_locked',
                'statusText' => 'Akan Datang',
                'statusBadge' => 'badge-upcoming', // Biru
                'canCancel' => false,
                'canReview' => false,
                'showReview' => false,
                'buttonText' => 'Batalkan Booking',
                'buttonAction' => null,
                'buttonClass' => 'btn-disabled',
                'buttonDisabled' => true,
                'disabledReason' => 'Tidak dapat dibatalkan (< 2 jam sebelum waktu booking)',
            ];
        }
        // 4. CONFIRMED - Sedang berlangsung
        elseif ($booking->status === 'confirmed' && $hasStarted && !$hasEnded) {
            $state = [
                'status' => 'ongoing',
                'statusText' => 'Sedang Berlangsung',
                'statusBadge' => 'badge-ongoing', // Hijau terang
                'canCancel' => false,
                'canReview' => false,
                'showReview' => false,
                'buttonText' => 'Batalkan Booking',
                'buttonAction' => null,
                'buttonClass' => 'btn-disabled',
                'buttonDisabled' => true,
                'disabledReason' => 'Booking sedang berlangsung',
            ];
        }
        // 5. CONFIRMED/COMPLETED - Sudah selesai, belum review
        elseif (in_array($booking->status, ['confirmed', 'completed']) && $hasEnded && !$hasReview) {
            $state = [
                'status' => 'completed_no_review',
                'statusText' => 'Selesai',
                'statusBadge' => 'badge-completed', // Hijau
                'canCancel' => false,
                'canReview' => true,
                'showReview' => false,
                'buttonText' => 'Beri Rating & Ulasan',
                'buttonAction' => 'review',
                'buttonClass' => 'btn-primary',
                'buttonDisabled' => false,
            ];
        }
        // 6. COMPLETED - Sudah review
        elseif ($hasEnded && $hasReview) {
            $state = [
                'status' => 'completed_reviewed',
                'statusText' => 'Selesai',
                'statusBadge' => 'badge-completed', // Hijau
                'canCancel' => false,
                'canReview' => false,
                'showReview' => true,
                'review' => [
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'created_at' => Carbon::parse($review->created_at)->format('d M Y')
                ],
                'buttonText' => null,
                'buttonAction' => null,
                'buttonClass' => null,
                'buttonDisabled' => true,
            ];
        }
        // 7. DEFAULT - Fallback
        else {
            $state = [
                'status' => 'unknown',
                'statusText' => ucfirst($booking->status),
                'statusBadge' => 'badge-secondary',
                'canCancel' => false,
                'canReview' => false,
                'showReview' => false,
                'buttonText' => null,
                'buttonAction' => null,
                'buttonClass' => null,
                'buttonDisabled' => true,
            ];
        }
        
        // ========== RETURN FORMATTED DATA ==========
        
        return [
            'id' => $booking->id,
            'booking_code' => $booking->booking_code,
            
            // Venue info
            'venue_id' => $booking->venue_id,
            'venue_name' => $booking->venue->name ?? 'Venue',
            'venue_image' => $this->getVenueImageUrl($booking->venue),
            'venue_location' => $booking->venue->address ?? '-',
            'venue_category' => $booking->venue->category ?? 'Futsal',
            
            // Booking details
            'date' => Carbon::parse($booking->tanggal_booking)->locale('id')->isoFormat('dddd, D MMMM YYYY'),
            'date_short' => Carbon::parse($booking->tanggal_booking)->format('d M Y'),
            'time_start' => Carbon::parse($booking->waktu_booking)->format('H:i'),
            'time_end' => Carbon::parse($booking->end_time)->format('H:i'),
            'time_range' => Carbon::parse($booking->waktu_booking)->format('H:i') . ' - ' . Carbon::parse($booking->end_time)->format('H:i'),
            'duration' => $booking->durasi . ' Jam',
            
            // Price
            'total' => 'Rp ' . number_format($booking->total_biaya, 0, ',', '.'),
            'total_raw' => $booking->total_biaya,
            
            // Payment
            'payment_method' => $booking->payment_method ? ucfirst($booking->payment_method) : 'Transfer',
            
            // State (dari logic di atas)
            'state' => $state,
            
            // Raw data untuk debugging
            'raw_status' => $booking->status,
            'booking_datetime' => $bookingDateTime->format('Y-m-d H:i:s'),
            'end_datetime' => $endDateTime->format('Y-m-d H:i:s'),
        ];
    }
    
    /**
     * ✅ GET VENUE IMAGE
     */
    private function getVenueImageUrl($venue)
    {
        if (empty($venue->photo)) {
            return asset('images/default-venue.jpg');
        }

        if (file_exists(public_path('storage/' . $venue->photo))) {
            return asset('storage/' . $venue->photo);
        }

        return asset($venue->photo);
    }
    
    /**
     * ✅ CANCEL BOOKING
     * Hanya bisa cancel jika:
     * - Status = confirmed
     * - Lebih dari 2 jam sebelum waktu booking
     */
    public function cancelBooking($id)
    {
        \Log::info("=== CANCEL BOOKING ===");
        \Log::info("Booking ID: {$id}");
        \Log::info("User ID: " . Auth::id());
        
        try {
            return DB::transaction(function () use ($id) {
                
                // Get booking with lock
                $booking = Pemesanan::with(['jadwal', 'venue'])
                    ->where('id', $id)
                    ->where('user_id', Auth::id())
                    ->lockForUpdate()
                    ->firstOrFail();
                
                \Log::info("Booking found: {$booking->booking_code}, Status: {$booking->status}");
                
                // Check if can cancel
                $now = Carbon::now();
                $bookingDate = Carbon::parse($booking->tanggal_booking)->format('Y-m-d');
                $bookingDateTime = Carbon::parse($bookingDate . ' ' . $booking->waktu_booking);

                $hoursUntilStart = $now->diffInHours($bookingDateTime, false);
                
                \Log::info("Hours until start: {$hoursUntilStart}");
                
                // Validation: Must be confirmed
                if ($booking->status !== 'confirmed') {
                    \Log::warning("Cannot cancel: Status is {$booking->status}");
                    return response()->json([
                        'success' => false,
                        'message' => 'Hanya booking dengan status "Terkonfirmasi" yang dapat dibatalkan'
                    ], 400);
                }
                
                // Validation: Must be > 2 hours before
                if ($hoursUntilStart <= 2) {
                    \Log::warning("Cannot cancel: Only {$hoursUntilStart} hours remaining");
                    return response()->json([
                        'success' => false,
                        'message' => 'Booking hanya dapat dibatalkan minimal 2 jam sebelum waktu booking'
                    ], 400);
                }
                
                // Update booking status
                $booking->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'cancellation_reason' => 'Dibatalkan oleh user',
                ]);
                
                \Log::info("✅ Booking cancelled");
                
                // Release jadwal
                if ($booking->jadwal) {
                    $booking->jadwal->update([
                        'status' => Jadwal::STATUS_AVAILABLE,
                        'locked_until' => null,
                        'booking_id' => null,
                    ]);
                    
                    \Log::info("✅ Jadwal released: ID {$booking->jadwal->id}");
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Booking berhasil dibatalkan. Slot telah dikembalikan.'
                ]);
            });
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error("Booking not found: {$id}");
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan'
            ], 404);
            
        } catch (\Exception $e) {
            \Log::error("❌ Error cancelling booking: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * ✅ SUBMIT REVIEW
     * Hanya bisa review jika:
     * - Booking sudah selesai (lewat end_time)
     * - Belum pernah review
     * - Status = confirmed/completed
     */
    public function submitReview(Request $request, $id)
    {
        \Log::info("=== SUBMIT REVIEW ===");
        \Log::info("Booking ID: {$id}");
        \Log::info("User ID: " . Auth::id());
        
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:10', 'max:1000'],
        ], [
            'rating.required' => 'Rating wajib diisi',
            'rating.min' => 'Rating minimal 1 bintang',
            'rating.max' => 'Rating maksimal 5 bintang',
            'comment.required' => 'Ulasan wajib diisi',
            'comment.min' => 'Ulasan minimal 10 karakter',
            'comment.max' => 'Ulasan maksimal 1000 karakter',
        ]);
        
        try {
            return DB::transaction(function () use ($id, $validated) {
                
                // Get booking
                $booking = Pemesanan::with('venue')
                    ->where('id', $id)
                    ->where('user_id', Auth::id())
                    ->lockForUpdate()
                    ->firstOrFail();
                
                \Log::info("Booking found: {$booking->booking_code}");
                
                // Check if booking has ended
                $now = Carbon::now();
                $bookingDate = Carbon::parse($booking->tanggal_booking)->format('Y-m-d');
                $endDateTime = Carbon::parse($bookingDate . ' ' . $booking->end_time);
                
                if ($now->lt($endDateTime)) {
                    \Log::warning("Cannot review: Booking hasn't ended yet");
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda hanya dapat memberikan review setelah booking selesai'
                    ], 400);
                }
                
                // Check if already reviewed
                $existingReview = Review::where('venue_id', $booking->venue_id)
                    ->where('user_id', Auth::id())
                    ->where('booking_id', $booking->id)
                    ->first();
                
                if ($existingReview) {
                    \Log::warning("Already reviewed");
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda sudah memberikan review untuk booking ini'
                    ], 400);
                }
                
                // Create review
                $review = Review::create([
                    'venue_id' => $booking->venue_id,
                    'user_id' => Auth::id(),
                    'booking_id' => $booking->id,
                    'customer_name' => Auth::user()->name,
                    'rating' => $validated['rating'],
                    'comment' => $validated['comment'],
                ]);
                
                \Log::info("✅ Review created: ID {$review->id}");
                
                // Update booking status to completed
                if ($booking->status !== 'completed') {
                    $booking->update(['status' => 'completed']);
                    \Log::info("✅ Booking status updated to completed");
                }
                
                // Update venue rating
                $this->updateVenueRating($booking->venue_id);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Terima kasih! Review Anda berhasil dikirim.',
                    'review' => [
                        'id' => $review->id,
                        'rating' => $review->rating,
                        'comment' => $review->comment,
                        'created_at' => Carbon::parse($review->created_at)->format('d M Y')
                    ]
                ]);
            });
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error("Booking not found: {$id}");
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan'
            ], 404);
            
        } catch (\Exception $e) {
            \Log::error("❌ Error submitting review: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * ✅ UPDATE VENUE RATING
     * Recalculate average rating dan total reviews
     */
    private function updateVenueRating($venueId)
    {
        $stats = Review::where('venue_id', $venueId)
            ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as total_reviews')
            ->first();
        
        Venue::where('id', $venueId)->update([
            'rating' => round($stats->avg_rating, 1),
            'reviews_count' => $stats->total_reviews,
        ]);
        
        \Log::info("✅ Venue rating updated: {$stats->avg_rating} ({$stats->total_reviews} reviews)");
    }
}