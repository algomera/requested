<?php

	namespace Database\Seeders;

	use App\Models\Item;
	use App\Models\Location;
	use App\Models\Product;
	use App\Models\ProductionOrder;
	use App\Models\ProductionOrderMaterial;
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
				'status' => 'created',
			]);

			ProductionOrderMaterial::factory()->create([
				'production_order_id' => $production_order->id,
				'product_id' => 1,
				'quantity' => 1,
			]);
			ProductionOrderMaterial::factory()->create([
				'production_order_id' => $production_order->id,
				'product_id' => 2,
				'quantity' => 1
			]);

			// Ordine di Magazzino (versamento): quando checko matricole e le completo, avanzo il processato
			$warehouse_order_versamento = WarehouseOrder::factory()->create([
				'production_order_id' => $production_order->id,
				'destination_id' => Location::where('type', 'versamento')->first()->id,
				'type' => 'versamento',
				'reason' => 'Versamento di Produzione',
				'user_id' => null,
				'system' => 1,
			]);

			$warehouse_order_versamento->rows()->create([
				'position' => 0,
				'pickup_id' => null,
				'quantity_total' => $production_order->quantity,
				'quantity_processed' => 0,
				'status' => 'to_transfer'
			]);

			// Ordine di Magazzino (scarico): quando clicco su "scarica materiale" devo avanzare il processato per ogni riga
			// del prodotto utilizzato e togliere giacenza dal magazzino (dall'ubicazione di produzione che viene segnata nella tabella della distinta base)
			$warehouse_order_scarico = WarehouseOrder::factory()->create([
				'production_order_id' => $production_order->id,
				'destination_id' => null,
				'type' => 'scarico',
				'reason' => 'Scarico del materiale',
				'user_id' => null,
				'system' => 1,
			]);

			foreach ($production_order->materials as $k => $material) {
				$warehouse_order_scarico->rows()->create([
					'product_id' => $material->product_id,
					'position' => $k,
					'pickup_id' => $material->location_id,
					'quantity_total' => $material->quantity * $production_order->quantity,
					'quantity_processed' => 0,
					'status' => 'to_transfer'
				]);
			}
		}
	}
