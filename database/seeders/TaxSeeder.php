<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Tax;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tax::create([
            'id' => 1,
            'name' => 'IVA General 16%',
            'value' => 16,
        ]);

        Tax::create([
            'id' => 2,
            'name' => 'IVA General Reducido 9%',
            'value' => 9,
        ]);

        Tax::create([
            'id' => 3,
            'name' => 'IVA Reducido 8%',
            'value' => 8,
        ]);

        Tax::create([
            'id' => 4,
            'name' => 'Retención ISLR 2% Servicios',
            'value' => 2,
        ]);

        Tax::create([
            'id' => 5,
            'name' => 'IGTF 3%',
            'value' => 3,
        ]);

        Tax::create([
            'id' => 9999,
            'name' => 'Exento de IVA',
            'value' => 0,
        ]);
    }
}
