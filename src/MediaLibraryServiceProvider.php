<?php

namespace Ankushtyagi\MediaLibrary;

use Illuminate\Support\ServiceProvider;

class MediaLibraryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/media-library.php' => config_path('media-library.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ], 'migrations');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/media-library.php', 'media-library'
        );

        $this->app->singleton('media-library', function ($app) {
            return new MediaLibrary(config('media-library'));
        });
    }
}