<?php

namespace Tests\Unit;

use App\Constants\ConstUser;
use App\Managers\CollageManager;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Tests\TestCase;

class CollageTest extends TestCase
{
    public function testCreateCollage()
    {
        try {
            $user = $this->user();
            $collage = CollageManager::create($this->collageAttributes($user, 'Test Title'), $user);
            $this::assertEquals($collage->title, 'Test Title', 'Collage title');
            $this::assertEquals($collage->user_id, $user->id, 'Collage owner id');
        } finally {
            $this->cleanup($collage ?? null);
        }
    }

    public function testShowCollage()
    {
        try {
            // Can see my collage
            $user = $this->user();
            $collage = $this->collage($user);
            $shownCollage = CollageManager::show($collage, $user);
            $this->assertEquals($collage->id, $shownCollage->id ?? null, "User's collage ID is shown");

            // Can't see someone else's collage
            $otherUser = $this->user();
            $otherUsersCollage = $this->collage($otherUser);
            $shownCollage = CollageManager::show($otherUsersCollage, $user);
            $this->assertEquals(null, $shownCollage->id ?? null, "Other user's collage ID is not shown");

            // Admin can see anyone's Collage
            $user->assignRole(ConstUser::ROLE_ADMIN);
            $this->assertEquals($collage->id, $shownCollage->id ?? null, "Other user's collage ID is shown to admins");
        } finally {
            $this->cleanup();
        }
    }

    public function testListCollages()
    {
        try {
            // Can see my own collages
            $user = $this->user();
            $this->collage($user);
            $this->collage($user);
            $shownCollages = CollageManager::list([], $user);
            $this->assertEquals(2, count($shownCollages->items()), "Can see my own collages");

            // Can't see other user's collages
            $otherUser = $this->user();
            $this->collage($otherUser);
            $shownCollages = CollageManager::list([], $user);
            $this->assertEquals(2, count($shownCollages->items()), "Can see my own collages, but can't see other user's collages");

            // Admin can see anyone's collages
            $user->assignRole(ConstUser::ROLE_ADMIN);
            $shownCollages = CollageManager::list([], $user);
            $this->assertEquals(3, count($shownCollages->items()), "Admin can see any user's collages");
        } finally {
            $this->cleanup();
        }
    }

    public function testLDeleteCollage()
    {
        try {
            // Can delete my collage
            $user = $this->user();
            $collage = $this->collage($user);
            CollageManager::delete($collage, $user);

            // Can't delete other user's collage
            $otherUser = $this->user();
            $otherUsersCollage = $this->collage($otherUser);
            static::expectExceptionObject(new UnauthorizedException(401,'You are not allowed to do this'));
            CollageManager::delete($otherUsersCollage, $user);

            // admin can delete anyone's collage
            $user->assignRole(ConstUser::ROLE_ADMIN);
            CollageManager::delete($otherUsersCollage, $user);
        } finally {
            $this->cleanup();
        }
    }
}
