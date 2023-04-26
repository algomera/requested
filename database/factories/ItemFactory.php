<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
	        'code'        => fake()->regexify('[A-Z0-9]{6}'),
	        'name'        => fake()->domainWord,
	        'description' => fake()->text(50),
        ];
    }
}
