<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_is_displayed(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertViewIs('index');
    }

    public function test_valid_input_displays_confirmation_page(): void
    {
        $this->post('/contacts/confirm', $this->validContactData())
            ->assertOk()
            ->assertViewIs('confirm')
            ->assertSee('山田')
            ->assertSee('商品のお届けについて');
    }

    public function test_valid_input_creates_contact(): void
    {
        $this->post('/contacts', $this->validContactData())
            ->assertOk();

        $this->assertDatabaseHas('contacts', [
            'name' => '山田　太郎',
            'email' => 'contact@example.com',
            'tel' => '08012345678',
            'category' => '商品のお届けについて',
        ]);
    }

    public function test_submission_displays_thanks_page(): void
    {
        $this->post('/contacts', $this->validContactData())
            ->assertOk()
            ->assertViewIs('thanks')
            ->assertSee('お問い合わせありがとうございました');
    }

    public function test_required_fields_are_validated(): void
    {
        $this->from('/')->post('/contacts/confirm', [])
            ->assertRedirect('/')
            ->assertSessionHasErrors([
                'first_name',
                'last_name',
                'gender',
                'email',
                'tel1',
                'tel2',
                'tel3',
                'address',
                'category',
                'content',
            ]);
    }

    public function test_email_must_be_valid(): void
    {
        $this->post('/contacts/confirm', $this->validContactData([
            'email' => 'invalid-email',
        ]))->assertSessionHasErrors('email');
    }

    public function test_phone_parts_must_contain_only_digits(): void
    {
        $this->post('/contacts/confirm', $this->validContactData([
            'tel2' => '12a4',
        ]))->assertSessionHasErrors('tel2');
    }

    public function test_combined_phone_number_must_not_exceed_eleven_digits(): void
    {
        $this->post('/contacts/confirm', $this->validContactData([
            'tel1' => '12345',
            'tel2' => '12345',
            'tel3' => '12',
        ]))->assertSessionHasErrors('tel1');
    }

    public function test_content_must_not_exceed_120_characters(): void
    {
        $this->post('/contacts/confirm', $this->validContactData([
            'content' => str_repeat('あ', 121),
        ]))->assertSessionHasErrors('content');
    }

    public function test_category_must_be_an_allowed_value(): void
    {
        $this->post('/contacts/confirm', $this->validContactData([
            'category' => '許可されていない種類',
        ]))->assertSessionHasErrors('category');
    }

    private function validContactData(array $overrides = []): array
    {
        return array_merge([
            'first_name' => '山田',
            'last_name' => '太郎',
            'gender' => '1',
            'email' => 'contact@example.com',
            'tel1' => '080',
            'tel2' => '1234',
            'tel3' => '5678',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'テストビル101',
            'category' => '商品のお届けについて',
            'content' => 'お問い合わせ内容です。',
        ], $overrides);
    }
}
