<?php
use Smartcms\Core\CoreServiceProvider;
use Illuminate\Support\Facades\Config;
$smartcms_packages = CoreServiceProvider::getSmartCmsPackages();

// auto register route of package
if(count($smartcms_packages)){
    foreach($smartcms_packages as $package_name){
        $package_name = strtolower($package_name);
        $package_routes = Config::get("{$package_name}::routes");
        
        $basicAuthRoutes = array();
        $basicAuthAndhasPermissionsRoutes = array();
        $checkAuthRoutes         = array();
        $normalRoutes    = array();
        
        if($package_routes && is_array($package_routes)){
            foreach($package_routes as $route_name =>  $route){
                $url    = $route['url'];
                $action = (isset($route['action']) && $route['action']) ? $route['action'] : 'Smartcms\Core\CoreController@getDefault';
                $login  = (int)((isset($route['login']) && $route['login']) ? $route['login'] : 0);
                $method = strtolower((isset($route['method']) && $route['method']) ? $route['method'] : 'get');
                $permissions = (isset($route['permissions']) && $route['permissions']) ? $route['permissions'] : '';
                
                if($login == 1){
                    if($permissions){
                        $basicAuthAndhasPermissionsRoutes[$route_name] = $route;
                    }else{ 
                        $basicAuthRoutes[$route_name] = $route;
                    }
                }elseif($login == 0) {
                    $normalRoutes[$route_name] = $route;
                }elseif($login == -1){
                    $checkAuthRoutes[$route_name] = $route;
                }
            }        
        }
        
        if($normalRoutes){
            foreach($normalRoutes as $route_name => $route){
                $url    = $route['url'];
                $action = (isset($route['action']) && $route['action']) ? $route['action'] : 'Smartcms\Core\CoreController@getDefault';
                $method = strtolower((isset($route['method']) && $route['method']) ? $route['method'] : 'get');                    
                if(strpos($action, "\\") === false){
                    $action = 'Smartcms\\'.ucfirst($package_name).'\\Controllers\\'.$action;
                }
                Route::$method($url, array(
                        'as' => $route_name,
                        'uses' => $action
                ));
            }
        }
        
        if($checkAuthRoutes){
            Route::group(array('before' => 'notAuth', 'prefix' => Config::get('core::config.uri')), function() use ($checkAuthRoutes,$package_name){
                foreach($checkAuthRoutes as $route_name => $route){
                    $url    = $route['url'];
                    $action = (isset($route['action']) && $route['action']) ? $route['action'] : 'Smartcms\Core\CoreController@getDefault';
                    $method = strtolower((isset($route['method']) && $route['method']) ? $route['method'] : 'get');                    
                    if(strpos($action, "\\") === false){
                        $action = 'Smartcms\\'.ucfirst($package_name).'\\Controllers\\'.$action;
                    }
                    Route::$method($url, array(
                            'as' => $route_name,
                            'uses' => $action
                    ));
                }
            });
        }
        
        if($basicAuthRoutes){
            Route::group(array('before' => 'basicAuth', 'prefix' => Config::get('core::config.uri')), function() use ($basicAuthRoutes,$package_name){
                foreach($basicAuthRoutes as $route_name => $route){
                    $url    = $route['url'];
                    $action = (isset($route['action']) && $route['action']) ? $route['action'] : 'Smartcms\Core\CoreController@getDefault';
                    $method = strtolower((isset($route['method']) && $route['method']) ? $route['method'] : 'get');
                    if(strpos($action, "\\") === false){
                        $action = 'Smartcms\\'.ucfirst($package_name).'\\Controllers\\'.$action;
                    }
                    Route::$method($url, array(
                            'as' => $route_name,
                            'uses' => $action
                    ));
                }
            });
        }
        if($basicAuthAndhasPermissionsRoutes){
            Route::group(array('before' => 'basicAuth|hasPermissions', 'prefix' => Config::get('core::config.uri')), function() use ($basicAuthAndhasPermissionsRoutes,$package_name){
                foreach($basicAuthAndhasPermissionsRoutes as $route_name => $route){
                    $url    = $route['url'];
                    $action = (isset($route['action']) && $route['action']) ? $route['action'] : 'Smartcms\Core\CoreController@getDefault';
                    $method = strtolower((isset($route['method']) && $route['method']) ? $route['method'] : 'get');                
                    if(strpos($action, "\\") === false){
                        $action = 'Smartcms\\'.ucfirst($package_name).'\\Controllers\\'.$action;
                    }
                    Route::$method($url, array(
                            'as' => $route_name,
                            'uses' => $action
                    ));
                }
            });
        }        
    }        
}
