<?php

	namespace Database\Seeders;

	use App\Models\Location;
	use App\Models\Product;
	use App\Models\Unit;
	use Illuminate\Database\Console\Seeds\WithoutModelEvents;
	use Illuminate\Database\Seeder;

	class ProductSeeder extends Seeder
	{
		/**
		 * Run the database seeds.
		 */
		public function run(): void
		{
//			$products = Product::factory(10)->create();
//			foreach ($products as $product) {
//				$product->update([
//					'units' => fake()->randomElement(array_keys(config('requested.products.units')))
//				]);
//			}
			$tappo = Product::create([
				'code' => 'TAPPO',
				'description' => 'Tappo',
				'unit_id' => Unit::where('abbreviation', 'pz')->first()->id,
				'serial_management' => false,
			]);
			$tubo = Product::create([
				'code' => 'TUBO',
				'description' => 'Tubo',
				'unit_id' => Unit::where('abbreviation', 'pz')->first()->id,
				'serial_management' => false,
			]);
			$penna = Product::create([
				'code' => 'PENNA',
				'description' => 'Penna Bic',
				'unit_id' => Unit::where('abbreviation', 'pz')->first()->id,
				'serial_management' => true,
			]);

			$tappo->locations()->attach(Location::where('code', 'RICEVIM')->first()->id, [
				'quantity' => 5
			]);
			$tappo->locations()->attach(Location::where('code', 'GRANDI')->first()->id, [
				'quantity' => 20
			]);
			$tubo->locations()->attach(Location::where('code', 'GRANDI')->first()->id, [
				'quantity' => 40
			]);

			$tappo->locations()->attach(Location::where('code', 'PRODUZ')->first()->id, [
				'quantity' => 50
			]);
			$tubo->locations()->attach(Location::where('code', 'PRODUZ')->first()->id, [
				'quantity' => 50
			]);
		}
	}
