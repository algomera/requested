<?php

	namespace Database\Seeders;

	use App\Models\Product;
	use Illuminate\Database\Console\Seeds\WithoutModelEvents;
	use Illuminate\Database\Seeder;

	class ProductSeeder extends Seeder
	{
		/**
		 * Run the database seeds.
		 */
		public function run(): void {
//			$products = Product::factory(10)->create();
//			foreach ($products as $product) {
//				$product->update([
//					'units' => fake()->randomElement(array_keys(config('requested.products.units')))
//				]);
//			}
			$tappo = Product::create([
				'code' => 'TAPPO',
				'name' => 'Tappo',
				'units' => 'pz'
			]);
			$tubo = Product::create([
				'code' => 'TUBO',
				'name' => 'Tubo',
				'units' => 'pz'
			]);
			$penna = Product::create([
				'code' => 'PENNA',
				'name' => 'Penna Bic',
				'units' => 'pz'
			]);
		}
	}
