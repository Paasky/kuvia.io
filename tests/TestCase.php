<?php

namespace Tests;

use App\Managers\CollageManager;
use App\Models\Collage;
use App\Models\Image;
use App\Models\Uploader;
use App\Traits\ManagesDbTransactions;
use App\User;
use Faker\Generator;
use Faker\Provider\Internet;
use Faker\Provider\Person;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, ManagesDbTransactions;

    public function user(): User
    {
        return User::create($this->userAttributes());
    }

    public function userAttributes(string $email = '', string $password = 'test'): array
    {
        if (!$email) {
            $i = 0;
            do {
                $email = 'test' . ($i ?: '') . '@kuvia.io';
                $i++;
            } while (User::where('email', $email)->exists());
        }
        return [
            'name' => Person::firstNameMale(),
            'email' => $email,
            'password' => bcrypt($password),
        ];
    }

    /**
     * @param Collage|null $collage
     * @param Uploader|User|null $uploaderOrUser
     * @return Image
     */
    public function image(Collage $collage = null, $uploaderOrUser = null): Image
    {
        if ($uploaderOrUser instanceof Uploader) {
            $uploader = $uploaderOrUser;
        } else {
            $uploader = $this->uploader();
        }

        if ($uploaderOrUser instanceof User) {
            $user = $uploaderOrUser;
        } else {
            $user = null;
        }

        return Image::create($this->imageAttributes($collage, $uploader, $user));
    }

    public function imageAttributes(Collage $collage = null, Uploader $uploader = null, User $user = null, string $filename = ''): array
    {
        return [
            'filename' => $filename ?: 'test.jpg',
            'file_hash' => md5('test'),
            'uploader_id' => $uploader ? $uploader->id : $this->uploader()->id,
            'user_id' => $user ? $user->id : null,
            'collage_id' => $collage ? $collage->id : $this->collage()->id,
        ];
    }

    public function imagePath(): string
    {
        return base_path('tests/Data/test.jpg');
    }

    public function collage(User $user = null): Collage
    {
        return Collage::create($this->collageAttributes($user));
    }

    public function collageAttributes(User $user = null, string $title = 'Test Collage', bool $isAutoApprove = false): array
    {
        return [
            'title' => $title,
            'slug' => CollageManager::generateSlug($title),
            'shortcode' => CollageManager::generateShortcode(),
            'user_id' => $user ? $user->id : $this->user()->id,
            'is_auto_approve' => $isAutoApprove
        ];
    }

    public function uploader(array $attributes = []): Uploader
    {
        $attributes = $attributes ?: $this->uploaderAttributes();
        return Uploader::create($attributes);
    }

    public function uploaderAttributes(User $user = null): array
    {
        return [
            'user_id' => $user ? $user->id : null,
        ];
    }
}
