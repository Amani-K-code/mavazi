<?php

namespace Tests\Feature;

use App\Models\Inventory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StorekeeperTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }


    public function test_storekeeper_cannot_decrement_stock()
    {
        // 1. Seed the specific tables needed for this test
        $this->seed([\Database\Seeders\UserSeeder::class, \Database\Seeders\InventorySeeder::class]);

        // Finding the data from seeders
        $storekeeper = User::where('user_id_alias', '006')->first(); 
        $item = Inventory::first();

        // 3. Proceed with the test
        $this->actingAs($storekeeper)
            ->post("/storekeeper/inventory/{$item->id}/restock", ['restock_amount' => -5])
            ->assertSessionHasErrors('restock_amount'); 
    }
    
}
