<?php

namespace Tests;

use App\Models\Collage;
use App\Models\Image;
use App\Models\Uploader;
use App\Traits\ManagesDbTransactions;
use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, ManagesDbTransactions;

    public function userAttributes(string $email = 'test@test.com'): array
    {
        return [
            'email' => $email,
        ];
    }

    public function user(array $attributes = []): User
    {
        $attributes = $attributes ?: $this->userAttributes();
        return User::create($attributes);
    }

    public function imageAttributes(string $title = 'Test Image'): array
    {
        return [
            'uploader_id' => $uploader->id,
            'collage_id' => $collage->id,
            'filename' => $filename,
            'user_id' => $user ? $user->id ? null,
        ];
    }

    public function image(array $attributes = []): Image
    {
        $attributes = $attributes ?: $this->imageAttributes();
        return Image::create($attributes);
    }

    public function imagePath(): string
    {
        return base_path('tests/Data/test.jpg');
    }

    public function collageAttributes(string $title = 'Test Collage'): array
    {
        return [
            'title' => $title,
        ];
    }

    public function collage(User $user = null, array $attributes = []): Collage
    {
        $user = $user ?: $this->user();
        $attributes = $attributes ?: $this->collageAttributes();
        $attributes['user_id'] = $user->id;
        return Collage::create($attributes);
    }

    public function uploaderAttributes(User $user = null): array
    {
        return [
            'user_id' => $user ? $user->id : null,
        ];
    }

    public function uploader(array $attributes = []): Uploader
    {
        $attributes = $attributes ?: $this->uploaderAttributes();
        return Uploader::create($attributes);
    }
}
