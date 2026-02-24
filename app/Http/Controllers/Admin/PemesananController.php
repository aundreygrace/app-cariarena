<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemesananController extends Controller
{
    public function index(Request $request)
    {
        $query = Pemesanan::with('venue');
        
        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_customer', 'like', '%' . $request->search . '%')
                  ->orWhere('booking_code', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $request->search . '%');
            });
        }
        
        // Filter berdasarkan status — gunakan nilai DB yang benar (lowercase)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan venue
        if ($request->filled('venue_id')) {
            $query->where('venue_id', $request->venue_id);
        }
        
        $pemesanans = $query->orderBy('created_at', 'desc')->paginate(10);
        $venues = Venue::all();
        
        // Statistik — semua venue (tanpa filter venue_id agar data admin lengkap)
        $totalPemesanans = Pemesanan::count();
        $activePemesanans = Pemesanan::whereDate('tanggal_booking', today())
            ->where('status', Pemesanan::STATUS_CONFIRMED)
            ->count();
        $pendingPemesanans = Pemesanan::where('status', Pemesanan::STATUS_PENDING)->count();
        
        // Hitung tingkat okupansi hari ini (semua venue)
        $totalSlots = Venue::count() * 8; // 8 slot per venue per hari
        $bookedSlots = Pemesanan::whereDate('tanggal_booking', today())
            ->whereIn('status', [Pemesanan::STATUS_CONFIRMED, Pemesanan::STATUS_COMPLETED])
            ->count();
        $occupancyRate = $totalSlots > 0 ? round(($bookedSlots / $totalSlots) * 100) : 0;
        
        return view('admin.pemesanan', compact(
            'pemesanans', 
            'venues',
            'totalPemesanans',
            'activePemesanans',
            'pendingPemesanans',
            'occupancyRate'
        ));
    }
    
    public function show($id)
    {
        try {
            $pemesanan = Pemesanan::with('venue')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $pemesanan
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data pemesanan tidak ditemukan'
            ], 404);
        }
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nama_customer'  => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'venue_id'       => 'required|exists:venues,id',
            'tanggal_booking'=> 'required|date',
            'waktu_booking'  => 'required',
            'end_time'       => 'required',
            'durasi'         => 'required|integer|min:1',
            'total_biaya'    => 'required|numeric|min:0',
            // ✅ FIX: Gunakan nilai status yang sesuai dengan DB constraint
            'status'         => 'required|in:draft,pending,confirmed,expired,cancelled,completed',
            'catatan'        => 'nullable|string',
        ]);

        // Cek konflik jadwal (semua venue)
        $bentrok = Pemesanan::hasConflict(
            $request->venue_id,
            $request->tanggal_booking,
            $request->waktu_booking,
            $request->end_time
        );

        if ($bentrok) {
            return back()
                ->with('error', 'Jadwal bentrok dengan booking lain pada venue dan waktu yang sama.')
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // ✅ FIX: Gunakan generateBookingCode() dari Model (aman, cek duplikat)
            $kodeBooking = Pemesanan::generateBookingCode();

            $pemesanan = Pemesanan::create([
                'nama_customer'  => $request->nama_customer,
                'customer_phone' => $request->customer_phone,
                'venue_id'       => $request->venue_id,
                'tanggal_booking'=> $request->tanggal_booking,
                'waktu_booking'  => $request->waktu_booking,
                'end_time'       => $request->end_time,
                'durasi'         => $request->durasi,
                'total_biaya'    => $request->total_biaya,
                'status'         => $request->status,
                'catatan'        => $request->catatan,
                'booking_code'   => $kodeBooking,
            ]);

            // Jika status confirmed, otomatis buat transaksi
            if ($request->status === Pemesanan::STATUS_CONFIRMED) {
                $this->buatTransaksiOtomatis($pemesanan);
            }

            DB::commit();

            return redirect()->route('admin.pemesanan.index')
                ->with('success', 'Pemesanan berhasil ditambahkan dengan kode ' . $kodeBooking);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function edit($id)
    {
        $pemesanan = Pemesanan::with('venue')->findOrFail($id);
        $venues = Venue::all();
        return view('admin.pemesanan.edit', compact('pemesanan', 'venues'));
    }
    
    public function update(Request $request, $id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        
        $request->validate([
            'nama_customer'  => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'venue_id'       => 'required|exists:venues,id',
            'tanggal_booking'=> 'required|date',
            'waktu_booking'  => 'required',
            'end_time'       => 'required',
            'durasi'         => 'required|integer|min:1',
            'total_biaya'    => 'required|numeric|min:0',
            // ✅ FIX: Nilai status konsisten dengan DB
            'status'         => 'required|in:draft,pending,confirmed,expired,cancelled,completed',
            'catatan'        => 'nullable|string',
        ]);

        // Cek konflik jadwal, kecualikan booking ini sendiri
        $bentrok = Pemesanan::hasConflict(
            $request->venue_id,
            $request->tanggal_booking,
            $request->waktu_booking,
            $request->end_time,
            $pemesanan->id
        );

        if ($bentrok) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal bentrok dengan booking lain pada venue dan waktu yang sama.'
            ], 422);
        }

        try {
            DB::beginTransaction();

            $statusLama = $pemesanan->status;

            $pemesanan->update([
                'nama_customer'  => $request->nama_customer,
                'customer_phone' => $request->customer_phone,
                'venue_id'       => $request->venue_id,
                'tanggal_booking'=> $request->tanggal_booking,
                'waktu_booking'  => $request->waktu_booking,
                'end_time'       => $request->end_time,
                'durasi'         => $request->durasi,
                'total_biaya'    => $request->total_biaya,
                'status'         => $request->status,
                'catatan'        => $request->catatan,
            ]);

            // Jika status baru confirmed dan sebelumnya bukan confirmed, buat transaksi
            if ($request->status === Pemesanan::STATUS_CONFIRMED 
                && $statusLama !== Pemesanan::STATUS_CONFIRMED) {
                $this->buatTransaksiOtomatis($pemesanan->fresh());
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pemesanan berhasil diperbarui.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $pemesanan = Pemesanan::findOrFail($id);
            $pemesanan->delete();

            DB::commit();

            return redirect()->route('admin.pemesanan.index')
                ->with('success', 'Pemesanan berhasil dihapus.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function confirm($id)
    {
        try {
            DB::beginTransaction();

            $pemesanan = Pemesanan::findOrFail($id);
            $statusLama = $pemesanan->status;

            // ✅ FIX: Gunakan STATUS_CONFIRMED constant dari Model
            $pemesanan->update(['status' => Pemesanan::STATUS_CONFIRMED]);

            // Buat transaksi otomatis jika belum ada
            if ($statusLama !== Pemesanan::STATUS_CONFIRMED) {
                $this->buatTransaksiOtomatis($pemesanan->fresh());
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Pemesanan berhasil dikonfirmasi.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function cancel($id)
    {
        try {
            DB::beginTransaction();

            $pemesanan = Pemesanan::findOrFail($id);
            // ✅ FIX: Gunakan STATUS_CANCELLED constant
            $pemesanan->update(['status' => Pemesanan::STATUS_CANCELLED]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Pemesanan berhasil dibatalkan.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function complete($id)
    {
        try {
            DB::beginTransaction();

            $pemesanan = Pemesanan::findOrFail($id);
            // ✅ FIX: Gunakan STATUS_COMPLETED constant
            $pemesanan->update(['status' => Pemesanan::STATUS_COMPLETED]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Pemesanan berhasil diselesaikan.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Buat transaksi otomatis saat booking dikonfirmasi.
     * Mencegah duplikasi jika transaksi sudah ada.
     */
    private function buatTransaksiOtomatis(Pemesanan $pemesanan): void
    {
        // Cek apakah transaksi sudah ada untuk booking ini
        $sudahAda = DB::table('transactions')
            ->where('booking_id', $pemesanan->id)
            ->exists();

        if ($sudahAda) {
            return;
        }

        // Generate transaction number unik
        do {
            $transactionNumber = 'TRX-' . strtoupper(\Illuminate\Support\Str::random(10));
        } while (DB::table('transactions')->where('transaction_number', $transactionNumber)->exists());

        DB::table('transactions')->insert([
            'transaction_number'  => $transactionNumber,
            'booking_id'          => $pemesanan->id,
            'customer_id'         => $pemesanan->user_id,
            'pengguna'            => $pemesanan->nama_customer,
            'nama_venue'          => $pemesanan->venue?->name ?? '-',
            'metode_pembayaran'   => $pemesanan->payment_method ?? 'cash',
            'amount'              => $pemesanan->total_biaya,
            'transaction_date'    => now(),
            'status'              => 'completed',
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);
    }
}