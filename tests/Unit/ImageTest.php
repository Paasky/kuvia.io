<?php

namespace Tests\Unit;

use App\Constants\ConstUser;
use App\Managers\ImageManager;
use App\Utilities\KuviaFileSystem;
use App\Utilities\Paths;
use self;
use Tests\TestCase;

class ImageTest extends TestCase
{
    public function testCreateImage()
    {
        self::beginTransaction();
        try {
            // Unknown uploader
            $imagePath = $this->imagePath();
            $collage = $this->collage();
            $uploader = $this->uploader();
            $image = ImageManager::create($imagePath, $collage, $uploader);
            $this::assertEquals($image->collage_id, $collage->id, 'Image Collage ID');
            $this::assertEquals($image->uploader_id, $uploader->id, 'Image Uploader ID');
            $this::assertEquals($image->user_id, null, 'Image Owner ID');
            $this::assertEquals(file_get_contents($imagePath), KuviaFileSystem::get(Paths::image($image)), 'Uploaded binary matches');

            // Known Uploader
            $user = $this->user();
            $image = ImageManager::create($imagePath, $collage, $uploader, $user);
            $this::assertEquals($image->user_id, $user->id, 'Image Owner ID');
        } finally {
            if (isset($image)) {
                KuviaFileSystem::delete(Paths::image($image));
            }
            self::rollbackTransaction();
        }
    }

    public function testShowImage()
    {
        self::beginTransaction();
        try {
            // Unknown uploader
            $imagePath = $this->imagePath();
            $collage = $this->collage();
            $uploader = $this->uploader();
            $image = $this->image();
            $this::assertEquals($image->collage_id, $collage->id, 'Image Collage ID');
            $this::assertEquals($image->uploader_id, $uploader->id, 'Image Uploader ID');
            $this::assertEquals($image->user_id, null, 'Image Owner ID');
            $this::assertEquals(file_get_contents($imagePath), KuviaFileSystem::get(Paths::image($image)), 'Uploaded binary matches');

            // Known Uploader
            $user = $this->user();
            $image = ImageManager::create($imagePath, $collage, $uploader, $user);
            $this::assertEquals($image->user_id, $user->id, 'Image Owner ID');
        } finally {
            if (isset($image)) {
                KuviaFileSystem::delete(Paths::image($image));
            }
            self::rollbackTransaction();
        }
    }

    public function testListImages()
    {
        self::beginTransaction();
        try {
            // Can see my own images
            $user = $this->user();
            $this->image($user);
            $this->image($user);
            $shownImages = ImageManager::list($user);
            $this->assertEquals(2, count($shownImages->items()), "Can see my own images");

            // Can't see other user's images
            $otherUser = $this->user();
            $this->image($otherUser);
            $shownImages = ImageManager::list($user);
            $this->assertEquals(2, count($shownImages->items()), "Can see my own images, but can't see other user's images");

            // Admin can see anyone's images
            $user->assignRole(ConstUser::ROLE_ADMIN);
            $this->assertEquals(3, count($shownImages->items()), "Admin can see any user's images");
        } finally {
            self::rollbackTransaction();
        }
    }

    public function testLDownloadImage()
    {
        self::beginTransaction();
        try {

        } finally {
            self::rollbackTransaction();
        }
    }

    public function testLDeleteImage()
    {
        self::beginTransaction();
        try {
            // Can delete my image
            $user = $this->user();
            $image = $this->image($user);
            ImageManager::delete($user, $image);

            // Can't delete other user's image
            $otherUser = $this->user();
            $otherUsersImage = $this->image($otherUser);
            static::expectExceptionObject(new NotFoundHttpException('Image not found'));
            ImageManager::delete($user, $otherUsersImage);

            // admin can delete anyone's image
            $user->assignRole(ConstUser::ROLE_ADMIN);
            ImageManager::delete($user, $otherUsersImage);
        } finally {
            self::rollbackTransaction();
        }
    }
}
