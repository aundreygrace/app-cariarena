<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    // Nama tabel sesuai database
    protected $table = 'reviews';

    // Kolom yang bisa diisi (sesuai struktur database)
    protected $fillable = [
        'venue_id',
        'customer_name',
        'rating',
        'comment'
        // Note: created_at sudah otomatis dari database
    ];

    // Kolom yang harus disembunyikan
    protected $hidden = [];

    // Casting tipe data
    protected $casts = [
        'rating' => 'integer',
        'created_at' => 'datetime'
    ];

    // Timestamps - database sudah punya created_at, tapi tidak ada updated_at
    public $timestamps = false;

    /**
     * Relasi dengan venue
     */
    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    /**
     * Scope untuk filter berdasarkan venue
     */
    public function scopeByVenue($query, $venueId)
    {
        return $query->where('venue_id', $venueId);
    }

    /**
     * Scope untuk rating tertentu
     */
    public function scopeWithRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Accessor untuk inisial customer
     */
    public function getInitialsAttribute()
    {
        $names = explode(' ', $this->customer_name);
        $initials = '';
        
        foreach ($names as $name) {
            if (!empty(trim($name))) {
                $initials .= strtoupper(substr(trim($name), 0, 1));
                if (strlen($initials) >= 2) {
                    break; // Maksimal 2 huruf
                }
            }
        }
        
        return $initials ?: 'U'; // Default 'U' untuk User
    }

    /**
     * Accessor untuk format tanggal yang lebih baik
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d M Y');
    }

    /**
     * Accessor untuk waktu yang lalu
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Accessor untuk rating dalam bintang
     */
    public function getStarRatingAttribute()
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '★';
            } else {
                $stars .= '☆';
            }
        }
        return $stars;
    }

    /**
     * Validasi rating (1-5)
     */
    public static function validateRating($rating)
    {
        return is_numeric($rating) && $rating >= 1 && $rating <= 5;
    }

    /**
     * Method untuk menghitung rata-rata rating venue
     */
    public static function averageRatingForVenue($venueId)
    {
        return self::where('venue_id', $venueId)->avg('rating');
    }

    /**
     * Method untuk menghitung total review venue
     */
    public static function countForVenue($venueId)
    {
        return self::where('venue_id', $venueId)->count();
    }

    /**
     * Boot method untuk validasi
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($review) {
            // Validasi rating
            if ($review->rating < 1 || $review->rating > 5) {
                throw new \Exception('Rating harus antara 1-5');
            }
        });
    }
}