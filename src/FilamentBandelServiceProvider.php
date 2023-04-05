<?php

namespace Widiu7omo\FilamentBandel;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Widiu7omo\FilamentBandel\Commands\FilamentBanCommand;

class FilamentBandelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-bandel')
            ->hasMigrations(['create_bans_table', 'add_banned_at_column_to_users_table'])
            ->hasTranslations();
    }
}
