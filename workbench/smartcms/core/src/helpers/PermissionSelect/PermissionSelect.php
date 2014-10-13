<?php 

namespace Smartcms\Core\Helpers;

use Illuminate\Support\Facades\URL;
use PermissionProvider;

/**
* Breadcrumb class
*/
class PermissionSelect 
{
    public function show(){
        $permissions = PermissionProvider::findAll1();
        return 'Permission Select Helper';
    }
}