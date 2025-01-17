<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait ManagesDbTransactions
{
    public static function beginTransaction(): void
    {
        DB::beginTransaction();
    }
    public static function commitTransaction(): void
    {
        DB::commit();
    }
    public static function rollbackTransaction(): void
    {
        DB::rollBack();
    }
}
