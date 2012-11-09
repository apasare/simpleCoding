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

define('SL', '/'); // slash
define('BSL', '\\'); // backslash
define('CS', '_'); // class name separator

set_include_path(implode(PATH_SEPARATOR, array(
    SIMPLECODING_ROOT.SL.'application'.SL.'modules',
    SIMPLECODING_ROOT.SL.'lib',
    SIMPLECODING_ROOT.SL.'config'
)));

function __autoload($class_name){
    $path = str_replace(CS, SL, $class_name).'.php';
    
    require_once $path;
}

set_error_handler('SC::errorToExceptionHandler', E_ALL);