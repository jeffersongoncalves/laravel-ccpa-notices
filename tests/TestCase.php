<?php

namespace JeffersonGoncalves\CcpaNotices\Tests;

use JeffersonGoncalves\Ccpa\CcpaServiceProvider;
use JeffersonGoncalves\CcpaNotices\CcpaNoticesServiceProvider;
use JeffersonGoncalves\CcpaNotices\Tests\Fixtures\Customer;
use JeffersonGoncalves\PestPluginDatabaseMigrations\LoadsPackageMigrations;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    use LoadsPackageMigrations;

    protected function getPackageProviders($app): array
    {
        return [
            CcpaServiceProvider::class,
            CcpaNoticesServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('ccpa.models', [Customer::class]);
        $app['config']->set('ccpa-notices.business', ['name' => 'Acme Inc.', 'contact' => 'privacy@acme.test', 'toll_free_number' => '1-800-555-0100']);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadPackageMigrations(CcpaNoticesServiceProvider::class);
    }
}
