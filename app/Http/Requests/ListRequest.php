<?php

namespace App\Http\Requests;

use App\Traits\Paginates;

class ListRequest extends Request
{
    public function rules(): array
    {
        return [
            Paginates::$perPage => [
                'integer',
                'min:0',
            ],
            Paginates::$perPage => [
                'integer',
                'min:0',
            ],
            Paginates::$search => [
                'array',
            ],
            Paginates::$search . '.*' => [
                'string',
            ],
            Paginates::$searchIn => [
                'array',
            ],
            Paginates::$searchIn . '.*' => [
                'string',
            ],
            Paginates::$orderBy => [
                'array',
            ],
            Paginates::$orderBy . '.*' => [
                'string',
            ],
            Paginates::$show => [
                'array',
            ],
            Paginates::$show . '.*' => [
                'string',
            ],
            Paginates::$hide => [
                'array',
            ],
            Paginates::$hide . '.*' => [
                'string',
            ],
        ];
    }
}
