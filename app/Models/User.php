<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\ServiceRequest;
use App\Models\Business;
use App\Models\Review;
use App\Models\Message;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role',
        'phone', 'avatar', 'city', 'state', 'zip',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function business()
    {
        return $this->hasOne(Business::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class);
    }

    // public function favorites()
    // {
    //     return $this->belongsToMany(Business::class, 'favorites')->withTimestamps();
    // }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    public function favorites()
    {
        return $this->belongsToMany(
            Business::class,
            'favorites', // pivot table name
            'user_id',
            'business_id'
        );
    }


    // ── Helpers ────────────────────────────────────────────────────────────────

    public function isAdmin(): bool    { return $this->role === 'admin'; }
    public function isProvider(): bool { return $this->role === 'provider'; }
    public function isHomeowner(): bool{ return $this->role === 'homeowner'; }

    // public function hasFavorited(Business $business): bool
    // {
    //     return $this->favorites()->where('business_id', $business->id)->exists();
    // }

    public function hasFavorited(Business $business): bool
    {
        return $this->favorites()
            ->where('business_id', $business->id)
            ->exists();
    }
}