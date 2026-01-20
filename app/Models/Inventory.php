<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    /** @use HasFactory<\Database\Factories\InventoryFactory> */
    use HasFactory;

    protected $fillable= ['item_name', 'category', 'size_label', 'price', 'stock_quantity', 'reserved_quantity', 'low_stock_threshold'];
}
