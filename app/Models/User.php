<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
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
        'social_media' => 'array',
    ];

    /**
     * ✅ GET PROFILE PHOTO URL (Role-based)
     * No need for helper!
     */
    public function getProfilePhotoUrlAttribute()
    {
        if (!$this->profile_photo) {
            return null;
        }

        // Determine role folder
        $isOwner = $this->hasRole('owner');
        $roleFolder = $isOwner ? 'owners' : 'users';
        
        // Build path: storage/profile-photos/{role}/{user_id}/{filename}
        $path = "profile-photos/{$roleFolder}/{$this->id}/{$this->profile_photo}";
        
        // Check if file exists in role-based folder
        if (Storage::disk('public')->exists($path)) {
            return asset("storage/{$path}");
        }
        
        // Fallback: check old location (backward compatibility)
        $oldPath = "profile-photos/{$this->profile_photo}";
        if (Storage::disk('public')->exists($oldPath)) {
            return asset("storage/{$oldPath}");
        }
        
        return null;
    }

    /**
     * Check if user has profile photo
     */
    public function getHasProfilePhotoAttribute()
    {
        return !is_null($this->profile_photo_url);
    }

    public function venues()
    {
        return $this->hasMany(Venue::class, 'user_id');
    }

    public function getPrimaryVenue()
    {
        return $this->venues()->first();
    }

    public function getRoleAttribute()
    {
        return $this->roles->first()->name ?? 'user';
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isVenue()
    {
        return $this->hasRole('owner');
    }

    public function isUser()
    {
        return $this->hasRole('user') || $this->roles->isEmpty();
    }
}
