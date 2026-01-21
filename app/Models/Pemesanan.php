<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Pemesanan extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'booking';

    // Kolom yang dapat diisi (mass assignment)
    protected $fillable = [
        'jadwal_id',
        'venue_id',
        'user_id',
        'nama_customer',
        'tanggal_booking',
        'waktu_booking',
        'end_time',
        'durasi',
        'total_biaya',
        'catatan',
        'status',
        'booking_code',
        'customer_phone',
        'created_at',
        'updated_at'
    ];

    // Tipe data yang akan di-cast
    protected $casts = [
        'tanggal_booking' => 'date',
        'waktu_booking' => 'string', // Diubah dari datetime ke string karena di database berupa time
        'end_time' => 'string', // Diubah dari datetime ke string karena di database berupa time
        'total_biaya' => 'integer',
        'durasi' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // ========== RELASI DENGAN TABEL LAIN ==========
    
    /**
     * Relasi dengan model Venue (tabel venues)
     */
    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    /**
     * Relasi dengan model Jadwal (tabel jadwal)
     */
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }    

    /**
     * Relasi dengan model User (tabel users)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    

    /**
     * Relasi dengan model Transaction (tabel transactions)
     * PERBAIKAN: Di database transactions, customer_id merujuk ke booking.id
     */
    public function transaction()
    {
        return $this->hasOne(Transaksi::class, 'customer_id', 'id');
    }

    // ========== ACCESSOR UNTUK SEMUA ROLE ==========

    /**
     * Accessor untuk mendapatkan status dalam format yang lebih baik
     */
    public function getStatusFormattedAttribute()
    {
        $statusMap = [
            'Menunggu' => 'Menunggu Konfirmasi',
            'Terkonfirmasi' => 'Terkonfirmasi',
            'Selesai' => 'Selesai',
            'Dibatalkan' => 'Dibatalkan',
            'pending' => 'Menunggu Pembayaran',
            'confirmed' => 'Terkonfirmasi',
            'paid' => 'Lunas',
            'cancelled' => 'Dibatalkan'
        ];

        return $statusMap[$this->status] ?? $this->status;
    }

    /**
     * Accessor untuk format total biaya
     */
    public function getTotalBiayaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_biaya, 0, ',', '.');
    }

    /**
     * Accessor untuk format tanggal booking
     */
    public function getTanggalBookingFormattedAttribute()
    {
        try {
            return Carbon::parse($this->tanggal_booking)->translatedFormat('d F Y');
        } catch (\Exception $e) {
            return date('d F Y', strtotime($this->tanggal_booking));
        }
    }

    /**
     * Accessor untuk format hari dan tanggal booking
     */
    public function getHariTanggalBookingAttribute()
    {
        try {
            return Carbon::parse($this->tanggal_booking)->locale('id')->isoFormat('dddd, D MMMM YYYY');
        } catch (\Exception $e) {
            return date('l, d F Y', strtotime($this->tanggal_booking));
        }
    }

    /**
     * Accessor untuk format waktu booking
     */
    public function getWaktuBookingFormattedAttribute()
    {
        try {
            $start = $this->parseTime($this->waktu_booking)->format('H:i');
            $end = $this->end_time 
                ? $this->parseTime($this->end_time)->format('H:i')
                : $this->parseTime($this->waktu_booking)->addHours($this->durasi)->format('H:i');
            return $start . ' - ' . $end;
        } catch (\Exception $e) {
            return $this->waktu_booking . ' (' . $this->durasi . ' jam)';
        }
    }

    /**
     * Accessor untuk rentang waktu lengkap
     */
    public function getRentangWaktuLengkapAttribute()
    {
        return $this->hari_tanggal_booking . ', ' . $this->waktu_booking_formatted;
    }

    /**
     * Helper untuk parse waktu dari string
     */
    private function parseTime($timeString)
    {
        if ($timeString instanceof Carbon) {
            return $timeString;
        }
        
        try {
            // Coba parse format HH:MM:SS
            if (preg_match('/^\d{1,2}:\d{2}:\d{2}$/', $timeString)) {
                return Carbon::createFromFormat('H:i:s', $timeString);
            }
            // Coba parse format HH:MM
            elseif (preg_match('/^\d{1,2}:\d{2}$/', $timeString)) {
                return Carbon::createFromFormat('H:i', $timeString);
            }
            // Fallback
            else {
                return Carbon::parse($timeString);
            }
        } catch (\Exception $e) {
            return Carbon::now();
        }
    }

    /**
     * Accessor untuk waktu relatif
     */
    public function getWaktuRelatifAttribute()
    {
        return $this->created_at ? $this->created_at->diffForHumans() : '';
    }

    /**
     * Accessor untuk CSS class status (sesuai dengan frontend)
     */
    public function getCssStatusAttribute()
    {
        $statusMap = [
            'Terkonfirmasi' => 'confirmed',
            'Selesai' => 'completed', 
            'Menunggu' => 'pending',
            'Dibatalkan' => 'cancelled',
            'pending' => 'pending',
            'confirmed' => 'confirmed',
            'paid' => 'completed',
            'cancelled' => 'cancelled'
        ];
        
        return $statusMap[$this->status] ?? 'pending';
    }

    /**
     * Accessor untuk status badge class (untuk riwayat view)
     */
    public function getStatusBadgeClassAttribute()
    {
        $status = strtolower($this->status);
        
        if (in_array($status, ['selesai', 'completed', 'paid'])) {
            return 'badge-completed';
        } elseif (in_array($status, ['terkonfirmasi', 'confirmed'])) {
            return 'badge-upcoming';
        } elseif (in_array($status, ['dibatalkan', 'cancelled'])) {
            return 'badge-cancelled';
        } elseif (in_array($status, ['menunggu', 'pending'])) {
            return 'badge-upcoming';
        } else {
            return 'badge-upcoming';
        }
    }

    /**
     * Accessor untuk label status (sesuai dengan frontend)
     */
    public function getLabelStatusAttribute()
    {
        $labelMap = [
            'Terkonfirmasi' => 'Terkonfirmasi',
            'Selesai' => 'Selesai',
            'Menunggu' => 'Menunggu',
            'Dibatalkan' => 'Dibatalkan',
            'pending' => 'Menunggu',
            'confirmed' => 'Terkonfirmasi',
            'paid' => 'Lunas',
            'cancelled' => 'Dibatalkan'
        ];
        
        return $labelMap[$this->status] ?? $this->status;
    }

    /**
     * Format waktu booking untuk display
     */
    public function getWaktuDisplayAttribute()
    {
        try {
            if (!$this->waktu_booking) {
                return 'Waktu tidak tersedia';
            }
            
            $waktuMulai = $this->parseTime($this->waktu_booking)->format('H:i');
            $waktuSelesai = $this->end_time 
                ? $this->parseTime($this->end_time)->format('H:i')
                : $this->parseTime($this->waktu_booking)->addHours($this->durasi)->format('H:i');
            
            return $waktuMulai . '–' . $waktuSelesai;
        } catch (\Exception $e) {
            return 'Format waktu salah';
        }
    }

    /**
     * Accessor untuk menampilkan nama venue
     */
    public function getNamaVenueAttribute()
    {
        if ($this->relationLoaded('venue') && $this->venue) {
            return $this->venue->name;
        }
        return 'Venue Tidak Ditemukan';
    }

    /**
     * Accessor untuk lokasi venue
     */
    public function getLokasiVenueAttribute()
    {
        if ($this->relationLoaded('venue') && $this->venue) {
            return $this->venue->address;
        }
        return 'Lokasi Tidak Diketahui';
    }

    /**
     * Accessor untuk kategori venue
     */
    public function getKategoriVenueAttribute()
    {
        if ($this->relationLoaded('venue') && $this->venue) {
            return $this->venue->category;
        }
        return 'Umum';
    }

    /**
     * Accessor untuk format durasi
     */
    public function getDurasiDisplayAttribute()
    {
        return $this->durasi . ' jam';
    }

    /**
     * Accessor untuk warna status
     */
    public function getColorStatusAttribute()
    {
        $colors = [
            'Menunggu' => 'warning',
            'Terkonfirmasi' => 'success',
            'Selesai' => 'info',
            'Dibatalkan' => 'danger',
            'pending' => 'warning',
            'confirmed' => 'success',
            'paid' => 'primary',
            'cancelled' => 'danger'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Accessor untuk icon status
     */
    public function getIconStatusAttribute()
    {
        $icons = [
            'Menunggu' => '⏳',
            'Terkonfirmasi' => '✅',
            'Selesai' => '🏁',
            'Dibatalkan' => '❌',
            'pending' => '⏳',
            'confirmed' => '✅',
            'paid' => '💰',
            'cancelled' => '❌'
        ];

        return $icons[$this->status] ?? '📋';
    }

    /**
     * Accessor untuk mendapatkan rating dari catatan
     */
    public function getRatingValueAttribute()
    {
        if (!$this->catatan) return 0;
        
        if (preg_match('/rating[:\s]*(\d+)/i', $this->catatan, $matches)) {
            return (int)$matches[1];
        }
        
        if (preg_match('/Rating[^0-9]*([1-5])/i', $this->catatan, $matches)) {
            return (int)$matches[1];
        }
        
        return 0;
    }

    /**
     * Accessor untuk mendapatkan review dari catatan
     */
    public function getReviewTextAttribute()
    {
        if (!$this->catatan) return '';
        
        if (preg_match('/review[:\s]*(.+)/i', $this->catatan, $matches)) {
            return trim($matches[1]);
        }
        
        if (preg_match('/ulasan[:\s]*(.+)/i', $this->catatan, $matches)) {
            return trim($matches[1]);
        }
        
        return '';
    }

    /**
     * Accessor untuk mengecek apakah sudah ada rating
     */
    public function getHasRatingAttribute()
    {
        return $this->rating_value > 0;
    }

    /**
     * Accessor untuk mendapatkan total biaya dari transaksi atau booking
     */
    public function getTotalBiayaAkhirAttribute()
    {
        if ($this->relationLoaded('transaction') && $this->transaction) {
            return $this->transaction->amount ?? $this->total_biaya;
        }
        return $this->total_biaya;
    }

    /**
     * Accessor untuk data venue
     */
    public function getVenueDataAttribute()
    {
        if ($this->relationLoaded('venue') && $this->venue) {
            return $this->venue;
        }
        
        return (object)[
            'id' => $this->venue_id,
            'name' => 'Venue Tidak Diketahui',
            'address' => 'Lokasi Tidak Diketahui',
            'category' => 'Umum',
            'photo' => null
        ];
    }

    /**
     * Accessor untuk data transaksi
     */
    public function getTransactionDataAttribute()
    {
        if ($this->relationLoaded('transaction') && $this->transaction) {
            return $this->transaction;
        }
        
        return (object)[
            'id' => null,
            'transaction_number' => null,
            'amount' => $this->total_biaya,
            'metode_pembayaran' => 'Tunai',
            'status' => 'pending',
            'transaction_date' => $this->created_at
        ];
    }

    /**
     * Accessor untuk data jadwal
     */
    public function getJadwalDataAttribute()
    {
        if ($this->relationLoaded('jadwal') && $this->jadwal) {
            return $this->jadwal;
        }
        
        return (object)[
            'id' => $this->jadwal_id,
            'tanggal' => $this->tanggal_booking,
            'waktu_mulai' => $this->waktu_booking,
            'waktu_selesai' => $this->end_time,
            'status' => 'Booked'
        ];
    }

    /**
     * Accessor untuk mendapatkan email dari user relasi
     */
    public function getCustomerEmailAttribute()
    {
        if ($this->relationLoaded('user') && $this->user) {
            return $this->user->email;
        }
        return null;
    }

    /**
     * Accessor untuk mengecek apakah booking sudah lewat
     */
    public function getIsPastAttribute()
    {
        try {
            $bookingDateTime = Carbon::parse($this->tanggal_booking . ' ' . $this->waktu_booking);
            return $bookingDateTime->isPast();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Accessor untuk mengecek apakah booking hari ini
     */
    public function getIsTodayAttribute()
    {
        try {
            return Carbon::parse($this->tanggal_booking)->isToday();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Accessor untuk mengecek apakah booking di masa depan
     */
    public function getIsFutureAttribute()
    {
        try {
            $bookingDateTime = Carbon::parse($this->tanggal_booking . ' ' . $this->waktu_booking);
            return $bookingDateTime->isFuture();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Accessor untuk mendapatkan datetime booking (Carbon object)
     */
    public function getDateTimeBookingAttribute()
    {
        try {
            return Carbon::parse($this->tanggal_booking . ' ' . $this->waktu_booking);
        } catch (\Exception $e) {
            return null;
        }
    }

    // ========== SCOPE UNTUK SEMUA ROLE ==========
    
    /**
     * Scope untuk pemesanan aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'Terkonfirmasi')
                    ->where('tanggal_booking', '>=', now()->toDateString());
    }

    /**
     * Scope untuk pemesanan menunggu
     */
    public function scopeMenunggu($query)
    {
        return $query->where('status', 'Menunggu');
    }

    /**
     * Scope untuk pemesanan selesai
     */
    public function scopeSelesai($query)
    {
        return $query->where('status', 'Selesai');
    }

    /**
     * Scope untuk pemesanan dibatalkan
     */
    public function scopeDibatalkan($query)
    {
        return $query->where('status', 'Dibatalkan');
    }

    /**
     * Scope berdasarkan venue_id
     */
    public function scopeByVenue($query, $venueId)
    {
        if (is_array($venueId)) {
            return $query->whereIn('venue_id', $venueId);
        }
        return $query->where('venue_id', $venueId);
    }

    /**
     * Scope untuk booking hari ini
     */
    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal_booking', now()->toDateString());
    }

    /**
     * Scope untuk booking minggu ini
     */
    public function scopeMingguIni($query)
    {
        return $query->whereBetween('tanggal_booking', [
            now()->startOfWeek()->toDateString(),
            now()->endOfWeek()->toDateString()
        ]);
    }

    /**
     * Scope untuk booking bulan ini
     */
    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal_booking', now()->month)
                    ->whereYear('tanggal_booking', now()->year);
    }

    /**
     * Scope untuk booking terbaru
     */
    public function scopeTerbaru($query, $limit = 5)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    /**
     * Scope dengan status tertentu
     */
    public function scopeDenganStatus($query, $status)
    {
        if (is_array($status)) {
            return $query->whereIn('status', $status);
        }
        return $query->where('status', $status);
    }

    /**
     * Scope berdasarkan rentang tanggal
     */
    public function scopeRentangTanggal($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal_booking', [$startDate, $endDate]);
    }

    /**
     * Scope untuk booking yang akan datang
     */
    public function scopeAkanDatang($query)
    {
        return $query->where('tanggal_booking', '>=', now()->toDateString())
                    ->whereIn('status', ['Menunggu', 'Terkonfirmasi', 'pending', 'confirmed']);
    }

    // ========== SCOPE KHUSUS ROLE ==========

    /**
     * Scope untuk User - booking milik user yang login
     */
    public function scopeForUser($query)
    {
        $user = Auth::user();
        if (!$user) return $query;
        
        return $query->where(function($q) use ($user) {
            // Prioritas 1: user_id jika ada
            $q->where('user_id', $user->id);
            
            // Fallback: cari berdasarkan nama dan nomor telepon
            $q->orWhere(function($q2) use ($user) {
                $q2->where('nama_customer', $user->name)
                   ->orWhere('customer_phone', $user->phone);
            });
        });
    }
    

    /**
     * Scope untuk Venue - booking untuk venue milik user yang login
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
     * Scope untuk Admin - semua booking
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
     * Method untuk mengecek apakah pemesanan bisa dibatalkan
     */
    public function bisaDibatalkan()
    {
        // Status harus Menunggu atau Terkonfirmasi
        if (!in_array($this->status, ['Menunggu', 'Terkonfirmasi', 'pending', 'confirmed'])) {
            return false;
        }

        $waktuBooking = $this->date_time_booking;
        if (!$waktuBooking) {
            return false;
        }

        $sekarang = Carbon::now();
        
        // Cek jika waktu booking sudah lewat
        if (!$waktuBooking->isFuture()) {
            return false;
        }

        // Cek jika kurang dari 2 jam sebelum booking
        $selisihJam = $sekarang->diffInHours($waktuBooking, false);
        return $selisihJam < -2; // true jika lebih dari 2 jam sebelum booking
    }

    /**
     * Method untuk mengecek apakah pemesanan bisa dikonfirmasi
     */
    public function bisaDikonfirmasi()
    {
        return in_array($this->status, ['Menunggu', 'pending']);
    }

    /**
     * Method untuk mengecek apakah pemesanan bisa diselesaikan
     */
    public function bisaDiselesaikan()
    {
        return in_array($this->status, ['Terkonfirmasi', 'confirmed', 'paid']);
    }

    /**
     * Method untuk mengecek apakah pemesanan sudah selesai (untuk review)
     */
    public function sudahSelesai()
    {
        $waktuBooking = $this->date_time_booking;
        if (!$waktuBooking) {
            return false;
        }

        // Status harus terkonfirmasi dan waktu sudah lewat
        return in_array($this->status, ['Terkonfirmasi', 'confirmed', 'Selesai', 'completed', 'paid']) && 
               $waktuBooking->isPast();
    }

    /**
     * Method untuk mengecek apakah sudah ada rating
     */
    public function sudahAdaRating()
    {
        return $this->rating_value > 0;
    }

    /**
     * Method untuk mengecek apakah bisa direview
     */
    public function bisaDireview()
    {
        return $this->sudahSelesai() && !$this->sudahAdaRating();
    }

    /**
     * Method untuk mengecek konflik jadwal
     */
    public static function hasConflict($venueId, $tanggal, $waktuMulai, $waktuSelesai, $excludeId = null)
    {
        $query = self::where('venue_id', $venueId)
                    ->where('tanggal_booking', $tanggal)
                    ->whereIn('status', ['Menunggu', 'Terkonfirmasi', 'pending', 'confirmed', 'paid'])
                    ->where(function($q) use ($waktuMulai, $waktuSelesai) {
                        $q->whereBetween('waktu_booking', [$waktuMulai, $waktuSelesai])
                          ->orWhereBetween('end_time', [$waktuMulai, $waktuSelesai])
                          ->orWhere(function($q2) use ($waktuMulai, $waktuSelesai) {
                              $q2->where('waktu_booking', '<=', $waktuMulai)
                                 ->where('end_time', '>=', $waktuSelesai);
                          });
                    });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Method untuk generate kode booking
     */
    public static function generateBookingCode()
    {
        $prefix = 'BK';
        $date = date('Ymd');
        
        do {
            $random = strtoupper(substr(md5(uniqid()), 0, 6));
            $code = "{$prefix}-{$date}-{$random}";
        } while (self::where('booking_code', $code)->exists());

        return $code;
    }

    /**
     * Method untuk update status pemesanan
     */
    public function updateStatus($newStatus, $reason = null)
    {
        $allowedStatuses = ['Menunggu', 'Terkonfirmasi', 'Selesai', 'Dibatalkan', 'pending', 'confirmed', 'paid', 'cancelled'];
        
        if (!in_array($newStatus, $allowedStatuses)) {
            return false;
        }

        $oldStatus = $this->status;
        $this->status = $newStatus;
        
        if ($reason) {
            $catatanBaru = "Status berubah dari {$oldStatus} ke {$newStatus} | Alasan: {$reason} | Waktu: " . now()->format('Y-m-d H:i:s');
            $this->catatan = $this->catatan 
                ? $this->catatan . "\n\n" . $catatanBaru
                : $catatanBaru;
        }

        return $this->save();
    }

    /**
     * Method untuk menandai sebagai selesai
     */
    public function markAsCompleted()
    {
        $this->status = 'Selesai';
        $this->catatan = $this->catatan 
            ? $this->catatan . "\n\nDiselesaikan pada: " . now()->format('Y-m-d H:i:s')
            : "Diselesaikan pada: " . now()->format('Y-m-d H:i:s');
        
        return $this->save();
    }

    /**
     * Method untuk menandai sebagai dibatalkan
     */
    public function markAsCancelled($reason = 'Dibatalkan oleh pengguna')
    {
        $this->status = 'Dibatalkan';
        $this->catatan = $this->catatan 
            ? $this->catatan . "\n\nDibatalkan pada: " . now()->format('Y-m-d H:i:s') . " | Alasan: " . $reason
            : "Dibatalkan pada: " . now()->format('Y-m-d H:i:s') . " | Alasan: " . $reason;
        
        return $this->save();
    }

    /**
     * Method untuk tambah rating dan ulasan
     */
    public function tambahRating($rating, $ulasan = null)
    {
        $teksRating = "Rating: {$rating}";
        if ($ulasan) {
            $teksRating .= "\nUlasan: {$ulasan}";
        }
        
        $this->catatan = $this->catatan 
            ? $this->catatan . "\n\n" . $teksRating
            : $teksRating;
        
        return $this->save();
    }

    // ========== METHOD UNTUK RIWAYAT ==========

    /**
     * Format data untuk riwayat view
     */
    public function formatUntukRiwayat()
    {
        // Tentukan status untuk frontend
        $status = strtolower($this->status);
        $statusText = $this->label_status;
        $statusClass = $this->status_badge_class;
        
        // Ambil data venue
        $venue = $this->venue;
        $namaVenue = $venue ? $venue->name : 'Venue Tidak Diketahui';
        $lokasiVenue = $venue ? $venue->address : 'Lokasi Tidak Diketahui';
        $kategoriVenue = $venue ? $venue->category : 'Umum';
        
        // Ambil total biaya
        $totalBiaya = $this->transaction ? $this->transaction->amount : $this->total_biaya;
        
        // Cek apakah bisa dibatalkan
        $bisaDibatalkan = $this->bisaDibatalkan();
        
        // Cek apakah sudah selesai
        $sudahSelesai = $this->sudahSelesai();
        
        // Cek rating
        $memilikiRating = $this->sudahAdaRating();
        $rating = $this->rating_value;
        $ulasan = $this->review_text;
        
        // Bisa direview jika sudah selesai dan belum ada rating
        $bisaDireview = $sudahSelesai && !$memilikiRating;

        return (object)[
            'id' => $this->id,
            'booking_code' => $this->booking_code,
            'nama_customer' => $this->nama_customer,
            'tanggal_booking' => $this->tanggal_booking,
            'date_formatted' => $this->hari_tanggal_booking,
            'waktu_booking' => $this->waktu_booking,
            'time_range' => $this->waktu_booking_formatted,
            'end_time' => $this->end_time,
            'durasi' => $this->durasi,
            'total_price' => $totalBiaya,
            'total_biaya' => $totalBiaya,
            'status' => $status,
            'status_class' => $statusClass,
            'status_text' => $statusText,
            'venue_name' => $namaVenue,
            'location' => $lokasiVenue,
            'category' => $kategoriVenue,
            'customer_phone' => $this->customer_phone,
            'catatan' => $this->catatan,
            'created_at' => $this->created_at,
            'can_cancel' => $bisaDibatalkan,
            'can_review' => $bisaDireview,
            'has_rating' => $memilikiRating,
            'rating' => $rating,
            'review' => $ulasan,
            'booking_date_time' => $this->date_time_booking,
            'is_past' => $this->is_past,
            'is_today' => $this->is_today,
            'is_future' => $this->is_future,
            'is_completed' => $sudahSelesai
        ];
    }

    /**
     * Method untuk ekstrak rating dari catatan
     */
    public function ekstrakRating($catatan = null)
    {
        $catatanText = $catatan ?: $this->catatan;
        
        if (!$catatanText) return 0;
        
        if (preg_match('/rating[:\s]*(\d+)/i', $catatanText, $matches)) {
            return (int)$matches[1];
        }
        
        if (preg_match('/Rating:\s*(\d+)/i', $catatanText, $matches)) {
            return (int)$matches[1];
        }
        
        if (preg_match('/Rating[^0-9]*([1-5])/i', $catatanText, $matches)) {
            return (int)$matches[1];
        }
        
        return 0;
    }

    /**
     * Method untuk ekstrak ulasan dari catatan
     */
    public function ekstrakUlasan($catatan = null)
    {
        $catatanText = $catatan ?: $this->catatan;
        
        if (!$catatanText) return '';
        
        if (preg_match('/review[:\s]*(.+)/i', $catatanText, $matches)) {
            return trim($matches[1]);
        }
        
        if (preg_match('/Ulasan[:\s]*(.+)/i', $catatanText, $matches)) {
            return trim($matches[1]);
        }
        
        return '';
    }

    // ========== METHOD STATISTIK ==========

    /**
     * Method untuk menghitung total pendapatan
     */
    public static function getTotalRevenue($venueIds = null, $startDate = null, $endDate = null)
    {
        $query = self::whereIn('status', ['Terkonfirmasi', 'Selesai', 'confirmed', 'paid']);

        if ($venueIds) {
            $query->byVenue($venueIds);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_booking', [$startDate, $endDate]);
        }

        return $query->sum('total_biaya');
    }

    /**
     * Method untuk statistik pemesanan
     */
    public static function getBookingStats($venueIds = null, $period = 'month')
    {
        $query = self::selectRaw('status, COUNT(*) as count');

        if ($venueIds) {
            $query->byVenue($venueIds);
        }

        // Filter periode
        if ($period === 'today') {
            $query->whereDate('created_at', today());
        } elseif ($period === 'week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($period === 'month') {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        }

        return $query->groupBy('status')
                    ->get()
                    ->pluck('count', 'status')
                    ->toArray();
    }

    /**
     * Method untuk mendapatkan pemesanan yang akan datang
     */
    public static function getUpcomingBookings($venueIds = null, $limit = 5)
    {
        $query = self::with('venue')
                  ->where('tanggal_booking', '>=', now()->toDateString())
                  ->whereIn('status', ['Menunggu', 'Terkonfirmasi', 'pending', 'confirmed'])
                  ->orderBy('tanggal_booking')
                  ->orderBy('waktu_booking');

        if ($venueIds) {
            $query->byVenue($venueIds);
        }

        return $query->limit($limit)->get();
    }

    // ========== METHOD UNTUK VALIDASI ==========

    /**
     * Validasi apakah booking milik user
     */
    public function validateOwnership($user = null)
    {
        $user = $user ?: Auth::user();
        if (!$user) return false;

        return $this->user_id === $user->id || 
               $this->customer_phone === $user->phone ||
               $this->nama_customer === $user->name;
    }

    /**
     * Load relasi untuk riwayat view
     */
    public function loadForRiwayat()
    {
        return $this->load(['venue' => function($q) {
            $q->select('id', 'name', 'address', 'category', 'photo');
        }, 'transaction' => function($q) {
            $q->select('id', 'amount', 'metode_pembayaran', 'status', 'transaction_number', 'transaction_date');
        }]);
    }
}