<?php
/**
 * NestedMenusServiceProvider
 */
namespace Delatbabel\NestedMenus;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use App;

/**
 * NestedMenusServiceProvider
 */
class NestedMenusServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the service provider.
     *
     * This method is called after all other service providers have
     * been registered, meaning you have access to all other services
     * that have been registered by the framework.
     *
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        // Publish the database migrations and seeds
        $this->publishes([
            __DIR__ . '/../database/migrations' => $this->app->databasePath() . '/migrations'
        ], 'migrations');
        $this->publishes([
            __DIR__ . '/../database/seeds' => $this->app->databasePath() . '/seeds'
        ], 'seeds');

        // Register other providers required by this provider, which saves the caller
        // from having to register them each individually.
        App::register(\Baum\Providers\BaumServiceProvider::class);
        App::register(\Cviebrock\EloquentSluggable\ServiceProvider::class);
        App::register(\Lavary\Menu\ServiceProvider::class);
    }

    /**
     * Register the service provider.
     *
     * Within the register method, you should only bind things into the
     * service container. You should never attempt to register any event
     * listeners, routes, or any other piece of functionality within the
     * register method.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
