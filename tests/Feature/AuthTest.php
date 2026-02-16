<?php

namespace Tests\Feature;

use App\Models\User;
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
}