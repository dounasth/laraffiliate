<?php namespace Bonweb\Laraffiliate;

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
        include __DIR__ . '/../../util/helpers.php';
        include __DIR__.'/../../events.php';
        include __DIR__.'/../../filters.php';
        include __DIR__.'/../../routes.php';
        $this->setConnection();
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
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
