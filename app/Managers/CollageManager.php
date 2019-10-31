<?php

namespace App\Managers;

use App\Models\Collage;
use App\User;
use Illuminate\Pagination\Paginator;

class CollageManager
{
    public static function create(User $user, array $params): User
    {

    }

    public static function show(User $user, Collage $collage): ?Collage
    {

    }

    public static function list(User $user, array $params = []): Paginator
    {

    }

    public static function disable(User $user, Collage &$collage): void
    {

    }

    public static function delete(User $user, Collage $collage): void
    {

    }
}
