<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'id' => 1,
            'name' => "Plásticos",
        ]);

        Category::create([
            'id' => 2,
            'name' => "Medicinas",
        ]);

        Category::create([
            'id' => 3,
            'name' => "Misceláneos",
        ]);

        Category::create([
            'id' => 4,
            'name' => "Productos Importados",
        ]);

        Category::create([
            'id' => 5,
            'name' => "Sumplementos y Medicina Natural",
        ]);

        Category::create([
            'id' => 6,
            'name' => "Alimentos",
        ]);

        Category::create([
            'id' => 9999,
            'name' => "No definido",
        ]);
    }
}
