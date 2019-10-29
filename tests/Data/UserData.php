<?php

namespace Tests\Data;

trait UserData
{
    public function userParams(
        string $email = 'test@test.com'
    ): array
    {
        return [
            'email' => $email,
        ];
    }
}
