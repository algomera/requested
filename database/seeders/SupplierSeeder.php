<?php

	namespace Database\Seeders;

	use App\Models\Location;
	use App\Models\Supplier;
	use Illuminate\Database\Console\Seeds\WithoutModelEvents;
	use Illuminate\Database\Seeder;

	class SupplierSeeder extends Seeder
	{
		/**
		 * Run the database seeds.
		 */
		public function run(): void
		{
			Location::factory(10)->create([
				'type' => 'fornitore'
			]);
		}
	}
