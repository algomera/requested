<?php

	namespace Database\Seeders;

	use App\Models\ProductionOrder;
	use Illuminate\Database\Console\Seeds\WithoutModelEvents;
	use Illuminate\Database\Seeder;

	class ProductionOrderSeeder extends Seeder
	{
		/**
		 * Run the database seeds.
		 */
		public function run(): void {
			ProductionOrder::factory(5)->create();
		}
	}
