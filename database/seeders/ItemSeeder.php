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
			foreach (range(1, 5) as $n) {
				$item = Item::create([
					'product_id' => Product::all()->shuffle()->first()->id
				]);
				$item->products()->attach(Product::all()->shuffle()->first(), [
					'quantity' => fake()->numberBetween(1, 5)
				]);
			}
		}
	}
