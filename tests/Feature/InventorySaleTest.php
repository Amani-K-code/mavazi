<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Inventory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventorySaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_sale_decrements_stock_and_triggers_low_stock_alert()
    {
        /** @var \App\Models\User $cashier */ 
        $cashier = User::factory()->create(['role' => 'Cashier']);
        
        $item = Inventory::create([
            'item_name' => 'POLO T-SHIRT',
            'size_label' => 'S',
            'stock_quantity' => 2,
            'low_stock_threshold' => 5,
            'category' => 'Shirts',
            'price' => 975.00
        ]);

        // 1. Verify Auto-Notification on creation (Stock is 2)
        $this->assertDatabaseHas('notifications', [
            'type' => 'SYSTEM_NOTE',
            'message' => 'POLO T-SHIRT (Size: S) is low! Only 2 left.'
        ]);

        // 2. Perform Sale
        $this->actingAs($cashier)->post('/sales/store', [
            'customer_name' => 'John Doe',
            'child_name' => 'Jane Doe',
            'payment_method' => 'M-PESA',
            'status' => 'CONFIRMED',
            'total_amount' => 975.00,
            'cart_data' => json_encode([
                ['id' => $item->id, 'price' => 975.00, 'qty' => 1]
            ]),
        ]);

        $this->assertEquals(1, $item->fresh()->stock_quantity);
    }
}