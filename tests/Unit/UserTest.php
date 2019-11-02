<?php

namespace Tests\Unit;

use App\Managers\UserManager;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testCanRegister()
    {
        self::beginTransaction();
        try {
            $user = UserManager::create($this->userAttributes(
                'asda@gmail.com'
            ));
            $this->assertEquals('asda@gmail.com', $user->email, 'User email matches');
        } finally {
            self::rollbackTransaction();
        }
    }
}
