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

namespace SimpleCoding;

class Config
{
    const CONFIG_FILE_PATH = 'config.ini';
    const OPTION_PATH_SEPARATOR = '/';

    private static $_config = array();
    private static $_cache = array();
    
    static function init()
    {
        self::$_config = parse_ini_file(self::CONFIG_FILE_PATH, true);
        
        if (!self::getOption('base_path')) {
            self::setOption('base_path', SIMPLECODING_ROOT);
        }
        
        if (!self::getOption('root_folder')) {
            self::setOption(
                'root_folder',
                trim(str_replace(
                    realpath($_SERVER['DOCUMENT_ROOT']), 
                    '', 
                    Config::getOption('base_path')
                ), DS)
            );
        }
    }
    
    static function getOption($path, $cache = true)
    {
        if (isset(self::$_cache[$path]) && $cache) {
            return self::$_cache[$path];
        }
        
        $pieces = explode(self::OPTION_PATH_SEPARATOR, $path);
        
        $piece = array_shift($pieces);
        if (!isset(self::$_config[$piece])) {
            return null;
        }

        $option = self::$_config[$piece];
        
        while ($piece = array_shift($pieces)) {
            if (!isset($option[$piece])) {
                return null;
            }
            $option = $option[$piece];
        }
        
        self::$_cache[$path] = $option;
        
        return $option;
    }
    
    static function setOption($path, $value)
    {
        if (empty($path)) {
            return;
        }
        
        $pieces = explode(self::OPTION_PATH_SEPARATOR, $path);
        
        $piece = array_shift($pieces);
        if (!isset(self::$_config[$piece])) {
            if (count($pieces)) {
                self::$_config[$piece] = array();
            } else {
                self::$_config[$piece] = '';
            }
        }

        $option = &self::$_config[$piece];
        
        while ($piece = array_shift($pieces)) {
            if (!isset($option[$piece])) {
                if (count($pieces)) {
                    $option[$piece] = array();
                } else {
                    $option[$piece] = '';
                }
            }

            $option = &$option[$piece];
        }
        
        $option = $value;
        self::$_cache[$path] = $value;
    }
}
