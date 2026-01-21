<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';
    
    protected $fillable = [
        'venue_id',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'status',
        'catatan'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // ========== KONSTANTA STATUS ==========
    
    const STATUS_AVAILABLE = 'Available';
    const STATUS_BOOKED = 'Booked';
    const STATUS_MAINTENANCE = 'Maintenance';
    const STATUS_UNAVAILABLE = 'Unavailable';
    
    public static function getStatusOptions()
    {
        return [
            self::STATUS_AVAILABLE => 'Tersedia',
            self::STATUS_BOOKED => 'Dipesan',
            self::STATUS_MAINTENANCE => 'Perawatan',
            self::STATUS_UNAVAILABLE => 'Tidak Tersedia',
        ];
    }

    // ========== RELASI DENGAN TABEL LAIN ==========
    
    /**
     * Relasi dengan model Venue
     */
    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    /**
     * Relasi dengan model Pemesanan
     */
    public function pemesanan()
    {
        return $this->hasOne(Pemesanan::class);
    }
    

    /**
     * Relasi dengan model VenueOwner (untuk role venue)
     */
    public function venueOwner()
    {
        return $this->hasOneThrough(
            User::class,
            Venue::class,
            'id', // Foreign key di venues table
            'id', // Foreign key di users table
            'venue_id', // Local key di jadwal table
            'user_id' // Local key di venues table
        );
    }

    // ========== ACCESSOR UNTUK SEMUA ROLE ==========

    /**
     * Accessor untuk format status
     */
    public function getStatusFormattedAttribute()
    {
        $statusMap = [
            self::STATUS_AVAILABLE => 'Tersedia',
            self::STATUS_BOOKED => 'Dipesan',
            self::STATUS_MAINTENANCE => 'Perawatan',
            self::STATUS_UNAVAILABLE => 'Tidak Tersedia',
        ];

        return $statusMap[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Accessor untuk format tanggal
     */
    public function getTanggalFormattedAttribute()
    {
        try {
            return Carbon::parse($this->tanggal)->translatedFormat('d F Y');
        } catch (\Exception $e) {
            return date('d F Y', strtotime($this->tanggal));
        }
    }

    /**
     * Accessor untuk format hari dan tanggal
     */
    public function getHariTanggalAttribute()
    {
        try {
            return Carbon::parse($this->tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY');
        } catch (\Exception $e) {
            return date('l, d F Y', strtotime($this->tanggal));
        }
    }

    /**
     * Accessor untuk format waktu mulai
     */
    public function getWaktuMulaiFormattedAttribute()
    {
        try {
            return Carbon::parse($this->waktu_mulai)->format('H:i');
        } catch (\Exception $e) {
            return date('H:i', strtotime($this->waktu_mulai));
        }
    }

    /**
     * Accessor untuk format waktu selesai
     */
    public function getWaktuSelesaiFormattedAttribute()
    {
        try {
            return Carbon::parse($this->waktu_selesai)->format('H:i');
        } catch (\Exception $e) {
            return date('H:i', strtotime($this->waktu_selesai));
        }
    }

    /**
     * Accessor untuk rentang waktu (format: 19:00 - 21:00)
     */
    public function getRentangWaktuAttribute()
    {
        return $this->waktu_mulai_formatted . ' - ' . $this->waktu_selesai_formatted;
    }

    /**
     * Accessor untuk durasi dalam jam
     */
    public function getDurasiJamAttribute()
    {
        try {
            $start = Carbon::parse($this->waktu_mulai);
            $end = Carbon::parse($this->waktu_selesai);
            $duration = $end->diffInHours($start);
            return $duration . ' jam';
        } catch (\Exception $e) {
            return 'Tidak diketahui';
        }
    }

    /**
     * Accessor untuk durasi dalam menit
     */
    public function getDurasiMenitAttribute()
    {
        try {
            $start = Carbon::parse($this->waktu_mulai);
            $end = Carbon::parse($this->waktu_selesai);
            return $end->diffInMinutes($start) . ' menit';
        } catch (\Exception $e) {
            return 'Tidak diketahui';
        }
    }

    /**
     * Accessor untuk CSS class status
     */
    public function getCssStatusAttribute()
    {
        $statusMap = [
            self::STATUS_AVAILABLE => 'available',
            self::STATUS_BOOKED => 'booked',
            self::STATUS_MAINTENANCE => 'maintenance',
            self::STATUS_UNAVAILABLE => 'unavailable',
        ];
        
        return $statusMap[$this->status] ?? 'unknown';
    }

    /**
     * Accessor untuk warna badge status
     */
    public static function getStatusColor($status)
    {
        $colors = [
            self::STATUS_AVAILABLE => 'success',
            self::STATUS_BOOKED => 'warning',
            self::STATUS_MAINTENANCE => 'info',
            self::STATUS_UNAVAILABLE => 'danger',
        ];

        return $colors[$status] ?? 'secondary';
    }

    /**
     * Accessor untuk warna status instance
     */
    public function getColorStatusAttribute()
    {
        return self::getStatusColor($this->status);
    }

    /**
     * Accessor untuk icon status
     */
    public function getIconStatusAttribute()
    {
        $icons = [
            self::STATUS_AVAILABLE => '✅',
            self::STATUS_BOOKED => '📅',
            self::STATUS_MAINTENANCE => '🔧',
            self::STATUS_UNAVAILABLE => '🚫',
        ];

        return $icons[$this->status] ?? '📋';
    }

    /**
     * Accessor untuk mengecek apakah jadwal tersedia
     */
    public function getIsAvailableAttribute()
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    /**
     * Accessor untuk mengecek apakah jadwal sudah dipesan
     */
    public function getIsBookedAttribute()
    {
        return $this->status === self::STATUS_BOOKED;
    }

    /**
     * Accessor untuk data venue dengan fallback
     */
    public function getVenueDataAttribute()
    {
        if ($this->relationLoaded('venue') && $this->venue) {
            return $this->venue;
        }
        
        return (object)[
            'id' => $this->venue_id,
            'name' => 'Venue Tidak Diketahui',
            'location' => 'Lokasi Tidak Diketahui',
            'kategori' => 'Umum'
        ];
    }

    /**
     * Accessor untuk nama venue
     */
    public function getNamaVenueAttribute()
    {
        return $this->venue_data->name;
    }

    /**
     * Accessor untuk lokasi venue
     */
    public function getLokasiVenueAttribute()
    {
        return $this->venue_data->location;
    }

    /**
     * Accessor untuk mengecek apakah jadwal sudah lewat
     */
    public function getIsPastAttribute()
    {
        try {
            $jadwalDateTime = Carbon::parse($this->tanggal . ' ' . $this->waktu_mulai);
            return $jadwalDateTime->isPast();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Accessor untuk mengecek apakah jadwal hari ini
     */
    public function getIsTodayAttribute()
    {
        try {
            return Carbon::parse($this->tanggal)->isToday();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Accessor untuk mengecek apakah jadwal besok
     */
    public function getIsTomorrowAttribute()
    {
        try {
            return Carbon::parse($this->tanggal)->isTomorrow();
        } catch (\Exception $e) {
            return false;
        }
    }

    // ========== SCOPE UNTUK SEMUA ROLE ==========

    /**
     * Scope untuk jadwal tersedia
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    /**
     * Scope untuk jadwal dipesan
     */
    public function scopeBooked($query)
    {
        return $query->where('status', self::STATUS_BOOKED);
    }

    /**
     * Scope untuk jadwal maintenance
     */
    public function scopeMaintenance($query)
    {
        return $query->where('status', self::STATUS_MAINTENANCE);
    }

    /**
     * Scope untuk jadwal tidak tersedia
     */
    public function scopeUnavailable($query)
    {
        return $query->where('status', self::STATUS_UNAVAILABLE);
    }

    /**
     * Scope untuk jadwal aktif (tersedia)
     */
    public function scopeAktif($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE)
                    ->where('tanggal', '>=', now()->toDateString());
    }

    /**
     * Scope untuk jadwal berdasarkan venue
     */
    public function scopeByVenue($query, $venueId)
    {
        if (is_array($venueId)) {
            return $query->whereIn('venue_id', $venueId);
        }
        return $query->where('venue_id', $venueId);
    }

    /**
     * Scope untuk jadwal hari ini
     */
    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal', now()->toDateString());
    }

    /**
     * Scope untuk jadwal besok
     */
    public function scopeBesok($query)
    {
        return $query->whereDate('tanggal', now()->addDay()->toDateString());
    }

    /**
     * Scope untuk jadwal minggu ini
     */
    public function scopeMingguIni($query)
    {
        return $query->whereBetween('tanggal', [
            now()->startOfWeek()->toDateString(),
            now()->endOfWeek()->toDateString()
        ]);
    }

    /**
     * Scope untuk jadwal bulan ini
     */
    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal', now()->month)
                    ->whereYear('tanggal', now()->year);
    }

    /**
     * Scope untuk jadwal yang akan datang
     */
    public function scopeAkanDatang($query)
    {
        return $query->where('tanggal', '>=', now()->toDateString());
    }

    /**
     * Scope untuk jadwal berdasarkan tanggal
     */
    public function scopeByTanggal($query, $tanggal)
    {
        return $query->whereDate('tanggal', $tanggal);
    }

    /**
     * Scope untuk jadwal berdasarkan rentang tanggal
     */
    public function scopeRentangTanggal($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    /**
     * Scope untuk jadwal berdasarkan waktu
     */
    public function scopeByWaktu($query, $waktuMulai, $waktuSelesai = null)
    {
        if ($waktuSelesai) {
            return $query->whereBetween('waktu_mulai', [$waktuMulai, $waktuSelesai]);
        }
        return $query->where('waktu_mulai', '>=', $waktuMulai);
    }

    /**
     * Scope untuk jadwal terbaru
     */
    public function scopeTerbaru($query, $limit = 10)
    {
        return $query->orderBy('tanggal', 'desc')
                    ->orderBy('waktu_mulai', 'desc')
                    ->limit($limit);
    }

    // ========== SCOPE KHUSUS ROLE ==========

    /**
     * Scope untuk User - jadwal tersedia untuk booking
     */
    public function scopeForUser($query)
    {
        return $query->available()
                    ->akanDatang()
                    ->orderBy('tanggal')
                    ->orderBy('waktu_mulai');
    }

    /**
     * Scope untuk Venue - jadwal untuk venue milik user
     */
    public function scopeForVenueOwner($query)
    {
        $user = Auth::user();
        if (!$user) return $query;
        
        if (method_exists($user, 'venues')) {
            $venueIds = $user->venues()->pluck('id');
            return $query->whereIn('venue_id', $venueIds);
        }
        
        return $query;
    }

    /**
     * Scope untuk Admin - semua jadwal
     */
    public function scopeForAdmin($query)
    {
        return $query;
    }

    /**
     * Scope otomatis berdasarkan role user
     */
    public function scopeForCurrentUser($query)
    {
        $user = Auth::user();
        if (!$user) return $query;

        if ($user->hasRole('admin')) {
            return $query->forAdmin();
        } elseif ($user->hasRole('venue')) {
            return $query->forVenueOwner();
        } else {
            return $query->forUser();
        }
    }

    // ========== METHOD BISNIS LOGIC ==========

    /**
     * Method untuk mengecek apakah jadwal bisa dibooking
     */
    public function bisaDibooking()
    {
        return $this->is_available && !$this->is_past;
    }

    /**
     * Method untuk mengecek konflik jadwal
     */
    public static function hasConflict($venueId, $tanggal, $waktuMulai, $waktuSelesai, $excludeId = null)
    {
        $query = self::where('venue_id', $venueId)
                    ->where('tanggal', $tanggal)
                    ->where('status', self::STATUS_BOOKED)
                    ->where(function($q) use ($waktuMulai, $waktuSelesai) {
                        $q->whereBetween('waktu_mulai', [$waktuMulai, $waktuSelesai])
                          ->orWhereBetween('waktu_selesai', [$waktuMulai, $waktuSelesai])
                          ->orWhere(function($q2) use ($waktuMulai, $waktuSelesai) {
                              $q2->where('waktu_mulai', '<=', $waktuMulai)
                                 ->where('waktu_selesai', '>=', $waktuSelesai);
                          });
                    });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Method untuk update status jadwal
     */
    public function updateStatus($newStatus, $reason = null)
    {
        $allowedStatuses = [self::STATUS_AVAILABLE, self::STATUS_BOOKED, 
                           self::STATUS_MAINTENANCE, self::STATUS_UNAVAILABLE];
        
        if (!in_array($newStatus, $allowedStatuses)) {
            return false;
        }

        $this->status = $newStatus;
        
        if ($reason) {
            $this->catatan = $this->catatan 
                ? $this->catatan . "\n" . $reason 
                : $reason;
        }

        return $this->save();
    }

    public function getNamaPelangganAttribute()
{
    if (!$this->relationLoaded('pemesanan')) {
        $this->load('pemesanan.user');
    }

    if (!$this->pemesanan) {
        return '-';
    }

    // Booking dari user
    if ($this->pemesanan->user_id && $this->pemesanan->user) {
        return $this->pemesanan->user->name;
    }

    // Booking offline
    return $this->pemesanan->nama_customer ?? 'Pelanggan Offline';
}


public function getSumberBookingAttribute()
{
    if (!$this->pemesanan) {
        return '-';
    }

    return $this->pemesanan->user_id
        ? 'User Aplikasi'
        : 'Offline (Datang di Tempat)';
}


public function getIsOfflineBookingAttribute()
{
    return $this->pemesanan && is_null($this->pemesanan->user_id);
}



    /**
     * Method untuk menandai jadwal sebagai dipesan
     */
    public function markAsBooked($bookingId = null)
    {
        $this->status = self::STATUS_BOOKED;
        
        if ($bookingId) {
            $note = "Dipesan pada: " . now()->format('Y-m-d H:i') . 
                   " | Booking ID: " . $bookingId;
            $this->catatan = $this->catatan 
                ? $this->catatan . "\n" . $note 
                : $note;
        }
        
        return $this->save();
    }

    /**
     * Method untuk menandai jadwal sebagai tersedia
     */
    public function markAsAvailable($reason = null)
    {
        $this->status = self::STATUS_AVAILABLE;
        
        if ($reason) {
            $this->catatan = $this->catatan 
                ? $this->catatan . "\n" . $reason 
                : $reason;
        }
        
        return $this->save();
    }

    // ========== METHOD STATISTIK ==========

    /**
     * Method untuk statistik jadwal
     */
    public static function getJadwalStats($venueIds = null, $period = 'month')
    {
        $query = self::selectRaw('status, COUNT(*) as count');

        if ($venueIds) {
            $query->byVenue($venueIds);
        }

        // Filter periode
        if ($period === 'today') {
            $query->hariIni();
        } elseif ($period === 'week') {
            $query->mingguIni();
        } elseif ($period === 'month') {
            $query->bulanIni();
        }

        return $query->groupBy('status')
                    ->get()
                    ->pluck('count', 'status')
                    ->toArray();
    }

    /**
     * Method untuk mendapatkan jadwal tersedia terdekat
     */
    public static function getJadwalTersediaTerdekat($venueIds = null, $limit = 5)
    {
        $query = self::with('venue')
                  ->available()
                  ->akanDatang()
                  ->orderBy('tanggal')
                  ->orderBy('waktu_mulai');

        if ($venueIds) {
            $query->byVenue($venueIds);
        }

        return $query->limit($limit)->get();
    }

    // ========== METHOD KHUSUS USER ==========

    /**
     * Dapatkan jadwal tersedia untuk user
     */
    public static function getJadwalTersediaForUser($kategori = null, $tanggal = null, $limit = 10)
    {
        $query = self::with(['venue' => function($q) use ($kategori) {
                        if ($kategori) {
                            $q->where('kategori', $kategori);
                        }
                    }])
                  ->available()
                  ->akanDatang();

        if ($tanggal) {
            $query->whereDate('tanggal', $tanggal);
        }

        return $query->orderBy('tanggal')
                    ->orderBy('waktu_mulai')
                    ->limit($limit)
                    ->get()
                    ->filter(function($jadwal) {
                        return $jadwal->venue !== null;
                    });
    }

    // ========== METHOD KHUSUS VENUE ==========

    /**
     * Hitung total jadwal untuk venue
     */
    public static function countJadwalForVenue($venueId)
    {
        return self::byVenue($venueId)->count();
    }

    /**
     * Hitung jadwal tersedia untuk venue
     */
    public static function countJadwalTersedia($venueId)
    {
        return self::byVenue($venueId)->available()->akanDatang()->count();
    }

    /**
     * Ambil jadwal terbaru untuk venue
     */
    public static function getJadwalTerbaru($venueId, $limit = 5)
    {
        return self::byVenue($venueId)
                  ->with('venue')
                  ->terbaru($limit)
                  ->get();
    }

    /**
     * Statistik jadwal untuk venue
     */
    public static function getStatistikJadwal($venueId, $days = 30)
    {
        $startDate = now()->subDays($days)->startOfDay();
        $endDate = now()->addDays(30)->endOfDay(); // 30 hari ke depan

        return self::byVenue($venueId)
                  ->selectRaw('DATE(tanggal) as date, status, COUNT(*) as count')
                  ->whereBetween('tanggal', [$startDate, $endDate])
                  ->groupBy('date', 'status')
                  ->orderBy('date')
                  ->get();
    }

    // ========== METHOD KHUSUS ADMIN ==========

    /**
     * Statistik keseluruhan untuk admin
     */
    public static function getStatistikAdmin()
    {
        return [
            'total_jadwal' => self::count(),
            'jadwal_tersedia' => self::available()->count(),
            'jadwal_dipesan' => self::booked()->count(),
            'jadwal_hari_ini' => self::hariIni()->count(),
            'jadwal_bulan_ini' => self::bulanIni()->count(),
        ];
    }

    /**
     * Top venues berdasarkan jumlah jadwal
     */
    public static function getTopVenuesByJadwal($limit = 5)
    {
        return self::with('venue')
                  ->selectRaw('venue_id, COUNT(*) as total_jadwal')
                  ->groupBy('venue_id')
                  ->orderBy('total_jadwal', 'desc')
                  ->limit($limit)
                  ->get();
    }

    // ========== METHOD UTILITAS ==========

    /**
     * Cek apakah jadwal untuk venue milik user tertentu
     */
    public function isOwnedByVenueOwner($user = null)
    {
        $user = $user ?: Auth::user();
        if (!$user || !method_exists($user, 'venues')) return false;

        $venueIds = $user->venues()->pluck('id')->toArray();
        return in_array($this->venue_id, $venueIds);
    }

    /**
     * Load relasi untuk view jadwal
     */
    public function loadForJadwalView()
    {
        return $this->load(['venue' => function($q) {
            $q->select('id', 'name', 'location', 'kategori', 'price_per_hour');
        }]);
    }

    /**
     * Method untuk mendapatkan data jadwal dalam format array
     */
    public function toJadwalArray()
    {
        if (!$this->relationLoaded('venue')) {
            $this->loadForJadwalView();
        }
        
        return [
            'id' => $this->id,
            'venue_id' => $this->venue_id,
            'venue_name' => $this->nama_venue,
            'venue_location' => $this->lokasi_venue,
            'tanggal' => $this->tanggal,
            'tanggal_formatted' => $this->tanggal_formatted,
            'hari_tanggal' => $this->hari_tanggal,
            'waktu_mulai' => $this->waktu_mulai,
            'waktu_mulai_formatted' => $this->waktu_mulai_formatted,
            'waktu_selesai' => $this->waktu_selesai,
            'waktu_selesai_formatted' => $this->waktu_selesai_formatted,
            'rentang_waktu' => $this->rentang_waktu,
            'durasi_jam' => $this->durasi_jam,
            'durasi_menit' => $this->durasi_menit,
            'status' => $this->status,
            'status_formatted' => $this->status_formatted,
            'css_status' => $this->css_status,
            'color_status' => $this->color_status,
            'icon_status' => $this->icon_status,
            'catatan' => $this->catatan,
            'is_available' => $this->is_available,
            'is_booked' => $this->is_booked,
            'is_past' => $this->is_past,
            'is_today' => $this->is_today,
            'is_tomorrow' => $this->is_tomorrow,
            'bisa_dibooking' => $this->bisaDibooking(),
            'venue' => $this->venue_data,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }

    /**
     * Method untuk format sederhana
     */
    public function toSimpleArray()
    {
        return [
            'id' => $this->id,
            'tanggal' => $this->tanggal_formatted,
            'waktu' => $this->rentang_waktu,
            'status' => $this->status_formatted,
            'status_color' => $this->color_status,
            'venue_name' => $this->nama_venue
        ];
    }
}