<?php

	namespace Database\Factories;

	use App\Models\Unit;
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
				'description' => fake()->domainWord,
				'unit_id'       => Unit::all()->shuffle()->first()->id,
			];
		}
	}
