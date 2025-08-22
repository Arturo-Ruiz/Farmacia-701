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
    }
}
