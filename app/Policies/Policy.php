<?php

namespace App\Policies;

use App\Constants\ConstPolicy;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

abstract class Policy extends ConstPolicy
{
    protected $user;
    protected $action;
    protected $model;

    /**
     * Policy constructor.
     * @param User $user
     * @param string $action
     * @param Model|string $model
     */
    public function __construct(User $user, string $action, $model)
    {
        $this->user = $user;

        if (!in_array($action, self::ACTIONS)) {
            throw new \BadFunctionCallException("Unknown action $action");
        }
        $this->action = $action;

        if (!is_string($model) && !$model instanceof Model) {
            throw new \BadFunctionCallException(
                "Unknown model type:" . gettype($model) . ' > class:' . (@get_class($model) ?: 'no class')
            );
        }
        $this->model = $model;
    }

    /**
     * Which Model::class does this policy apply to
     * Remember to register this in PermissionsManager::policies() !
     * @return string
     */
    abstract public static function modelClass(): string;

    /**
     * What Permission is required for
     *  $this->>user to be able to
     *  $this->>action on
     *  $this->>model (note this can be a class string (for create) or a model instance (for read/use/update/delete)
     * @param string $guardName
     * @return Permission
     */
    abstract public function requiredPermission(string $guardName = 'web'): Permission;
}
