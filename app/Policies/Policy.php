<?php

namespace App\Policies;

use App\Constants\ConstPermission;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class Policy extends ConstPermission
{
    /** @var User */
    protected $user;
    /** @var string */
    protected $action;
    /** @var Model */
    protected $model;

    public function __construct(User $user, string $action, Model $model)
    {
        $this->user = $user;

        if (!in_array($action, self::ACTIONS)) {
            throw new \BadFunctionCallException("Unknown action $action");
        }
        $this->action = $action;

        $this->model = $model;
    }

    /**
     * Identifier of the policy, ex ´collage´
     * @return string
     */
    abstract public static function entity(): string;

    /**
     * Which Model::class does this policy apply to
     * Remember to register this in PermissionsManager::policies() !
     * @return string
     */
    abstract public static function modelClass(): string;

    /**
     * What Permission is required for
     *  {$this->user} to be able to
     *  {$this->action} on
     *  {$this->model} (note this can be null (for create) or a model instance (for read/use/update/delete)
     * @return string
     */
    abstract public function requiredAbility(): string;

    /**
     * Add filter into a "list models" query
     * @param User $user
     * @return Builder
     */
    abstract public static function getListQuery(User $user): Builder;
}
