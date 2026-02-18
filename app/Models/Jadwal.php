<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'catatan',
        'booking_id',
        'source',
        'locked_until',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_mulai' => 'string',
        'waktu_selesai' => 'string',
        'locked_until' => 'datetime',
    ];

    // Status constants
    const STATUS_AVAILABLE = 'Available';
    const STATUS_PENDING = 'Pending';
    const STATUS_BOOKED = 'Booked';

    // ========== RELASI ==========

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'booking_id');
    }

    // ========== SCOPE ==========

    public function scopeByVenue($query, $venueId)
    {
        return $query->where('venue_id', $venueId);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE)
                    ->where(function($q) {
                        $q->whereNull('locked_until')
                          ->orWhere('locked_until', '<', now());
                    });
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('tanggal', $date);
    }

    // ========== METHODS ==========

    /**
     * Lock jadwal untuk waktu tertentu (dalam menit)
     */
    public function lock($minutes = 15)
    {
        $this->update([
            'locked_until' => now()->addMinutes($minutes),
            'status' => self::STATUS_PENDING,
        ]);

        \Log::info("Jadwal #{$this->id} locked until " . now()->addMinutes($minutes));
    }

    /**
     * Unlock jadwal
     */
    public function unlock()
    {
        $this->update([
            'locked_until' => null,
            'status' => self::STATUS_AVAILABLE,
            'booking_id' => null,
        ]);

        \Log::info("Jadwal #{$this->id} unlocked");
    }

    /**
     * Mark jadwal sebagai booked
     */
    public function markAsBooked($bookingId)
    {
        $this->update([
            'status' => self::STATUS_BOOKED,
            'booking_id' => $bookingId,
            'locked_until' => null, // Clear lock karena sudah confirmed
        ]);

        \Log::info("Jadwal #{$this->id} marked as booked");
    }

    /**
     * ✅ METHOD UTAMA: Check apakah slot tersedia untuk booking
     * 
     * @param int $venueId
     * @param string $tanggal Format: Y-m-d
     * @param string $waktuMulai Format: H:i:s atau H:i
     * @param int $durasi Dalam jam
     * @return array ['available' => bool, 'message' => string, 'jadwal' => Jadwal|null]
     */
    public static function checkSlotAvailability($venueId, $tanggal, $waktuMulai, $durasi)
    {
        \Log::info("=== CHECK SLOT AVAILABILITY ===");
        \Log::info("Venue ID: {$venueId}");
        \Log::info("Tanggal: {$tanggal}");
        \Log::info("Waktu Mulai: {$waktuMulai}");
        \Log::info("Durasi: {$durasi} jam");

        // Normalize waktu format
        if (strlen($waktuMulai) === 5) {
            $waktuMulai = $waktuMulai . ':00';
        }

        // Hitung waktu selesai
        $waktuSelesai = Carbon::createFromFormat('H:i:s', $waktuMulai)
            ->addHours($durasi)
            ->format('H:i:s');

        \Log::info("Waktu Selesai Calculated: {$waktuSelesai}");

        // Cari jadwal yang match dengan waktu_mulai
        $jadwal = self::where('venue_id', $venueId)
            ->whereDate('tanggal', $tanggal)
            ->where('waktu_mulai', $waktuMulai)
            ->first();

        if (!$jadwal) {
            \Log::error("❌ Jadwal tidak ditemukan untuk waktu {$waktuMulai}");
            return [
                'available' => false,
                'message' => 'Slot waktu tidak tersedia di sistem',
                'jadwal' => null,
            ];
        }

        \Log::info("✅ Jadwal ditemukan: ID {$jadwal->id}, Status: {$jadwal->status}");

        // Cek apakah jadwal available
        if ($jadwal->status !== self::STATUS_AVAILABLE) {
            \Log::error("❌ Jadwal status: {$jadwal->status} (not Available)");
            return [
                'available' => false,
                'message' => 'Slot waktu sudah dibooking',
                'jadwal' => null,
            ];
        }

        // Cek apakah jadwal sedang di-lock
        if ($jadwal->locked_until && now()->lt($jadwal->locked_until)) {
            \Log::error("❌ Jadwal masih di-lock sampai {$jadwal->locked_until}");
            return [
                'available' => false,
                'message' => 'Slot waktu sedang dalam proses booking oleh user lain',
                'jadwal' => null,
            ];
        }

        // ✅ ADDITIONAL CHECK: Cek apakah ada slot lain yang overlap
        // Misalnya user pilih jam 08:00 durasi 3 jam (sampai 11:00)
        // Kita perlu pastikan slot 09:00 dan 10:00 juga available
        
        $overlappingSlots = self::where('venue_id', $venueId)
            ->whereDate('tanggal', $tanggal)
            ->where('waktu_mulai', '>', $waktuMulai)
            ->where('waktu_mulai', '<', $waktuSelesai)
            ->where(function($query) {
                $query->where('status', '!=', self::STATUS_AVAILABLE)
                      ->orWhere(function($q) {
                          $q->whereNotNull('locked_until')
                            ->where('locked_until', '>', now());
                      });
            })
            ->count();

        if ($overlappingSlots > 0) {
            \Log::error("❌ Ada {$overlappingSlots} slot yang overlap dan tidak available");
            return [
                'available' => false,
                'message' => 'Beberapa slot dalam rentang waktu yang Anda pilih sudah dibooking',
                'jadwal' => null,
            ];
        }

        \Log::info("✅✅✅ Slot TERSEDIA!");
        return [
            'available' => true,
            'message' => 'Slot tersedia',
            'jadwal' => $jadwal,
        ];
    }

    /**
     * Get available slots untuk tanggal tertentu
     */
    public static function getAvailableSlots($venueId, $tanggal)
    {
        return self::where('venue_id', $venueId)
            ->whereDate('tanggal', $tanggal)
            ->available()
            ->orderBy('waktu_mulai')
            ->get();
    }

    /**
     * Cleanup expired locks
     * Dipanggil oleh scheduler
     */
    public static function cleanupExpiredLocks()
    {
        $count = self::where('status', self::STATUS_PENDING)
            ->whereNotNull('locked_until')
            ->where('locked_until', '<=', now())
            ->update([
                'status' => self::STATUS_AVAILABLE,
                'locked_until' => null,
                'booking_id' => null,
            ]);

        if ($count > 0) {
            \Log::info("✅ Cleaned up {$count} expired jadwal locks");
        }

        return $count;
    }

    /**
     * Check if jadwal is currently locked
     */
    public function isLocked()
    {
        return $this->locked_until && now()->lt($this->locked_until);
    }

    /**
     * Check if jadwal is available
     */
    public function isAvailable()
    {
        return $this->status === self::STATUS_AVAILABLE && !$this->isLocked();
    }
}