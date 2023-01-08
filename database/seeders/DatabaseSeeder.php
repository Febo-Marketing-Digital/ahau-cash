<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'lastname' => 'User',
            'birthdate' => '1977-01-01',
            'gender' => 'M',
            'email' => 'admin@email.com',
            'password' => bcrypt('password'),
            'type' => 'admin',
        ]);
    }
}
