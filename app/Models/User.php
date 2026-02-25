<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'profile_photo',
        'venue_name',
        'description',
        'social_media',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'social_media'      => 'array',
    ];

    /**
     * Generate Supabase public URL langsung — TANPA koneksi S3.
     * Bucket profile-photos bersifat Public sehingga URL bisa di-generate
     * secara statik tanpa perlu credentials AWS/Supabase sama sekali.
     */
    public function getProfilePhotoUrlAttribute(): ?string
    {
        if (empty($this->profile_photo)) {
            return null;
        }

        // Supabase project URL (hardcoded fallback jika env belum di-set)
        $supabaseUrl = rtrim(
            config('services.supabase.url', env('SUPABASE_URL', 'https://tyxxjuqqtpezebmwqhug.supabase.co')),
            '/'
        );

        // File lama tersimpan langsung di profile-photos/{filename}
        // Format URL public Supabase: /storage/v1/object/public/{bucket}/{path}
        return "{$supabaseUrl}/storage/v1/object/public/profile-photos/{$this->profile_photo}";
    }

    public function getHasProfilePhotoAttribute(): bool
    {
        return !empty($this->profile_photo);
    }

    public function venues()
    {
        return $this->hasMany(Venue::class, 'user_id');
    }

    public function getPrimaryVenue()
    {
        return $this->venues()->first();
    }

    public function getRoleAttribute(): string
    {
        return $this->roles->first()->name ?? 'user';
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isVenue(): bool
    {
        return $this->hasRole('owner');
    }

    public function isUser(): bool
    {
        return $this->hasRole('user') || $this->roles->isEmpty();
    }
}