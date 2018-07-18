<?php
/**
 * ECRCHS Services Provider
 * @author Blake Nahin <bnahin@live.com>
 */

namespace CachetHQ\Cachet\Foundation\Providers;

use CachetHQ\Cachet\Foundation\Common\Bnahin\EcrchsServices;
use Illuminate\Support\ServiceProvider;

class EcrchsServiceProvider extends ServiceProvider
{

    protected $defer = true;

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(EcrchsServices::class, function () {
            return new EcrchsServices;
        });
    }
}
