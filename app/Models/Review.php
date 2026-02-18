<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';

    protected $fillable = [
        'venue_id',
        'user_id',
        'booking_id',
        'customer_name',
        'rating',
        'comment'
    ];

    protected $casts = [
        'rating' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Kalau sekarang sudah punya updated_at di database
    public $timestamps = true;

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeByVenue($query, $venueId)
    {
        return $query->where('venue_id', $venueId);
    }

    public function scopeWithRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getInitialsAttribute()
    {
        $names = explode(' ', $this->customer_name);
        $initials = '';

        foreach ($names as $name) {
            if (!empty(trim($name))) {
                $initials .= strtoupper(substr(trim($name), 0, 1));
                if (strlen($initials) >= 2) break;
            }
        }

        return $initials ?: 'U';
    }

    public function getFormattedDateAttribute()
    {
        return $this->created_at?->format('d M Y');
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at?->diffForHumans();
    }

    public function getStarRatingAttribute()
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            $stars .= $i <= $this->rating ? '★' : '☆';
        }
        return $stars;
    }

    /*
    |--------------------------------------------------------------------------
    | STATIC HELPERS
    |--------------------------------------------------------------------------
    */

    public static function averageRatingForVenue($venueId)
    {
        return self::where('venue_id', $venueId)->avg('rating');
    }

    public static function countForVenue($venueId)
    {
        return self::where('venue_id', $venueId)->count();
    }

    /*
    |--------------------------------------------------------------------------
    | BOOT VALIDATION
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($review) {
            if ($review->rating < 1 || $review->rating > 5) {
                throw new \Exception('Rating harus antara 1-5');
            }
        });
    }
}
