<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class HealthCheckTestSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'Admin')->first() ?? User::first();

        // 1. Trigger RED Circle (Critical)
        Inventory::updateOrCreate(
            ['item_name' => 'POLO T-SHIRT', 'size_label' => 'S UPto 4 Yrs'],
            [
                'stock_quantity' => 2, // Fixed the 'category => 2' typo here
                'category' => 'Shirts', 
                'price' => 975
            ]
        );

        // 2. Trigger ORANGE Circle (Warning)
        Inventory::updateOrCreate(
            ['item_name' => 'TROUSERS (Half Elastic)', 'size_label' => 'Size 8'],
            [
                'stock_quantity' => 7, 
                'category' => 'Bottoms', 
                'price' => 1500
            ]
        );

        // 3. Trigger GREEN Circle (Healthy)
        Inventory::updateOrCreate(
            ['item_name' => 'SWEATER', 'size_label' => 'Size 28'],
            [
                'stock_quantity' => 25, 
                'category' => 'Outerwear', 
                'price' => 1800
            ]
        );

        if ($admin) {
            // Alert for the sidebar/left panel
            Notification::create([
                'type' => 'SYSTEM_NOTE',
                'sender_id' => $admin->id,
                'receiver_role' => 'All',
                'message' => 'CRITICAL STOCK: Polo T-shirts (S) are almost out! (2 left)',
                'is_read' => false
            ]);

            // Note for the Chat Hub (Right panel)
            Notification::create([
                'type' => 'SYSTEM_NOTE',
                'sender_id' => $admin->id,
                'receiver_role' => 'All',
                'message' => 'Team, I have updated the stock levels for the sweaters. Let me know if the sizes are correct! 📦✨',
                'is_read' => false
            ]);
        }
    }
}