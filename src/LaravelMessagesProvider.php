<?php

namespace StemateF\LaravelConversation;

use Illuminate\Support\ServiceProvider;

class LaravelMessagesProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        $this->publishes([
            __DIR__ . '/config' => config_path(''),
        ]);

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
