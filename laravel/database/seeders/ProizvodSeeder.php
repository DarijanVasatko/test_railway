<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Proizvod;

class ProizvodSeeder extends Seeder
{
    public function run(): void
    {
        // Generate 50 fake products using the factory
        Proizvod::factory()->count(500)->create();
    }
}
