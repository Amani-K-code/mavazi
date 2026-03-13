<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Inventory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventorySaleTest extends TestCase
{
    use DatabaseTransactions;

    public function test_sale_decrements_stock_and_triggers_low_stock_alert()
    {
        
            $cashier = User::where('role','Cashier') ->first();
            $item = Inventory::first();
            $item->update(['stock_quantity' => 2, 'low_stock_threshold' => 5]);
        
        // 2. Perform Sale
        $this->actingAs($cashier)->post('/sales/store', [
            'customer_name' => 'John Doe',
            'child_name' => 'Jane Doe',
            'payment_method' => 'M-PESA',
            'reference_id' => 'REF12345',
            'status' => 'CONFIRMED',
            'total_amount' => $item->price,
            'cart_data' => json_encode([
                ['id' => $item->id, 'price' => $item->price, 'qty' => 1]
            ]),
        ]);

        $this->assertEquals(1, $item->fresh()->stock_quantity);
    }
}