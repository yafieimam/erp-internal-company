<?php

namespace Techalyst\Kanban\Provider;

use Illuminate\Support\ServiceProvider;

class KanbanServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/kanban'),
            __DIR__.'/../resources/assets' => public_path('vendors/kanban'),
            __DIR__.'/../config/config.php' => app()->basePath() . '/config/kanban.php',
            __DIR__.'/../Helpers/KanbanHelper.php' => app()->basePath() . '/app/Helpers/KanbanHelper.php',
        ]);

        $this->loadRoutesFrom(__DIR__.'/../routes.php');
        $this->loadMigrationsFrom(__DIR__.'/../migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'kanban');
        $this->registerHelpers();

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->make('Techalyst\Kanban\Controllers\TaskController');

    }

    /**
     * Register helpers file
     */
    public function registerHelpers()
    {
        // Load the helpers in app/Http/helpers.php
        if (file_exists($file = app_path('Helpers/KanbanHelper.php')))
        {
            require_once $file;
        }
    }
}
