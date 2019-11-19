<?php


namespace Yjtec\LaravelAmqpLink;

use Illuminate\Support\ServiceProvider;

class AmqpLinkServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/amqp_link.php' => config_path('amqp_link.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('AmqpLink', function ($app) {
            return new AmqpLink();
        });
        $this->app->singleton('Publisher', function ($app) {
            return new Publisher(config());
        });
        $this->app->singleton('Consumer', function ($app) {
            return new Consumer(config());
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['AmqpLink', 'Yjtec\LaravelAmqpLink\Publisher', 'Yjtec\LaravelAmqpLink\Publisher'];
    }
}