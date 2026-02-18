<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Transaksi;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'booking';

    protected $fillable = [
        'jadwal_id',
        'venue_id',
        'user_id',
        'nama_customer',
        'customer_phone',
        'tanggal_booking',
        'waktu_booking',
        'end_time',
        'durasi',
        'total_biaya',
        'catatan',
        'status',
        'booking_code',
        'payment_method',
        'paid_at',
        'payment_expired_at'
    ];

    protected $casts = [
        'tanggal_booking' => 'date',
        'waktu_booking' => 'string',
        'end_time' => 'string',
        'total_biaya' => 'integer',
        'durasi' => 'integer',
        'paid_at' => 'datetime',
        'payment_expired_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // ========== STATUS CONSTANTS ==========
    
    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';

    public static function getStatuses()
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PENDING => 'Menunggu Pembayaran',
            self::STATUS_CONFIRMED => 'Terkonfirmasi',
            self::STATUS_EXPIRED => 'Kadaluarsa',
            self::STATUS_CANCELLED => 'Dibatalkan',
            self::STATUS_COMPLETED => 'Selesai'
        ];
    }

    // ========== RELASI ==========
    
    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    public function transaksi()
    {
        return $this->hasOne(
            Transaksi::class,
            'booking_id', // FK di transactions
            'id'          // PK di booking
        );
    }



    // ========== ACCESSORS ==========

    public function getStatusFormattedAttribute()
    {
        $statuses = self::getStatuses();
        return $statuses[$this->status] ?? ucfirst($this->status);
    }

    public function getTotalBiayaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_biaya, 0, ',', '.');
    }

    public function getTanggalBookingFormattedAttribute()
    {
        try {
            return Carbon::parse($this->tanggal_booking)->translatedFormat('l, d F Y');
        } catch (\Exception $e) {
            return date('l, d F Y', strtotime($this->tanggal_booking));
        }
    }

    public function getWaktuBookingFormattedAttribute()
    {
        $start = date('H:i', strtotime($this->waktu_booking));
        $end = date('H:i', strtotime($this->end_time));
        return $start . ' - ' . $end;
    }

    public function getRentangWaktuLengkapAttribute()
    {
        return $this->tanggal_booking_formatted . ', ' . $this->waktu_booking_formatted;
    }

    public function getPaymentMethodFormattedAttribute()
    {
        $methods = [
            'cash' => 'Tunai',
            'transfer' => 'Transfer Bank',
            'credit_card' => 'Kartu Kredit',
            'debit_card' => 'Kartu Debit',
            'qris' => 'QRIS'
        ];
        
        return $methods[$this->payment_method] ?? $this->payment_method;
    }

    public function getIsExpiredAttribute()
    {
        if ($this->status === self::STATUS_EXPIRED) {
            return true;
        }
        
        if ($this->payment_expired_at) {
            return now()->gt($this->payment_expired_at);
        }
        
        return false;
    }

    public function getIsPaidAttribute()
    {
        return !is_null($this->paid_at);
    }

    public function getRemainingPaymentTimeAttribute()
    {
        if (!$this->payment_expired_at || $this->is_expired) {
            return 0;
        }
        
        return max(0, $this->payment_expired_at->diffInMinutes(now()));
    }

    // ========== SCOPE ==========

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    public function scopeExpired($query)
    {
        return $query->where('status', self::STATUS_EXPIRED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * ✅ PERBAIKAN: Scope dengan nama yang konsisten
     */
    public function scopeDenganStatus($query, $status)
    {
        if (is_array($status)) {
            return $query->whereIn('status', $status);
        }
        return $query->where('status', $status);
    }

    public function scopeByVenue($query, $venueId)
    {
        if (is_array($venueId)) {
            return $query->whereIn('venue_id', $venueId);
        }
        return $query->where('venue_id', $venueId);
    }

    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal_booking', today());
    }

    /**
     * ✅ PERBAIKAN: Scope untuk rentang tanggal
     */
    public function scopeRentangTanggal($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal_booking', [$startDate, $endDate]);
    }

    public function scopeAkanDatang($query)
    {
        return $query->where('tanggal_booking', '>=', now()->toDateString())
                    ->whereIn('status', [self::STATUS_PENDING, self::STATUS_CONFIRMED]);
    }

    public function scopeByUser($query, $userId = null)
    {
        $userId = $userId ?? Auth::id();
        return $query->where('user_id', $userId);
    }

    public function scopeWithPaymentExpiring($query, $minutes = 15)
    {
        return $query->where('status', self::STATUS_PENDING)
                    ->where('payment_expired_at', '>', now())
                    ->where('payment_expired_at', '<=', now()->addMinutes($minutes));
    }

    // ========== BUSINESS LOGIC ==========

    /**
     * Cek apakah booking bisa dibatalkan
     * 
     * @return bool
     */
    public function canBeCancelled()
    {
        // Hanya pending atau confirmed yang bisa dibatalkan
        // Dan booking belum lewat tanggalnya
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_CONFIRMED]) 
            && $this->tanggal_booking >= now()->toDateString();
    }

    /**
     * ✅ PERBAIKAN: Mark booking as confirmed dan update jadwal
     * 
     * @param string|null $paymentMethod
     * @return $this
     */
    public function markAsConfirmed($paymentMethod = null)
    {
        $this->update([
            'status' => self::STATUS_CONFIRMED,
            'payment_method' => $paymentMethod,
            'paid_at' => now(),
            'payment_expired_at' => null
        ]);

        // ✅ UPDATE JADWAL MENJADI BOOKED
        if ($this->jadwal) {
            $this->jadwal->markAsBooked($this->id);
            \Log::info("✅ Jadwal #{$this->jadwal->id} di-mark sebagai BOOKED");
        } else {
            \Log::warning("⚠️ Booking #{$this->id} tidak punya relasi ke jadwal");
        }

        return $this;
    }

    /**
     * Mark as paid (alias untuk markAsConfirmed)
     * 
     * @param string|null $paymentMethod
     * @param int|null $amount
     * @return $this
     */
    public function markAsPaid($paymentMethod = null, $amount = null)
    {
        $data = [
            'status' => self::STATUS_CONFIRMED,
            'payment_method' => $paymentMethod,
            'paid_at' => now(),
            'payment_expired_at' => null
        ];

        if ($amount) {
            $data['total_biaya'] = $amount;
        }

        $this->update($data);

        // Update jadwal
        if ($this->jadwal) {
            $this->jadwal->markAsBooked($this->id);
        }

        return $this;
    }

    /**
     * ✅ PERBAIKAN: Cancel booking dan unlock jadwal
     * 
     * @param string|null $reason
     * @return $this
     */
    public function markAsCancelled($reason = null)
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
            'payment_expired_at' => null,
            'catatan' => $this->catatan 
                ? $this->catatan . "\n\nDibatalkan pada: " . now()->format('Y-m-d H:i:s') . ($reason ? " | Alasan: {$reason}" : "")
                : "Dibatalkan pada: " . now()->format('Y-m-d H:i:s') . ($reason ? " | Alasan: {$reason}" : "")
        ]);

        // ✅ UNLOCK JADWAL
        if ($this->jadwal) {
            $this->jadwal->unlock();
            \Log::info("✅ Jadwal #{$this->jadwal->id} di-unlock (booking cancelled)");
        }

        return $this;
    }

    /**
     * ✅ PERBAIKAN: Mark as expired dan unlock jadwal
     * 
     * @return $this
     */
    public function markAsExpired()
    {
        $this->update([
            'status' => self::STATUS_EXPIRED,
            'payment_expired_at' => null
        ]);

        // ✅ UNLOCK JADWAL
        if ($this->jadwal) {
            $this->jadwal->unlock();
            \Log::info("✅ Jadwal #{$this->jadwal->id} di-unlock (booking expired)");
        }

        return $this;
    }

    /**
     * Mark as completed
     * 
     * @return $this
     */
    public function markAsCompleted()
    {
        $this->update([
            'status' => self::STATUS_COMPLETED
        ]);

        return $this;
    }

    // ========== STATIC METHODS ==========

    /**
     * Generate kode booking unik
     * 
     * @return string
     */
    public static function generateBookingCode()
    {
        do {
            $code = 'BK-' . strtoupper(\Illuminate\Support\Str::random(8));
        } while (self::where('booking_code', $code)->exists());

        return $code;
    }

    /**
     * Cek apakah ada conflict dengan booking lain
     * 
     * @param int $venueId
     * @param string $date
     * @param string $start
     * @param string $end
     * @param int|null $excludeId
     * @return bool
     */
    public static function hasConflict($venueId, $date, $start, $end, $excludeId = null) 
    {
        $query = self::where('venue_id', $venueId)
            ->where('tanggal_booking', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('waktu_booking', [$start, $end])
                  ->orWhereBetween('end_time', [$start, $end])
                  ->orWhere(function ($q) use ($start, $end) {
                      $q->where('waktu_booking', '<', $start)
                        ->where('end_time', '>', $end);
                  });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Cek ketersediaan untuk booking baru
     * 
     * @param int $venueId
     * @param string $tanggal
     * @param string $waktuMulai
     * @param int $durasi
     * @return bool
     */
    public static function checkAvailability($venueId, $tanggal, $waktuMulai, $durasi)
    {
        $waktuSelesai = date('H:i:s', strtotime("+{$durasi} hours", strtotime($waktuMulai)));
        
        return !self::hasConflict($venueId, $tanggal, $waktuMulai, $waktuSelesai);
    }

    /**
     * Get statistik booking
     * 
     * @param int|array|null $venueIds
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array
     */
    public static function getBookingStats($venueIds = null, $startDate = null, $endDate = null)
    {
        $query = self::query();

        if ($venueIds) {
            if (is_array($venueIds)) {
                $query->whereIn('venue_id', $venueIds);
            } else {
                $query->where('venue_id', $venueIds);
            }
        }

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_booking', [$startDate, $endDate]);
        }

        $total = $query->count();
        $pending = (clone $query)->where('status', self::STATUS_PENDING)->count();
        $confirmed = (clone $query)->where('status', self::STATUS_CONFIRMED)->count();
        $completed = (clone $query)->where('status', self::STATUS_COMPLETED)->count();
        $cancelled = (clone $query)->where('status', self::STATUS_CANCELLED)->count();
        $revenue = (clone $query)->whereIn('status', [self::STATUS_CONFIRMED, self::STATUS_COMPLETED])
                          ->sum('total_biaya');

        return [
            'total' => $total,
            'pending' => $pending,
            'confirmed' => $confirmed,
            'completed' => $completed,
            'cancelled' => $cancelled,
            'revenue' => $revenue
        ];
    }

    /**
     * ✅ METHOD UTAMA: Cleanup booking yang expired
     * Method ini dipanggil oleh scheduler
     * 
     * @return int Jumlah booking yang di-cleanup
     */
    public static function cleanupExpiredBookings()
    {
        $expiredBookings = self::where('status', self::STATUS_PENDING)
            ->where('payment_expired_at', '<=', now())
            ->with('jadwal')
            ->get();

        $count = 0;
        foreach ($expiredBookings as $booking) {
            $booking->markAsExpired();
            $count++;
        }

        if ($count > 0) {
            \Log::info("✅ Cleaned up {$count} expired bookings");
        }

        return $count;
    }

    // ========== FORMAT UNTUK FRONTEND ==========

    /**
     * Format data untuk ditampilkan di riwayat booking
     * 
     * @return array
     */
    public function formatUntukRiwayat()
    {
        return [
            'id' => $this->id,
            'booking_code' => $this->booking_code,
            'nama_customer' => $this->nama_customer,
            'customer_phone' => $this->customer_phone,
            'tanggal_booking' => $this->tanggal_booking,
            'date_formatted' => $this->tanggal_booking_formatted,
            'waktu_booking' => $this->waktu_booking,
            'time_range' => $this->waktu_booking_formatted,
            'end_time' => $this->end_time,
            'durasi' => $this->durasi,
            'total_biaya' => $this->total_biaya,
            'total_formatted' => $this->total_biaya_formatted,
            'status' => $this->status,
            'status_formatted' => $this->status_formatted,
            'status_class' => $this->getStatusBadgeClass(),
            'payment_method' => $this->payment_method,
            'payment_method_formatted' => $this->payment_method_formatted,
            'paid_at' => $this->paid_at,
            'payment_expired_at' => $this->payment_expired_at,
            'is_expired' => $this->is_expired,
            'is_paid' => $this->is_paid,
            'remaining_payment_time' => $this->remaining_payment_time,
            'catatan' => $this->catatan,
            'created_at' => $this->created_at,
            'can_cancel' => $this->canBeCancelled(),
            'venue' => $this->venue ? [
                'id' => $this->venue->id,
                'name' => $this->venue->name,
                'location' => $this->venue->location,
                'category' => $this->venue->category,
                'photo' => $this->venue->photo
            ] : null,
            'jadwal' => $this->jadwal ? [
                'id' => $this->jadwal->id,
                'tanggal' => $this->jadwal->tanggal,
                'waktu_mulai' => $this->jadwal->waktu_mulai,
                'waktu_selesai' => $this->jadwal->waktu_selesai,
                'status' => $this->jadwal->status
            ] : null
        ];
    }

    /**
     * Get CSS class untuk badge status
     * 
     * @return string
     */
    public function getStatusBadgeClass()
    {
        switch ($this->status) {
            case self::STATUS_CONFIRMED:
            case self::STATUS_COMPLETED:
                return 'badge-success';
            case self::STATUS_PENDING:
                return 'badge-warning';
            case self::STATUS_CANCELLED:
            case self::STATUS_EXPIRED:
                return 'badge-danger';
            case self::STATUS_DRAFT:
                return 'badge-secondary';
            default:
                return 'badge-secondary';
        }
    }
}