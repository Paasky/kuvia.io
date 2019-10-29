<?php

namespace App\Managers;

use App\Models\Collage;
use App\Models\Image;
use App\Models\Uploader;
use App\Utilities\KuviaFileSystem;
use App\Utilities\Paths;
use Illuminate\Support\Str;

class ImageManager
{
    public static function create(string $pathToFile, Collage $collage, Uploader $uploader): Image
    {
        $extension = pathinfo($pathToFile, PATHINFO_EXTENSION);
        $filename = Str::uuid()->toString() . ".{$extension}";
        $image = new Image([
            'uploader_id' => $uploader->id,
            'collage_id' => $collage->id,
            'filename' => $filename,
        ]);

        KuviaFileSystem::move($pathToFile, Paths::images($collage));

        if ($collage->auto_approve) {
            static::approve($image);
        }

        return $image;
    }

    public static function approve(Image &$image): void
    {
        $image->markApproved();
    }

    public static function decline(Image &$image): void
    {
        $image->markRejected();
    }

    public static function delete(Image $image): void
    {
        KuviaFileSystem::delete(Paths::image($image));
    }
}
