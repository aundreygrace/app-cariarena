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
