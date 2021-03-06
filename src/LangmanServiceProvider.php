<?php

namespace Themsaid\LangmanGUI;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class LangmanServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/langmanGUI.php' => config_path('langmanGUI.php'),
        ], 'config');

        $this->loadViewsFrom(__DIR__.'/views', 'langmanGUI');

        $this->registerRoutes();
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/langmanGUI.php', 'langmanGUI');

        $this->app->singleton(Manager::class, function () {
            return new Manager(
                new Filesystem,
                $this->app['path.lang'],
                [$this->app['path.resources'], $this->app['path']]
            );
        });
    }

    /**
     * Register the Langman routes.
     */
    protected function registerRoutes()
    {
        $this->app['router']->group(config('langmanGUI.route_group_config'), function ($router) {
            $router->get('/langman', 'LangmanController@index');

            $router->post('/langman/sync', 'LangmanController@sync');

            $router->post('/langman/save', 'LangmanController@save');
        });
    }
}
