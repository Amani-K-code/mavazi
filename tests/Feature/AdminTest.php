<?php

namespace Tests\Feature;

use App\Models\Inventory;
use App\Models\Reservation;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use DatabaseTransactions; //To keep all the seed files and database as is before tests

    protected $admin;
    protected $cashier;
    protected $item;


    protected function setUp(): void
    {
        parent::setUp();

        // From Userseeder file:
        $this-> admin = User::where('role', 'Admin')->first();
        $this-> cashier = User::where('role', 'Cashier')->first();


        $this->item = Inventory::first() ?? Inventory::create([
            'item_name' => 'Test Blazer',
            'stock_quantity' => 10,
            'price' => 2500,
            'category' => 'Outerwear',
            'is_locked' => false
        ]);
    }

    /** 1. Testing RBAC */

    public function test_cashier_cannot_access_admin_dashboard()
    {
        $this->actingAs($this->cashier)
            ->get('/admin/dashboard')
            ->assertRedirect(); // this should redirect to the cashier dashboard
    }

    public function test_admin_can_access_admin_dashboard()
    {
        $this->actingAs($this->admin)
            ->get('/admin/dashboard')
            ->assertStatus(200) // Should have loaded successfully
            ->assertViewIs('admin.dashboard'); // Should load the correct view
    }


    /** 2. Testing Price Locking Logic */

    public function test_admin_cannot_change_locked_price_without_correct_password()
    {
        // 1. Create a totally unique item just for this specific test
        $isolatedItem = Inventory::create([
            'item_name' => 'Unique Isolation Blazer',
            'stock_quantity' => 10,
            'price' => 2500.00,
            'category' => 'Testing-Only',
            'size_label' => 'M',
            'is_locked' => true, // Start it as locked
            'low_stock_threshold' => 5
        ]);

        // 2. Perform the request on THIS item, not $this->item
        $response = $this->actingAs($this->admin)
            ->post("/admin/inventory/{$isolatedItem->id}/update-price", [
                'price' => 2000,
                'password' => 'wrongpassword'
            ]);

        // 3. Verify the error message
        $response->assertSessionHas('error', 'Incorrect admin password for locked item.');

        // 4. Verify the price of OUR isolated item stayed at 2500
        $this->assertEquals(2500.00, $isolatedItem->fresh()->price);
    }

    public function test_admin_can_change_locked_price_with_correct_password(){
        $this->admin->password = bcrypt('lgs@adm1n');
        $this->admin->save();
        
    
        $this->item->update(['is_locked' => true]);
        
        $this->actingAs($this->admin)
            ->post("/admin/inventory/{$this->item->id}/update-price", [
                'price' => 3000,
                'password' => 'lgs@adm1n', // Correct pw
                'is_locked' => true
            ])
            ->assertSessionHas('success');

            $this->assertEquals(3000, $this->item->fresh()->price);
    }


    /** 3. Test Duplicate Reference ID Prevention */
    public function test_system_prevents_duplicate_mpesa_reference_ids()
    {
        //Create an existing sale
        Sale::create([
            'user_id' => $this->cashier->id,
            'total_amount' => 1000,
            'reference_id' => 'REF12345', // Existing ref ID
            'status' => 'CONFIRMED',
            'receipt_no' => 'RCP-1452',
            'customer_name' => 'Hashim',
            'child_name' => 'Mike',
            'payment_method' => 'M-PESA'
        ]);

        //Attempt to create another sale with same ref ID:
        $response = $this->actingAs($this->cashier)
            ->post('/sales/store', [
                'reference_id' => 'REF12345', // Duplicate
                'customer_name' => 'Test User',
                'child_name' => 'Test Child',
                'total_amount' => 1500,
                'payment_method' => 'M-PESA'
            ]);

            //Should fail validation
            $response->assertSessionHasErrors('reference_id');
    }



        /** 4. Test Admin Stock Restoration */
        public function test_admin_can_manually_restore_reserved_stock(){
            //Creating a fake reservation
            $reservation = Reservation::create([
                'inventory_id' => $this->item->id,
                'quantity' => 2,
                'staff_id' => $this->admin->user_id_alias,
                'expires_at' => now()->addDays(30),
                'status' => 'pending'
            ]);


            $initialStock = $this ->item->stock_quantity;

            $this->actingAs($this->admin)
                ->post("/admin/restore/{$reservation->id}")
                ->assertSessionHas('success');


            //Check if stock returned to inventory
            $this->assertEquals($initialStock + 2, $this->item->fresh()->stock_quantity);

            //Check if reservation record is removed or status changed
            $this->assertDatabaseMissing('reservations', ['id' => $reservation->id]);

        }

        /** 5. Test Bulk Discount Logic  **/
        public function test_admin_can_apply_bulk_discount_to_category()
        {
            // 1. Create a fresh item with a UNIQUE category to avoid seeder interference
            $discountItem = Inventory::create([
                'item_name' => 'Bulk Test Blazer',
                'stock_quantity' => 10,
                'price' => 3000,
                'category' => 'Bulk-Test-Category',
                'size_label' => 'L'
            ]);

            // 2. Apply discount to THAT specific category
            $this->actingAs($this->admin)
                ->post('/admin/inventory/bulk-discount', [
                    'category' => 'Bulk-Test-Category',
                    'percentage' => 10
                ])
                ->assertSessionHas('success');

            // 3. Cast to int to avoid "3000.00" vs 2700 mismatch
            $this->assertEquals(2700, (int)$discountItem->fresh()->price);
        }        
    }