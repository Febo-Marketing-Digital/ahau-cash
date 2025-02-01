<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // setup basic role and permissions
        //$role = Role::create(['name' => 'Admin']);
        // $roleStaff = Role::create(['name' => 'Staff']);
        // $roleClient = Role::create(['name' => 'Client']);
        // $roleInvestor = Role::create(['name' => 'Investor']);
        // $roleSuperAdmin = Role::create(['name' => 'Super-Admin']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider

        // $permission1 = Permission::create(['name' => 'view all loans']);
        // $permission2 = Permission::create(['name' => 'view all users']);
        // $permission3 = Permission::create(['name' => 'approve loan']);
        // $permission4 = Permission::create(['name' => 'close loan']);
        // $permission5 = Permission::create(['name' => 'ban client']);

        // Permission::create(['name' => 'create client']);
        // Permission::create(['name' => 'edit client']);
        // Permission::create(['name' => 'delete client']);
        // Permission::create(['name' => 'upload client document']);
        // Permission::create(['name' => 'delete client document']);
        // Permission::create(['name' => 'create loan']);

        // Permission::create(['name' => 'view owned clients']);
        // Permission::create(['name' => 'view granted loans']);

        // Permission::create(['name' => 'view all stats']);
        // Permission::create(['name' => 'view owned stats']);

        //$role->givePermissionTo([$permission1, $permission2, $permission3, $permission4, $permission5]);

        // assign role to test admin user
        // $admin = User::whereEmail('admin@email.com')->firstOrFail();
        // $admin->assignRole($role);

        // $roleStaff->givePermissionTo(['create client', 'edit client', 'upload client document', 'delete client document']);
        // $roleInvestor->givePermissionTo([
        //     'view owned clients', 'view granted loans'
        // ]);

        // Permission::create(['name' => 'view staff']);
        // Permission::create(['name' => 'view banks']);
        //Permission::create(['name' => 'view investors']);
    }
}
