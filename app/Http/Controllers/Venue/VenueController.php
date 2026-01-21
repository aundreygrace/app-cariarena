<?php

namespace App\Http\Controllers\Venue;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VenueController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $venues = Venue::where('user_id', $user->id)->get();
        
        // Get primary venue for profile display
        $primaryVenue = $user->getPrimaryVenue();
        $profileName = $primaryVenue ? $primaryVenue->name : $user->venue_name ?? 'Venue Owner';
        
        return view('venue.venue-saya.index', compact('venues', 'profileName'));
    }

    public function show($id)
{
    // PERBAIKAN: Gunakan Auth::id() bukan auth('venue')->id()
    $venue = Venue::where('user_id', Auth::id())
                  ->with(['jadwals' => function($query) {
                      $query->select('venue_id', 'tanggal', 'waktu_mulai', 'waktu_selesai', 'status')
                            ->where('tanggal', '>=', now()->format('Y-m-d'));
                  }])
                  ->findOrFail($id);
    
    // Format data jadwal untuk JavaScript
    $jadwalData = $venue->jadwals->map(function($jadwal) {
        return [
            'date' => $jadwal->tanggal,
            'start_time' => $jadwal->waktu_mulai,
            'end_time' => $jadwal->waktu_selesai,
            'status' => $jadwal->status
        ];
    });
    
    return view('venue.venue-saya.lihat-detail-venue', compact('venue', 'jadwalData'));
}

    public function edit($id)
    {
        $venue = Venue::where('user_id', Auth::id())->findOrFail($id);
        return response()->json($venue);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'category' => 'required|string|in:Futsal,Badminton,Basket,Soccer',
            'address' => 'required|string',
            'price_per_hour' => 'required',
            'status' => 'required|in:Aktif,Maintenance,Tidak Aktif',
            'photo' => 'nullable|file|image|max:4096',
            'photo_link' => 'nullable|string|url',
        ]);

        // Convert price to integer (remove dots and commas)
        $price = $request->price_per_hour;
        if (is_string($price)) {
            $price = preg_replace('/[^\d]/', '', $price);
        }
        $validated['price_per_hour'] = (int) $price;

        // Validate price is positive
        if ($validated['price_per_hour'] <= 0) {
            return back()->withErrors(['price_per_hour' => 'Harga per jam harus lebih dari 0'])->withInput();
        }

        // Handle photo upload atau link
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('venues', 'public');
            $validated['photo'] = $path;
        } elseif ($request->photo_link) {
            $validated['photo'] = $request->photo_link;
        } else {
            $validated['photo'] = null;
        }

        // Handle facilities dari checkbox
        $facilities = [];
        if ($request->newFacilityParking) $facilities[] = 'Parkir';
        if ($request->newFacilityToilet) $facilities[] = 'Toilet';
        if ($request->newFacilityKantin) $facilities[] = 'Kantin';
        if ($request->newFacilityAC) $facilities[] = 'AC';
        if ($request->newFacilityMusholla) $facilities[] = 'Musholla';
        if ($request->newFacilityRuangGanti) $facilities[] = 'Ruang Ganti';
        if ($request->newFacilityRuangTunggu) $facilities[] = 'Ruang Tunggu/Tribun';
        if ($request->newFacilitySoundSystem) $facilities[] = 'Sound System';

        $validated['facilities'] = $facilities;
        $validated['user_id'] = Auth::id();
        $validated['rating'] = 0;
        $validated['reviews_count'] = 0;

        Venue::create($validated);

        return redirect()->route('venue.venue-saya')->with('success', 'Venue berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $venue = Venue::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'category' => 'required|string|in:Futsal,Badminton,Basket,Soccer',
            'address' => 'required|string',
            'price_per_hour' => 'required',
            'status' => 'required|in:Aktif,Maintenance,Tidak Aktif',
            'photo' => 'nullable|file|image|max:4096',
            'photo_link' => 'nullable|string|url',
        ]);

        // Convert price to integer (remove dots and commas)
        $price = $request->price_per_hour;
        if (is_string($price)) {
            $price = preg_replace('/[^\d]/', '', $price);
        }
        $validated['price_per_hour'] = (int) $price;

        // Validate price is positive
        if ($validated['price_per_hour'] <= 0) {
            return back()->withErrors(['price_per_hour' => 'Harga per jam harus lebih dari 0'])->withInput();
        }

        // Handle photo upload atau link
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($venue->photo && !Str::contains($venue->photo, ['http', 'drive.google.com'])) {
                Storage::disk('public')->delete($venue->photo);
            }
            $path = $request->file('photo')->store('venues', 'public');
            $validated['photo'] = $path;
        } elseif ($request->photo_link) {
            // Delete old photo if exists
            if ($venue->photo && !Str::contains($venue->photo, ['http', 'drive.google.com'])) {
                Storage::disk('public')->delete($venue->photo);
            }
            $validated['photo'] = $request->photo_link;
        } else {
            // Keep existing photo if no new photo provided
            unset($validated['photo']);
        }

        // Handle facilities dari checkbox
        $facilities = [];
        if ($request->facilityParking) $facilities[] = 'Parkir';
        if ($request->facilityToilet) $facilities[] = 'Toilet';
        if ($request->facilityKantin) $facilities[] = 'Kantin';
        if ($request->facilityAC) $facilities[] = 'AC';
        if ($request->facilityMusholla) $facilities[] = 'Musholla';
        if ($request->facilityRuangGanti) $facilities[] = 'Ruang Ganti';
        if ($request->facilityRuangTunggu) $facilities[] = 'Ruang Tunggu/Tribun';
        if ($request->facilitySoundSystem) $facilities[] = 'Sound System';

        $validated['facilities'] = $facilities;

        $venue->update($validated);

        return redirect()->route('venue.venue-saya')->with('success', 'Venue berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $venue = Venue::where('user_id', Auth::id())->findOrFail($id);

        // Delete photo if exists
        if ($venue->photo && !Str::contains($venue->photo, ['http', 'drive.google.com'])) {
            Storage::disk('public')->delete($venue->photo);
        }

        $venue->delete();

        return redirect()->route('venue.venue-saya')->with('success', 'Venue berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        $venue = Venue::where('user_id', Auth::id())->findOrFail($id);
        
        // UPDATE LOGIC TOGGLE STATUS UNTUK 3 STATUS
        $currentStatus = $venue->status;
        
        if ($currentStatus === 'Aktif') {
            $venue->status = 'Maintenance';
        } elseif ($currentStatus === 'Maintenance') {
            $venue->status = 'Tidak Aktif';
        } else {
            $venue->status = 'Aktif';
        }
        
        $venue->save();

        return redirect()->route('venue.venue-saya')->with('success', 'Status venue berhasil diubah.');
    }

    
}