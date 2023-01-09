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
        if (App::environment('local')) {
            \App\Models\User::factory()->create([
                'name' => 'Admin',
                'lastname' => 'User',
                'birthdate' => '1977-01-01',
                'gender' => 'M',
                'email' => 'admin@email.com',
                'password' => bcrypt('password'),
                'type' => 'admin',
            ]);
        } else {
            \App\Models\User::factory()->create([
                'name' => 'Antonio',
                'lastname' => 'Flores',
                'birthdate' => '1980-01-01',
                'gender' => 'M',
                'email' => 'aefab@hotmail.com',
                'password' => bcrypt('Febo2022'),
                'type' => 'admin',
            ]);
        }
    }
}
