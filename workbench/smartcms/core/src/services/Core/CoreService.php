<?php 
namespace Smartcms\Core\Services\Core;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Config;


class CoreService{
    public static function getSmartCmsPackages(){ 
//        $smartcms_modules = Session::get('smartcms_modules');
//        if($smartcms_modules){
//            return $smartcms_modules;
//        }else{
            $smartcms_workbench_path = base_path().'/workbench/smartcms';
            if(file_exists($smartcms_workbench_path)){
                $smartcms_packages = glob($smartcms_workbench_path.'/*',GLOB_ONLYDIR);            
                if(count($smartcms_packages)){                
                    foreach($smartcms_packages as $package_path){
                        $path_info    = pathinfo($package_path);
                        $package_name = $path_info['basename'];
                        $packages[]   = $package_name;
                    }
                }
            }
            Session::set("smartcms_modules",$packages);
            return $packages;
//        }
    }
    public static function getAllPermissions(){
        $persmissions = array();
        $packages = static::getSmartCmsPackages();
        if(count($packages)){
            foreach($packages as $package_name){
                $p_persmissions = Config::get($package_name."::permissions");
                $persmissions[$package_name] = $p_persmissions;
            }
        }
        return $persmissions;
    }
    public static function getPermissionWithRoute($route_name){
        $smartcms_packages = static::getSmartCmsPackages();
        if(count($smartcms_packages)){
            foreach($smartcms_packages as $package_name){
                $routes = Config::get($package_name.'::routes');
                if($routes && isset($routes[$route_name])){
                    if(isset($routes[$route_name]['permissions']))
                        return $routes[$route_name]['permissions'];
                }
            }
        }else return null;
    }
    public static function getLastQuery(){
        $queries = \DB::getQueryLog();
         $sql = end($queries);

         if( ! empty($sql['bindings']))
         {
           $pdo = \DB::getPdo();
           foreach($sql['bindings'] as $binding)
           {
             $sql['query'] =
               preg_replace('/\?/', $pdo->quote($binding),
                 $sql['query'], 1);
           }
         }         
        return $sql['query'];
    }
}