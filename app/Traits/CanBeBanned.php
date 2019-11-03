<?php


namespace App\Traits;

use App\Models\KuviaModel;
use App\User;
use Carbon\Carbon;
use Cog\Laravel\Ban\Traits\Bannable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin KuviaModel
 * @property int $banned_by
 * @property Carbon $banned_at
 * @property-read User $bannedBy
 */
trait CanBeBanned
{
    use Bannable;

    public function bannedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'banned_by');
    }

    public function shouldApplyBannedAtScope()
    {
        return true;
    }
}
