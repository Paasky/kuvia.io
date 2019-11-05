<?php

use App\Constants\ConstPermission;
use App\Managers\PermissionManager;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        foreach (['web', 'api'] as $guardName) {
            Permission::firstOrCreate([
                'name' => ConstPermission::ANYTHING,
                'guard_name' => $guardName,
            ]);
            Permission::firstOrCreate([
                'name' => ConstPermission::ANYTHING_MINE,
                'guard_name' => $guardName,
            ]);
        }
        foreach (ConstPermission::ACTIONS as $action) {
            foreach (ConstPermission::RELATIONS as $relation) {
                foreach (PermissionManager::policies() as $policyClass) {
                    foreach (['web','api'] as $guardName) {
                        $entity = $policyClass::entity();
                        Permission::firstOrCreate([
                                'name' => "$action $relation $entity",
                                'guard_name' => $guardName,
                        ]);
                    }
                }
            }
        }
    }
}
