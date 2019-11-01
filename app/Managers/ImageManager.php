<?php

namespace App\Managers;

use App\Models\Collage;
use App\Models\Image;
use App\Models\Uploader;
use App\User;
use App\Utilities\KuviaFileSystem;
use App\Utilities\Paths;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Str;
use League\Flysystem\FileExistsException;

class ImageManager
{
    /**
     * @param string $pathToFile
     * @param Collage $collage
     * @param Uploader $uploader
     * @param User|null $user
     * @return Image
     * @throws FileExistsException
     */
    public static function create(string $pathToFile, Collage $collage, Uploader $uploader, User $user = null): Image
    {
        $extension = pathinfo($pathToFile, PATHINFO_EXTENSION);
        $filename = Str::uuid()->toString() . ".{$extension}";
        $image = new Image([
            'uploader_id' => $uploader->id,
            'collage_id' => $collage->id,
            'filename' => $filename,
            'user_id' => $user ? $user->id : null,
        ]);

        KuviaFileSystem::move($pathToFile, Paths::images($collage, $filename));

        if ($collage->auto_approve) {
            static::approve($image);
        }

        return $image;
    }

    /**
     * @param Image $image
     * @param Uploader|User|null $uploaderOrUser
     * @return Image|null
     */
    public static function show(Image $image, $uploaderOrUser = null): ?Image
    {

    }

    /**
     * @param Image $image
     * @param Uploader|User|null $uploaderOrUser
     * @return string|null
     */
    public static function download(Image $image, $uploaderOrUser = null): ?string
    {

    }

    /**
     * @param array $params
     * @param Uploader|User|null $uploaderOrUser
     * @return Paginator
     */
    public static function list(array $params = [], $uploaderOrUser = null): Paginator
    {

    }

    /**
     * @param Image $image
     * @param User|null $user
     */
    public static function approve(Image &$image, User $user = null): void
    {
        $image->markApproved();
    }

    /**
     * @param Image $image
     * @param User|null $user
     */
    public static function decline(Image &$image, User $user = null): void
    {
        $image->markRejected();
    }

    /**
     * @param Image $image
     * @param User|null $user
     */
    public static function delete(Image $image, User $user = null): void
    {
        KuviaFileSystem::delete(Paths::image($image));
    }
}
