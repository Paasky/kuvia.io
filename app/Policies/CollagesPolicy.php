<?php

namespace App\Policies;

use App\Models\Collage;
use App\User;
use Illuminate\Database\Eloquent\Builder;

class CollagesPolicy extends Policy
{
    /** @var Collage */
    protected $model;

    /**
     * Identifier of the policy, ex ´collage´
     * @return string
     */
    public static function entity(): string
    {
        return 'collage';
    }

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
     *  {$this->user} to be able to
     *  {$this->action} on
     *  {$this->model} (note this can be null (for create) or a model instance (for read/use/update/delete)
     * @return string
     */
    public function requiredAbility(): string
    {
        $entity = static::entity();

        switch (true) {
            case $this->model->user_id == $this->user->id:
                $relation = self::REL_MY;
                break;

            default:
                $relation = self::REL_ANY;
        }

        return "$this->action $relation $entity";
    }

    /**
     * Add filter into a "list models" query
     * @param User $user
     * @return Builder
     */
    public static function getListQuery(User $user): Builder
    {
        $query = Collage::query();
        if (!$user->can(self::ACTION_READ . ' ' . self::REL_ANY . ' ' . self::entity())) {
            $query->where('user_id', $user->id);
        }
        return $query;
    }
}
