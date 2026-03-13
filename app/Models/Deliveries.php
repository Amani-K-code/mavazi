<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deliveries extends Model
{
    /** @use HasFactory<\Database\Factories\DeliveriesFactory> */
    use HasFactory;

    protected $fillable = [
        'delivery_date',
        'total_invoice_amount',
        'payment_due_date',
        'status',
        'user_id',
    ];


    public function items() {
        return $this->hasMany(DeliveryItem::class, 'delivery_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
