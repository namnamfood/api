<?php

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Admin::create([
            'fullname' => 'Admin Admin',
            'email' => 'admin@gmail.com',
            'phone' => '+994504200000',
            'password' => bcrypt('secret')
        ]);
    }
}
