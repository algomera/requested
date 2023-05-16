<?php

	namespace Database\Seeders;

	use App\Models\Item;
	use App\Models\Product;
	use App\Models\ProductionOrder;
	use Illuminate\Database\Console\Seeds\WithoutModelEvents;
	use Illuminate\Database\Seeder;

	class ProductionOrderSeeder extends Seeder
	{
		/**
		 * Run the database seeds.
		 */
		public function run(): void
		{
			ProductionOrder::factory()->create([
				'item_id' => Item::where('product_id', Product::where('code', 'PENNA')->first()->id)->first()->id,
				'quantity' => 7,
				'status' => 'created'
			]);
		}
	}
