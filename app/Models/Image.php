<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Hootlex\Moderation\Moderatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $filename
 * @property int $uploader_id
 * @property int $user_id
 * @property int $collage_id
 * @property int $status
 * @property int $moderated_by
 * @property Carbon $moderated_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read Uploader $uploader
 * @property-read User $user
 * @property-read Collage $collage
 * @property-read User $moderatedBy
 */
class Image extends KuviaModel
{
    use Moderatable;

    protected $fillable = [
        'filename',
        'uploader_id',
        'user_id',
        'collage_id',
        'status',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(Uploader::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function collage(): BelongsTo
    {
        return $this->belongsTo(Collage::class);
    }

    public function moderatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }
}
