<?php namespace Lud\Club;

use Illuminate\Support\ServiceProvider;
use Config; use App;

class ClubServiceProvider extends ServiceProvider {

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
		$this->package('lud/club');
		require __DIR__.'/../../routes.php';
		$modelName = Club::modelName();
		$validatorName = Club::modelValidatorName();
		$table = with(new $modelName([]))->getTable();
		// @todo this is bad because it loads all the validation on every
		// request, we must be able to listen to Model::boot event.
		// More, laravel doesn't even reuse this object, it's only taking its
		// class name ...
		$modelName::observe(new $validatorName(App::make('validator')));
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerCommands();
	}

	/**
	 * Register club's commands
	 * @return void
	 */
	public function registerCommands()
	{
        $this->app['command.club.users_migration'] = $this->app->share(function($app)
        {
            return new Command\UsersTableCommand($this->app['files']);
        });

        $this->commands(array('command.club.users_migration'));
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

}
