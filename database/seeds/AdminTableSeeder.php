<?php

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_super_admin = Role::where('name', 'Super Admin')->first();
        $role_admin = Role::where('name', 'Admin')->first();
        $super_admin = new Admin();
        $super_admin->fullname = 'Orkhan Alirzayev';
        $super_admin->email = 'superadmin@gmail.com';
        $super_admin->phone = '+994500000';
        $super_admin->password = bcrypt('secret');
        $super_admin->save();
        $super_admin->roles()->attach($role_super_admin);

        $admin = new Admin();
        $admin->fullname = 'Manager Name';
        $admin->email = 'admin@gmail.com';
        $admin->phone = '+994500001';
        $admin->password = bcrypt('secret');
        $admin->save();
        $admin->roles()->attach($role_admin);
    }
}
