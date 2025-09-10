<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Laboratory;

class LaboratorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Laboratory::create([
            'id' => 1,
            'name' => 'Laboratorio 1',
            'keyword' => 'Laboratorio 1'
        ]);

        Laboratory::create([
            'id' => 2,
            'name' => 'Laboratorio 2',
            'keyword' => 'Laboratorio 2'
        ]);
        Laboratory::create([
            'id' => 3,
            'name' => 'Laboratorio 3',
            'keyword' => 'Laboratorio 3'
        ]);
        Laboratory::create([
            'id' => 4,
            'name' => 'Laboratorio 4',
            'keyword' => 'Laboratorio 4'
        ]);
        Laboratory::create([
            'id' => 5,
            'name' => 'Laboratorio 5',
            'keyword' => 'Laboratorio 5'
        ]);
    }
}
