<?php

	namespace Database\Seeders;

	use App\Models\Location;
	use App\Models\Product;
	use App\Models\ProductionOrder;
	use App\Models\ProductionOrderMaterial;
	use App\Models\Serial;
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
			// Prodotto MATRICOLARE
			$production_order = ProductionOrder::factory()->create([
				'product_id' => Product::where('code', 'PENNA')->first()->id,
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
				'product_id' => Product::where('code', 'PENNA')->first()->id,
				'position' => 0,
				'pickup_id' => null,
				'destination_id' => Location::where('type', 'versamento')->first()->id,
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
					'destination_id' => null,
					'quantity_total' => $material->quantity * $production_order->quantity,
					'quantity_processed' => 0,
					'status' => 'to_transfer'
				]);
			}


			// Prodotto NON MATRICOLARE
			$production_order_nm = ProductionOrder::factory()->create([
				'product_id' => Product::where('code', 'TAPPO')->first()->id,
				'quantity' => 5,
				'status' => 'created',
			]);

			ProductionOrderMaterial::factory()->create([
				'production_order_id' => $production_order_nm->id,
				'product_id' => 1,
				'quantity' => 1,
			]);
			ProductionOrderMaterial::factory()->create([
				'production_order_id' => $production_order_nm->id,
				'product_id' => 2,
				'quantity' => 1
			]);

			// Ordine di Magazzino (versamento): quando checko matricole e le completo, avanzo il processato
			$warehouse_order_versamento = WarehouseOrder::factory()->create([
				'production_order_id' => $production_order_nm->id,
				'destination_id' => Location::where('type', 'versamento')->first()->id,
				'type' => 'versamento',
				'reason' => 'Versamento di Produzione',
				'user_id' => null,
				'system' => 1,
			]);

			$warehouse_order_versamento->rows()->create([
				'product_id' => Product::where('code', 'TAPPO')->first()->id,
				'position' => 0,
				'pickup_id' => null,
				'destination_id' => Location::where('type', 'versamento')->first()->id,
				'quantity_total' => $production_order_nm->quantity,
				'quantity_processed' => 0,
				'status' => 'to_transfer'
			]);

			// Ordine di Magazzino (scarico): quando clicco su "scarica materiale" devo avanzare il processato per ogni riga
			// del prodotto utilizzato e togliere giacenza dal magazzino (dall'ubicazione di produzione che viene segnata nella tabella della distinta base)
			$warehouse_order_scarico = WarehouseOrder::factory()->create([
				'production_order_id' => $production_order_nm->id,
				'destination_id' => null,
				'type' => 'scarico',
				'reason' => 'Scarico del materiale',
				'user_id' => null,
				'system' => 1,
			]);

			foreach ($production_order_nm->materials as $k => $material) {
				$warehouse_order_scarico->rows()->create([
					'product_id' => $material->product_id,
					'position' => $k,
					'pickup_id' => $material->location_id,
					'destination_id' => null,
					'quantity_total' => $material->quantity * $production_order_nm->quantity,
					'quantity_processed' => 0,
					'status' => 'to_transfer'
				]);
			}

			// Ordine di Magazzino (spedizione): ordine matricolare
			$warehouse_order_spedizione = WarehouseOrder::factory()->create([
				'production_order_id' => $production_order->id,
				'destination_id' => Location::find(1)->id, // Baltur
				'type' => 'spedizione',
				'reason' => 'Spedizione a Baltur (matricolare)',
				'user_id' => null,
				'system' => 1,
			]);

			$warehouse_order_spedizione->rows()->create([
				'product_id' => Product::where('code', 'PENNA')->first()->id,
				'position' => 0,
				'pickup_id' => Location::where('code', 'SPEDIZ')->first()->id,
				'destination_id' => Location::find(1)->id,
				'quantity_total' => $production_order->quantity,
				'quantity_processed' => 0,
				'status' => 'to_transfer'
			]);

			// Ordine di Magazzino (spedizione): ordine NON matricolare
			$warehouse_order_spedizione_nm = WarehouseOrder::factory()->create([
				'production_order_id' => $production_order_nm->id,
				'destination_id' => Location::find(1)->id, // Baltur
				'type' => 'spedizione',
				'reason' => 'Spedizione a Baltur (non matricolare)',
				'user_id' => null,
				'system' => 1,
			]);

			$warehouse_order_spedizione_nm->rows()->create([
				'product_id' => Product::where('code', 'TAPPO')->first()->id,
				'position' => 0,
				'pickup_id' => Location::where('code', 'SPEDIZ')->first()->id,
				'destination_id' => Location::find(1)->id,
				'quantity_total' => $production_order->quantity,
				'quantity_processed' => 0,
				'status' => 'to_transfer'
			]);

			// Ordine di Magazzino (ricevimento): ordine matricolare
			$warehouse_order_ricevimento = WarehouseOrder::factory()->create([
				'production_order_id' => null,
				'destination_id' => Location::where('code', 'RICEVIM')->first()->id, // Ricevimento
				'type' => 'ricevimento',
				'reason' => 'Ricevimento da Chimar (matricolare)',
				'user_id' => null,
				'system' => 1,
			]);

			$warehouse_order_ricevimento->rows()->create([
				'product_id' => Product::where('code', 'PENNA')->first()->id,
				'position' => 0,
				'pickup_id' => Location::find(2)->id,
				'destination_id' => Location::where('code', 'RICEVIM')->first()->id,
				'quantity_total' => 4,
				'quantity_processed' => 0,
				'status' => 'to_transfer'
			]);
			Serial::factory(4)->create([
				'production_order_id' => null,
				'warehouse_order_id' => $warehouse_order_ricevimento->id,
				'product_id' => Product::where('code', 'PENNA')->first()->id,
			]);

			// Ordine di Magazzino (ricevimento): ordine NON matricolare
			$warehouse_order_ricevimento_nm = WarehouseOrder::factory()->create([
				'production_order_id' => null,
				'destination_id' => Location::where('code', 'RICEVIM')->first()->id, // Ricevimento
				'type' => 'ricevimento',
				'reason' => 'Ricevimento da Chimar (non matricolare)',
				'user_id' => null,
				'system' => 1,
			]);

			$warehouse_order_ricevimento_nm->rows()->create([
				'product_id' => Product::where('code', 'TAPPO')->first()->id,
				'position' => 0,
				'pickup_id' => Location::find(2)->id,
				'destination_id' => Location::where('code', 'RICEVIM')->first()->id,
				'quantity_total' => 7,
				'quantity_processed' => 0,
				'status' => 'to_transfer'
			]);
		}
	}
