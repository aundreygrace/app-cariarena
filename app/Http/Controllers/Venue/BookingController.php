<?php

namespace App\Http\Controllers\Venue;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan; // Ganti Booking dengan Pemesanan
use App\Models\Venue;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function masuk(Request $request)
    {
        // Ambil venue yang dimiliki oleh user yang login
        $userVenues = Venue::where('user_id', Auth::id())->pluck('id');
        
        // Query booking berdasarkan venue user menggunakan model Pemesanan
        $bookingsQuery = Pemesanan::with('venue')
            ->whereIn('venue_id', $userVenues);
        
        // Filter berdasarkan pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $bookingsQuery->where(function($query) use ($search) {
                $query->where('nama_customer', 'like', "%{$search}%")
                    ->orWhere('booking_code', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhereHas('venue', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }
        
        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $bookingsQuery->where('status', $request->status);
        }
        
        // Filter berdasarkan venue
        if ($request->has('venue_id') && $request->venue_id != '') {
            $bookingsQuery->where('venue_id', $request->venue_id);
        }
        
        // Hitung total berdasarkan status menggunakan scope dari model Pemesanan
        $totalBookings = $bookingsQuery->count();
        $pendingBookings = Pemesanan::whereIn('venue_id', $userVenues)
        ->where('status', 'pending')->count();
    
        $confirmedBookings = Pemesanan::whereIn('venue_id', $userVenues)
        ->where('status', 'confirmed')->count();
    
         $cancelledBookings = Pemesanan::whereIn('venue_id', $userVenues)
        ->where('status', 'cancelled')->count();
    
        
        // Ambil data booking dengan pagination
        $bookings = $bookingsQuery->orderBy('created_at', 'desc')->paginate(10);
        
        // Ambil daftar venue untuk filter
        $venues = Venue::where('user_id', Auth::id())->get();
        
        return view('venue.booking-masuk', compact(
            'bookings',
            'venues',
            'totalBookings',
            'pendingBookings',
            'confirmedBookings',
            'cancelledBookings'
        ));
    }
    
    public function store(Request $request)
    {
        try {
            // Bersihkan data dulu
            $data = $request->all();
            
            // Konversi durasi ke integer
            if (isset($data['durasi'])) {
                $data['durasi'] = (int) $data['durasi'];
            }
            
            // Bersihkan total_biaya
            if (isset($data['total_biaya'])) {
                if (is_string($data['total_biaya']) && (strpos($data['total_biaya'], 'Rp') !== false)) {
                    $data['total_biaya'] = preg_replace('/[^0-9]/', '', $data['total_biaya']);
                }
                $data['total_biaya'] = (int) $data['total_biaya'];
            }
    
            $validator = \Validator::make($data, [
                'venue_id' => 'required|exists:venues,id',
                'nama_customer' => 'required|string|max:255',
                'customer_phone' => 'required|string|max:15',
                'tanggal_booking' => 'required|date',
                'waktu_booking' => 'required',
                'durasi' => 'required|integer|min:1|max:24',
                'total_biaya' => 'required|integer|min:0',
                'catatan' => 'nullable|string'
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Generate booking code menggunakan method dari model Pemesanan
            $bookingCode = Pemesanan::generateBookingCode();
            
            // Calculate end time - PASTIKAN DURASI INTEGER
            $startTime = Carbon::createFromFormat(
                'H:i',
                substr($data['waktu_booking'], 0, 5)
            );            
            
            $durasi = (int) $data['durasi'];
            $endTime = $startTime->copy()->addHours($durasi);
            
            // Check for schedule conflicts menggunakan method dari model Pemesanan
            $conflict = Pemesanan::hasConflict(
                $data['venue_id'], 
                $data['tanggal_booking'], 
                $data['waktu_booking'], 
                $endTime->format('H:i')
            );
                
            if ($conflict) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal bertabrakan dengan booking lain'
                ], 400);
            }

            $jadwal = Jadwal::where('venue_id', $data['venue_id'])
                ->where('tanggal', $data['tanggal_booking'])
                ->where('waktu_mulai', substr($data['waktu_booking'], 0, 5))
                ->first();

            if (!$jadwal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot jadwal belum dibuat oleh owner'
                ], 400);
            }
            
            // Create booking menggunakan model Pemesanan
            $booking = Pemesanan::create([
                'jadwal_id'        => $jadwal->id,   // ⬅️ KUNCI
                'venue_id'         => $data['venue_id'],
                'user_id'          => null,
                'nama_customer'    => $data['nama_customer'],
                'customer_phone'   => $data['customer_phone'],
                'tanggal_booking'  => $data['tanggal_booking'],
                'waktu_booking'    => $data['waktu_booking'],
                'end_time'         => $endTime->format('H:i'),
                'durasi'           => $durasi,
                'total_biaya'      => $data['total_biaya'],
                'catatan'          => $data['catatan'],
                'status'           => 'pending',
                'booking_code'     => $bookingCode,
            ]);
            

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibuat',
                'data' => $booking
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Store booking error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function detail($id)
    {
        try {
            $booking = Pemesanan::with('venue')
                ->where('id', $id)
                ->first();
                
            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking tidak ditemukan'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $booking
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function update(Request $request, $id)
    {
        try {
            // 1️⃣ ambil data booking DULU
            $booking = Pemesanan::find($id);
    
            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking tidak ditemukan'
                ], 404);
            }
    
            // 2️⃣ kunci booking online yang sudah confirmed
            if ($booking->user_id !== null && $booking->status === 'confirmed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking online yang sudah dibayar tidak bisa diubah'
                ], 403);
            }
    
            // 3️⃣ Bersihkan dan konversi data sebelum validasi
            $data = $request->all();
            
            // Konversi durasi ke integer
            if (isset($data['durasi'])) {
                $data['durasi'] = (int) $data['durasi'];
            }
            
            // Bersihkan total_biaya
            if (isset($data['total_biaya'])) {
                if (is_string($data['total_biaya']) && (strpos($data['total_biaya'], 'Rp') !== false)) {
                    $data['total_biaya'] = preg_replace('/[^0-9]/', '', $data['total_biaya']);
                }
                $data['total_biaya'] = (int) $data['total_biaya'];
            }
    
            // 4️⃣ validasi request
            $validator = \Validator::make($data, [
                'nama_customer' => 'required|string|max:255',
                'customer_phone' => 'required|string|max:15',
                'tanggal_booking' => 'required|date',
                'waktu_booking' => 'required',
                'durasi' => 'required|integer|min:1|max:24',
                'total_biaya' => 'required|integer|min:0',
                'status' => 'required|in:pending,confirmed,cancelled',
                'catatan' => 'nullable|string',
                'venue_id' => 'required|exists:venues,id'
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
    
            // 5️⃣ hitung jam selesai - PASTIKAN DURASI INTEGER
            $startTime = Carbon::createFromFormat(
                'H:i',
                substr($data['waktu_booking'], 0, 5)
            );            
            
            // PASTIKAN durasi adalah integer
            $durasi = (int) $data['durasi'];
            $endTime = $startTime->copy()->addHours($durasi);
    
            // 6️⃣ cek konflik jadwal (exclude current booking)
            $conflict = Pemesanan::hasConflict(
                $booking->venue_id,
                $data['tanggal_booking'],
                $data['waktu_booking'],
                $endTime->format('H:i'),
                $id
            );
    
            if ($conflict) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal bertabrakan dengan booking lain'
                ], 400);
            }
    
            // 7️⃣ update data
            $updated = $booking->update([
                'nama_customer' => $data['nama_customer'],
                'customer_phone' => $data['customer_phone'],
                'tanggal_booking' => $data['tanggal_booking'],
                'waktu_booking' => $data['waktu_booking'],
                'end_time' => $endTime->format('H:i'),
                'durasi' => $durasi, // PASTIKAN INTEGER
                'total_biaya' => $data['total_biaya'],
                'status' => $data['status'],
                'catatan' => $data['catatan'] ?? null,
            ]);

            // ===============================
            // SINKRON JADWAL
            // ===============================
            if ($booking->jadwal) {
                $booking->jadwal->update([
                    'tanggal'       => $data['tanggal_booking'],
                    'waktu_mulai'   => substr($data['waktu_booking'], 0, 5),
                    'waktu_selesai' => $endTime->format('H:i'),
                    'status'        => $data['status'] === 'cancelled'
                        ? 'Available'
                        : 'Booked',
                ]);
            }
    
            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Booking berhasil diupdate',
                    'data' => $booking->refresh()
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan perubahan ke database'
                ], 500);
            }
    
        } catch (\Exception $e) {
            \Log::error('Update booking error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    
    public function delete($id)
    {
        try {
            $booking = Pemesanan::find($id);
            
            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking tidak ditemukan'
                ], 404);
            }
            
            $booking->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // Method tambahan untuk API/data
    public function getBookings(Request $request)
    {
        try {
            // Ambil venue yang dimiliki oleh user yang login
            $userVenues = Venue::where('user_id', Auth::id())->pluck('id');
            
            // Query booking berdasarkan venue user
            $bookingsQuery = Pemesanan::with('venue')
                ->whereIn('venue_id', $userVenues);
            
            // Filter berdasarkan pencarian
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $bookingsQuery->where(function($query) use ($search) {
                    $query->where('nama_customer', 'like', "%{$search}%")
                        ->orWhere('booking_code', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%")
                        ->orWhereHas('venue', function($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                });
            }
            
            // Filter berdasarkan status
            if ($request->has('status') && $request->status != '') {
                $bookingsQuery->where('status', $request->status);
            }
            
            // Filter berdasarkan venue
            if ($request->has('venue_id') && $request->venue_id != '') {
                $bookingsQuery->where('venue_id', $request->venue_id);
            }
            
            // Ambil data booking
            $bookings = $bookingsQuery->orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $bookings
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getVenues()
    {
        try {
            $venues = Venue::where('user_id', Auth::id())->get();
            
            return response()->json([
                'success' => true,
                'data' => $venues
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}