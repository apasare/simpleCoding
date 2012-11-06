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

define('SL', '/');
define('BSL', '\\');
define('CS', '_');

require_once 'config.php';
require_once 'sql.php';

if(!ini_get('date.timezone'))
	ini_set('date.timezone', $config['timezone']);

set_include_path(implode(PATH_SEPARATOR, array(
	$config['base_path'].$config['folders']['extends'],
	$config['base_path'].$config['folders']['core'],
	$config['base_path'].$config['folders']['modules']
)));

function __autoload($class_name){
	global $config;
	$path = str_replace(CS, SL, $class_name).$config['extensions']['script_file'];
	require_once $path;
}

function error_to_exception_handler($errno, $errmsg, $errfile, $errline){
	throw new ErrorException($errmsg, 0, $errno, $errfile, $errline);
}

set_error_handler('error_to_exception_handler');
