<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the UserSeeder to seed the users table
        $this->call(UserSeeder::class);

        // Call the CategorySeeder to seed the categories table
        $this->call(CategorySeeder::class);

        // Call the TaxSeeder to seed the taxes table
        $this->call(TaxSeeder::class);

        //Call the DayRateSeeder to seed the day_rates table
        $this->call(DayRateSeeder::class);

        // Call the AdSeeder to seed the ads table
        $this->call(AdSeeder::class);

        // Call the CarouselSeeder to seed the carousels table
        $this->call(CarouselSeeder::class);

        // Call the LaboratorySeeder to seed the laboratories table1
        $this->call(LaboratorySeeder::class);
    }
}
