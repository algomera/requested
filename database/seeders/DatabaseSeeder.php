<?php

	namespace Database\Seeders;
	// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
	use Illuminate\Database\Seeder;
	use Illuminate\Support\Facades\DB;

	class DatabaseSeeder extends Seeder
	{
		/**
		 * Seed the application's database.
		 */
		public function run(): void
		{
			$this->call([
				RolesPermissionsSeeder::class,
				UserSeeder::class,
				UnitSeeder::class,
				DestinationSeeder::class,
				SupplierSeeder::class,
				LocationSeeder::class,
				ProductSeeder::class,
				ItemSeeder::class,
				ProductionOrderSeeder::class,
				SerialSeeder::class,
			]);
//			$sql = 'app/database/test_data.sql';
//			DB::unprepared(file_get_contents($sql));
		}
	}
