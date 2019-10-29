<?php

namespace App\Managers;

use App\Models\Collage;
use App\Models\Image;
use App\Models\Uploader;
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

        return $image;
    }

    public static function approve(Image &$image): void
    {

    }

    public static function decline(Image &$image): void
    {

    }

    public static function delete(Image $image): void
    {

    }
}
