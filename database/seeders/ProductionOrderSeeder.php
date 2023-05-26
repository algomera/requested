<?php

	namespace Database\Seeders;

	use App\Models\Item;
	use App\Models\Location;
	use App\Models\Product;
	use App\Models\ProductionOrder;
	use App\Models\WarehouseOrder;
	use Illuminate\Database\Console\Seeds\WithoutModelEvents;
	use Illuminate\Database\Seeder;

	class ProductionOrderSeeder extends Seeder
	{
		/**
		 * Run the database seeds.
		 */
		public function run(): void
		{
			$production_order = ProductionOrder::factory()->create([
				'item_id' => Item::where('product_id', Product::where('code', 'PENNA')->first()->id)->first()->id,
				'quantity' => 7,
				'status' => 'created'
			]);

			$production_order->materials()->create([
				'product_id' => 1,
				'quantity' => 1
			]);
			$production_order->materials()->create([
				'product_id' => 2,
				'quantity' => 1
			]);

			$warehouse_order = WarehouseOrder::factory()->create([
				'production_order_id' => $production_order->id,
				'destination_id' => Location::where('type', 'versamento')->first()->id, // ubicazione di destinazione
				'reason' => 'versamento di produzione',
				'user_id' => null, // se system è a 0 (creato ordine da utente), user_id avrà l'ID di chi ha creato l'ordine di magazzino
				'system' => 1, // BOOL
			]);

			$warehouse_order->rows()->create([
				'position' => 1, // incrementale
				'pickup_id' => null, // ubicazione di prelievo NULLABLE
				'quantity_total' => $production_order->quantity,
				'quantity_processed' => 0,
				'status' => 'to_transfer'
			]);
		}
	}
