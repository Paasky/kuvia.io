<?php

namespace Tests;

use App\Traits\ManagesDbTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, ManagesDbTransactions;
}
