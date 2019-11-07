<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * @mixin \Eloquent
 * @property int $id
 */
abstract class KuviaModel extends Model implements AuditableContract
{
    use Auditable;

    abstract public static function searchColumns(): array;
    abstract public static function alwaysHidden(): array;

    public function canMakeVisible(string $property): bool
    {
        if (in_array($property, static::alwaysHidden())) {
            return false;
        }
        return in_array($property, $this->hidden);
    }

    public function canAppend(string $property): bool
    {
        if (in_array($property, static::alwaysHidden())) {
            return false;
        }
        return method_exists($this, Str::camel("get_{$property}_attribute"));
    }
}
