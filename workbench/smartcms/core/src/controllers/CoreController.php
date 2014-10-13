<?php

namespace Smartcms\Core\Controllers;

use Smartcms\Core\Controllers\BaseController;
use Smartcms\Core\Services\Validators\User as UserValidator;
use View;
use Input;
use Sentry;
use Redirect;
use Config;
use Response;
use Image;

class CoreController extends BaseController{
    public function getDefault(){
        
    }     
    
    // 100x100, x100, 100x
    public function getImage($module = 'core', $src = null , $size = null){
        if(!$size) $size = 'x';
        
        $arr_size  = explode('x',$size);
        
        $width     = (int) $arr_size[0];
        $height    = (int) $arr_size[1];
        
        if(!$width) $width = null;
        if(!$height) $height = null;
        
                
        $cache_image = Image::cache(function($image) use($module, $src, $width, $height){
            if($src){
                $real_src = 'images/' . strtolower($module).'/' . strtolower($src);
                $real_src_full_path = public_path() . '/'. $real_src;

                if(!file_exists($real_src_full_path) || !is_file($real_src_full_path)){
                    $real_src = 'images/core/default.jpg';
                }
            }else{
                $real_src = 'images/core/default.jpg';
            }
            if(empty($width)&& empty($height)){
                return $image->make('public/'. $real_src);
            }else{
                return $image->make('public/'. $real_src)->resize($width,$height, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
        }, 10 , false);
        return Response::make($cache_image, 200, array('Content-Type' => 'image/jpeg'));
    }
}