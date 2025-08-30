<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Contact;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition(): array
    {
        $faker = $this->faker;

        $category = \App\Models\Category::inRandomOrder()->value('name')
            ?? $faker->randomElement([
                '商品のお届けについて',
                '商品の交換について',
                '商品トラブル',
                'ショップへのお問い合わせ',
                'その他',
            ]);

        return [
            'name'      => $faker->name(),
            'gender'    => $faker->numberBetween(1, 3),
            'email'     => $faker->unique()->safeEmail(),
            'tel'       => $faker->numerify('0#0########'),
            'address'   => $faker->address(),
            'building'  => $faker->optional()->secondaryAddress(),
            'category'  => $category,
            'content'   => $faker->realText(120),
            'created_at' => $faker->dateTimeBetween('-90 days', 'now'),
            'updated_at' => now(),
        ];
    }
}
