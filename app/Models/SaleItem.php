<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    /** @use HasFactory<\Database\Factories\SaleItemFactory> */
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'inventory_id',
        'quantity',
        'unit_price'
    ];

    public function inventory(){
        return $this->belongsTo(Inventory::class);
    }
}
