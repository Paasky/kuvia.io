<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * @mixin \Eloquent
 * @property int $id
 */
abstract class KuviaModel extends Model implements AuditableContract
{
    use Auditable;
}
