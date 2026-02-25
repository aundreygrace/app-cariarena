<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;

class Venue extends Model
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'user_id',
        'name',
        'category',
        'address',
        'price_per_hour',
        'facilities',
        'photo',
        'rating',
        'reviews_count',
        'status'
    ];

    protected $casts = [
        'price_per_hour' => 'integer',
        'rating' => 'decimal:1',
        'reviews_count' => 'integer',
        'facilities' => 'array'
    ];

    public const STATUS_ACTIVE = 'Aktif';
    public const STATUS_MAINTENANCE = 'Maintenance';
    public const STATUS_INACTIVE = 'Tidak Aktif';

    public const CATEGORY_FUTSAL = 'Futsal';
    public const CATEGORY_BADMINTON = 'Badminton';
    public const CATEGORY_BASKET = 'Basket';
    public const CATEGORY_SOCCER = 'Soccer';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($venue) {
            if (!$venue->rating) $venue->rating = 0;
            if (!$venue->reviews_count) $venue->reviews_count = 0;
            if (!$venue->status) $venue->status = self::STATUS_ACTIVE;
        });

        static::deleting(function ($venue) {
            $venue->jadwals()->delete();
            $venue->pemesanans()->delete();
            $venue->fields()->delete();
            $venue->reviews()->delete();
        });
    }

    // ===================== RELASI =====================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function pemesanans()
    {
        return $this->hasMany(Pemesanan::class, 'venue_id');
    }

    public function fields()
    {
        return $this->hasMany(Field::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // ===================== STATIC OPTIONS =====================

    public static function getStatusOptions()
    {
        return [
            self::STATUS_ACTIVE => 'Aktif',
            self::STATUS_MAINTENANCE => 'Maintenance',
            self::STATUS_INACTIVE => 'Tidak Aktif'
        ];
    }

    public static function getCategoryOptions()
    {
        return [
            self::CATEGORY_FUTSAL => 'Futsal',
            self::CATEGORY_BADMINTON => 'Badminton',
            self::CATEGORY_BASKET => 'Basket',
            self::CATEGORY_SOCCER => 'Soccer'
        ];
    }

    // ===================== SCOPES =====================

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeMaintenance($query)
    {
        return $query->where('status', self::STATUS_MAINTENANCE);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    public function scopeFutsal($query)
    {
        return $query->where('category', self::CATEGORY_FUTSAL);
    }

    public function scopeBadminton($query)
    {
        return $query->where('category', self::CATEGORY_BADMINTON);
    }

    public function scopeBasket($query)
    {
        return $query->where('category', self::CATEGORY_BASKET);
    }

    public function scopeSoccer($query)
    {
        return $query->where('category', self::CATEGORY_SOCCER);
    }

    // ===================== ACCESSORS =====================

    public function getStatusBadgeAttribute()
    {
        $statuses = [
            self::STATUS_ACTIVE => ['class' => 'badge badge-success', 'text' => 'Aktif'],
            self::STATUS_MAINTENANCE => ['class' => 'badge badge-warning', 'text' => 'Maintenance'],
            self::STATUS_INACTIVE => ['class' => 'badge badge-danger', 'text' => 'Tidak Aktif']
        ];
        return $statuses[$this->status] ?? $statuses[self::STATUS_INACTIVE];
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price_per_hour, 0, ',', '.') . '/jam';
    }

    public function getFacilitiesArrayAttribute()
    {
        if (empty($this->facilities) || !is_array($this->facilities)) {
            return [];
        }
        return $this->facilities;
    }

    /**
     * ✅ FIXED: Accessor photo URL — support Supabase S3 & local
     */
    public function getPhotoUrlAttribute()
    {
        if (empty($this->photo)) {
            return $this->getDefaultPhotoUrl();
        }

        // Jika sudah URL lengkap (http/https), langsung return
        if (str_starts_with($this->photo, 'http')) {
            return $this->photo;
        }

        $photo = $this->photo;

        // Normalisasi path:
        // "venue/GRIYAFUTSAL.png"  -> "GRIYAFUTSAL.png"
        // "venues/GRIYAFUTSAL.png" -> "GRIYAFUTSAL.png"
        // "FATHKIFUTSAL.png"       -> "FATHKIFUTSAL.png" (tidak berubah)
        $photo = preg_replace('#^venues?/#i', '', $photo);

        // Jika tidak punya ekstensi (data kotor seperti "lapfutsalsda"), return default
        if (!str_contains($photo, '.')) {
            return $this->getDefaultPhotoUrl();
        }

        $disk = config('filesystems.default');

        // Jika pakai S3/Supabase — semua file ada di root bucket "venues"
        if ($disk === 's3') {
            return Storage::disk('s3')->url($photo);
        }

        // Local
        if (Storage::disk('public')->exists('venues/' . $photo)) {
            return asset('storage/venues/' . $photo);
        }

        return $this->getDefaultPhotoUrl();
    }

    public function getDefaultPhotoUrl()
    {
        $defaultImages = [
            'Futsal'     => 'https://images.unsplash.com/photo-1575361204480-aadea25e6e68?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
            'Badminton'  => 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
            'Basket'     => 'https://images.unsplash.com/photo-1544919982-9b7ce4d44d5b?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
            'Soccer'     => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
            'Sepak Bola' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
        ];
        return $defaultImages[$this->category] ?? 'https://images.unsplash.com/photo-1551632811-561732d1e306?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80';
    }

    // ===================== STATUS METHODS =====================

    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isUnderMaintenance()
    {
        return $this->status === self::STATUS_MAINTENANCE;
    }

    public function isInactive()
    {
        return $this->status === self::STATUS_INACTIVE;
    }

    public function markAsActive()
    {
        $this->update(['status' => self::STATUS_ACTIVE]);
        return $this;
    }

    public function markAsMaintenance()
    {
        $this->update(['status' => self::STATUS_MAINTENANCE]);
        return $this;
    }

    public function markAsInactive()
    {
        $this->update(['status' => self::STATUS_INACTIVE]);
        return $this;
    }

    public function toggleStatus()
    {
        if ($this->isActive()) {
            return $this->markAsInactive();
        } else {
            return $this->markAsActive();
        }
    }

    // ===================== RATING & REVIEW =====================

    public function updateRating($newRating, $incrementReviews = true)
    {
        $totalRating = ($this->rating * $this->reviews_count) + $newRating;
        $newReviewsCount = $incrementReviews ? $this->reviews_count + 1 : $this->reviews_count;
        $averageRating = $newReviewsCount > 0 ? $totalRating / $newReviewsCount : 0;

        $this->update([
            'rating' => round($averageRating, 1),
            'reviews_count' => $newReviewsCount
        ]);
        return $this;
    }

    public function addReview($customerName, $rating, $comment)
    {
        $review = $this->reviews()->create([
            'customer_name' => $customerName,
            'rating' => $rating,
            'comment' => $comment,
            'created_at' => now()
        ]);
        $this->updateRating($rating);
        return $review;
    }

    // ===================== VALIDATION =====================

    public static function getValidationRules($venueId = null)
    {
        return [
            'user_id'       => 'required|exists:users,id',
            'name'          => 'required|string|max:100',
            'category'      => 'required|in:Futsal,Badminton,Basket,Soccer',
            'address'       => 'required|string',
            'price_per_hour'=> 'required|integer|min:0',
            'facilities'    => 'nullable|array',
            'photo'         => 'nullable|string|max:255',
            'rating'        => 'nullable|numeric|min:0|max:5',
            'reviews_count' => 'nullable|integer|min:0',
            'status'        => 'required|in:Aktif,Maintenance,Tidak Aktif'
        ];
    }

    // ===================== SEARCH & FILTER =====================

    public static function search($query, $category = null, $minPrice = null, $maxPrice = null, $status = null)
    {
        return self::when($query, function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('address', 'like', "%{$query}%");
            })
            ->when($category, function ($q) use ($category) {
                $q->where('category', $category);
            })
            ->when($minPrice, function ($q) use ($minPrice) {
                $q->where('price_per_hour', '>=', $minPrice);
            })
            ->when($maxPrice, function ($q) use ($maxPrice) {
                $q->where('price_per_hour', '<=', $maxPrice);
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->orderBy('rating', 'desc')
            ->orderBy('reviews_count', 'desc');
    }

    public static function getPopularVenues($limit = 10)
    {
        return self::active()
            ->orderBy('rating', 'desc')
            ->orderBy('reviews_count', 'desc')
            ->limit($limit)
            ->get();
    }

    // ===================== STATISTICS =====================

    public function getStatistics()
    {
        return [
            'total_pemesanans'    => $this->pemesanans()->count(),
            'active_pemesanans'   => $this->pemesanans()->whereIn('status', ['Menunggu', 'Terkonfirmasi'])->count(),
            'completed_pemesanans'=> $this->pemesanans()->where('status', 'Completed')->count(),
            'total_revenue'       => $this->pemesanans()->where('status', 'Completed')->sum('total_biaya'),
            'available_slots'     => $this->jadwals()->where('status', 'Available')->count(),
            'booked_slots'        => $this->jadwals()->where('status', 'Booked')->count(),
            'total_reviews'       => $this->reviews_count,
            'average_rating'      => $this->rating,
        ];
    }

    public function getReviewStatistics()
    {
        $reviews = $this->reviews;
        return [
            'total'     => $reviews->count(),
            'average'   => $reviews->avg('rating') ?? 0,
            'five_star' => $reviews->where('rating', 5)->count(),
            'four_star' => $reviews->where('rating', 4)->count(),
            'three_star'=> $reviews->where('rating', 3)->count(),
            'two_star'  => $reviews->where('rating', 2)->count(),
            'one_star'  => $reviews->where('rating', 1)->count(),
        ];
    }

    // ===================== AVAILABILITY =====================

    public function checkAvailability($date, $startTime, $endTime)
    {
        return !$this->jadwals()
            ->where('tanggal', $date)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('waktu_mulai', [$startTime, $endTime])
                      ->orWhereBetween('waktu_selesai', [$startTime, $endTime])
                      ->orWhere(function ($q) use ($startTime, $endTime) {
                          $q->where('waktu_mulai', '<=', $startTime)
                            ->where('waktu_selesai', '>=', $endTime);
                      });
            })
            ->where('status', 'Booked')
            ->exists();
    }

    public function getAvailableSlots($date)
    {
        return $this->jadwals()
            ->where('tanggal', $date)
            ->where('status', 'Available')
            ->orderBy('waktu_mulai')
            ->get();
    }

    // ===================== UTILITY =====================

    public function getInitials()
    {
        $words = explode(' ', $this->name);
        $initials = '';
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return substr($initials, 0, 2);
    }

    public function getFacilitiesString($separator = ', ')
    {
        if (empty($this->facilities) || !is_array($this->facilities)) {
            return 'Tidak ada fasilitas';
        }
        return implode($separator, $this->facilities);
    }

    public function hasFacility($facility)
    {
        if (empty($this->facilities) || !is_array($this->facilities)) {
            return false;
        }
        return in_array($facility, $this->facilities);
    }
}