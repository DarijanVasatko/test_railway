<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@techshop.tsd'], // CHANGE THIS
            [
                'ime'       => 'Admin',
                'prezime'   => 'User',
                'telefon'   => '000000000',
                'password'  => bcrypt('TlwK[(9}fQ1~g;Q7]xo-H~(J!Vz}.4PouVhrnH^ir,VK-aGqAG'), // CHANGE THIS
                'is_admin'  => true,
            ]
        );
    }
}
