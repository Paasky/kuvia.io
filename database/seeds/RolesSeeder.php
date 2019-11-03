<?php

use App\Constants\ConstUser;
use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run()
    {
        Role::unguard();
        Role::updateOrCreate(
            ['id' => 1],
            [
                'name' => ConstUser::ROLE_ADMIN,
                'guard_name' => 'web',
            ]
        );
    }
}
