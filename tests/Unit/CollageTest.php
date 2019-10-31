<?php

namespace Tests\Unit;

use App\Constants\ConstUser;
use App\Managers\CollageManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class CollageTest extends TestCase
{
    public function testCreateCollage()
    {
        self::beginTransaction();
        try {
            $user = $this->user();
            $collage = CollageManager::create($user, $this->collageAttributes('Test Title'));
            $this::assertEquals($collage->title, 'Test Title', 'Collage title');
            $this::assertEquals($collage->user_id, $user->id, 'Collage owner id');
        } finally {
            self::rollbackTransaction();
        }
    }

    public function testShowCollage()
    {
        self::beginTransaction();
        try {
            // Can see my collage
            $user = $this->user();
            $collage = $this->collage($user);
            $shownCollage = CollageManager::show($user, $collage);
            $this->assertEquals($collage->id, $shownCollage->id ?? null, "User's collage ID is shown");

            // Can't see someone else's collage
            $otherUser = $this->user();
            $otherUsersCollage = $this->collage($otherUser);
            $shownCollage = CollageManager::show($user, $otherUsersCollage);
            $this->assertEquals(null, $shownCollage->id ?? null, "Other user's collage ID is not shown");

            // Admin can see anyone's Collage
            $user->assignRole(ConstUser::ROLE_ADMIN);
            $this->assertEquals($collage->id, $shownCollage->id ?? null, "Other user's collage ID is shown to admins");
        } finally {
            self::rollbackTransaction();
        }
    }

    public function testListCollages()
    {
        self::beginTransaction();
        try {
            // Can see my own collages
            $user = $this->user();
            $this->collage($user);
            $this->collage($user);
            $shownCollages = CollageManager::list($user);
            $this->assertEquals(2, count($shownCollages->items()), "Can see my own collages");

            // Can't see other user's collages
            $otherUser = $this->user();
            $this->collage($otherUser);
            $shownCollages = CollageManager::list($user);
            $this->assertEquals(2, count($shownCollages->items()), "Can see my own collages, but can't see other user's collages");

            // Admin can see anyone's collages
            $user->assignRole(ConstUser::ROLE_ADMIN);
            $this->assertEquals(3, count($shownCollages->items()), "Admin can see any user's collages");
        } finally {
            self::rollbackTransaction();
        }
    }

    public function testLDeleteCollage()
    {
        self::beginTransaction();
        try {
            // Can delete my collage
            $user = $this->user();
            $collage = $this->collage($user);
            CollageManager::delete($user, $collage);

            // Can't delete other user's collage
            $otherUser = $this->user();
            $otherUsersCollage = $this->collage($otherUser);
            static::expectExceptionObject(new NotFoundHttpException('Collage not found'));
            CollageManager::delete($user, $otherUsersCollage);

            // admin can delete anyone's collage
            $user->assignRole(ConstUser::ROLE_ADMIN);
            CollageManager::delete($user, $otherUsersCollage);
        } finally {
            self::rollbackTransaction();
        }
    }
}
