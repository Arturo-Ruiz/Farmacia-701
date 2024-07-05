<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Arturo Ruiz',
            'email' => 'Gregory.Arturo.Ruiz@gmail.com',
            'password' => bcrypt('#9d+aOZlk{0B6/g8V2Gl'),
        ]);
    }
}
