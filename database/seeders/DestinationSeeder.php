<?php

	namespace Database\Seeders;

	use App\Models\Destination;
	use Illuminate\Database\Console\Seeds\WithoutModelEvents;
	use Illuminate\Database\Seeder;

	class DestinationSeeder extends Seeder
	{
		/**
		 * Run the database seeds.
		 */
		public function run(): void {
			$baltur = Destination::factory()->create([
				'name' => 'Baltur',
			]);
			$chimar = Destination::factory()->create([
				'name' => 'Chimar',
			]);
		}
	}
