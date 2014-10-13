<?php 

namespace Smartcms\Core\Facades;

use Illuminate\Support\Facades\Facade;

class PermissionSelect extends Facade 
{

    /**
    * Get the registered name of the component.
    *
    * @return string
    */
    protected static function getFacadeAccessor() { return 'permissionselect'; }

}