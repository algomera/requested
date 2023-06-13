<?php

	namespace Database\Factories;

	use App\Models\Location;
	use App\Models\Product;
	use Carbon\Carbon;
	use Illuminate\Database\Eloquent\Factories\Factory;

	/**
	 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductionOrder>
	 */
	class ProductionOrderFactory extends Factory
	{
		/**
		 * Define the model's default state.
		 *
		 * @return array<string, mixed>
		 */
		public function definition(): array {
			return [
				'code'           => fake()->regexify('[A-Z0-9]{10}'),
				'product_id'        => Product::all()->shuffle()->first()->id,
				'quantity'       => fake()->numberBetween(1, 7),
				'delivery_date'  => fake()->dateTimeBetween('now', Carbon::now()->addMonth()),
				'status'         => fake()->randomElement(array_keys(config('requested.production_orders.status')))
			];
		}
	}
