<?php

namespace App\Managers;

use App\Constants\ConstPermission;
use App\Traits\Paginates;

abstract class Manager extends ConstPermission
{
    use Paginates;
}
