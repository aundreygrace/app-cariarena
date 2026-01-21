<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BookingService;
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
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('nama_customer', 'like', '%' . $request->search . '%')
                  ->orWhere('booking_code', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $request->search . '%');
            });
        }
        
        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan venue
        if ($request->has('venue_id') && $request->venue_id != '') {
            $query->where('venue_id', $request->venue_id);
        }
        
        $pemesanans = $query->orderBy('created_at', 'desc')->paginate(10);
        $venues = Venue::all();
        
        // Statistik
        $totalPemesanans = Pemesanan::count();
        $activePemesanans = Pemesanan::where('tanggal_booking', today())->where('status', 'Terkonfirmasi')->count();
        $pendingPemesanans = Pemesanan::where('status', 'Menunggu')->count();
        
        // Hitung tingkat okupansi
        $totalSlots = 8; // Asumsi 8 slot per hari
        $bookedSlots = Pemesanan::where('tanggal_booking', today())->whereIn('status', ['Terkonfirmasi', 'Selesai'])->count();
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
        $validasi = $request->validate([
            'nama_customer' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'venue_id' => 'required|exists:venues,id',
            'tanggal_booking' => 'required|date',
            'waktu_booking' => 'required',
            'end_time' => 'required',
            'durasi' => 'required|integer|min:1',
            'total_biaya' => 'required|numeric|min:0',
            'status' => 'required|in:Menunggu,Terkonfirmasi,Selesai,Dibatalkan',
            'catatan' => 'nullable|string'
        ]);

        if (BookingService::isBentrok(
            $request->venue_id,
            $request->tanggal_booking,
            $request->waktu_booking,
            $request->end_time
        )) {
            return back()
                ->with('error', 'Jadwal bentrok dengan booking lain')
                ->withInput();
        }
        

        try {
            DB::beginTransaction();

            // Generate kode booking
            $pemesananTerakhir = Pemesanan::orderBy('id', 'desc')->first();
            $idBerikutnya = $pemesananTerakhir ? $pemesananTerakhir->id + 1 : 1;
            $kodeBooking = 'B' . str_pad($idBerikutnya, 4, '0', STR_PAD_LEFT);

            $pemesanan = Pemesanan::create([
                'nama_customer' => $request->nama_customer,
                'customer_phone' => $request->customer_phone,
                'venue_id' => $request->venue_id,
                'tanggal_booking' => $request->tanggal_booking,
                'waktu_booking' => $request->waktu_booking,
                'end_time' => $request->end_time,
                'durasi' => $request->durasi,
                'total_biaya' => $request->total_biaya,
                'status' => $request->status,
                'catatan' => $request->catatan,
                'booking_code' => $kodeBooking,
            ]);

            DB::commit();

            return redirect()->route('pemesanan.index')
                ->with('success', 'Pemesanan berhasil ditambahkan.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function edit($id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        $venues = Venue::all();
        return view('admin.pemesanan.edit', compact('pemesanan', 'venues'));
    }
    
    public function update(Request $request, $id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        
        $validasi = $request->validate([
            'nama_customer' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'venue_id' => 'required|exists:venues,id',
            'tanggal_booking' => 'required|date',
            'waktu_booking' => 'required',
            'end_time' => 'required',
            'durasi' => 'required|integer|min:1',
            'total_biaya' => 'required|numeric|min:0',
            'status' => 'required|in:Menunggu,Terkonfirmasi,Selesai,Dibatalkan',
            'catatan' => 'nullable|string'
        ]);

        if (BookingService::isBentrok(
            $request->venue_id,
            $request->tanggal_booking,
            $request->waktu_booking,
            $request->end_time,
            $pemesanan->id
        )) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal bentrok dengan booking lain'
            ], 422);
        }
        

        try {
            DB::beginTransaction();

            $pemesanan->update([
                'nama_customer' => $request->nama_customer,
                'customer_phone' => $request->customer_phone,
                'venue_id' => $request->venue_id,
                'tanggal_booking' => $request->tanggal_booking,
                'waktu_booking' => $request->waktu_booking,
                'end_time' => $request->end_time,
                'durasi' => $request->durasi,
                'total_biaya' => $request->total_biaya,
                'status' => $request->status,
                'catatan' => $request->catatan,
            ]);

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

            return redirect()->route('pemesanan.index')
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
            $pemesanan->update(['status' => 'Terkonfirmasi']);

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
            $pemesanan->update(['status' => 'Dibatalkan']);

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
            $pemesanan->update(['status' => 'Selesai']);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Pemesanan berhasil diselesaikan.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}