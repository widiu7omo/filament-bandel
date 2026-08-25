<?php

namespace Widiu7omo\FilamentBandel\Tests;

use Cog\Laravel\Ban\Providers\BanServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use Widiu7omo\FilamentBandel\FilamentBandelServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Widiu7omo\\FilamentBandel\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app): array
    {
        return [
            BanServiceProvider::class,
            FilamentBandelServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');

        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function defineDatabaseMigrations(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Exercise the package's real publishable migration stubs, in the same
        // order an installing application would run them.
        (include __DIR__.'/../database/migrations/add_banned_at_column_to_users_table.php.stub')->up();
        (include __DIR__.'/../database/migrations/create_bans_table.php.stub')->up();
    }
}
