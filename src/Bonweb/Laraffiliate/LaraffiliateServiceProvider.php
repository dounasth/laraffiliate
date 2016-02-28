<?php namespace Bonweb\Laraffiliate;

use Bonweb\Laraffiliate\Commands\CronParseProducts;
use Illuminate\Foundation\Artisan;
use Illuminate\Support\ServiceProvider;

class LaraffiliateServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('bonweb/laraffiliate');
        include_once __DIR__ . '/../../util/helpers.php';
        include_once __DIR__.'/../../events.php';
        include_once __DIR__.'/../../filters.php';
        include_once __DIR__.'/../../routes.php';
        $this->setConnection();
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
//        \Artisan::add(new CronParseProducts());
        $this->app['command.cron.parse-products'] = $this->app->share(
            function ($app) {
                return new CronParseProducts();
            }
        );

        $this->commands('command.cron.parse-products');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

    public function setConnection()
    {
        $importConnection = \Config::get('laraffiliate::general.import_db');
        \Config::set('database.connections.affilimport', $importConnection);
    }

}
