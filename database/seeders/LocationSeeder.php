<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $location_1 = Location::create([
			'code' => 'A0101',
			'description' => 'A 01 01',
			'type' => fake()->randomElement(array_keys(config('requested.locations.types'))),
        ]);
	    $location_2 = Location::create([
		    'code' => 'A0102',
		    'description' => 'A 01 02',
		    'type' => fake()->randomElement(array_keys(config('requested.locations.types'))),
	    ]);
	    $location_3 = Location::create([
		    'code' => 'A0103',
		    'description' => 'A 01 03',
		    'type' => fake()->randomElement(array_keys(config('requested.locations.types'))),
	    ]);
	    $location_4 = Location::create([
		    'code' => 'A0201',
		    'description' => 'A 02 01',
		    'type' => fake()->randomElement(array_keys(config('requested.locations.types'))),
	    ]);
	    $location_5 = Location::create([
		    'code' => 'A0205',
		    'description' => 'A 02 05',
		    'type' => fake()->randomElement(array_keys(config('requested.locations.types'))),
	    ]);
	    $location_6 = Location::create([
		    'code' => 'A0203',
		    'description' => 'A 02 03',
		    'type' => fake()->randomElement(array_keys(config('requested.locations.types'))),
	    ]);
	    $location_7 = Location::create([
		    'code' => 'B0101',
		    'description' => 'B 01 01',
		    'type' => fake()->randomElement(array_keys(config('requested.locations.types'))),
	    ]);
	    $location_8 = Location::create([
		    'code' => 'B0102',
		    'description' => 'B 01 02',
		    'type' => fake()->randomElement(array_keys(config('requested.locations.types'))),
	    ]);
    }
}
