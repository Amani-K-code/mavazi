<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    /** @use HasFactory<\Database\Factories\ReservationFactory> */
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'quantity',
        'staff_id',
        'expires_at',
        'status',
    ];


    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }
}
