<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use App\Models\Review;
use App\Models\Pemesanan;
use App\Models\Jadwal;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PesanController extends Controller
{
    public function index()
    {
        $venues = Venue::where('status', 'Aktif')
            ->orderByDesc('rating')
            ->orderByDesc('reviews_count')
            ->get();

        return view('user.pesan.index', compact('venues'));
    }

    public function pesanSekarang($id)
    {
        $venue = Venue::findOrFail($id);

        if ($venue->status !== 'Aktif') {
            return redirect()->route('pesan.index')
                ->with('error', 'Venue sedang tidak tersedia.');
        }

        $reviews = Review::where('venue_id', $id)
            ->latest()
            ->limit(4)
            ->get();
        
        $selectedDate = request('date', now()->toDateString());
        
        try {
            $selectedDate = Carbon::parse($selectedDate)->toDateString();
        } catch (\Exception $e) {
            $selectedDate = now()->toDateString();
        }

        \Log::info("=== DEBUG PESAN SEKARANG ===");
        \Log::info("Venue ID: {$venue->id}");
        \Log::info("Selected Date: {$selectedDate}");

        // ✅ AMBIL JADWAL DENGAN FILTER PAST SLOTS
        $jadwalList = $this->getAvailableSlots($venue->id, $selectedDate);

        \Log::info("Final jadwalList count: " . $jadwalList->count());

        return view('user.pesan.pesan-sekarang', [
            'venue' => $venue,
            'reviews' => $reviews,
            'averageRating' => $venue->rating ?? 0,
            'reviewsCount' => $venue->reviews_count ?? 0,
            'facilities' => $this->getFacilitiesFromDatabase($venue),
            'venueImageUrl' => $this->getVenueImageUrl($venue),
            'jadwalList' => $jadwalList,
            'selectedDate' => $selectedDate,
        ]);
    }

    public function booking($id)
    {
        $venue = Venue::findOrFail($id);

        if ($venue->status !== 'Aktif') {
            return redirect()->route('pesan.index')
                ->with('error', 'Venue sedang tidak tersedia.');
        }

        $selectedDate = request('date', now()->toDateString());
        
        try {
            $selectedDate = Carbon::parse($selectedDate)->toDateString();
        } catch (\Exception $e) {
            $selectedDate = now()->toDateString();
        }

        \Log::info("=== DEBUG BOOKING PAGE ===");
        \Log::info("Venue ID: {$venue->id}");
        \Log::info("Selected Date: {$selectedDate}");

        // ✅ AMBIL JADWAL DENGAN FILTER PAST SLOTS
        $jadwalList = $this->getAvailableSlots($venue->id, $selectedDate);

        \Log::info("Booking page - jadwalList count: " . $jadwalList->count());

        return view('user.pesan.pembayaran', [
            'venue' => $venue,
            'selectedDate' => $selectedDate,
            'jadwalList' => $jadwalList,
            'date' => now()->format('d F Y'),
            'time' => '08:00',
            'hours' => 2,
            'duration' => '2 Jam',
            'total' => $venue->price_per_hour * 2,
        ]);
    }

    /**
     * ✅ METHOD BARU: Get available slots dengan filter past time
     * 
     * @param int $venueId
     * @param string $date
     * @return \Illuminate\Support\Collection
     */
    private function getAvailableSlots($venueId, $date)
    {
        $now = Carbon::now();
        $today = $now->toDateString();
        $currentTime = $now->format('H:i:s');

        $query = DB::table('jadwal')
            ->select('id', 'venue_id', DB::raw('tanggal::text'), DB::raw('waktu_mulai::text'), 'status', 'locked_until')
            ->where('venue_id', $venueId)
            ->whereRaw('tanggal::date = ?::date', [$date])
            ->where('status', 'Available')
            ->where(function($q) {
                $q->whereNull('locked_until')
                  ->orWhere('locked_until', '<', DB::raw('NOW()'));
            });

        // ✅ FILTER: Jika tanggal hari ini, hanya tampilkan slot yang belum lewat
        if ($date === $today) {
            $query->where('waktu_mulai', '>=', $currentTime);
        }

        $rawJadwal = $query->orderBy('waktu_mulai')->get();

        return collect($rawJadwal)->map(function($jadwal) {
            return [
                'id' => $jadwal->id,
                'waktu_mulai' => substr($jadwal->waktu_mulai, 0, 5),
                'tanggal' => $jadwal->tanggal,
                'status' => $jadwal->status,
                'is_available' => true,
                'max_hours' => 12,
            ];
        })->values();
    }

    /**
     * ✅ IMPROVED: Store booking dengan multi-slot locking
     */
    public function storeBooking(Request $request)
    {
        \Log::info("=== STORE BOOKING START ===");
        \Log::info("Request data:", $request->all());
        
        $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'waktu_booking' => 'required|date_format:H:i',
            'durasi' => 'required|integer|min:1|max:12',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                
                $venue = Venue::findOrFail($request->venue_id);
                $waktuMulai = $request->waktu_booking . ':00';
                $durasi = (int) $request->durasi;

                \Log::info("=== MULTI-SLOT BOOKING CHECK ===");
                \Log::info("Waktu Mulai: {$waktuMulai}");
                \Log::info("Durasi: {$durasi} jam");

                // ✅ STEP 1: Check availability untuk slot pertama
                $availability = Jadwal::checkSlotAvailability(
                    $request->venue_id,
                    $request->tanggal_booking,
                    $waktuMulai,
                    $durasi
                );

                if (!$availability['available']) {
                    \Log::error("Slot not available: " . $availability['message']);
                    throw new \Exception($availability['message']);
                }

                $mainJadwal = $availability['jadwal'];
                
                // ✅ STEP 2: Lock slot utama
                $mainJadwal->lock(15);
                \Log::info("✅ Main slot #{$mainJadwal->id} locked");

                // ✅ STEP 3: Lock additional slots jika durasi > 1 jam
                $additionalSlots = [];
                if ($durasi > 1) {
                    $additionalSlots = $this->lockAdditionalSlots(
                        $request->venue_id,
                        $request->tanggal_booking,
                        $waktuMulai,
                        $durasi
                    );
                }

                // ✅ STEP 4: Calculate end time
                $endTime = Carbon::createFromFormat('H:i:s', $waktuMulai)
                    ->addHours($durasi)
                    ->format('H:i:s');

                $totalBiaya = $venue->price_per_hour * $durasi;

                // ✅ STEP 5: Create booking
                $booking = Pemesanan::create([
                    'jadwal_id' => $mainJadwal->id,
                    'venue_id' => $venue->id,
                    'user_id' => Auth::id(),
                    'nama_customer' => Auth::user()->name,
                    'customer_phone' => Auth::user()->phone ?? '-',
                    'tanggal_booking' => $request->tanggal_booking,
                    'waktu_booking' => $waktuMulai,
                    'end_time' => $endTime,
                    'durasi' => $durasi,
                    'total_biaya' => $totalBiaya,
                    'status' => Pemesanan::STATUS_PENDING,
                    'booking_code' => Pemesanan::generateBookingCode(),
                    'payment_expired_at' => now()->addMinutes(15),
                ]);

                // ✅ STEP 6: Link booking to main jadwal
                $mainJadwal->update(['booking_id' => $booking->id]);

                // ✅ STEP 7: Link booking to additional slots
                foreach ($additionalSlots as $slot) {
                    $slot->update(['booking_id' => $booking->id]);
                }

                \Log::info("✅ Booking created successfully!");
                \Log::info("Booking ID: {$booking->id}");
                \Log::info("Booking code: {$booking->booking_code}");
                \Log::info("Main slot: #{$mainJadwal->id}");
                \Log::info("Additional slots locked: " . count($additionalSlots));

                return redirect()->route('pesan.bayar', ['booking_code' => $booking->booking_code])
                    ->with('success', 'Booking berhasil dibuat. Selesaikan pembayaran dalam 15 menit.');

            });

        } catch (\Exception $e) {
            \Log::error('❌ Booking gagal: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
        
            return redirect()
                ->route('pesan.booking', ['id' => $request->venue_id])
                ->with('error', $e->getMessage());
        }
    }

    /**
     * ✅ METHOD BARU: Lock additional slots untuk multi-hour booking
     * 
     * @param int $venueId
     * @param string $tanggal
     * @param string $waktuMulai
     * @param int $durasi
     * @return array Array of Jadwal models yang di-lock
     * @throws \Exception
     */
    private function lockAdditionalSlots($venueId, $tanggal, $waktuMulai, $durasi)
    {
        $lockedSlots = [];
        
        // Loop untuk setiap jam tambahan (mulai dari jam ke-2)
        for ($hour = 1; $hour < $durasi; $hour++) {
            $slotTime = Carbon::createFromFormat('H:i:s', $waktuMulai)
                ->addHours($hour)
                ->format('H:i:s');

            \Log::info("Checking additional slot at: {$slotTime}");

            // Cari slot dengan waktu_mulai yang sesuai
            $slot = Jadwal::where('venue_id', $venueId)
                ->where('tanggal', $tanggal)
                ->where('waktu_mulai', $slotTime)
                ->first();

            if (!$slot) {
                // Unlock semua slot yang sudah di-lock sebelumnya
                foreach ($lockedSlots as $lockedSlot) {
                    $lockedSlot->unlock();
                }
                
                throw new \Exception("Slot jam {$slotTime} tidak tersedia di sistem");
            }

            // Check apakah slot available
            if (!$slot->isAvailable()) {
                // Unlock semua slot yang sudah di-lock sebelumnya
                foreach ($lockedSlots as $lockedSlot) {
                    $lockedSlot->unlock();
                }
                
                throw new \Exception("Slot jam {$slotTime} sudah dibooking atau sedang di-lock");
            }

            // Lock slot ini
            $slot->lock(15);
            $lockedSlots[] = $slot;
            
            \Log::info("✅ Additional slot #{$slot->id} at {$slotTime} locked");
        }

        return $lockedSlots;
    }

    public function bayar($booking_code)
    {
        \Log::info("=== BAYAR PAGE ===");
        \Log::info("Booking code: {$booking_code}");

        $booking = Pemesanan::where('booking_code', $booking_code)
            ->where('user_id', Auth::id())
            ->with(['venue', 'jadwal'])
            ->firstOrFail();

        \Log::info("Booking found: ID {$booking->id}");
        \Log::info("Status: {$booking->status}");

        if ($booking->is_expired) {
            \Log::info("⏰ Booking expired, marking as expired...");
            $booking->markAsExpired();
            
            return redirect()->route('pesan.index')
                ->with('error', 'Waktu pembayaran telah habis. Silakan booking ulang.');
        }

        $remainingMinutes = $booking->remaining_payment_time;

        return view('user.pesan.bayar', [
            'booking' => $booking,
            'venue' => $booking->venue,
            'total' => $booking->total_biaya,
            'adminFee' => max(5000, $booking->total_biaya * 0.05),
            'totalPayment' => max(5000, $booking->total_biaya * 0.05) + $booking->total_biaya,
            'remainingMinutes' => $remainingMinutes,
            'expiresAt' => $booking->payment_expired_at,
        ]);
    }

    /**
     * ✅ IMPROVED: Proses pembayaran dengan multi-slot handling
     */
    public function prosesBayar(Request $request)
    {
        $request->validate([
            'booking_code' => 'required',
            'payment_method' => 'required|in:transfer,qris',
        ]);
    
        return DB::transaction(function () use ($request) {
    
            $booking = Pemesanan::where('booking_code', $request->booking_code)
                ->where('user_id', Auth::id())
                ->lockForUpdate()
                ->firstOrFail();
    
            if ($booking->is_expired) {
                $booking->markAsExpired();
                return redirect()->route('pesan.index')
                    ->with('error', 'Waktu pembayaran habis.');
            }
    
            // ✅ HITUNG BIAYA (FINAL)
            $subtotal = (int) $booking->total_biaya;
            $adminFee = max(5000, round($subtotal * 0.05));
            $grandTotal = $subtotal + $adminFee;
    
            // ✅ UPDATE BOOKING
            $booking->update([
                'status' => Pemesanan::STATUS_CONFIRMED,
                'payment_method' => $request->payment_method,
                'paid_at' => now(),
            ]);
    
            // ✅ UPDATE MAIN JADWAL
            if ($booking->jadwal) {
                $booking->jadwal->markAsBooked($booking->id);
                \Log::info("✅ Main jadwal #{$booking->jadwal->id} marked as booked");
            }

            // ✅ UPDATE ADDITIONAL JADWAL (jika durasi > 1 jam)
            if ($booking->durasi > 1) {
                $this->markAdditionalSlotsAsBooked($booking);
            }
    
            // ✅ SIMPAN TRANSAKSI (SUMBER KEBENARAN UANG)
            Transaksi::create([
                'transaction_number' => 'TRX-' . strtoupper(Str::random(10)),
                'booking_id' => $booking->id,
                'customer_id' => Auth::id(),
                'metode_pembayaran' => $request->payment_method,
                'amount' => $grandTotal,
                'transaction_date' => now(),
                'status' => 'completed',
            ]);
    
            return redirect()->route('pesan.riwayat-booking', [
                'booking_code' => $booking->booking_code
            ]);
        });
    }

    /**
     * ✅ METHOD BARU: Mark additional slots as booked setelah payment confirmed
     * 
     * @param Pemesanan $booking
     */
    private function markAdditionalSlotsAsBooked(Pemesanan $booking)
    {
        $additionalSlots = Jadwal::where('venue_id', $booking->venue_id)
            ->where('tanggal', $booking->tanggal_booking)
            ->where('booking_id', $booking->id)
            ->where('id', '!=', $booking->jadwal_id) // Exclude main slot
            ->get();

        foreach ($additionalSlots as $slot) {
            $slot->markAsBooked($booking->id);
            \Log::info("✅ Additional jadwal #{$slot->id} marked as booked");
        }
    }

    public function riwayatBooking(Request $request)
    {
        $booking = Pemesanan::where('booking_code', $request->booking_code)
            ->where('user_id', auth()->id())
            ->with(['venue', 'transaksi'])
            ->firstOrFail();

        // 🔑 TOTAL FINAL WAJIB DARI TRANSAKSI
        $totalPaid = $booking->transaksi?->amount ?? 0;

        return view('user.pesan.riwayat-booking', [
            'booking'   => $booking,
            'venue'     => $booking->venue,
            'totalPaid' => $totalPaid,
        ]);
    }

    /**
     * ✅ IMPROVED: Cancel booking dengan multi-slot unlocking
     */
    public function cancelBooking($id)
    {
        \Log::info("=== CANCEL BOOKING ===");
        \Log::info("Booking ID: {$id}");
        
        try {
            DB::transaction(function () use ($id) {
                
                $booking = Pemesanan::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->with('jadwal')
                    ->lockForUpdate()
                    ->firstOrFail();

                \Log::info("Booking found: Status {$booking->status}");

                // ✅ Only pending bookings can be cancelled
                if ($booking->status !== Pemesanan::STATUS_PENDING) {
                    throw new \Exception('Hanya booking dengan status pending yang bisa dibatalkan');
                }

                // ✅ Mark as cancelled
                $booking->update([
                    'status' => Pemesanan::STATUS_CANCELLED,
                    'cancelled_at' => now(),
                    'cancellation_reason' => 'Dibatalkan oleh user',
                ]);

                \Log::info("✅ Booking cancelled");

                // ✅ Release main jadwal
                if ($booking->jadwal) {
                    $booking->jadwal->unlock();
                    \Log::info("✅ Main jadwal released");
                }

                // ✅ Release additional slots
                $this->unlockAdditionalSlots($booking);
            });

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibatalkan. Slot telah dikembalikan.'
            ]);

        } catch (\Exception $e) {
            \Log::error("❌ Error cancel booking: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ METHOD BARU: Unlock additional slots saat booking dibatalkan/expired
     * 
     * @param Pemesanan $booking
     */
    private function unlockAdditionalSlots(Pemesanan $booking)
    {
        $additionalSlots = Jadwal::where('venue_id', $booking->venue_id)
            ->where('tanggal', $booking->tanggal_booking)
            ->where('booking_id', $booking->id)
            ->where('id', '!=', $booking->jadwal_id) // Exclude main slot
            ->get();

        foreach ($additionalSlots as $slot) {
            $slot->unlock();
            \Log::info("✅ Additional jadwal #{$slot->id} unlocked");
        }
    }

    /**
     * Helper: Get status text
     */
    private function getStatusText($status)
    {
        $statusMap = [
            'pending' => 'Menunggu Pembayaran',
            'confirmed' => 'Terkonfirmasi',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'expired' => 'Kadaluarsa',
        ];

        return $statusMap[$status] ?? ucfirst($status);
    }

    /**
     * Helper: Get status badge class
     */
    private function getStatusBadge($status)
    {
        $badgeMap = [
            'pending' => 'warning',
            'confirmed' => 'success',
            'completed' => 'info',
            'cancelled' => 'danger',
            'expired' => 'secondary',
        ];

        return $badgeMap[$status] ?? 'secondary';
    }

    private function getFacilitiesFromDatabase($venue)
    {
        if (empty($venue->facilities)) {
            return [];
        }
    
        $facilities = is_string($venue->facilities)
            ? json_decode($venue->facilities, true)
            : $venue->facilities;
    
        if (!is_array($facilities)) {
            return [];
        }
    
        $iconMap = [
            'Wifi' => 'fas fa-wifi',
            'Toilet' => 'fas fa-restroom',
            'Parkir' => 'fas fa-parking',
            'Kantin' => 'fas fa-utensils',
            'Mushola' => 'fas fa-mosque',
            'Ruang Ganti' => 'fas fa-tshirt',
        ];
    
        $result = [];
    
        foreach ($facilities as $facility) {
            if (is_string($facility)) {
                $result[] = [
                    'name' => $facility,
                    'icon' => $iconMap[$facility] ?? 'fas fa-check-circle',
                ];
            }
            elseif (is_array($facility) && isset($facility['name'], $facility['icon'])) {
                $result[] = $facility;
            }
        }
    
        return $result;
    }

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
}