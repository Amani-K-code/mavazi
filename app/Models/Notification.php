<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /** @use HasFactory<\Database\Factories\NotificationFactory> */
    use HasFactory;

    protected $fillable = ['sender_id', 'receiver_id', 'type', 'message', 'is_read', 'receiver_role', 'is_system_alert'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    protected $casts = [
        'is_read' => 'boolean',
        'is_system_alert' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
