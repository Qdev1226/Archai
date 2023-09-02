<?php
namespace YTP\Helper;

class Utils{

    protected static $settings = null;

    /**
     * get single value from option table
     */
    public static function getOption($option_name, $default = false, $boolean = false){
        $option = get_option($option_name);
        $result = '';
            if($option != ''){
                $result = $option;
            } else {
                $result = $default;
            }
        if($boolean){
            return (boolean) $result;
        }
        return $result ;
    }

    /**
     * get array value from option table
     */
    public static function getOptionDeep($option_name, $key, $default = false, $boolean = false){
        $option = get_option($option_name);
        if (isset($option[$key]) && $option[$key] != '') {
            $result =  $option[$key] ;
        }else {
            $result = $default;
        }

        if($boolean){
            return (boolean) $result;
        }
        return $result;
    }

    /**
     * trim extra line and Tab
     */
    public static function trim($string){
        $string = preg_replace('/\s+/i', 'whiteSpace', $string);
        $string = preg_replace('/whiteSpace/i', ' ', $string);
        return $string;
    }

    public static function isset($array, $key, $default=false){
        if(isset($array[$key]) && $array[$key] != ''){
            return $array[$key];
        }
        return $default;
    }

}