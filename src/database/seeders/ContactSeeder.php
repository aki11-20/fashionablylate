<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        for ($number = 1; $number <= 35; $number++) {
            $email = sprintf('demo-contact-%02d@example.test', $number);
            $attributes = Contact::factory()->make()->only([
                'name',
                'gender',
                'tel',
                'address',
                'building',
                'category',
                'content',
            ]);

            Contact::firstOrCreate(
                ['email' => $email],
                $attributes
            );
        }
    }
}
