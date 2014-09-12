<?php 
namespace Smartosc\Core\Services\Core;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Config;


class CoreService{
    public static function getSmartOscPackages(){ 
//        $smartosc_modules = Session::get('smartosc_modules');
//        if($smartosc_modules){
//            return $smartosc_modules;
//        }else{
            $smartosc_workbench_path = base_path().'/workbench/smartosc';
            if(file_exists($smartosc_workbench_path)){
                $smartosc_packages = glob($smartosc_workbench_path.'/*',GLOB_ONLYDIR);            
                if(count($smartosc_packages)){                
                    foreach($smartosc_packages as $package_path){
                        $path_info    = pathinfo($package_path);
                        $package_name = $path_info['basename'];
                        $packages[]   = $package_name;
                    }
                }
            }
            Session::set("smartosc_modules",$packages);
            return $packages;
//        }
    }
    public static function getAllPermissions(){
        $persmissions = array();
        $packages = static::getSmartOscPackages();
        if(count($packages)){
            foreach($packages as $package_name){
                $p_persmissions = Config::get($package_name."::permissions");
                $persmissions[$package_name] = $p_persmissions;
            }
        }
        return $persmissions;
    }
    public static function getPermissionWithRoute($route_name){
        $smartosc_packages = static::getSmartOscPackages();
        if(count($smartosc_packages)){
            foreach($smartosc_packages as $package_name){
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