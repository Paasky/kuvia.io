<?php

namespace App\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait Paginates
{
    /**
     * @param Builder $query
     * @param array $params
     * @return LengthAwarePaginator
     */
    public static function paginator($query, array $params): LengthAwarePaginator
    {
        return $query->paginate(
            $params['perPage'] ?? null,
            ['*'],
            'page',
            $params['page'] ?? null
        );
    }
}
