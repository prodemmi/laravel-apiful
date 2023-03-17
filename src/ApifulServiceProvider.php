<?php

namespace Prodemmi\Apiful;

use Illuminate\Support\Facades\Artisan;
use JsonSerializable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\Arrayable;
use Prodemmi\Apiful\Console\MakeDecorator;

class ApifulServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/apiful.php', 'apiful');

        $this->app->bind('apiful', function ($app) {
            return new Apiful();
        });

    }

    public function boot()
    {

        if ( $this->app->runningInConsole() ) {

            $this->registerPublishing();

            $this->commands([
                MakeDecorator::class
            ]);

        }

        if ( File::exists(__DIR__ . '/helpers.php') ) {
            require __DIR__ . '/helpers.php';
        }

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'apiful');

        Request::macro('apiful', function (mixed $data = null) {
            return apiful($data);
        });

        Artisan::call("vendor:publish --tag='apiful-config'");

    }

    protected function registerPublishing()
    {

        $this->publishes([
            __DIR__ . '/../config/apiful.php' => config_path('apiful.php'),
        ], 'apiful-config');

        $this->publishes([
            __DIR__ . '/../resources/lang' => $this->app->langPath('vendor/apiful'),
        ], 'apiful-lang');

    }

}