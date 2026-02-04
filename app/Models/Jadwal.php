<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

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
        'catatan',
        'source',
        'booking_id',
        'locked_until'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'locked_until' => 'datetime'
    ];

    // ========== KONSTANTA STATUS ==========
    
    const STATUS_AVAILABLE = 'Available';
    const STATUS_PENDING = 'Pending';
    const STATUS_BOOKED = 'Booked';
    
    public static function getStatusOptions()
    {
        return [
            self::STATUS_AVAILABLE => 'Tersedia',
            self::STATUS_PENDING => 'Menunggu Pembayaran',
            self::STATUS_BOOKED => 'Dipesan',
        ];
    }

    // ========== RELASI ==========
    
    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function pemesanan()
    {
        return $this->hasOne(Pemesanan::class, 'jadwal_id');
    }

    // ========== METHOD UTAMA ==========

    public function lock($minutes = 15)
    {
        $this->update([
            'locked_until' => now()->addMinutes($minutes),
            'status' => self::STATUS_PENDING, // Status menjadi Pending
            'source' => 'user_booking'
        ]);
        
        \Log::info("Slot {$this->id} terkunci sampai {$this->locked_until}");
        return $this;
    }

    public function unlock()
    {
        $this->update([
            'locked_until' => null,
            'status' => self::STATUS_AVAILABLE, // Kembali ke Available
            'booking_id' => null,
            'source' => 'admin'
        ]);
        
        \Log::info("Slot {$this->id} dibuka kuncinya");
        return $this;
    }
    

    public function isLocked()
    {
        return $this->locked_until && now()->lt($this->locked_until);
    }

    public function isAvailableForBooking()
    {
        return $this->status === self::STATUS_AVAILABLE && 
               !$this->isLocked() && 
               !$this->isPast();
    }

    public function markAsBooked($bookingId = null)
    {
        $this->update([
            'status' => self::STATUS_BOOKED,
            'locked_until' => null,
            'booking_id' => $bookingId,
            'source' => $bookingId ? 'user_confirmed' : 'admin'
        ]);
        
        if ($bookingId) {
            $note = "✅ Dikonfirmasi: " . now()->format('d/m/Y H:i') . 
                   " | Booking: " . $bookingId;
            $this->catatan = $this->catatan 
                ? $this->catatan . "\n" . $note 
                : $note;
            $this->save();
        }
        
        \Log::info("Slot {$this->id} status menjadi BOOKED");
        return $this;
    }

    public static function releaseExpiredLocks()
    {
        $expired = self::where('status', self::STATUS_PENDING)
                      ->where('locked_until', '<', now())
                      ->get();
        
        foreach ($expired as $jadwal) {
            $jadwal->unlock();
        }
        
        \Log::info("Released {$expired->count()} expired locks");
        return $expired->count();
    }

    public static function checkSlotAvailability($venueId, $tanggal, $waktuMulai, $durasi = null)
    {
        $waktuMulaiDb = date('H:i:s', strtotime($waktuMulai));
    
        $jadwal = self::where('venue_id', $venueId)
                    ->where('tanggal', $tanggal)
                    ->where('waktu_mulai', $waktuMulaiDb)
                    ->first();
        
        if (!$jadwal) {
            return [
                'available' => false,
                'message' => 'Slot belum dibuat oleh owner venue'
            ];
        }

        // ❗ Cek jika slot sudah Booked atau Pending
        if ($jadwal->status !== self::STATUS_AVAILABLE) {
            $statusText = $jadwal->status === self::STATUS_PENDING 
                ? 'Menunggu Pembayaran' 
                : 'Sudah Dipesan';
            return [
                'available' => false,
                'message' => "Slot {$statusText}"
            ];
        }

        if ($jadwal->isLocked()) {
            $minutesLeft = now()->diffInMinutes($jadwal->locked_until, false);
            return [
                'available' => false,
                'message' => "Slot sedang diproses (tersedia dalam {$minutesLeft} menit)"
            ];
        }

        if ($jadwal->isPast()) {
            return [
                'available' => false,
                'message' => 'Slot sudah lewat'
            ];
        }

        if ($durasi) {
            $availableHours = $jadwal->getAvailableHours();
            if ($durasi > $availableHours) {
                return [
                    'available' => false,
                    'message' => "Durasi melebihi batas maksimal ({$availableHours} jam)"
                ];
            }

            $endTime = date('H:i:s', strtotime("+{$durasi} hours", strtotime($waktuMulaiDb)));
            $conflict = self::hasConflict($venueId, $tanggal, $waktuMulaiDb, $endTime);
            
            if ($conflict) {
                return [
                    'available' => false,
                    'message' => 'Waktu bertabrakan dengan booking lain'
                ];
            }
        }

        return [
            'available' => true,
            'jadwal' => $jadwal,
            'message' => 'Slot tersedia'
        ];
    }

    public static function hasConflict($venueId, $tanggal, $waktuMulai, $waktuSelesai, $excludeId = null)
    {
        $query = self::where('venue_id', $venueId)
                    ->where('tanggal', $tanggal)
                    ->whereIn('status', [self::STATUS_BOOKED, self::STATUS_PENDING])
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

    // ========== SCOPE ==========

    public function scopeAvailableForBooking($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE)
                     ->where(function($q) {
                         $q->whereNull('locked_until')
                           ->orWhere('locked_until', '<', now());
                     })
                     ->where('tanggal', '>=', now()->toDateString());
    }

    // ========== ACCESSORS ==========

    public function getStatusFormattedAttribute()
    {
        $statusMap = [
            self::STATUS_AVAILABLE => 'Tersedia',
            self::STATUS_PENDING => 'Menunggu Pembayaran',
            self::STATUS_BOOKED => 'Dipesan',
        ];

        return $statusMap[$this->status] ?? ucfirst($this->status);
    }

    public function getTanggalFormattedAttribute()
    {
        return Carbon::parse($this->tanggal)->translatedFormat('d F Y');
    }

    public function getWaktuMulaiFormattedAttribute()
    {
        return Carbon::parse($this->waktu_mulai)->format('H:i');
    }

    public function getWaktuSelesaiFormattedAttribute()
    {
        return $this->waktu_selesai 
            ? Carbon::parse($this->waktu_selesai)->format('H:i')
            : '--:--';
    }

    public function getRentangWaktuAttribute()
    {
        $selesai = $this->waktu_selesai_formatted;
        return $selesai !== '--:--' 
            ? "{$this->waktu_mulai_formatted} - {$selesai}"
            : $this->waktu_mulai_formatted;
    }

    public function getIsAvailableAttribute()
    {
        return $this->status === self::STATUS_AVAILABLE && !$this->isLocked();
    }

    public function getIsPastAttribute()
    {
        try {
            $jadwalDateTime = Carbon::parse($this->tanggal . ' ' . $this->waktu_mulai);
            return $jadwalDateTime->isPast();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getNamaVenueAttribute()
    {
        return $this->venue->name ?? 'Venue';
    }

    // ========== METHOD BANTUAN ==========

    public function getAvailableHours()
    {
        try {
            if (!$this->waktu_selesai) return 1;
            $start = Carbon::parse($this->waktu_mulai);
            $end = Carbon::parse($this->waktu_selesai);
            return $end->diffInHours($start);
        } catch (\Exception $e) {
            return 1;
        }
    }

    // Jadwal.php - Method getAvailableSlotsForUser()
    public static function getAvailableSlotsForUser($venueId, $date = null)
    {
        $query = self::with('venue')
                    ->where('venue_id', $venueId)
                    ->availableForBooking(); // Scope hanya slot Available
        
        if ($date) {
            $query->whereDate('tanggal', $date);
        }
        
        return $query->orderBy('tanggal')
                    ->orderBy('waktu_mulai')
                    ->get()
                    ->map(function($jadwal) {
                        return [
                            'id' => $jadwal->id,
                            'date' => $jadwal->tanggal->format('Y-m-d'),
                            'start_time' => $jadwal->waktu_mulai_formatted,
                            'status' => $jadwal->status,
                            'is_available' => $jadwal->isAvailableForBooking(),
                            'is_locked' => $jadwal->isLocked(),
                        ];
                    });
    }

    public function scopeAvailableForDate($query, $date)
    {
        return $query->where('tanggal', $date)
            ->where('status', self::STATUS_AVAILABLE)
            ->where('tanggal', '>=', now()->toDateString())
            ->where(function($q) {
                $q->whereNull('locked_until')
                  ->orWhere('locked_until', '<', now());
            });
    }
    
    public function scopeByVenue( Builder $query, $venueId)
    {
        return $query->where('venue_id', $venueId);
    }
 

}