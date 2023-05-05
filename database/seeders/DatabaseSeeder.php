<?php

	namespace Database\Seeders;
	// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
	use Illuminate\Database\Seeder;

	class DatabaseSeeder extends Seeder
	{
		/**
		 * Seed the application's database.
		 */
		public function run(): void {
			$this->call([
				RolesPermissionsSeeder::class,
				UserSeeder::class,
				DestinationSeeder::class,
				SupplierSeeder::class,
				LocationSeeder::class,
				ProductSeeder::class,
				ItemSeeder::class,
				ProductionOrderSeeder::class,
			]);
		}
	}
