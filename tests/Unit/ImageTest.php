<?php

namespace Tests\Unit;

use App\Constants\ConstUser;
use App\Managers\ImageManager;
use App\Utilities\KuviaFileSystem;
use App\Utilities\Paths;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
            KuviaFileSystem::delete(Paths::image($image));

            // Known Uploader
            $user = $this->user();
            $image = ImageManager::create($imagePath, $collage, $uploader, $user);
            $this::assertEquals($image->user_id, $user->id, 'Image Owner ID');
            KuviaFileSystem::delete(Paths::image($image));
        } finally {
            self::rollbackTransaction();
        }
    }

    public function testShowUploaderImage()
    {
        self::beginTransaction();
        try {
            // Unapproved Image
            $uploader = $this->uploader();
            $image = $this->image($uploader);
            $shownImage = ImageManager::show($image, $uploader);
            $this::assertEquals(null, $shownImage->id ?? null, 'Image ID');

            // Approved Image
            $image = $this->image($uploader);
            ImageManager::approve($image, $this->user());
            $shownImage = ImageManager::show($image, $uploader);
            $this::assertEquals($image->id, $shownImage->id ?? null, 'Image ID');

            // Declined Image
            $image = $this->image($uploader);
            ImageManager::decline($image, $this->user());
            $shownImage = ImageManager::show($image, $uploader);
            $this::assertEquals(null, $shownImage->id ?? null, 'Image ID');
        } finally {
            self::rollbackTransaction();
        }
    }

    public function testShowUserImage()
    {
        self::beginTransaction();
        try {
            // Unapproved Image
            $user = $this->user();
            $image = $this->image($user);
            $shownImage = ImageManager::show($image, $user);
            $this::assertEquals(null, $shownImage->id ?? null, 'Image ID');

            // Approved Image
            $image = $this->image($user);
            ImageManager::approve($image, $this->user());
            $shownImage = ImageManager::show($image, $user);
            $this::assertEquals($image->id, $shownImage->id ?? null, 'Image ID');

            // Declined Image
            $image = $this->image($user);
            ImageManager::decline($image, $this->user());
            $shownImage = ImageManager::show($image, $user);
            $this::assertEquals(null, $shownImage->id ?? null, 'Image ID');
        } finally {
            self::rollbackTransaction();
        }
    }

    public function testListUploaderImages()
    {
        self::beginTransaction();
        try {
            // Can see my own images
            $uploader = $this->uploader();
            $this->image($uploader);
            $this->image($uploader);
            $shownImages = ImageManager::list([], $uploader);
            $this->assertEquals(2, count($shownImages->items()), "Can see my own images");

            // Can't see other uploader's images
            $otherUploader = $this->uploader();
            $this->image($otherUploader);
            $shownImages = ImageManager::list([], $uploader);
            $this->assertEquals(2, count($shownImages->items()), "Can see my own images, but can't see other uploader's images");

            // Admin can see anyone's images
            $user = $this->user();
            $user->assignRole(ConstUser::ROLE_ADMIN);
            $shownImages = ImageManager::list([], $user);
            $this->assertEquals(3, count($shownImages->items()), "Admin can see any user's images");
        } finally {
            self::rollbackTransaction();
        }
    }

    public function testListUserImages()
    {
        self::beginTransaction();
        try {
            // Can see my own images
            $user = $this->user();
            $this->image($user);
            $this->image($user);
            $shownImages = ImageManager::list([], $user);
            $this->assertEquals(2, count($shownImages->items()), "Can see my own images");

            // Can't see other user's images
            $otherUser = $this->user();
            $this->image($otherUser);
            $shownImages = ImageManager::list([], $user);
            $this->assertEquals(2, count($shownImages->items()), "Can see my own images, but can't see other user's images");

            // Admin can see anyone's images
            $user = $this->user();
            $user->assignRole(ConstUser::ROLE_ADMIN);
            $shownImages = ImageManager::list([], $user);
            $this->assertEquals(3, count($shownImages->items()), "Admin can see any user's images");
        } finally {
            self::rollbackTransaction();
        }
    }

    public function testLDeleteUploaderImage()
    {
        self::beginTransaction();
        try {
            // Can delete my image
            $imagePath = $this->imagePath();
            $collage = $this->collage();
            $uploader = $this->uploader();
            $image = ImageManager::create($imagePath, $collage, $uploader);
            ImageManager::delete($image, $uploader);
            static::assertFalse(KuviaFileSystem::exists(Paths::image($image)), 'File no longer exists');

            // Can't delete other uploader's image
            $otherUploader = $this->uploader();
            $otherUploadersImage = $this->image($otherUploader);
            static::expectExceptionObject(new NotFoundHttpException('Image not found'));
            ImageManager::delete($otherUploadersImage, $uploader);

            // admin can delete anyone's image
            $user = $this->user();
            $user->assignRole(ConstUser::ROLE_ADMIN);
            ImageManager::delete($otherUploadersImage, $uploader);
        } finally {
            self::rollbackTransaction();
        }
    }

    public function testLDeleteUserImage()
    {
        self::beginTransaction();
        try {
            // Can delete my image
            $user = $this->user();
            $image = $this->image($user);
            ImageManager::delete($image, $user);

            // Can't delete other user's image
            $otherUser = $this->user();
            $otherUsersImage = $this->image($otherUser);
            static::expectExceptionObject(new NotFoundHttpException('Image not found'));
            ImageManager::delete($otherUsersImage, $user);

            // admin can delete anyone's image
            $user->assignRole(ConstUser::ROLE_ADMIN);
            ImageManager::delete($otherUsersImage, $user);
        } finally {
            self::rollbackTransaction();
        }
    }
}
