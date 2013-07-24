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

namespace SimpleCoding\Core;

use SimpleCoding\Http;

class Framework
{
    private static $_request;
    private static $_response;

    public static function init()
    {
        
    }
    
    public static function getResponse()
    {
        if (null == self::$_response) {
            self::$_response = new Http\Response;
        }
        return self::$_response;
    }
    
    public static function getRequest()
    {
        if (null == self::$_request) {
            self::$_request = new Http\Request();
        }
        return self::$_request;
    }
}
