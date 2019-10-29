<?php

namespace App\Utilities;

use App\Models\Collage;
use App\Models\Image;

class Paths
{
    public static function images(Collage $collage): string
    {
        return "/collage_uploads/{$collage->id}/images";
    }
    public static function image(Image $image): string
    {
        return self::images($image->collage) . "/$image->filename";
    }
}
