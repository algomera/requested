<?php

	namespace Database\Seeders;

	use App\Models\Item;
	use App\Models\Product;
	use Illuminate\Database\Console\Seeds\WithoutModelEvents;
	use Illuminate\Database\Seeder;

	class ItemSeeder extends Seeder
	{
		/**
		 * Run the database seeds.
		 */
		public function run(): void {
			$items = Item::factory(5)->create([
				'product_id' => Product::all()->shuffle()->first()->id
			]);
			foreach ($items as $item) {
				$item->products()->attach(Product::all()->shuffle()->first(), [
					'quantity' => fake()->numberBetween(1, 5)
				]);
			}
		}
	}
