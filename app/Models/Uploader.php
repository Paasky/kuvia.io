<?php

namespace App\Models;

use App\Traits\CanBeBanned;
use App\User;
use Carbon\Carbon;
use Cog\Laravel\Ban\Traits\Bannable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property string $ip
 * @property string $user_agent
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read User $user
 * @property-read Image[]|Collection $images
 */
class Uploader extends KuviaModel
{
    use CanBeBanned;

    protected $fillable = [
        'ip',
        'user_agent',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    public function bannedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'banned_by');
    }
}
