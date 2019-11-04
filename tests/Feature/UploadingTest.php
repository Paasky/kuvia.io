<?php

namespace Tests\Feature;

use App\Constants\ConstUser;
use App\Managers\ImageManager;
use App\Traits\ManagesDbTransactions;
use App\Utilities\KuviaFileSystem;
use App\Utilities\Paths;
use Carbon\Carbon;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Tests\TestCase;

class UploadingTest extends TestCase
{
    use ManagesDbTransactions;

    public function testCreateWithAutoApproveThenUploadThenList()
    {
        try {
            $collage = $this->collage();
            $collage->is_auto_approve = true;
            $imagePath = $this->imagePath();
            $uploader = $this->uploader();
            $image = ImageManager::create($imagePath, $collage, $uploader);

            $this->assertEquals(Carbon::now()->toDateString(), $image->approved_at->toDateString(), 'Image was approved today');
            $this->assertEquals(1, count($collage->images), 'Collage has one image');
            $this->assertEquals($image->id, $collage->images[0]->id, 'Image ID matches');
        } finally {
            if ($image ?? null) {
                KuviaFileSystem::delete(Paths::image($image));
            }
            $this->cleanup($image ?? null);
        }
    }

    public function testCreateThenUploadThenApproveThenDeclineThenApprove()
    {
        try {
            $collage = $this->collage();
            $imagePath = $this->imagePath();
            $uploader = $this->uploader();
            $image = ImageManager::create($imagePath, $collage, $uploader);

            ImageManager::approve($image, $collage->user);
            $this->assertEquals(Carbon::now()->toDateString(), $image->approved_at->toDateString(), 'Image was approved today');
            $this->assertEquals(1, $collage->images()->count(), 'Collage has one image');
            $this->assertEquals($image->id, $collage->images[0]->id, 'Image ID matches');

            ImageManager::decline($image, $collage->user);
            $this->assertEquals(Carbon::now()->toDateString(), $image->declined_at->toDateString(), 'Image was declined today');
            $this->assertNull($image->approved_at, 'Image is no longer approved');
            $this->assertEquals(0, $collage->images()->count(), 'Collage has no images');

            ImageManager::approve($image, $collage->user);
            $this->assertEquals(Carbon::now()->toDateString(), $image->approved_at->toDateString(), 'Image 1 was approved today');
            $this->assertNull($image->declined_at, 'Image is no longer declined');
            $this->assertEquals(1, $collage->images()->count(), 'Collage has one image');
            $this->assertEquals($image->id, $collage->images[0]->id, 'Image ID matches');
        } finally {
            if ($image ?? null) {
                KuviaFileSystem::delete(Paths::image($image));
            }
            $this->cleanup($image ?? null);
        }
    }

    public function testCreateThenUploadTwoThenApproveBothThenList()
    {
        try {
            $collage = $this->collage();
            $imagePath = $this->imagePath();
            $uploader = $this->uploader();
            $image1 = ImageManager::create($imagePath, $collage, $uploader);
            $image2 = ImageManager::create($imagePath, $collage, $uploader);

            $this->assertNull($image1->approved_at, 'Image 1 was not approved');
            $this->assertNull($image2->approved_at, 'Image 2 was not approved');
            $this->assertEquals(0, $collage->images()->count(), 'Collage has no images');

            // Collage owner can approve
            ImageManager::approve($image1, $collage->user);
            $this->assertEquals(Carbon::now()->toDateString(), $image1->approved_at->toDateString(), 'Image 1 was approved today');
            $this->assertEquals(1, $collage->images()->count(), 'Collage has one image');
            $this->assertEquals($image1->id, $collage->images[0]->id, 'Image 1 ID matches');

            // Other users can't approve
            $otherUser = $this->user();
            static::expectExceptionObject(new UnauthorizedException(401,'You are not allowed to do this'));
            ImageManager::approve($image2, $otherUser);

            // Admin can approve
            $otherUser->assignRole(ConstUser::ROLE_ADMIN);
            ImageManager::approve($image2, $otherUser);
            $this->assertEquals(Carbon::now()->toDateString(), $image2->approved_at->toDateString(), 'Image 2 was approved today');
            $this->assertEquals(2, $collage->images()->count(), 'Collage has two images');
            $this->assertEquals($image1->id, $collage->images[0]->id, 'Image 1 ID matches');
            $this->assertEquals($image2->id, $collage->images[1]->id, 'Image 2 ID matches');
        } finally {
            if ($image1 ?? null) {
                KuviaFileSystem::delete(Paths::image($image1));
            }
            if ($image2 ?? null) {
                KuviaFileSystem::delete(Paths::image($image2));
            }
            $this->cleanup($image1 ?? null, $image2 ?? null);
        }
    }

    public function testCreateThenUploadTwoThenDeclineBoth()
    {
        try {
            $collage = $this->collage();
            $imagePath = $this->imagePath();
            $uploader = $this->uploader();
            $image1 = ImageManager::create($imagePath, $collage, $uploader);
            $image2 = ImageManager::create($imagePath, $collage, $uploader);

            // Collage owner can decline
            ImageManager::decline($image1, $collage->user);
            $this->assertEquals(Carbon::now()->toDateString(), $image1->declined_at->toDateString(), 'Image 1 was declined today');
            $this->assertEquals(0, $collage->images()->count(), 'Collage has no images');

            // Other users can't decline
            $otherUser = $this->user();
            static::expectExceptionObject(new UnauthorizedException(401,'You are not allowed to do this'));
            ImageManager::decline($image2, $otherUser);

            // Admin can decline
            $otherUser->assignRole(ConstUser::ROLE_ADMIN);
            ImageManager::decline($image2, $otherUser);
            $this->assertEquals(Carbon::now()->toDateString(), $image2->declined_at->toDateString(), 'Image 2 was declined today');
            $this->assertEquals(0, $collage->images()->count(), 'Collage has no images');
        } finally {
            if ($image1 ?? null) {
                KuviaFileSystem::delete(Paths::image($image1));
            }
            if ($image2 ?? null) {
                KuviaFileSystem::delete(Paths::image($image2));
            }
            $this->cleanup($image1 ?? null, $image2 ?? null);
        }
    }
}
