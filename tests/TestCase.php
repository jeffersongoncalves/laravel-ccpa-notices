<?php

namespace JeffersonGoncalves\CcpaNotices\Tests;

use JeffersonGoncalves\CcpaNotices\CcpaNoticesServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            CcpaNoticesServiceProvider::class,
        ];
    }
}
