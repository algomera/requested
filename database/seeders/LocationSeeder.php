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
	    $ricevimento = Location::create([
		    'code' => 'RICEVIM',
		    'description' => 'Ricevimento',
		    'type' => 'ricevimento',
	    ]);
	    $grandi_quantita = Location::create([
		    'code' => 'GRANDI',
		    'description' => 'Grandi quantità',
		    'type' => 'grandi_quantita',
	    ]);
        $produzione = Location::create([
			'code' => 'PRODUZ',
			'description' => 'Produzione',
			'type' => 'produzione',
        ]);
	    $scarto = Location::create([
		    'code' => 'SCARTO',
		    'description' => 'Scarto',
		    'type' => 'scarto',
	    ]);
	    $versamento = Location::create([
		    'code' => 'VERSAM',
		    'description' => 'Versamento',
		    'type' => 'versamento',
	    ]);
	    $spedizione = Location::create([
		    'code' => 'SPEDIZ',
		    'description' => 'Spedizione',
		    'type' => 'spedizione',
	    ]);
    }
}
