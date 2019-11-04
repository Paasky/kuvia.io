<?php

namespace App\Policies;

use App\Models\Collage;
use Spatie\Permission\Models\Permission;

class CollagesPolicy extends Policy
{
    /** @var Collage */
    protected $model;

    /**
     * Which Model::class does this policy apply to
     * Remember to register this in PermissionsManager::policies() !
     * @return string
     */
    public static function modelClass(): string
    {
        return Collage::class;
    }

    /**
     * What Permission is required for
     *  $this->user to be able to
     *  $this->>action on
     *  $this->>model (note this can be a class string (for create) or a model instance (for read/use/update/delete)
     * @param string $guardName
     * @return Permission
     */
    public function requiredPermission(string $guardName = 'web'): Permission
    {
        $entity = 'collage';
        if ($this->model->user_id == $this->user->id) {
            $relation = self::REL_MY;
        } else {
            $relation = self::REL_ANY;
        }

        $permission = "$this->action $relation $entity";

        $permission = Permission::first([
            'name' => $permission,
            'guard_name' => $guardName,
        ]);

        if (!$permission) {
            throw new \BadFunctionCallException("Required permission [name $permission, guard_name $guardName] does not exist, please run or update PermissionsSeeder");
        }

        return $permission;
    }
}
