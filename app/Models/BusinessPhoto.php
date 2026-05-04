<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessPhoto extends Model
{
    protected $fillable = ['business_id', 'path', 'caption', 'is_primary'];
    protected $casts = ['is_primary' => 'boolean'];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
