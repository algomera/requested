<?php

	namespace Database\Factories;

	use Illuminate\Database\Eloquent\Factories\Factory;

	/**
	 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductionOrderMaterial>
	 */
	class ProductionOrderMaterialFactory extends Factory
	{
		/**
		 * Define the model's default state.
		 *
		 * @return array<string, mixed>
		 */
		public function definition(): array
		{
			return [
				'quantity' => fake()->randomFloat(1, 100)
			];
		}
	}
