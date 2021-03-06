<?php namespace dam1r89\ProtoGenerator;

use Illuminate\Support\ServiceProvider;

class ProtoGeneratorServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

        $this->app['command.proto'] = $this->app->share(function()
        {
            return $this->app->make('dam1r89\ProtoGenerator\ProtoCommand');
        });

        $this->commands('command.proto');

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
