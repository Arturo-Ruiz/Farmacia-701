<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Carousel;

class CarouselSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Carousel::create(['id' => 1]);
        Carousel::create(['id' => 2]);
        Carousel::create(['id' => 3]);
        Carousel::create(['id' => 4]);
        Carousel::create(['id' => 5]);
    }
}
