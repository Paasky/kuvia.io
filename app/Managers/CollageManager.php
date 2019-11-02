<?php

namespace App\Managers;

use App\Models\Collage;
use App\User;
use Illuminate\Pagination\Paginator;

class CollageManager
{
    public static function create(array $params, User $user = null): Collage
    {

    }

    public static function show(Collage $collage, User $user = null): ?Collage
    {

    }

    public static function list(array $params = [], User $user = null): Paginator
    {

    }

    public static function disable(Collage &$collage, User $user): void
    {

    }

    public static function delete(Collage $collage, User $user): void
    {

    }
}
