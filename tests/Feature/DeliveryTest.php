<?php

namespace Tests\Feature;

use App\Models\Deliveries;
use App\Models\Inventory;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeliveryTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_delivery_workflow_pending_to_confirmed_with_seeded_data()
    {

        //Retrieving specific users created in the seeder files
        $storekeeper = User::where('role', 'Storekeeper')->first();
        $admin = User::where('role', 'Admin')->first();


        $item  = Inventory::where('item_name', 'SWEATER')->where('size_label', 'Size 28')->first();
        $initialStock = $item->stock_quantity;


        $this->actingAs($storekeeper)->post(route('storekeeper.deliveries.store'), [
            'delivery_date' => now()->format('Y-m-d'),
            'payment_due_date' => now()->addDays(30)->format('Y-m-d'),
            'total_invoice_amount' => 5000,
            'items' => [
                [
                    'inventory_id' => $item->id,
                    'item_name' => 'SWEATER',
                    'size' => 'Size 28',
                    'quantity' => 10,
                    'note' => 'Test delivery'
                ]
            ]
        ]);


        $this->assertDatabaseHas('deliveries', ['status' => 'PENDING']);
        $this->assertEquals($initialStock, $item->fresh()->stock_quantity);

        $delivery = Deliveries::latest()->first();

        // Simulate the Admin coming from a specific page so 'back()' has a destination
        $response = $this->actingAs($admin)
            ->from(route('admin.dashboard')) 
            ->post(route('admin.deliveries.approve', $delivery->id));

        // Check that it actually redirected (success)
        $response->assertStatus(302);

        // Debugging: If this still fails, uncomment the line below to see why
        // dd($delivery->fresh()->status);


        $this->actingAs($admin)->post(route('admin.deliveries.approve', $delivery->id));


        $this->assertEquals('CONFIRMED', $delivery->fresh()->status);
        $this->assertEquals($initialStock + 10, $item->fresh()->stock_quantity);
    }
    
}
