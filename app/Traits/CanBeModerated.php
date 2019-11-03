<?php


namespace App\Traits;

use App\Models\KuviaModel;
use App\User;
use Carbon\Carbon;
use Cog\Laravel\Ban\Traits\Bannable;
use Hootlex\Moderation\Moderatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin KuviaModel
 * @property int $status
 * @property int $moderated_by
 * @property Carbon $moderated_at
 * @property-read User $moderatedBy
 */
trait CanBeModerated
{
    use Moderatable;

    public function moderatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }
}
