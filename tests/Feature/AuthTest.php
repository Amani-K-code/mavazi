<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_with_alias()
    {
        $admin = User::factory()->create([
            'role' => 'Admin',
            'user_id_alias' => '004',
            'password' => bcrypt('password123')
        ]);

        $response = $this->post(route('login.post'), [
            'user_id_alias' => '004',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect('/admin/dashboard');
    }

    public function test_cashier_can_login_with_alias()
    {
        $cashier = User::factory()->create([
            'role' => 'Cashier',
            'user_id_alias' => '012',
            'password' => bcrypt('password123')
        ]);

        $this->post(route('login.post'), [
            'user_id_alias' => '012',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($cashier);
    }

    public function test_cashier_id_012_registration_success(){
    
    // The controller will find 011 and calculate 012

    $this->seed(UserSeeder::class);

    $response = $this->post('/register', [
        'name' => 'Test Cashier', 
        'email' => 'test012@logos.ac.ke',
        'password' => '1234@cash1er',
        'password_confirmation' => '1234@cash1er',
    ]);

    // 3. This will now PASS
    $this->assertDatabaseHas('users', ['user_id_alias' => '012']);

    // 4. Test login immediately after
    $response = $this->post(route('login.post'), [
        'user_id_alias' => '012',
        'password' => '1234@cash1er',
    ]);

    $this->assertAuthenticated();
    }
}