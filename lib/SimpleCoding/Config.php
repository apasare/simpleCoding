<?php

class SimpleCoding_Config{
    private static $_config = array();
    
    static function init(){
        $json = file_get_contents(SIMPLECODING_ROOT.SL.'config'.SL.'config.json');
        
        self::$_config = json_decode($json, true);
    }
}