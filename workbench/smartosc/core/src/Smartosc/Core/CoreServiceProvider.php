<?php 

namespace Smartosc\Core;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Environment;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Smartosc\Core\Facades\PermissionProvider;

class CoreServiceProvider extends ServiceProvider
{

    /**
    * Indicates if loading of the provider is deferred.
    *
    * @var bool
    */
    protected $defer = false;

    public function boot() 
    {
        $this->package('smartosc/core');
        $this->loadIncludes();        
    }

    /**
    * Register the service provider.
    *
    * @return void
    */
    public function register()
    {
        $this->autoRegisterServiceProviderSmartOscPackages();
        
        // load package config
        $this->app['config']->package('smartosc/core', __DIR__.'/../../config');

        // add the user seed command to the application
        $this->app['create:user'] = $this->app->share(function($app)
        {
            return new Commands\UserSeedCommand($app);
        });

        // add the install command to the application
        $this->app['core:install'] = $this->app->share(function($app)
        {
            return new Commands\InstallCommand($app);
        });

        // add the update command to the application
        $this->app['core:update'] = $this->app->share(function($app)
        {
            return new Commands\UpdateCommand($app);
        });
        
        // register helpers
        $this->registerHelpers();

        // register models
        $this->registerModels();
        
        // add commands
        $this->commands('create:user');
        $this->commands('core:install');
        $this->commands('core:update');        
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

    /**
     * Include some specific files from the src-root.
     *
     * @return void
     */
    private function loadIncludes()
    {
        // Add file names without the `php` extension to this list as needed.
        $filesToLoad = array(
            'composers',
            'filters',
            'routes',
        );

        // Run through $filesToLoad array.
        foreach ($filesToLoad as $file) {
            // Add needed database structure and file extension.
            $file = __DIR__ . '/../../' . $file . '.php';
            // If file exists, include.
            if (is_file($file)) include $file;
        }        
    }

    /**
    * Register helpers in app
    */
    public function registerHelpers()
    {
        // register breadcrumbs
        $this->app['breadcrumbs'] = $this->app->share(function()
        {
            return new \Smartosc\Core\Helpers\Breadcrumbs();
        });
        
        $this->app['permissionselect'] = $this->app->share(function(){
            return new \Smartosc\Core\Helpers\PermissionSelect();
        });
        
        // shortcut so developers don't need to add an Alias in app/config/app.php
        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Breadcrumbs', 'Smartosc\Core\Facades\Breadcrumbs');
            $loader->alias('PermissionSelect', 'Smartosc\Core\Facades\PermissionSelect');
        });
    }

    public function registerModels()
    {
        // register permission provider
        $this->app['permissionProvider'] = $this->app->share(function()
        {
            return new \Smartosc\Core\Models\Permissions\PermissionProvider();
        });
        
        // add permission provider to aliases
        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('PermissionProvider', 'Smartosc\Core\Facades\PermissionProvider');
        });
    }
    
    /**
     * Cong Ngo : 2014-06-20
     */
    // auto register service provider with smartosc workbench
    private function autoRegisterServiceProviderSmartOscPackages(){
        $smartosc_workbench_path = base_path().'/workbench/smartosc';
        $smartosc_packages = $this->getSmartOscPackages();
        if(count($smartosc_packages)){
            foreach($smartosc_packages as $package_name){
                if(strtolower($package_name) != 'core'){
                    $file_package_service_provider = ucfirst($package_name).'ServiceProvider.php';                       
                    $file_package_service_provider_path = $smartosc_workbench_path.'/'.$package_name.'/src/Smartosc/'.  ucfirst($package_name) .'/'.$file_package_service_provider;                        
                    if(file_exists($file_package_service_provider_path)){
                        $this->app->register('\Smartosc\\'.  ucfirst($package_name).'\\'.  ucfirst($package_name).'ServiceProvider');
                    }
                }
            }
        }
    }
    /**
     * Cong Ngo : 2014-06-20
     */
    public function getSmartOscPackages(){ 
        return Services\Core\CoreService::getSmartOscPackages();        
//        $smartosc_modules = Session::get('smartosc_modules');
//        if($smartosc_modules){
//            return $smartosc_modules;
//        }else{
//            $smartosc_workbench_path = base_path().'/workbench/smartosc';
//            if(file_exists($smartosc_workbench_path)){
//                $smartosc_packages = glob($smartosc_workbench_path.'/*',GLOB_ONLYDIR);            
//                if(count($smartosc_packages)){                
//                    foreach($smartosc_packages as $package_path){
//                        $path_info    = pathinfo($package_path);
//                        $package_name = $path_info['basename'];
//                        $packages[]   = $package_name;
//                    }
//                }
//            }
//            Session::set("smartosc_modules",$packages);
//            return $packages;
//        }
    }        
}
