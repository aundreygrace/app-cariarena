<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use App\Models\Review;
use App\Models\Pemesanan;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\BookingService;

class PesanController extends Controller
{
    public function index()
    {
        // Ambil data venues aktif dari database
        $venues = Venue::where('status', 'Aktif')
                      ->orderBy('rating', 'desc')
                      ->orderBy('reviews_count', 'desc')
                      ->get();

        return view('user.pesan.index', compact('venues'));
    }

    public function pesanSekarang($id)
    {
        try {
            // Cari venue berdasarkan ID
            $venue = Venue::findOrFail($id);
            
            // Cek apakah venue aktif
            if ($venue->status !== 'Aktif') {
                return redirect()->route('pesan.index')
                               ->with('error', 'Venue sedang tidak tersedia untuk pemesanan.');
            }

            // Ambil data reviews untuk venue ini
            $reviews = Review::where('venue_id', $id)
                            ->orderBy('created_at', 'desc')
                            ->limit(4)
                            ->get();

            // Hitung rating rata-rata dari database
            $averageRating = $venue->rating ?? 0;

            // Hitung jumlah reviews dari database
            $reviewsCount = $venue->reviews_count ?? 0;

            // Siapkan data fasilitas
            $facilities = $this->getFacilitiesFromDatabase($venue);

            // Process reviews untuk menambahkan initials
            $processedReviews = $reviews->map(function($review) {
                $review->initials = $this->getInitials($review->customer_name);
                return $review;
            });

            // Get venue image URL
            $venueImageUrl = $this->getVenueImageUrl($venue);

            return view('user.pesan.pesan-sekarang', compact(
                'venue', 
                'reviews',
                'processedReviews', 
                'averageRating', 
                'reviewsCount',
                'facilities',
                'venueImageUrl'
            ));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('pesan.index')
                           ->with('error', 'Venue tidak ditemukan.');
        }
    }

    /**
     * Method untuk halaman booking/pemesanan
     */
    public function booking($id)
    {
        try {
            // Cari venue berdasarkan ID
            $venue = Venue::findOrFail($id);
            
            // Cek apakah venue aktif
            if ($venue->status !== 'Aktif') {
                return redirect()->route('pesan.index')
                            ->with('error', 'Venue sedang tidak tersedia untuk pemesanan.');
            }

            // Set default values untuk preview
            $date = 'Minggu, 2 Nov 2023';
            $time = '07.00';
            $duration = '2 Jam';
            $total = $venue->price_per_hour * 2; // Default 2 jam
            $hours = 2;

            return view('user.pesan.pembayaran', compact(
                'venue',
                'date',
                'time',
                'duration',
                'total',
                'hours'
            ));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('pesan.index')
                        ->with('error', 'Venue tidak ditemukan.');
        }
    }

    /**
     * Method untuk halaman pembayaran
     */
    public function pembayaran(Request $request)
    {
        // Validasi request
        $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'date' => 'required|date',
            'time' => 'required',
            'duration' => 'required|integer|min:1',
        ]);

        $venueId = $request->venue_id;
        $date = $request->date;
        $time = $request->time;
        $duration = $request->duration;

        // Cari venue
        $venue = Venue::findOrFail($venueId);

        // Hitung total biaya
        $total = $venue->price_per_hour * $duration;

        // Generate booking code
        $bookingCode = 'BK-' . Str::random(8);

        return view('user.pesan.pembayaran', compact(
            'venue', 
            'date', 
            'time', 
            'duration', 
            'total', 
            'bookingCode'
        ));
    }

    /**
     * Method untuk halaman bayar
     */
    public function bayar(Request $request)
    {
        // Ambil parameter dari request (bisa dari query string atau request body)
        $venueId = $request->input('venue_id') ?? $request->venue_id;
        $date = $request->input('date') ?? $request->date;
        $time = $request->input('time') ?? $request->time;
        $duration = $request->input('duration') ?? $request->duration;
        $total = $request->input('total') ?? $request->total;
        $bookingCode = $request->input('booking_code') ?? 'BK-' . Str::random(8);
        
        // Jika tidak ada venue_id, coba ambil dari session atau default
        if (!$venueId) {
            // Coba ambil dari session terakhir
            $venueId = session('last_venue_id');
            
            if (!$venueId) {
                // Default ke venue pertama atau redirect
                $venue = Venue::where('status', 'Aktif')->first();
                if ($venue) {
                    $venueId = $venue->id;
                } else {
                    return redirect()->route('pesan.index')
                                ->with('error', 'Tidak ada venue yang tersedia.');
                }
            }
        }
        
        // Cari venue
        $venue = Venue::find($venueId);
        if (!$venue) {
            return redirect()->route('pesan.index')
                        ->with('error', 'Venue tidak ditemukan.');
        }
        
        // Pastikan total tidak kosong
        $total = (float) ($total ?: ($venue->price_per_hour * 2)); // Default 2 jam
        
        // Hitung biaya admin (5% dari total atau minimal Rp 5.000)
        $adminFee = max(5000, $total * 0.05);
        $totalPayment = $total + $adminFee;

        // Simpan data untuk digunakan nanti
        session([
            'last_venue_id' => $venueId,
            'booking_data' => [
                'date' => $date,
                'time' => $time,
                'duration' => $duration,
                'total' => $total,
                'booking_code' => $bookingCode
            ]
        ]);

        // Kirim data ke view
        return view('user.pesan.bayar', compact(
            'venue', 
            'date', 
            'time', 
            'duration', 
            'total', 
            'bookingCode',
            'adminFee',
            'totalPayment'
        ));
    }

/**
 * Method untuk halaman riwayat booking setelah pembayaran - DIPERBAIKI
 */
public function riwayatBooking(Request $request)
{
    try {
        // Ambil booking history user dari database
        $pemesanans = Pemesanan::where('user_id', auth()->id())
                        ->with('venue')
                        ->orderBy('created_at', 'desc')
                        ->get();
        
        // Tambahkan data dari parameter jika ada (untuk konfirmasi pembayaran)
        $successData = null;
        if ($request->has('status') && $request->status === 'success') {
            $successData = [
                'booking_code' => $request->booking_code ?? 'BK-' . Str::random(8),
                'date' => $request->date ?? date('Y-m-d'),
                'time' => $request->time ?? '10:00',
                'duration' => $request->duration ?? '2 Jam',
                'total' => $request->total ?? 240000,
                'venue_name' => $request->venue_name ?? 'Venue Booking',
                'address' => $request->address ?? 'Alamat tidak tersedia',
                'payment_method' => $request->payment_method ?? 'credit_card',
                'status' => 'confirmed'
            ];
            
            // Simpan juga ke session untuk ditampilkan
            session()->flash('payment_success', true);
            session()->flash('success_data', $successData);
        }
        
        // Jika ada session success dari prosesBayar()
        if (session('success')) {
            $successData = session('success_data') ?? $successData;
        }
        
        return view('user.pesan.riwayat-booking', compact('pemesanans', 'successData'));
        
    } catch (\Exception $e) {
        \Log::error('Error in riwayatBooking: ' . $e->getMessage());
        
        // Tetap tampilkan halaman riwayat dengan data kosong jika error
        $pemesanans = collect();
        $successData = $request->has('status') ? [
            'booking_code' => $request->booking_code,
            'date' => $request->date,
            'time' => $request->time,
            'duration' => $request->duration,
            'total' => $request->total,
            'venue_name' => $request->venue_name,
            'address' => $request->address,
            'payment_method' => $request->payment_method,
            'status' => 'confirmed'
        ] : null;
        
        return view('user.pesan.riwayat-booking', compact('pemesanans', 'successData'))
                    ->with('error', 'Terjadi kesalahan saat mengambil riwayat booking.');
    }
}

    /**
     * Method untuk halaman ulasan
     */
    public function ulasan($id)
    {
        try {
            $venue = Venue::findOrFail($id);
            
            // Ambil semua ulasan untuk venue ini
            $reviews = Review::where('venue_id', $id)
                            ->orderBy('created_at', 'desc')
                            ->get();

            // Hitung rating rata-rata
            $averageRating = $venue->rating ?? 0;
            $reviewsCount = $reviews->count();

            return view('user.pesan.ulasan', compact('venue', 'reviews', 'averageRating', 'reviewsCount'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('pesan.index')
                           ->with('error', 'Venue tidak ditemukan.');
        }
    }

        /**
         * Method untuk proses booking
         */
        public function processBooking(Request $request)
        {
            $validated = $request->validate([
                'venue_id' => 'required|exists:venues,id',
                'tanggal_booking' => 'required|date',
                'waktu_booking' => 'required',
                'durasi' => 'required|integer|min:1',
                'catatan' => 'nullable|string|max:500',
            ]);
        
            $endTime = date(
                'H:i',
                strtotime("+{$validated['durasi']} hours", strtotime($validated['waktu_booking']))
            );
        
            // Cek bentrok
            if (BookingService::isBentrok(
                $validated['venue_id'],
                $validated['tanggal_booking'],
                $validated['waktu_booking'],
                $endTime
            )) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal sudah terisi.'
                ], 422);
            }
        
            $venue = Venue::findOrFail($validated['venue_id']);
            $totalBiaya = $venue->price_per_hour * $validated['durasi'];
        
            $bookingCode = 'BK-' . strtoupper(Str::random(8));
        
            $pemesanan = Pemesanan::create([
                'user_id' => auth()->id(),
                'venue_id' => $validated['venue_id'],
                'kode_booking' => $bookingCode,
                'tanggal' => $validated['tanggal_booking'],
                'waktu' => $validated['waktu_booking'],
                'durasi' => $validated['durasi'],
                'total_harga' => $totalBiaya,
                'catatan' => $validated['catatan'],
                'status' => 'pending'
            ]);
        
            return response()->json([
                'success' => true,
                'booking_code' => $bookingCode,
                'redirect_url' => route('pesan.bayar', [
                    'booking_code' => $bookingCode
                ])
            ]);
        }
    

        public function prosesBayar(Request $request)
        {
            $request->validate([
                'booking_code' => 'required|exists:pemesanans,kode_booking',
                'payment_method' => 'required|string'
            ]);
        
            $pemesanan = Pemesanan::where('kode_booking', $request->booking_code)->firstOrFail();
        
            // Cegah bayar dua kali
            if ($pemesanan->status !== 'pending') {
                return redirect()->route('pesan.riwayat-booking')
                    ->with('error', 'Booking ini sudah diproses.');
            }
        
            $pemesanan->update([
                'status' => 'confirmed',
                'metode_pembayaran' => $request->payment_method,
                'tanggal_bayar' => now()
            ]);
        
            return redirect()->route('pesan.riwayat-booking')
                ->with('success', '✅ Pembayaran berhasil, booking dikonfirmasi.');
        }
        


    /**
     * Method untuk submit review
     */
    public function submitReview(Request $request)
    {
        $validated = $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        try {
            // Cek apakah user sudah memesan venue ini
            $hasBooking = Pemesanan::where('user_id', auth()->id())
                                ->where('venue_id', $validated['venue_id'])
                                ->where('status', 'completed')
                                ->exists();

            if (!$hasBooking) {
                return redirect()->back()
                               ->with('error', 'Anda harus membooking venue ini sebelum memberikan ulasan.');
            }

            // Simpan review
            Review::create([
                'user_id' => auth()->id(),
                'venue_id' => $validated['venue_id'],
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
                'customer_name' => auth()->user()->name
            ]);

            // Update rating venue
            $this->updateVenueRating($validated['venue_id']);

            return redirect()->back()
                           ->with('success', 'Ulasan berhasil dikirim!');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan saat mengirim ulasan.');
        }
    }

    /**
     * Helper method untuk mendapatkan data fasilitas
     */
    private function getFacilitiesFromDatabase($venue)
    {
        // Jika venue memiliki fasilitas di database, gunakan itu
        if (!empty($venue->facilities) && is_array($venue->facilities)) {
            $facilities = [];
            foreach ($venue->facilities as $facility) {
                $facilities[] = [
                    'name' => $facility,
                    'icon' => $this->getFacilityIcon($facility)
                ];
            }
            return $facilities;
        }

        // Fallback ke default facilities berdasarkan kategori
        return $this->getDefaultFacilities($venue->category);
    }

    /**
     * Helper method untuk default facilities
     */
    private function getDefaultFacilities($category)
    {
        $defaultFacilities = [
            'Futsal' => [
                ['name' => 'Lapangan Sintetis', 'icon' => 'fas fa-futbol'],
                ['name' => 'Pencahayaan LED', 'icon' => 'fas fa-lightbulb'],
                ['name' => 'Parkir Luas', 'icon' => 'fas fa-parking'],
                ['name' => 'Toilet Bersih', 'icon' => 'fas fa-restroom'],
                ['name' => 'Mushola', 'icon' => 'fas fa-mosque'],
                ['name' => 'Kantin', 'icon' => 'fas fa-utensils']
            ],
            'Bulu Tangkis' => [
                ['name' => 'Lapangan Indoor', 'icon' => 'fas fa-table-tennis'],
                ['name' => 'Pencahayaan Professional', 'icon' => 'fas fa-lightbulb'],
                ['name' => 'Parkir Luas', 'icon' => 'fas fa-parking'],
                ['name' => 'Toilet Bersih', 'icon' => 'fas fa-restroom'],
                ['name' => 'Mushola', 'icon' => 'fas fa-mosque'],
                ['name' => 'Ruang Ganti', 'icon' => 'fas fa-tshirt']
            ],
            'Basket' => [
                ['name' => 'Lapangan Outdoor', 'icon' => 'fas fa-basketball-ball'],
                ['name' => 'Pencahayaan Malam', 'icon' => 'fas fa-lightbulb'],
                ['name' => 'Parkir Luas', 'icon' => 'fas fa-parking'],
                ['name' => 'Toilet Bersih', 'icon' => 'fas fa-restroom'],
                ['name' => 'Mushola', 'icon' => 'fas fa-mosque'],
                ['name' => 'Tribun Penonton', 'icon' => 'fas fa-users']
            ],
            'Sepak Bola' => [
                ['name' => 'Lapangan Rumput', 'icon' => 'fas fa-futbol'],
                ['name' => 'Pencahayaan Stadion', 'icon' => 'fas fa-lightbulb'],
                ['name' => 'Parkir Luas', 'icon' => 'fas fa-parking'],
                ['name' => 'Toilet Bersih', 'icon' => 'fas fa-restroom'],
                ['name' => 'Mushola', 'icon' => 'fas fa-mosque'],
                ['name' => 'Ruang Ganti', 'icon' => 'fas fa-tshirt']
            ]
        ];

        return $defaultFacilities[$category] ?? $defaultFacilities['Futsal'];
    }

    /**
     * Helper method untuk mendapatkan icon berdasarkan nama fasilitas
     */
    private function getFacilityIcon($facilityName)
    {
        $iconMap = [
            'Lapangan' => 'fas fa-futbol',
            'Pencahayaan' => 'fas fa-lightbulb',
            'Parkir' => 'fas fa-parking',
            'Toilet' => 'fas fa-restroom',
            'Mushola' => 'fas fa-mosque',
            'Kantin' => 'fas fa-utensils',
            'Ruang Ganti' => 'fas fa-tshirt',
            'Tribun' => 'fas fa-users',
            'AC' => 'fas fa-snowflake',
            'WiFi' => 'fas fa-wifi',
            'Loker' => 'fas fa-lock'
        ];

        foreach ($iconMap as $key => $icon) {
            if (str_contains($facilityName, $key)) {
                return $icon;
            }
        }

        return 'fas fa-check-circle';
    }

    /**
     * Helper method untuk mendapatkan inisial nama
     */
    private function getInitials($name)
    {
        $words = explode(' ', $name);
        $initials = '';
        
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        
        return substr($initials, 0, 2);
    }

    /**
     * Helper method untuk mendapatkan URL gambar venue
     */
    private function getVenueImageUrl($venue)
    {
        // Bersihkan path dari spasi yang tidak perlu
        $photoPath = $venue->photo ? trim($venue->photo) : null;
        
        if ($photoPath) {
            // Cek apakah foto ada di storage
            if (Storage::exists('public/' . $photoPath)) {
                return asset('storage/' . $photoPath);
            }
            
            // Cek apakah foto ada di direktori venues langsung
            if (Storage::exists($photoPath)) {
                return asset('storage/' . $photoPath);
            }
            
            // Cek path dengan menghilangkan "venues/" jika ada duplikasi
            $cleanPath = str_replace('venues/', '', $photoPath);
            if (Storage::exists('public/venues/' . $cleanPath)) {
                return asset('storage/venues/' . $cleanPath);
            }
        }

        if ($venue->image_url) {
            return $venue->image_url;
        }

        // Default images berdasarkan kategori
        $defaultImages = [
            'Futsal' => 'https://images.unsplash.com/photo-1575361204480-aadea25e6e68?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
            'Badminton' => 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
            'Basket' => 'https://images.unsplash.com/photo-1544919982-9b7ce4d44d5b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
            'Soccer' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
            'Sepak Bola' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80'
        ];

        return $defaultImages[$venue->category] ?? $defaultImages['Futsal'];
    }

    /**
     * Helper method untuk cek ketersediaan slot waktu
     */
    private function checkTimeSlotAvailability($venueId, $date, $time, $duration)
    {
        // Logic untuk mengecek ketersediaan slot
        $existingPemesanans = Pemesanan::where('venue_id', $venueId)
                                  ->where('tanggal', $date)
                                  ->whereIn('status', ['confirmed', 'completed'])
                                  ->get();

        $requestedStart = strtotime($time);
        $requestedEnd = strtotime("+{$duration} hours", $requestedStart);

        foreach ($existingPemesanans as $pemesanan) {
            $pemesananStart = strtotime($pemesanan->waktu);
            $pemesananEnd = strtotime("+{$pemesanan->durasi} hours", $pemesananStart);

            // Cek apakah ada overlap
            if ($requestedStart < $pemesananEnd && $requestedEnd > $pemesananStart) {
                return false;
            }
        }

        return true;
    }

    /**
     * Helper method untuk update rating venue
     */
    private function updateVenueRating($venueId)
    {
        $reviews = Review::where('venue_id', $venueId)->get();
        
        if ($reviews->count() > 0) {
            $averageRating = $reviews->avg('rating');
            $reviewsCount = $reviews->count();

            Venue::where('id', $venueId)->update([
                'rating' => round($averageRating, 1),
                'reviews_count' => $reviewsCount
            ]);
        }
    }

    /**
     * Method untuk mendapatkan slot yang tersedia
     */
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'date' => 'required|date'
        ]);

        $venueId = $request->venue_id;
        $date = $request->date;

        // Logic untuk mendapatkan slot yang tersedia
        $availableSlots = $this->generateAvailableSlots($venueId, $date);

        return response()->json(['slots' => $availableSlots]);
    }

    /**
     * Method untuk mendapatkan booking yang sudah ada
     */
    public function getExistingBookings(Request $request)
    {
        $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'date' => 'required|date'
        ]);

        $venueId = $request->venue_id;
        $date = $request->date;

        $existingPemesanans = Pemesanan::where('venue_id', $venueId)
                                  ->where('tanggal', $date)
                                  ->whereIn('status', ['confirmed', 'completed'])
                                  ->select('waktu', 'durasi')
                                  ->get();

        return response()->json(['pemesanans' => $existingPemesanans]);
    }

    /**
     * Helper method untuk generate available slots
     */
    private function generateAvailableSlots($venueId, $date)
    {
        $slots = [];
        $startTime = strtotime('07:00');
        $endTime = strtotime('22:00');

        // Get existing bookings for the date
        $existingPemesanans = Pemesanan::where('venue_id', $venueId)
                                  ->where('tanggal', $date)
                                  ->whereIn('status', ['confirmed', 'completed'])
                                  ->get();

        for ($time = $startTime; $time <= $endTime; $time += 3600) {
            $slotTime = date('H:i', $time);
            $isAvailable = $this->isSlotAvailable($slotTime, $existingPemesanans);
            
            $slots[] = [
                'time' => $slotTime,
                'available' => $isAvailable
            ];
        }

        return $slots;
    }

    /**
     * Helper method untuk cek ketersediaan slot
     */
    private function isSlotAvailable($slotTime, $existingPemesanans)
    {
        $slotStart = strtotime($slotTime);
        
        foreach ($existingPemesanans as $pemesanan) {
            $pemesananStart = strtotime($pemesanan->waktu);
            $pemesananEnd = strtotime("+{$pemesanan->durasi} hours", $pemesananStart);
            
            if ($slotStart >= $pemesananStart && $slotStart < $pemesananEnd) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Method untuk halaman bayar-blade (opsional)
     */
    public function showBayarBlade()
    {
        return view('user.pesan.bayar');
    }

    /**
     * Method untuk get month bookings (opsional)
     */
    public function getMonthBookings(Request $request)
    {
        $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'month' => 'required|date_format:Y-m'
        ]);

        $venueId = $request->venue_id;
        $month = $request->month;

        $pemesanans = Pemesanan::where('venue_id', $venueId)
                              ->where('tanggal', 'like', $month . '%')
                              ->whereIn('status', ['confirmed', 'completed'])
                              ->select('tanggal', 'waktu', 'durasi')
                              ->get();

        return response()->json(['pemesanans' => $pemesanans]);
    }
}