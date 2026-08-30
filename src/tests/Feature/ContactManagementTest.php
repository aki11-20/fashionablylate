<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_contact_details(): void
    {
        $user = User::factory()->create();
        $contact = Contact::factory()->create([
            'email' => 'detail@example.com',
            'tel' => '08012345678',
            'address' => '東京都渋谷区テスト1-2-3',
            'content' => '詳細確認用のお問い合わせです。',
        ]);

        $this->actingAs($user)
            ->get('/admin')
            ->assertOk()
            ->assertSee($contact->email)
            ->assertSee($contact->tel)
            ->assertSee($contact->address)
            ->assertSee($contact->content);
    }

    public function test_authenticated_user_can_delete_contact(): void
    {
        $user = User::factory()->create();
        $contact = Contact::factory()->create();

        $this->actingAs($user)
            ->delete("/admin/{$contact->id}")
            ->assertRedirect('/admin');

        $this->assertDatabaseMissing('contacts', [
            'id' => $contact->id,
        ]);
    }

    public function test_guest_cannot_delete_contact(): void
    {
        $contact = Contact::factory()->create();

        $this->delete("/admin/{$contact->id}")
            ->assertRedirect('/login');

        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
        ]);
    }
}
