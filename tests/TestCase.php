<?php

namespace Ankushtyagi\MediaLibrary\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Ankushtyagi\MediaLibrary\MediaLibraryServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            MediaLibraryServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
