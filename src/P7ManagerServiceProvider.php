<?php
namespace Valepuri\P7manager;

use Illuminate\Support\ServiceProvider;

class P7ManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__.'/config/p7manager.php' => config_path('p7manager.php')]);
        $this->publishes([__DIR__.'/migrations/' => database_path('migrations')]);
        /*$this->loadRoutesFrom(__DIR__.'/routes.php');
        
        $this->loadViewsFrom(__DIR__.'/views', 'todolist');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/wisdmlabs/todolist'),
        ]);*/
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
       // $this->app->make('wisdmLabs\todolist\TodolistController');
    }
}