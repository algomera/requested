<?php

	namespace Database\Factories;

	use Illuminate\Database\Eloquent\Factories\Factory;

	/**
	 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
	 */
	class LocationFactory extends Factory
	{
		/**
		 * Define the model's default state.
		 *
		 * @return array<string, mixed>
		 */
		public function definition(): array
		{
			return [
				'code' => fake()->regexify('[A-Z]{4}[0-9]{2}'),
				'description' => fake()->sentence,
			];
		}
	}
