<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use App\Models\Review;
use App\Models\Pemesanan;
use App\Models\Jadwal;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PesanController extends Controller
{
    /* ======================================================
     *  LIST VENUE
     * ====================================================== */
    public function index()
    {
        $venues = Venue::where('status', 'Aktif')
            ->orderByDesc('rating')
            ->orderByDesc('reviews_count')
            ->get();

        return view('user.pesan.index', compact('venues'));
    }

    /* ======================================================
     *  DETAIL VENUE
     * ====================================================== */
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

        $jadwalList = Jadwal::where('venue_id', $venue->id)
        ->where('tanggal', $selectedDate)
        ->where('status', 'Available')
        ->orderBy('waktu_mulai')
        ->get();

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

        dd([
            'date_dari_request' => request('date'),
            'selectedDate' => $selectedDate,
            'hasil_jadwal' => $jadwalList->toArray(),
        ]);
        
    }

    /* ======================================================
     *  FORM BOOKING
     * ====================================================== */
    public function booking($id)
    {
        $venue = Venue::findOrFail($id);

        return view('user.pesan.pembayaran', [
            'venue' => $venue,
            'date' => now()->format('d F Y'),
            'time' => '08:00',
            'hours' => 2,
            'duration' => '2 Jam',
            'total' => $venue->price_per_hour * 2,
        ]);
    }

    /* ======================================================
     *  SIMPAN BOOKING (LOCK 15 MENIT)
     * ====================================================== */
    public function storeBooking(Request $request)
    {
        $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'tanggal_booking' => 'required|date',
            'waktu_booking' => 'required',
            'durasi' => 'required|integer|min:1',
        ]);

        $waktuMulai = $request->waktu_booking;
        $durasi = (int) $request->durasi;

        $endTime = Carbon::createFromFormat('H:i', $waktuMulai)
            ->addHours($durasi)
            ->format('H:i:s');

        // ❗ Cek bentrok booking
        if (Pemesanan::hasConflict(
            $request->venue_id,
            $request->tanggal_booking,
            $waktuMulai,
            $endTime
        )) {
            return back()->with('error', 'Slot waktu sudah dibooking.');
        }

        $venue = Venue::findOrFail($request->venue_id);
        $totalBiaya = $venue->price_per_hour * $durasi;

        $booking = Pemesanan::create([
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

        return redirect()->route('pesan.bayar', [
            'booking_code' => $booking->booking_code
        ]);
    }

    /* ======================================================
     *  HALAMAN BAYAR
     * ====================================================== */
    public function bayar(Request $request)
    {
        $booking = Pemesanan::where('booking_code', $request->booking_code)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($booking->is_expired) {
            $booking->markAsExpired();
            return redirect()->route('pesan.riwayat-booking')
                ->with('error', 'Waktu pembayaran telah habis.');
        }

        return view('user.pesan.bayar', [
            'booking' => $booking,
            'venue' => $booking->venue,
            'total' => $booking->total_biaya,
            'adminFee' => max(5000, $booking->total_biaya * 0.05),
            'totalPayment' => max(5000, $booking->total_biaya * 0.05) + $booking->total_biaya,
        ]);
    }

    /* ======================================================
     *  PROSES BAYAR (TANPA GATEWAY)
     * ====================================================== */
    public function prosesBayar(Request $request)
    {
        $request->validate([
            'booking_code' => 'required',
            'payment_method' => 'required|in:cash,transfer,qris',
        ]);

        $booking = Pemesanan::where('booking_code', $request->booking_code)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($booking->is_expired) {
            $booking->markAsExpired();
            return redirect()->route('pesan.riwayat-booking')
                ->with('error', 'Booking sudah kadaluarsa.');
        }

        $booking->markAsConfirmed($request->payment_method);

        Transaction::create([
            'transaction_number' => 'TRX-' . strtoupper(Str::random(8)),
            'booking_id' => $booking->id,
            'customer_id' => Auth::id(),
            'amount' => $booking->total_biaya,
            'transaction_date' => now(),
            'status' => 'completed',
        ]);

        return redirect()->route('pesan.riwayat-booking')
            ->with('success', 'Pembayaran berhasil, booking dikonfirmasi.');
    }

    /* ======================================================
     *  RIWAYAT BOOKING
     * ====================================================== */
    public function riwayatBooking()
    {
        $bookings = Pemesanan::where('user_id', Auth::id())
            ->with('venue')
            ->latest()
            ->get();

        return view('user.pesan.riwayat-booking', compact('bookings'));
    }

    /* ======================================================
     *  CANCEL BOOKING
     * ====================================================== */
    public function cancelBooking($id)
    {
        $booking = Pemesanan::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!$booking->canBeCancelled()) {
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak bisa dibatalkan'
            ], 400);
        }

        $booking->markAsCancelled();

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil dibatalkan'
        ]);
    }

    private function getFacilitiesFromDatabase($venue)
    {
        if (empty($venue->facilities)) {
            return [];
        }
    
        // Decode JSON jika masih string
        $facilities = is_string($venue->facilities)
            ? json_decode($venue->facilities, true)
            : $venue->facilities;
    
        if (!is_array($facilities)) {
            return [];
        }
    
        // Mapping agar sesuai Blade (icon + name)
        $iconMap = [
            'Wifi'      => 'fas fa-wifi',
            'Toilet'    => 'fas fa-restroom',
            'Parkir'    => 'fas fa-parking',
            'Kantin'    => 'fas fa-utensils',
            'Mushola'   => 'fas fa-mosque',
            'Ruang Ganti' => 'fas fa-tshirt',
        ];
    
        $result = [];
    
        foreach ($facilities as $facility) {
            // kalau string → convert ke array
            if (is_string($facility)) {
                $result[] = [
                    'name' => $facility,
                    'icon' => $iconMap[$facility] ?? 'fas fa-check-circle',
                ];
            }
    
            // kalau sudah array & lengkap → loloskan
            elseif (is_array($facility) && isset($facility['name'], $facility['icon'])) {
                $result[] = $facility;
            }
        }
    
        return $result;
    }
    

    private function getVenueImageUrl($venue)
    {
        // jika venue tidak punya foto
        if (empty($venue->photo)) {
            return asset('images/default-venue.jpg');
        }

        // jika foto disimpan di storage/app/public
        if (file_exists(public_path('storage/' . $venue->photo))) {
            return asset('storage/' . $venue->photo);
        }

        // fallback jika path sudah full
        return asset($venue->photo);
    }

    /* ======================================================
     *  HELPER (TETAP)
     * ====================================================== */
    // helper methods kamu BIARKAN seperti semula
}
