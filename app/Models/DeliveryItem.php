<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryItem extends Model
{
    /** @use HasFactory<\Database\Factories\DeliveryItemFactory> */
    use HasFactory;

    protected $fillable = [
        'delivery_id','inventory_id', 'item_name', 'size', 'quantity', 'note'
    ];

    public function delivery(){
        return $this->belongsTo(Deliveries::class);
    }

    public function inventory(){
        return $this->belongsTo(Inventory::class);
    }
}
