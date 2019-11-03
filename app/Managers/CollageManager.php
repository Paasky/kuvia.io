<?php

namespace App\Managers;

use App\Models\Collage;
use App\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Str;

class CollageManager
{
    public static function create(array $params, User $user = null): Collage
    {
        if (!$user) {
            throw new \BadFunctionCallException("No user given");
        }
        $params['user_id'] = $user->id;
        $params['slug'] = static::generateSlug($params['title']);
        return Collage::create($params);
    }

    public static function show(Collage $collage, User $user = null): ?Collage
    {
        return $collage;
    }

    public static function list(array $params = [], User $user = null): Paginator
    {

    }

    public static function disable(Collage &$collage, User $user): void
    {

    }

    public static function delete(Collage $collage, User $user): void
    {

    }

    public static function generateSlug(string $title): string
    {
        $i = 0;
        do {
            $slug = Str::slug($title . ($i ?: ''));
        } while (Collage::where('slug', $slug)->exists());

        return $slug;
    }

    public static function generateShortcode(): string
    {
        $vowels = 'aeiouy';
        $consonants = 'bcdfghjklmnpqrstvwxz';
        do {
            $shortcode = '';
            for($i = 0; $i < 6; $i++) {
                $str = $i % 2 ? $consonants : $vowels;
                $shortcode .= $str[rand(0, strlen($str)-1)];
            }
        } while (Collage::where('shortcode', $shortcode)->exists());

        return $shortcode;
    }
}
