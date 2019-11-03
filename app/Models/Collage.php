<?php

namespace App\Models;

use App\Traits\CanBeModerated;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property string $title
 * @property string $slug
 * @property string $shortcode
 * @property int $user_id
 * @property bool $is_auto_approve
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read User $user
 * @property-read Image[]|Collection $images
 */
class Collage extends KuviaModel
{
    use CanBeModerated;

    protected $fillable = [
        'title',
        'slug',
        'shortcode',
        'user_id',
        'status',
        'is_auto_approve',
    ];

    protected $casts = [
        'is_auto_approve' => 'boolean',
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
