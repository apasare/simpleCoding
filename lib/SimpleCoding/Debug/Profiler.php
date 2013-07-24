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

namespace SimpleCoding\Debug;

class Profiler
{
    const CRLF = "\r\n";

    protected static function output($fragment, $output, $mode)
    {
        file_put_contents(self::getFilename($fragment), $output, $mode);
    }
    
    protected static function prepareOutput($data)
    {
        $output  = 'time: ' . $data['time'] . ' seconds' . self::CRLF;
        $output .= 'memory: ' . $data['memory'] . ' bytes' . self::CRLF;
        $output .= 'memory peak: ' . $data['memory_peak'] . ' bytes' . self::CRLF;
        $output .= 'real memory: ' . $data['real_memory'] . ' bytes' . self::CRLF;
        $output .= 'real memory peak: ' . $data['real_memory_peak'] . ' bytes' . self::CRLF;
        $output .= self::CRLF;
        
        return $output;
    }
    
    public static function getFilename($fragment)
    {
        return SIMPLECODING_ROOT . DS . 'temp' . DS . hash('md4', $fragment) . '.profile';
    }
    
    public static function getProfileData()
    {
        return array(
            'time' => microtime(true),
            'memory' => memory_get_usage(),
            'real_memory' => memory_get_usage(true),
            'memory_peak' => memory_get_peak_usage(),
            'real_memory_peak' => memory_get_peak_usage(true)
        );
    }
    
    public static function start($fragment)
    {
        $data = self::getProfileData();
        
        $output  = 'Start: ' . $fragment . self::CRLF;
        $output .= self::prepareOutput($data);
        
        self::output($fragment, $output, LOCK_EX);
        
        register_tick_function('SimpleCoding\Debug\Profiler::tick', $fragment);
    }
    
    public static function tick($fragment)
    {
        $data = self::getProfileData();
        
        $output  = 'Tick: ' . $fragment . self::CRLF;
        $output .= self::prepareOutput($data);
        
        self::output($fragment, $output, LOCK_EX|FILE_APPEND);
    }
    
    public static function stop($fragment)
    {
        unregister_tick_function('SimpleCoding\Debug\Profiler::tick');
        
        $data = self::getProfileData();
        
        $output  = 'End: ' . $fragment . self::CRLF;
        $output .= self::prepareOutput($data);
        
        self::output($fragment, $output, LOCK_EX|FILE_APPEND);
    }
}
