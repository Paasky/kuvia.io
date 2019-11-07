<?php

namespace App\Http\Controllers;

use App\Constants\ConstPermission;
use App\Http\Requests\CollageRequest;
use App\Http\Requests\ListRequest;
use App\Managers\CollageManager;
use App\Managers\PermissionManager;
use App\Models\Collage;
use App\User;
use Illuminate\Contracts\Pagination\Paginator;

class CollageController extends Controller
{
    public function create(CollageRequest $request, User $user): Collage
    {
        PermissionManager::can(
            $user,
            ConstPermission::ACTION_CREATE,
            new Collage(['user_id' => $params['user_id'] ?? $user->id]),
            true
        );

        return CollageManager::create($request->allAllowed(), \Auth::user());
    }

    public function show(Collage &$collage, User $user): Collage
    {
        PermissionManager::can(
            $user,
            ConstPermission::ACTION_READ,
            $collage,
            true
        );

        return $collage;
    }

    public function list(ListRequest $request, User $user): Paginator
    {
        $query = PermissionManager::getListQuery($user, Collage::class);
        return $this->paginator($query, $request->allAllowed());
    }

    public function update(Collage &$collage, array $params, User $user): void
    {
        PermissionManager::can(
            $user,
            ConstPermission::ACTION_UPDATE,
            $collage,
            true
        );

        $collage->update($params);
    }

    public function delete(Collage $collage, User $user): void
    {
        PermissionManager::can(
            $user,
            ConstPermission::ACTION_DELETE,
            $collage,
            true
        );

        $collage->delete();
    }
}
