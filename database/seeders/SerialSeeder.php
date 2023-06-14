<?php

namespace Database\Seeders;

use App\Models\Ddt;
use App\Models\Item;
use App\Models\Location;
use App\Models\Product;
use App\Models\ProductionOrder;
use App\Models\Serial;
use App\Models\WarehouseOrder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SerialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Serial::factory(7)->create([
	        'production_order_id' => ProductionOrder::first(),
	        'product_id' =>  Product::where('code', 'PENNA')->first()->id,
        ]);

		$ddt = Ddt::create([
			'warehouse_order_id' => 3,
			'generated' => true,
			'generated_at' => now()
		]);

		$serials = Serial::factory(10)->create([
			'production_order_id' => ProductionOrder::first(),
			'product_id' => Product::where('code', 'PENNA')->first()->id,
			'completed' => true,
			'completed_at' => now()
		]);

	    DB::table('location_product')->insert([
			'location_id' => 18,
		    'product_id' => 3,
		    'quantity' => 10
	    ]);

	    foreach ($serials as $serial) {
		    DB::table('ddt_product')->updateOrInsert([
			    'ddt_id' => $ddt->id,
			    'serial_id' => $serial->id,
		    ], [
			    'quantity' => 1,
			    'created_at' => now(),
			    'updated_at' => now()
		    ]);
		}

		Product::factory(1)->create([
			'unit_id' => 1
		]);

	    $serials2 = Serial::factory(5)->create([
		    'production_order_id' => ProductionOrder::first(),
		    'product_id' => Product::find(4)->id,
		    'completed' => true,
		    'completed_at' => now()
	    ]);

	    DB::table('location_product')->insert([
		    'location_id' => 18,
		    'product_id' => 4,
		    'quantity' => 10
	    ]);

	    foreach ($serials2 as $s) {
		    DB::table('ddt_product')->updateOrInsert([
			    'ddt_id' => $ddt->id,
			    'serial_id' => $s->id,
		    ], [
			    'quantity' => 1,
			    'created_at' => now(),
			    'updated_at' => now()
		    ]);
	    }

		// prodotti
	    DB::table('ddt_product')->updateOrInsert([
		    'ddt_id' => $ddt->id,
		    'product_id' => 1,
	    ], [
		    'quantity' => 5,
		    'created_at' => now(),
		    'updated_at' => now()
	    ]);
	    DB::table('ddt_product')->updateOrInsert([
		    'ddt_id' => $ddt->id,
		    'product_id' => 2,
	    ], [
		    'quantity' => 10,
		    'created_at' => now(),
		    'updated_at' => now()
	    ]);

	    foreach (range(1, 10) as $pr) {
		    $p = Product::factory()->create([
				'unit_id' => 1
		    ]);
		    DB::table('ddt_product')->updateOrInsert([
			    'ddt_id' => $ddt->id,
			    'product_id' => $p->id,
		    ], [
			    'quantity' => fake()->numberBetween(1, 100),
			    'created_at' => now(),
			    'updated_at' => now()
		    ]);
		}




//		Serial::factory(2)->create([
//			'production_order_id' => ProductionOrder::first(),
//			'item_id' =>  Item::where('product_id', Product::where('code', 'PENNA')->first()->id)->first()->id,
//			'completed' => true,
//			'completed_at' => now()
//		]);
    }
}
