<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['sender_id', 'recipient_id', 'business_id', 'body', 'read_at'];
    protected $casts = ['read_at' => 'datetime'];

    public function sender()    { return $this->belongsTo(User::class, 'sender_id'); }
    public function recipient() { return $this->belongsTo(User::class, 'recipient_id'); }
    public function business()  { return $this->belongsTo(Business::class); }

    public function isRead(): bool   { return !is_null($this->read_at); }
    public function markRead(): void { $this->update(['read_at' => now()]); }
}
