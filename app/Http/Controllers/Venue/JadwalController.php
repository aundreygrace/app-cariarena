<?php

namespace App\Http\Controllers\Venue;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Pemesanan;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
    
        $venue = Venue::where('user_id', $user->id)->first();
        if (!$venue) {
            return redirect()->back()->with('error', 'Anda belum memiliki venue');
        }
    
        $jadwals = Jadwal::with(['pemesanan.user', 'venue'])
            ->byVenue($venue->id)
            ->orderBy('tanggal', 'desc')
            ->orderBy('waktu_mulai', 'asc')
            ->get();
    
        $bookingsData = [];
    
        foreach ($jadwals as $jadwal) {

            // FORMAT TANGGAL
            $formattedDate = $jadwal->tanggal
                ? $jadwal->tanggal->format('Y-m-d')
                : now()->format('Y-m-d');
        
            // JAM MULAI SLOT
            $timeStart = $jadwal->waktu_mulai
                ? date('H:i', strtotime($jadwal->waktu_mulai))
                : '--:--';
        
            // ===============================
            // JIKA ADA PEMESANAN
            // ===============================
            if ($jadwal->pemesanan) {
        
                $pemesanan = $jadwal->pemesanan;
        
                // ⬅️ JAM SELESAI DARI PEMESANAN
                $timeEnd = $pemesanan->end_time
                    ? date('H:i', strtotime($pemesanan->end_time))
                    : '--:--';
        
                $isUserBooking = (bool) $pemesanan->user;
        
                $bookingsData[$formattedDate][] = [
                    'time' => $timeStart . ' - ' . $timeEnd,
                    'available' => false,
                    'name' => $isUserBooking
                        ? $pemesanan->user->name
                        : $pemesanan->nama_customer,
        
                    'price' => 'Rp ' . number_format(
                        $pemesanan->total_biaya ?? 0,
                        0,
                        ',',
                        '.'
                    ),
        
                    'detail' => [
                        'nama' => $isUserBooking
                            ? $pemesanan->user->name
                            : $pemesanan->nama_customer,
                        'sumber' => $isUserBooking ? 'User' : 'Offline',
                        'tempat' => $venue->name,
                        'tanggal' => $jadwal->tanggal
                            ? date('d F Y', strtotime($jadwal->tanggal))
                            : '-',
                        'durasi' => ($pemesanan->durasi ?? 0) . ' jam',
                        'jam_selesai' => $timeEnd,
                        'biaya' => number_format(
                            $pemesanan->total_biaya ?? 0,
                            0,
                            ',',
                            '.'
                        ),
                        'status' => $pemesanan->status ?? '-',
                    ],
                ];
        
            }
            // ===============================
            // SLOT KOSONG (AVAILABLE)
            // ===============================
            else {
        
                $bookingsData[$formattedDate][] = [
                    'time' => $timeStart, // ⬅️ HANYA JAM MULAI
                    'available' => true,
                    'name' => '',
                    'price' => 'Rp ' . number_format(
                        $venue->price_per_hour ?? 0,
                        0,
                        ',',
                        '.'
                    ) . '/jam',
                    'detail' => null,
                ];
            }
        }
        
    
        // ===============================
        // STATISTIK
        // ===============================
        try {
            $totalBookings = Pemesanan::byVenue($venue->id)->count();
    
            $confirmedBookings = Pemesanan::byVenue($venue->id)
                ->denganStatus(['confirmed', 'completed'])
                ->count();
    
            $pendingBookings = Pemesanan::byVenue($venue->id)
                ->denganStatus('pending')
                ->count();
    
            $todayRevenue = Pemesanan::byVenue($venue->id)
                ->hariIni()
                ->denganStatus(['confirmed', 'completed'])
                ->sum('total_biaya') ?? 0;
    
            $totalRevenue = Pemesanan::byVenue($venue->id)
                ->denganStatus(['confirmed', 'completed'])
                ->sum('total_biaya') ?? 0;
    
        } catch (\Exception $e) {
            $totalBookings = 0;
            $confirmedBookings = 0;
            $pendingBookings = 0;
            $todayRevenue = 0;
            $totalRevenue = 0;
        }
    
        return view('venue.jadwal.index', compact(
            'bookingsData',
            'totalBookings',
            'confirmedBookings',
            'pendingBookings',
            'todayRevenue',
            'totalRevenue'
        ));
    } 
    
    public function aturJadwal()
    {
        $user = Auth::user();
        $venue = Venue::where('user_id', $user->id)->first();
        
        if (!$venue) {
            return redirect()->back()->with('error', 'Anda belum memiliki venue');
        }
        
        return view('venue.jadwal.atur-jadwal', compact('venue'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $venue = Venue::where('user_id', $user->id)->first();
    
        $request->validate([
            'tanggal'     => 'required|date|after_or_equal:today',
            'waktu_mulai' => 'required|date_format:H:i',
            'catatan'     => 'nullable|string|max:500',
        ]);
    
        // ❗ Cegah slot kembar
        $exists = Jadwal::where('venue_id', $venue->id)
            ->where('tanggal', $request->tanggal)
            ->where('waktu_mulai', $request->waktu_mulai)
            ->exists();
    
        if ($exists) {
            return back()->with('error', 'Slot jam ini sudah ada');
        }
    
        Jadwal::create([
            'venue_id'     => $venue->id,
            'tanggal'      => $request->tanggal,
            'waktu_mulai'  => $request->waktu_mulai,
            'waktu_selesai'=> null, // ✅ INTENTIONAL
            'status'       => 'Available',
            'catatan'      => $request->catatan,
        ]);
    
        return redirect()
            ->route('venue.jadwal.index')
            ->with('success', 'Slot jadwal berhasil ditambahkan');
    }    
    
    
    public function edit($id)
    {
        $user = Auth::user();
        $venue = Venue::where('user_id', $user->id)->first();
        
        if (!$venue) {
            return redirect()->back()->with('error', 'Anda belum memiliki venue');
        }
        
        $jadwal = Jadwal::where('id', $id)->where('venue_id', $venue->id)->firstOrFail();
        
        return view('venue.jadwal.edit', compact('jadwal'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $venue = Venue::where('user_id', $user->id)->first();
        
        if (!$venue) {
            return redirect()->back()->with('error', 'Anda belum memiliki venue');
        }
        
        $jadwal = Jadwal::where('id', $id)->where('venue_id', $venue->id)->firstOrFail();
        
        $request->validate([
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'catatan' => 'nullable|string'
        ]);
        
        try {
            $jadwal->update([
                'tanggal' => $request->tanggal,
                'waktu_mulai' => $request->waktu_mulai,
                'waktu_selesai' => $request->waktu_selesai,
                'catatan' => $request->catatan
            ]);
            
            return redirect()->route('venue.jadwal.index')->with('success', 'Jadwal berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui jadwal: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $venue = Venue::where('user_id', $user->id)->first();
        
        if (!$venue) {
            return redirect()->back()->with('error', 'Anda belum memiliki venue');
        }
        
        $jadwal = Jadwal::where('id', $id)->where('venue_id', $venue->id)->firstOrFail();
        
        try {
            // Cek apakah jadwal sudah memiliki pemesanan
            if ($jadwal->pemesanan) {
                return redirect()->back()->with('error', 'Tidak dapat menghapus jadwal yang sudah memiliki pemesanan');
            }
            
            $jadwal->delete();
            
            return redirect()->route('venue.jadwal.index')->with('success', 'Jadwal berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus jadwal: ' . $e->getMessage());
        }
    }

    // PERBAIKAN: Method API dengan error handling
    public function getMonthlyStatus(Request $request)
    {
        try {
            $user = Auth::user();
            $venue = Venue::where('user_id', $user->id)->first();
            
            if (!$venue) {
                return response()->json(['error' => 'Venue tidak ditemukan'], 404);
            }
            
            $year = $request->get('year', date('Y'));
            $month = $request->get('month', date('m'));
            
            $startDate = "{$year}-{$month}-01";
            $endDate = date('Y-m-t', strtotime($startDate));
            
            $bookings = Pemesanan::byVenue($venue->id)
                ->rentangTanggal($startDate, $endDate)
                ->denganStatus(['confirmed', 'completed'])
                ->get()
                ->groupBy(function($item) {
                    return date('Y-m-d', strtotime($item->tanggal_booking));
                })
                ->map(function($item) {
                    return $item->count();
                });
            
            return response()->json($bookings);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function getDailySchedule(Request $request)
    {
        try {
            $user = Auth::user();
            $venue = Venue::where('user_id', $user->id)->first();
            
            if (!$venue) {
                return response()->json(['error' => 'Venue tidak ditemukan'], 404);
            }
            
            $date = $request->get('date', date('Y-m-d'));
            
            $schedules = Jadwal::where('venue_id', $venue->id)
                ->where('tanggal', $date)
                ->with('pemesanan')
                ->orderBy('waktu_mulai', 'asc')
                ->get()
                ->map(function ($jadwal) {

                    $start = date('H:i', strtotime($jadwal->waktu_mulai));
                
                    // JIKA ADA BOOKING → ambil dari pemesanan
                    if ($jadwal->pemesanan) {
                        $end = date('H:i', strtotime($jadwal->pemesanan->end_time));
                    } else {
                        $end = '--:--';
                    }
                
                    return [
                        'id' => $jadwal->id,
                        'time' => $start . ' - ' . $end,
                        'available' => !$jadwal->pemesanan,
                        'name' => $jadwal->pemesanan
                            ? ($jadwal->pemesanan->user->name ?? $jadwal->pemesanan->nama_customer)
                            : '',
                        'price' => $jadwal->pemesanan
                            ? 'Rp ' . number_format($jadwal->pemesanan->total_biaya, 0, ',', '.')
                            : 'Rp ' . number_format($jadwal->venue->price_per_hour, 0, ',', '.') . '/jam',
                        'detail' => $jadwal->pemesanan ? [
                            'nama' => $jadwal->pemesanan->user->name ?? $jadwal->pemesanan->nama_customer,
                            'sumber' => $jadwal->pemesanan->user ? 'User' : 'Offline',
                            'durasi' => $jadwal->pemesanan->durasi . ' jam',
                            'biaya' => number_format($jadwal->pemesanan->total_biaya, 0, ',', '.'),
                            'status' => $jadwal->pemesanan->status,
                        ] : null,
                    ];
                });
            
            return response()->json($schedules);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

        public function getBookedSlots($venueId, $date)
    {
        return \DB::table('booking')
            ->where('venue_id', $venueId)
            ->where('tanggal_booking', $date)
            ->whereIn('status', ['pending', 'confirmed', 'draft'])
            ->where(function ($q) {
                $q->whereNull('payment_expired_at')
                ->orWhere('payment_expired_at', '>', now());
            })
            ->select('waktu_booking', 'end_time')
            ->get();
    }

}