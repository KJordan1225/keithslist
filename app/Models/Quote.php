<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ServiceRequest;
use App\Models\Business;

class Quote extends Model
{
    protected $fillable = ['service_request_id', 'business_id', 'amount', 'message', 'status'];

    public function serviceRequest() { return $this->belongsTo(ServiceRequest::class); }
    public function business()       { return $this->belongsTo(Business::class); }
}
