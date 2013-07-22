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

namespace SC;

class Session
{
    static function init($params, $name = null)
    {
        if (isset($params['save_path'])) {
            if (!is_dir($params['save_path'])) {
                SC\Helper::generateFolders($params['save_path']);
            }
            
            session_save_path($params['save_path']);
        }
        
        if ($params['lifetime']) {
            ini_set('session.gc_maxlifetime', $params['lifetime']);
            session_set_cookie_params($params['lifetime']);
        }
        
        self::start($name);
    }
    
    static function start($name = null)
    {
        if ($name) {
            session_name($name);
        }
        
        if (isset($_COOKIE[session_name()])) {
            session_id($_COOKIE[session_name()]);
        }
        
        session_start();
    }

    static function get($session = null)
    {
        if (!session_id()) {
            self::start();
        }
        
        if ($session === null) {
            return $_SESSION;
        } else if (isset($_SESSION[$session])) {
            return $_SESSION[$session];
        } else {
            return null;
        }
    }

    static function set($session, $value = null)
    {
        if (!session_id()) {
            self::start();
        }
        
        if ($value === null) {
            unset($_SESSION[$session]);
        } else {
            $_SESSION[$session] = $value;
        }
    }
}