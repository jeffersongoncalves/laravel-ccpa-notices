<?php

namespace JeffersonGoncalves\CcpaNotices;

use JeffersonGoncalves\CcpaNotices\Commands\NoticesCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CcpaNoticesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('ccpa-notices')
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->hasMigrations(['create_ccpa_processing_activities_table'])
            ->hasCommand(NoticesCommand::class);
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(NoticeManager::class);
    }
}
