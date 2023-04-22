<?php

	namespace Database\Factories;

	use Illuminate\Database\Eloquent\Factories\Factory;

	/**
	 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
	 */
	class ProductFactory extends Factory
	{
		/**
		 * Define the model's default state.
		 *
		 * @return array<string, mixed>
		 */
		public function definition(): array {
			return [
				'code'        => fake()->regexify('[A-Z0-9]{6}'),
				'name'        => fake()->domainWord,
				'description' => fake()->text(50),
				'units'       => fake()->randomElement([
					'NR',
					'MT'
				]),
				'quantity'    => fake()->randomFloat(2, 0, 10),
			];
		}
	}
