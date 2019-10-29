<?php

namespace Tests\Unit;

use App\Managers\UserManager;
use App\User;
use Tests\Data\UserData;
use Tests\TestCase;

class UserTest extends TestCase
{
    use UserData;

    public function testCanRegister()
    {
        self::startTransaction();
        try {
            $user = UserManager::create($this->userParams(
                'asda@gmail.com'
            ));
            $this->assertEquals('asda@gmail.com', $user->email, 'User email matches');
        } finally {
            self::rollbackTransaction();
        }
    }

    public function testCanActivate()
    {
        self::startTransaction();
        try {
            $user = UserManager::create($this->userParams());
            UserManager::activate($user);
            $this->assertEquals(true, $user->is_active, 'User is activated');
        } finally {
            self::rollbackTransaction();
        }
    }

    public function testCanSocialRegister()
    {
        self::startTransaction();
        try {

        } finally {
            self::rollbackTransaction();
        }
    }

    public function testCanSocialLogin()
    {
        self::startTransaction();
        try {

        } finally {
            self::rollbackTransaction();
        }
    }
}
