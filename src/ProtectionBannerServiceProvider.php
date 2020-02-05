<?php

namespace Markohs\ProtectionBanner;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Markohs\ProtectionBanner\Middleware\ProtectionBannerMiddleware;

class ProtectionBannerServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

        $this->loadViewsFrom(__DIR__.'/resources/views/', 'protectionbanner');

        $router->middleware('protectionbanner', 'Markohs\ProtectionBanner\Middleware\ProtectionBannerMiddleware');

        if (config('protectionbanner.autoregister') == null) {
            // Avoid complex situations on config:cache and production apps
            return;
        }

        foreach (config('protectionbanner.autoregister') as $group) {
            $router->pushMiddlewareToGroup($group, ProtectionBannerMiddleware::class);
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/protectionbanner.php', 'protectionbanner');

        // Register the service the package provides.
        $this->app->singleton('protectionbanner', function ($app) {
            return new ProtectionBanner;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['protectionbanner'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        $publishTag = 'ProtectionBanner';

        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/config/protectionbanner.php' => config_path('protectionbanner.php'),
        ], $publishTag);

        // Publishing the views.
        $this->publishes([
            __DIR__.'/resources/views' => base_path('resources/views/vendor/protectionbanner'),
        ], $publishTag);
    }
}
