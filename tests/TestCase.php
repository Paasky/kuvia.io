<?php

namespace Tests;

use App\Managers\CollageManager;
use App\Models\Collage;
use App\Models\Image;
use App\Models\Uploader;
use App\Traits\ManagesDbTransactions;
use App\User;
use Faker\Provider\Person;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, ManagesDbTransactions;

    /** @var Model[] */
    protected $models = [];

    public function cleanup(?Model ...$extraModels): void
    {
        /** @var Model $model */
        foreach (array_merge($this->models, $extraModels) as $model) {
            if ($model) {
                $model->delete();
            }
        }
        $this->models = [];
    }

    public function user(): User
    {
        $user = User::create($this->userAttributes());
        $this->models[] = $user;
        return $user;
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
     * @param Uploader|User|null $uploaderOrUser
     * @param Collage|null $collage
     * @return Image
     */
    public function image($uploaderOrUser = null, Collage $collage = null): Image
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

        $image = Image::create($this->imageAttributes($collage, $uploader, $user));
        $this->models[] = $image;
        return $image;
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
        $collage = Collage::create($this->collageAttributes($user));
        $this->models[] = $collage;
        return $collage;
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

    public function uploader(): Uploader
    {
        $uploader = Uploader::create($this->uploaderAttributes());
        $this->models[] = $uploader;
        return $uploader;
    }

    public function uploaderAttributes(User $user = null): array
    {
        return [
            'user_id' => $user ? $user->id : null,
        ];
    }
}
