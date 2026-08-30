<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSearchTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();
    }

    public function test_contacts_can_be_searched_by_keyword(): void
    {
        Contact::factory()->create([
            'name' => '検索対象 太郎',
            'email' => 'keyword-match@example.com',
        ]);
        Contact::factory()->create([
            'name' => '対象外 花子',
            'email' => 'keyword-other@example.com',
        ]);

        $this->actingAs($this->admin)
            ->get('/admin?keyword=検索対象')
            ->assertOk()
            ->assertSee('keyword-match@example.com')
            ->assertDontSee('keyword-other@example.com');
    }

    public function test_contacts_can_be_filtered_by_gender(): void
    {
        Contact::factory()->create([
            'gender' => 1,
            'email' => 'gender-one@example.com',
        ]);
        Contact::factory()->create([
            'gender' => 2,
            'email' => 'gender-two@example.com',
        ]);

        $this->actingAs($this->admin)
            ->get('/admin?gender=1')
            ->assertOk()
            ->assertSee('gender-one@example.com')
            ->assertDontSee('gender-two@example.com');
    }

    public function test_contacts_can_be_filtered_by_category(): void
    {
        Contact::factory()->create([
            'category' => '商品トラブル',
            'email' => 'category-match@example.com',
        ]);
        Contact::factory()->create([
            'category' => 'その他',
            'email' => 'category-other@example.com',
        ]);

        $this->actingAs($this->admin)
            ->get('/admin?category=' . urlencode('商品トラブル'))
            ->assertOk()
            ->assertSee('category-match@example.com')
            ->assertDontSee('category-other@example.com');
    }

    public function test_contacts_can_be_filtered_by_date(): void
    {
        Contact::factory()->create([
            'email' => 'date-match@example.com',
            'created_at' => '2026-01-15 10:00:00',
        ]);
        Contact::factory()->create([
            'email' => 'date-other@example.com',
            'created_at' => '2026-01-16 10:00:00',
        ]);

        $this->actingAs($this->admin)
            ->get('/admin?date=2026-01-15')
            ->assertOk()
            ->assertSee('date-match@example.com')
            ->assertDontSee('date-other@example.com');
    }

    public function test_gender_all_returns_every_gender(): void
    {
        Contact::factory()->create([
            'gender' => 1,
            'email' => 'all-gender-one@example.com',
        ]);
        Contact::factory()->create([
            'gender' => 2,
            'email' => 'all-gender-two@example.com',
        ]);

        $this->actingAs($this->admin)
            ->get('/admin?gender=all')
            ->assertOk()
            ->assertSee('all-gender-one@example.com')
            ->assertSee('all-gender-two@example.com');
    }
}
