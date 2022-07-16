<?php

namespace LaravelReady\OpenGoogleTranslate;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

final class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap of package services
     *
     * @return void
     */
    public function boot(): void
    {
        $this->bootPublishes();
    }

    /**
     * Register any application services
     *
     * @return void
     */
    public function register(): void
    {
        // package config file
        $this->mergeConfigFrom(__DIR__ . '/../config/open-google-translate.php', 'open-google-translate');

        // register package service
        $this->app->singleton('open-google-translate', function () {
            return new Translator();
        });
    }

    /**
     * Publishes resources on boot
     *
     * @return void
     */
    private function bootPublishes(): void
    {
        // package configs
        $this->publishes([
            __DIR__ . '/../config/open-google-translate.php' => $this->app->configPath('open-google-translate.php'),
        ], 'open-google-translate-config');
    }
}
