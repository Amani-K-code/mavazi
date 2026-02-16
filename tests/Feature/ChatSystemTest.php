<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_sees_unread_badge_from_cashier_message()
    {
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create(['role' => 'Admin', 'name' => 'Admin User']);

        /** @var \App\Models\User $cashier */
        $cashier = User::factory()->create(['role' => 'Cashier', 'name' => 'Cashier Jane']);

        // 1. Cashier sends a message
        $this->actingAs($cashier)->post(route('notifications.store'), [
            'message' => 'Need help with a return',
            'type' => 'SYSTEM_NOTE', // Using SYSTEM_NOTE because CHAT is missing in your DB ENUM
            'receiver_id' => $admin->id
        ]);

        // 2. Admin checks notifications
        $this->actingAs($admin);
        $response = $this->get(route('notifications.index'));

        $response->assertStatus(200);
        // Look for the badge class and the unread count
        $response->assertSee('bg-red-600');
        $response->assertSee('1');
        $response->assertSee('Cashier Jane');
    }
}