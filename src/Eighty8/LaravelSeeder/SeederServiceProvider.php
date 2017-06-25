<?php

namespace Eighty8\LaravelSeeder;

use App;
use Illuminate\Support\ServiceProvider;

class SeederServiceProvider extends ServiceProvider
{
    const SEEDERS_CONFIG_PATH = '/../../config/seeders.php';
    
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Boot.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . self::SEEDERS_CONFIG_PATH => config_path('seeders.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . self::SEEDERS_CONFIG_PATH, 'seeds'
        );

        $this->app->singleton('seeder.repository', function ($app) {
            return new SeederRepository($app['db'], config('seeders.table'));
        });

        $this->app->singleton('seeder.migrator', function ($app) {
            return new SeedMigrator($app['seeder.repository'], $app['db'], $app['files']);
        });

        $this->app->bind('command.seeder', function ($app) {
            return new SeedOverrideCommand($app['seeder.migrator']);
        });

        $this->app->bind('seeder.run', function ($app) {
            return new SeedCommand($app['seeder.migrator']);
        });

        $this->app->bind('seeder.install', function ($app) {
            return new SeedInstallCommand($app['seeder.repository']);
        });

        $this->app->bind('seeder.make', function () {
            return new SeedMakeCommand();
        });

        $this->app->bind('seeder.reset', function ($app) {
            return new SeedResetCommand($app['seeder.migrator']);
        });

        $this->app->bind('seeder.rollback', function ($app) {
            return new SeedRollbackCommand($app['seeder.migrator']);
        });

        $this->app->bind('seeder.refresh', function () {
            return new SeedRefreshCommand();
        });

        $this->commands([
            'seeder.run',
            'seeder.install',
            'seeder.make',
            'seeder.reset',
            'seeder.rollback',
            'seeder.refresh',
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'seeder.repository',
            'seeder.migrator',
            'command.seeder',
            'seeder.run',
            'seeder.install',
            'seeder.make',
            'seeder.reset',
            'seeder.rollback',
            'seeder.refresh',
        ];
    }
}
