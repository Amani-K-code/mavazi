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
            if ($inventory->stock_quantity <= $inventory->low_stock_threshold) {

            $message = "{$inventory->item_name} (Size: {$inventory->size_label}) is low! Only {$inventory->stock_quantity} left.";

            $alreadyNotified = Notification::where('message', 'like', "%{$inventory->item_name}%")
                ->where('is_read', false)
                ->exists();
            if (!$alreadyNotified) {
                Notification::create([
                    'type' => 'SYSTEM_NOTE',            
                'sender_id' => null,                   // Ensure this user ID exists or use null
                'receiver_role' => 'All',
                'message' => $message,
                'is_read' => false,               // Changed from 'status' => 'pending'
            ]);

            }
            }
        });
    }
}
