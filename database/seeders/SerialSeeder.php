<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Product;
use App\Models\ProductionOrder;
use App\Models\Serial;
use App\Models\WarehouseOrder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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

//		Serial::factory(2)->create([
//			'production_order_id' => ProductionOrder::first(),
//			'item_id' =>  Item::where('product_id', Product::where('code', 'PENNA')->first()->id)->first()->id,
//			'completed' => true,
//			'completed_at' => now()
//		]);
    }
}
