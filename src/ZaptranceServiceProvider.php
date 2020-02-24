<?php


/**
 * 
 */
namespace Zaptrance\Payment;

use Illuminate\Support\ServiceProvider;

class ZaptranceServiceProvider extends ServiceProvider
{
	
protected $defer = false;

	public function boot()
	{
		
		 //  
		$this->mergeConfigFrom(
			__DIR__. '\..\config\zaptrance.php','zaptrance'
		);

        $this->publishes([
            
            __DIR__.'\..\config\zaptrance.php'=> config_path('zaptrance.php')
        ]);
	}


	public function register()
	{
		$this->app->bind('laravel-zaptrance', function () {

            return new Zaptrance;

        });

	}
}