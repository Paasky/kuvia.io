<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Hootlex\Moderation\Moderatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property string $title
 * @property string $slug
 * @property string $shortcode
 * @property int $user_id
 * @property int $status
 * @property int $moderated_by
 * @property Carbon $moderated_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read User $user
 * @property-read User $moderatedBy
 * @property-read Image[]|Collection $images
 */
class Collage extends KuviaModel
{
    use Moderatable;

    protected $fillable = [
        'title',
        'slug',
        'shortcode',
        'user_id',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function moderatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }
}
