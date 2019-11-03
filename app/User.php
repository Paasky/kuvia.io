<?php

namespace App;

use App\Models\Collage;
use App\Models\Image;
use App\Models\Uploader;
use App\Traits\CanBeBanned;
use Carbon\Carbon;
use Cog\Laravel\Ban\Traits\Bannable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Redactors\LeftRedactor;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

/**
 * @mixin \Eloquent
 * @property string $name
 * @property string $email
 * @property Carbon $email_verified_at
 * @property string $password
 * @property string $remember_token
 *
 * @property-read string[]|Collection $upload_ips
 * @property-read string[]|Collection $upload_user_agents
 *
 * @property-read Collage[]|Collection $collages
 * @property-read Image[]|Collection $images
 * @property-read User[]|Collection $bannedUsers
 * @property-read Uploader[]|Collection $uploaders
 * @property-read Uploader[]|Collection $bannedUploaders
 * @property-read Collage[]|Collection $moderatedCollages
 * @property-read Image[]|Collection $moderatedImages
 */
class User extends Authenticatable implements AuditableContract
{
    use Notifiable, Auditable, CanBeBanned, HasPermissions, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $attributeModifiers = [
        'password' => LeftRedactor::class,
        'remember_token' => LeftRedactor::class,
    ];

    public function collages(): HasMany
    {
        return $this->hasMany(Collage::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    public function uploaders(): HasMany
    {
        return $this->hasMany(Uploader::class);
    }

    public function bannedUsers(): HasMany
    {
        return $this->hasMany(User::class, 'banned_by');
    }

    public function bannedUploaders(): HasMany
    {
        return $this->hasMany(Uploader::class, 'banned_by');
    }

    public function moderatedCollages(): HasMany
    {
        return $this->hasMany(Collage::class, 'moderated_by');
    }

    public function moderatedImages(): HasMany
    {
        return $this->hasMany(Image::class, 'moderated_by');
    }

    public function getUploadIpsAttribute(): Collection
    {
        return $this->uploaders->pluck('ip');
    }

    public function getUploadUserAgentsAttribute(): Collection
    {
        return $this->uploaders->pluck('user_agent');
    }
}
