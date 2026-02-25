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
        $query = Venue::with('user');

        // Filter pencarian (ilike = case-insensitive di PostgreSQL)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'ilike', '%' . $search . '%')
                  ->orWhere('address', 'ilike', '%' . $search . '%');
            });
        }

        // ✅ FIX: status di DB adalah 'Aktif', 'Maintenance', 'Tidak Aktif' — bukan 'active'
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // ✅ FIX: kolom di DB adalah 'category' — bukan 'sport_type'
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $venues = $query->latest()->paginate(10)->withQueryString();

        // ✅ FIX: statistik pakai nilai DB yang benar
        $totalVenue      = Venue::count();
        $venueAktif      = Venue::where('status', 'Aktif')->count();
        $venuePerawatan  = Venue::where('status', 'Maintenance')->count();
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
        // ✅ FIX: validasi sesuai kolom DB yang ada (name, address, category, bukan sport_type)
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'address'       => 'required|string',
            'category'      => 'required|in:Futsal,Badminton,Basket,Soccer',
            'user_id'       => 'required|exists:users,id',
            'price_per_hour'=> 'nullable|numeric|min:0',
            'facilities'    => 'nullable|array',
            'description'   => 'nullable|string',
            'status'        => 'required|in:Aktif,Maintenance,Tidak Aktif',
        ]);

        try {
            Log::info('Mencoba menyimpan venue baru:', $validated);
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

            // ✅ FIX: kolom DB yang benar, status nilai DB yang benar
            $validated = $request->validate([
                'name'          => 'required|string|max:255',
                'address'       => 'required|string',
                'category'      => 'required|in:Futsal,Badminton,Basket,Soccer',
                'user_id'       => 'nullable|exists:users,id',
                'price_per_hour'=> 'nullable|numeric|min:0',
                'facilities'    => 'nullable|array',
                'description'   => 'nullable|string',
                'status'        => 'required|in:Aktif,Maintenance,Tidak Aktif',
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