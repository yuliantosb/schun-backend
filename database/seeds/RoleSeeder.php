<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first();
        $role = Role::create(['name' => 'admin']);
        $permission = Permission::create(['name' => 'dashboard']);
        $role->givePermissionTo($permission);
        $user->assignRole($role);
    }
}
