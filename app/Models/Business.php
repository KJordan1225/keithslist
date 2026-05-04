<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'name', 'slug', 'description', 'logo',
        'phone', 'email', 'website',
        'address', 'city', 'state', 'zip',
        'latitude', 'longitude', 'service_radius',
        'years_in_business', 'licensed', 'insured', 'background_checked',
        'status', 'featured', 'avg_rating', 'review_count',
    ];

    protected $casts = [
        'licensed'           => 'boolean',
        'insured'            => 'boolean',
        'background_checked' => 'boolean',
        'featured'           => 'boolean',
        'avg_rating'         => 'float',
        'latitude'           => 'float',
        'longitude'          => 'float',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'business_category');
    }

    public function photos()
    {
        return $this->hasMany(BusinessPhoto::class);
    }

    public function primaryPhoto()
    {
        return $this->hasOne(BusinessPhoto::class)->where('is_primary', true);
    }

    public function hours()
    {
        return $this->hasMany(BusinessHour::class)->orderBy('day_of_week');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    // ── Scopes ─────────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeInCity($query, string $city, string $state)
    {
        return $query->where('city', $city)->where('state', $state);
    }

    public function scopeWithCategory($query, $categoryId)
    {
        return $query->whereHas('categories', fn($q) => $q->where('categories.id', $categoryId));
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    public function getRouteKeyName(): string { return 'slug'; }

    public function recalculateRating(): void
    {
        $avg = $this->approvedReviews()->avg('rating') ?? 0;
        $count = $this->approvedReviews()->count();
        $this->update(['avg_rating' => round($avg, 2), 'review_count' => $count]);
    }

    public function isOpenNow(): bool
    {
        $today = now()->dayOfWeek; // 0=Sun
        $hour = $this->hours->firstWhere('day_of_week', $today);
        if (!$hour || $hour->is_closed) return false;
        $now = now()->format('H:i:s');
        return $now >= $hour->open_time && $now <= $hour->close_time;
    }

    public function getFullAddressAttribute(): string
    {
        return "{$this->address}, {$this->city}, {$this->state} {$this->zip}";
    }
}
