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

define('SIMPLECODING_ROOT', getcwd()); // framework's root folder
define('DS', DIRECTORY_SEPARATOR); // directory separator
define('MODULES_REPOSITORY', 'application' . DS . 'modules');

set_include_path(implode(PATH_SEPARATOR, array(
    SIMPLECODING_ROOT . DS . MODULES_REPOSITORY,
    SIMPLECODING_ROOT . DS . 'lib',
    SIMPLECODING_ROOT . DS . 'config'
)));

spl_autoload_register();

set_error_handler('SC::errorToExceptionHandler', E_ALL);