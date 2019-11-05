<?php

namespace App\Managers;

use App\Models\Collage;
use App\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Str;

class CollageManager extends Manager
{
    public static function create(array $params, User $user): Collage
    {
        PermissionManager::can(
            $user,
            self::ACTION_CREATE,
            new Collage(['user_id' => $params['user_id'] ?? $user->id]),
            true
        );

        $params['slug'] = static::generateSlug($params['title']);
        return Collage::create($params);
    }

    public static function show(Collage $collage, User $user): ?Collage
    {
        PermissionManager::can(
            $user,
            self::ACTION_READ,
            $collage,
            true
        );

        return $collage;
    }

    public static function list(array $params, User $user): Paginator
    {
        $query = PermissionManager::getListQuery($user, Collage::class);
        return self::paginator($query, $params);
    }

    public static function update(Collage &$collage, array $params, User $user): ?Collage
    {
        PermissionManager::can(
            $user,
            self::ACTION_UPDATE,
            $collage,
            true
        );

        $collage->update($params);

        return $collage;
    }

    public static function delete(Collage $collage, User $user): void
    {
        PermissionManager::can(
            $user,
            self::ACTION_DELETE,
            $collage,
            true
        );

        $collage->delete();
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
