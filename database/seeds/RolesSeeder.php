<?php

use App\Constants\ConstPermission;
use App\Constants\ConstUser;
use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run()
    {
        Role::unguard();
        $role = Role::updateOrCreate(
            ['id' => 1],
            [
                'name' => ConstUser::ROLE_ADMIN,
                'guard_name' => 'web',
            ]
        );
        $role->givePermissionTo(ConstPermission::ANYTHING);

        $role = Role::updateOrCreate(
            ['id' => 2],
            [
                'name' => ConstUser::ROLE_USER,
                'guard_name' => 'web',
            ]
        );
        $role->givePermissionTo(ConstPermission::ANYTHING_MINE);
    }
}
