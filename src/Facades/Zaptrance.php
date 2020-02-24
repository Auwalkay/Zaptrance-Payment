<?php

namespace Zaptrance\Payment\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * 
 */
class Zaptrance extends Facade
{
	
	 protected static function getFacadeAccessor()
    {
        return 'laravel-zaptrance';
    }
}