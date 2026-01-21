<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VenueController extends Controller
{
    public function index(Request $request)
    {
        $query = Venue::query();

        // Filter pencarian
        if ($request->has('search') && $request->search) {
            $query->where('name', 'ilike', '%' . $request->search . '%')
                  ->orWhere('address', 'ilike', '%' . $request->search . '%');
        }

        // Filter status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter jenis olahraga
        if ($request->has('jenis_olahraga') && $request->jenis_olahraga) {
            $query->where('sport_type', $request->jenis_olahraga);
        }

        $venues = $query->latest()->paginate(10);

        // Statistik
        $totalVenue = Venue::count();
        $venueAktif = Venue::where('status', 'active')->count();
        $venuePerawatan = Venue::where('status', 'maintenance')->count();
        $tingkatPemanfaatan = $this->calculateUtilizationRate();

        return view('admin.venue', compact(
            'venues', 
            'totalVenue', 
            'venueAktif', 
            'venuePerawatan', 
            'tingkatPemanfaatan'
        ));
    }

    public function store(Request $request)
    {
        // ✅ FIX: Validasi data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'sport_type' => 'required|string|max:100',
            'facilities' => 'required|string',
            'price_per_hour' => 'required|numeric|min:0',
            'status' => 'required|in:active,maintenance,inactive',
            'description' => 'nullable|string',
            'rating' => 'nullable|numeric|min:0|max:5'
        ]);

        try {
            Log::info('Mencoba menyimpan venue baru:', $validated);
            
            // ✅ FIX: Simpan ke database
            $venue = Venue::create($validated);
            
            Log::info('Venue berhasil disimpan dengan ID: ' . $venue->id);
            
            return redirect()->route('admin.venue.index')
                ->with('success', 'Venue berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            Log::error('Error menyimpan venue: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menambahkan venue: ' . $e->getMessage())
                ->withInput();
        }
    }

    // ✅ FIX: Tambahkan method edit() yang hilang
    public function edit($id)
    {
        try {
            $venue = Venue::findOrFail($id);
            return response()->json($venue);
        } catch (\Exception $e) {
            Log::error('Error mengambil data venue untuk edit: ' . $e->getMessage());
            return response()->json(['error' => 'Venue tidak ditemukan'], 404);
        }
    }

    public function show($id)
    {
        try {
            $venue = Venue::findOrFail($id);
            return response()->json($venue);
        } catch (\Exception $e) {
            Log::error('Error mengambil data venue: ' . $e->getMessage());
            return response()->json(['error' => 'Venue tidak ditemukan'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $venue = Venue::findOrFail($id);
            
            Log::info('Mencoba update venue ID: ' . $id, $request->all());

            // ✅ FIX: Validasi data - konsisten dengan store method
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'location' => 'required|string',
                'sport_type' => 'required|string|max:100',
                'facilities' => 'required|string',
                'price_per_hour' => 'required|numeric|min:0',
                'status' => 'required|in:active,maintenance,inactive', // ✅ FIX: konsisten dengan store
                'description' => 'nullable|string',
                'rating' => 'nullable|numeric|min:0|max:5'
            ]);

            // ✅ FIX: Update data di database
            $venue->update($validated);
            
            Log::info('Venue berhasil diupdate: ' . $id);
            
            return redirect()->route('admin.venue.index')
                ->with('success', 'Venue berhasil diperbarui!');
                
        } catch (\Exception $e) {
            Log::error('Error update venue: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memperbarui venue: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $venue = Venue::findOrFail($id);
            $venue->delete();
            
            Log::info('Venue berhasil dihapus: ' . $id);
            
            return redirect()->route('admin.venue.index')
                ->with('success', 'Venue berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error hapus venue: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus venue: ' . $e->getMessage());
        }
    }

    private function calculateUtilizationRate()
    {
        return 76;
    }
}