<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_employee = new Role();
        $role_employee->name = 'admin';
        $role_employee->description = 'A Admin User';
        $role_employee->save();

        $role_manager = new Role();
        $role_manager->name = 'accountmanager';
        $role_manager->description = 'A Account Manager User';
        $role_manager->save();

        $role_manager = new Role();
        $role_manager->name = 'programming';
        $role_manager->description = 'A Programming User';
        $role_manager->save();

        $role_manager = new Role();
        $role_manager->name = 'shipping';
        $role_manager->description = 'A Shipping User';
        $role_manager->save();

    }
}
