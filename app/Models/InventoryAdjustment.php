<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryAdjustment extends Model
{
    /** @use HasFactory<\Database\Factories\InventoryAdjustmentFactory> */
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'quantity_before',
        'quantity_change',
        'quantity_after',
        'reason',
        'user_id'
    ];
}
