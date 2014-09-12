<?php namespace Smartosc\Core\Facades;

use Illuminate\Support\Facades\Facade;

class PermissionProvider extends Facade 
{

    /**
    * Get the registered name of the component.
    *
    * @return string
    */
    protected static function getFacadeAccessor() { return 'permissionProvider'; }

}