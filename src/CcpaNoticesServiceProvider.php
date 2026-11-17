<?php

namespace JeffersonGoncalves\CcpaNotices;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CcpaNoticesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-ccpa-notices')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations();
    }
}
