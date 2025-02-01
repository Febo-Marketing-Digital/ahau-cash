<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (app()->environment('local')) {
            $admin = \App\Models\User::factory()->create([
                'name' => 'Admin',
                'lastname' => 'User',
                'birthdate' => '1977-01-01',
                'gender' => 'M',
                'email' => 'admin@email.com',
                'password' => bcrypt('password'),
                'type' => 'admin',
            ]);

            \App\Models\User::factory()->create([
                'name' => 'Investor',
                'lastname' => 'User',
                'birthdate' => '1977-01-01',
                'gender' => 'M',
                'email' => 'investor@email.com',
                'password' => bcrypt('password'),
                'type' => 'investor',
            ]);

            \App\Models\User::factory()->create([
                'name' => 'Client',
                'lastname' => 'User',
                'birthdate' => '1977-01-01',
                'gender' => 'F',
                'email' => 'client@email.com',
                'password' => bcrypt('password'),
                'type' => 'client',
            ]);
        } else {
            $admin = \App\Models\User::factory()->create([
                'name' => 'Antonio',
                'lastname' => 'Flores',
                'birthdate' => '1980-01-01',
                'gender' => 'M',
                'email' => 'aefab@hotmail.com',
                'password' => bcrypt('Febo2022'),
                'type' => 'admin',
            ]);
        }

        $roleAdmin = Role::create(['name' => 'Admin']);
        $roleStaff = Role::create(['name' => 'Staff']);
        $roleClient = Role::create(['name' => 'Client']);
        $roleInvestor = Role::create(['name' => 'Investor']);
        $roleSuperAdmin = Role::create(['name' => 'Super-Admin']);

        $admin->assignRole($roleSuperAdmin);
    }
}
