<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Notification;
class Inventory extends Model
{
    /** @use HasFactory<\Database\Factories\InventoryFactory> */
    use HasFactory;

    protected $fillable= ['item_name', 'category', 'size_label', 'price', 'stock_quantity', 'reserved_quantity', 'low_stock_threshold'];


    protected static function booted()
    {
        static::saved(function ($inventory) {
            //trigger for Low Stock Alert
            if ($inventory->stock_quantity <= 5) {
            // Check if we already sent an alert to avoid spamming
            Notification::create([
                'type' => 'SYSTEM_NOTE',            
                'sender_id' => null,                   // Ensure this user ID exists or use null
                'receiver_role' => 'All',
                'message' => "{$inventory->item_name} (Size: {$inventory->size_label}) is low! Only {$inventory->stock_quantity} left.",
                'is_read' => false,               // Changed from 'status' => 'pending'
            ]);
            }
        });
    }
}
