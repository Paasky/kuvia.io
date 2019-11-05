<?php

namespace App\Managers;

use App\Policies\CollagesPolicy;
use App\Policies\Policy;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\UnauthorizedException;

class PermissionManager extends Manager
{
    public static function can(User $user, string $action, Model $model, bool $verify = false): bool
    {
        if ($user->can(self::ANYTHING)) {
            return true;
        }

        if ($model->user_id == $user->id && $user->can(self::ANYTHING_MINE)) {
            return true;
        }

        $policy = static::getPolicy($user, $action, $model);
        if ($user->can($policy->requiredAbility())) {
            return true;
        }

        if ($verify) {
            throw new UnauthorizedException("You are not allowed to do this");
        }
        return false;
    }

    public static function getListQuery(User $user, string $modelClass): Builder
    {
        $policyClass = static::policies()[$modelClass] ?? null;
        if (!$policyClass) {
            throw new \BadFunctionCallException("Policy for model class $modelClass is not registered in PermissionManager");
        }
        return $policyClass::getListQuery($user);
    }

    public static function getPolicy(User $user, string $action, Model $model): Policy
    {
        $modelClass = get_class($model);
        $policyClass = static::policies()[$modelClass] ?? null;
        if (!$policyClass) {
            throw new \BadFunctionCallException("Policy for model class $modelClass is not registered in PermissionManager");
        }

        return new $policyClass($user, $action, $model);
    }

    /**
     * @return Policy[]|string[]
     */
    public static function policies(): array
    {
        return [
            CollagesPolicy::modelClass() => CollagesPolicy::class,
        ];
    }
}
