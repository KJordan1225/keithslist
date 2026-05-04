<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessHour extends Model
{
    public $timestamps = false;

    protected $fillable = ['business_id', 'day_of_week', 'open_time', 'close_time', 'is_closed'];
    protected $casts = ['is_closed' => 'boolean'];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function getDayNameAttribute(): string
    {
        return ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'][$this->day_of_week];
    }
}
