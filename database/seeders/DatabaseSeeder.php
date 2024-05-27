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
        //Customers agregados
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => 'Admin123#$'
        ]);

        User::create([
            'name' => 'Developer',
            'email' => 'developer@gmail.com',
            'password' => 'developer123#$'
        ]);

        User::create([
            'name' => 'Test',
            'email' => 'test@gmail.com',
            'password' => 'Test123#$'
        ]);
    }
}
