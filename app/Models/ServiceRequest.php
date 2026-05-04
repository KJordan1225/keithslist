<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'category_id', 'title', 'description',
        'city', 'state', 'zip', 'desired_date', 'urgency',
        'budget_min', 'budget_max', 'status',
    ];

    protected $casts = ['desired_date' => 'date'];

    public function user()       { return $this->belongsTo(User::class); }
    public function category()   { return $this->belongsTo(Category::class); }
    public function quotes()     { return $this->hasMany(Quote::class); }
}