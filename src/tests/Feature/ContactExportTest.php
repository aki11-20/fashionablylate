<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_export_contacts(): void
    {
        $this->get('/admin/export')
            ->assertRedirect('/login');
    }

    public function test_authenticated_user_receives_csv_response(): void
    {
        $user = User::factory()->create();
        Contact::factory()->create([
            'email' => 'csv@example.com',
        ]);

        $response = $this->actingAs($user)
            ->get('/admin/export');

        $response->assertOk();
        $this->assertSame('text/csv; charset=UTF-8', $response->headers->get('content-type'));
        $this->assertStringContainsString('attachment; filename="contacts_', $response->headers->get('content-disposition'));
        $this->assertStringContainsString('csv@example.com', $response->streamedContent());
    }

    public function test_csv_export_applies_search_conditions(): void
    {
        $user = User::factory()->create();
        Contact::factory()->create([
            'name' => 'CSV検索対象',
            'gender' => 2,
            'email' => 'csv-match@example.com',
            'category' => '商品トラブル',
            'created_at' => '2026-02-03 10:00:00',
        ]);
        Contact::factory()->create([
            'name' => 'CSV対象外',
            'gender' => 1,
            'email' => 'csv-other@example.com',
            'category' => 'その他',
            'created_at' => '2026-02-04 10:00:00',
        ]);

        $response = $this->actingAs($user)->get('/admin/export?' . http_build_query([
            'keyword' => 'CSV検索対象',
            'gender' => '2',
            'category' => '商品トラブル',
            'date' => '2026-02-03',
        ]));

        $content = $response->streamedContent();

        $response->assertOk();
        $this->assertStringContainsString('csv-match@example.com', $content);
        $this->assertStringNotContainsString('csv-other@example.com', $content);
    }

    public function test_csv_export_with_gender_all_includes_every_gender(): void
    {
        $user = User::factory()->create();
        Contact::factory()->create([
            'gender' => 1,
            'email' => 'csv-gender-one@example.com',
        ]);
        Contact::factory()->create([
            'gender' => 2,
            'email' => 'csv-gender-two@example.com',
        ]);

        $content = $this->actingAs($user)
            ->get('/admin/export?gender=all')
            ->streamedContent();

        $this->assertStringContainsString('csv-gender-one@example.com', $content);
        $this->assertStringContainsString('csv-gender-two@example.com', $content);
    }
}
