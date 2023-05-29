<?php

	namespace Database\Seeders;

	use App\Models\Destination;
	use App\Models\Location;
	use Illuminate\Database\Console\Seeds\WithoutModelEvents;
	use Illuminate\Database\Seeder;

	class DestinationSeeder extends Seeder
	{
		/**
		 * Run the database seeds.
		 */
		public function run(): void
		{
			$baltur = Location::create([
				'code' => 'BALTUR',
				'description' => 'Via Mario Rossi, 11',
				'type' => 'destinazione',
			]);
			$chimar = Location::create([
				'code' => 'CHIMAR',
				'description' => 'Via Luigi Bianchi, 99',
				'type' => 'destinazione',
			]);
		}
	}
