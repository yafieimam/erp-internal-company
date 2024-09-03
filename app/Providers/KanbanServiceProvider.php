<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class KanbanServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerHelpers();
    }

    public function registerHelpers()
    {
        // Load the helpers in app/Http/helpers.php
        if (file_exists($file = app_path('Helpers/KanbanHelper.php')))
        {
            require_once $file;
        }
    }
}
