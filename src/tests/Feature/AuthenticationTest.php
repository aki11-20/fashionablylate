<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DemoAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin_page(): void
    {
        $this->get('/admin')
            ->assertRedirect('/login');
    }

    public function test_demo_admin_can_log_in_and_access_admin_page(): void
    {
        $this->seed(DemoAdminSeeder::class);

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $admin = User::where('email', 'admin@example.com')->firstOrFail();

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);
        $this->get('/admin')->assertOk();
    }

    public function test_authenticated_user_can_log_out(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect('/login');

        $this->assertGuest();
    }

    public function test_register_routes_do_not_exist(): void
    {
        $this->get('/register')->assertNotFound();
        $this->post('/register')->assertNotFound();
    }
}
