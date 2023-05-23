<?php

	namespace Database\Seeders;

	use App\Models\Unit;
	use Illuminate\Database\Console\Seeds\WithoutModelEvents;
	use Illuminate\Database\Seeder;

	class UnitSeeder extends Seeder
	{
		/**
		 * Run the database seeds.
		 */
		public function run(): void
		{
			$pezzi = Unit::create([
				'abbreviation' => 'pz',
				'description' => 'Pezzi',
				'decimals' => 0
			]);
			$litri = Unit::create([
				'abbreviation' => 'l',
				'description' => 'Litri',
				'decimals' => 2
			]);
			$metri = Unit::create([
				'abbreviation' => 'mt',
				'description' => 'Metri',
				'decimals' => 2
			]);
		}
	}
