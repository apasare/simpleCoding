<?php

/**
 * @copyright 2011 simpleCoding
 * This file is part of simpleCoding.
 * 
 * simpleCoding is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * simpleCoding is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with simpleCoding.  If not, see <http://www.gnu.org/licenses/>.
 */

class SC_Config{
    private static $_config = array();
    private static $_cache = array();
    
    static function init(){
        $json = file_get_contents(SIMPLECODING_ROOT.SL.'config'.SL.'config.json');
        
        self::$_config = json_decode($json, true);
        
        if(!self::getOption('base_path')){
            self::setOption('base_path', SIMPLECODING_ROOT);
        }
        if(!self::getOption('root_folder')){
            self::setOption(
                'root_folder',
                trim(str_replace(
                    realpath($_SERVER['DOCUMENT_ROOT']), 
                    '', 
                    SC_Config::getOption('base_path')
                ), BSL.SL)
            );
        }
    }
    
    static function getOption($path, $cache = true){
        if(isset(self::$_cache[$path]) && $cache){
            return self::$_cache[$path];
        }
        
        $pieces = explode(SL, $path);
        
        $piece = array_shift($pieces);
        if(!isset(self::$_config[$piece])){
            return null;
        }

        $option = self::$_config[$piece];
        
        while($piece = array_shift($pieces)){
            if(!isset($option[$piece])){
                return null;
            }
            $option = $option[$piece];
        }
        
        self::$_cache[$path] = $option;
        
        return $option;
    }
    
    static function setOption($path, $value){
        if(empty($path)){
            return;
        }
        
        $pieces = explode(SL, $path);
        
        $piece = array_shift($pieces);
        if(!isset(self::$_config[$piece])){
            if(count($pieces)){
                self::$_config[$piece] = array();
            }else{
                self::$_config[$piece] = '';
            }
        }

        $option =& self::$_config[$piece];
        
        while($piece = array_shift($pieces)){
            if(!isset($option[$piece])){
                if(count($pieces)){
                    $option[$piece] = array();
                }else{
                    $option[$piece] = '';
                }
            }

            $option =& $option[$piece];
        }
        
        $option = $value;
        self::$_cache[$path] = $value;
    }
}