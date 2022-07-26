<?php

namespace TF\Providers;

use Illuminate\Support\ServiceProvider;
use TF\Console\Commands\ClearRestLogger;
use TF\Contracts\RestLoggerInterface;
use TF\DBLogger;
use TF\Exceptions\InvalidLogDriverException;
use TF\FileLogger;
use TF\RedisLogger;
use TF\Http\Middleware\RestLogger;

class RestLogsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/restlog.php', 'restlogs'
        );

        $this->bindServices();
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        $this->loadConfig();
        $this->loadRoutes();
        $this->loadViews();
        $this->loadCommand();
        $this->loadMigrations();
    }

    /**
     * Bound all services
     */
    public function bindServices()
    {
        $driver = config('restlog.driver');

        switch ($driver) {
            case 'db':
                $instance = DBLogger::class;
                break;
            case 'redis':
                $instance = RedisLogger::class;
                break;

            default:
                $instance = FileLogger::class;
                break;
        }

        if (!(resolve($instance) instanceof RestLoggerInterface)) {
            throw new InvalidLogDriverException();
        }

        $this->app->singleton(RestLoggerInterface::class, $instance);

        $this->app->singleton('restlogger', function ($app) use ($instance) {
            return new RestLogger($app->make($instance));
        });
    }

    /**
     * Loads Config
     */
    public function loadConfig()
    {
        $this->publishes([
            __DIR__ . '/../../config/restlog.php' => config_path('restlog.php'),
        ], 'config');
    }

    /**
     * Load Routes
     */
    public function loadRoutes()
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
    }

    /**
     * Load Views
     */
    public function loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'restlog');
    }

    /**
     * Loading Default Commands
     */
    public function loadCommand()
    {
        $this->commands([
            ClearRestLogger::class,
        ]);
    }

    /**
     * Loading Migrations
     */
    public function loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }
}
