<?php

	namespace Database\Factories;

	use App\Models\Item;
	use App\Models\Product;
	use App\Models\ProductionOrder;
	use Illuminate\Database\Eloquent\Factories\Factory;

	/**
	 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Serial>
	 */
	class SerialFactory extends Factory
	{
		/**
		 * Define the model's default state.
		 *
		 * @return array<string, mixed>
		 */
		public function definition(): array {
			return [
				'production_order_id' => ProductionOrder::all()->shuffle()->first()->id,
				'product_id' => Product::all()->shuffle()->first()->id,
				'code'        => fake()->regexify('[A-Z0-9]{15}'),
			];
		}
	}
