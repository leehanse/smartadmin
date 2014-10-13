<?php namespace Core\Services;
use \Illuminate\Support\Facades\Input;
class FilterData{
    /**
     * 
     * @param type $arr : all fields to handle origin data
     */
    public static function removeHtmlExclude($arr_exclude = array()){
        $data_new = array();
        $data     = Input::all();
        foreach($data as $key => $item){
            if(!in_array($key,$arr_exclude)){
                if(!is_array($item)){
                    $data_new[$key] = htmlspecialchars($item);
                }else{

                }
            }
        }        
        Input::merge($data_new);
    }
}