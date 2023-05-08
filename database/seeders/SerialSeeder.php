<?php

namespace Database\Seeders;

use App\Models\Serial;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SerialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Serial::factory(50)->create();
    }
}
