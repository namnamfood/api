<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_superadmin = new Role();
        $role_superadmin->name = 'Super Admin';
        $role_superadmin->description = 'System Adminstrator';
        $role_superadmin->save();

        $role_admin = new Role();
        $role_admin->name = 'Admin';
        $role_admin->description = 'Market Admin';
        $role_admin->save();

        $role_manager = new Role();
        $role_manager->name = 'manager';
        $role_manager->description = 'Market Manager';
        $role_manager->save();
    }
}
