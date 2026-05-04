<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'business_id', 'user_id', 'rating',
        'quality_rating', 'responsiveness_rating', 'punctuality_rating', 'professionalism_rating',
        'title', 'body', 'service_used', 'service_date', 'price_paid',
        'would_hire_again', 'verified', 'status',
        'owner_response', 'owner_responded_at', 'helpful_count',
    ];

    protected $casts = [
        'would_hire_again'     => 'boolean',
        'verified'             => 'boolean',
        'service_date'         => 'date',
        'owner_responded_at'   => 'datetime',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function helpfulVotes()
    {
        return $this->belongsToMany(User::class, 'review_helpfuls')->withTimestamps();
    }

    protected static function booted(): void
    {
        static::saved(function (Review $review) {
            $review->business->recalculateRating();
        });

        static::deleted(function (Review $review) {
            $review->business->recalculateRating();
        });
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
