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

class SC_Singleton{
    static private $_singletons = array();
    
    static function get($index, $class = ''){
        if(isset(self::$_singletons[$index]) && is_object(self::$_singletons[$index])){
            return self::$_singletons[$index];
        }
        
        if($class){
            self::$_singletons[$index] = new $class;
        }else{
            self::$_singletons[$index] = new $index;
        }
        
        return self::$_singletons[$index];
    }
    
    static function set($index, $object, $override = false){
        if(!is_object($object)){
            throw new Exception('Invalid object.');
        }
        
        if(!isset(self::$_singletons[$index]) || $override){
            self::$_singletons[$index] = $object;
        }
        
        return self::$_singletons[$index];
    }
}