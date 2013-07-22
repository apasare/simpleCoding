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

class Helper
{
    static function getCurrentUrl($get = array())
    {
        $host = $_SERVER['HTTP_HOST'];
        $protocol = 'http';
        $get = array_merge($_GET, $get);
        
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
            $protocol = 'https';
        }
        
        $url = $protocol.':'.DS.DS.$host.$_SERVER['REQUEST_URI'];
        if(count($get)){
            foreach($get as $key => $value){
                if($value !== null){
                    $params[] = $key.'='.$value;
                }
            }
            
            if(isset($params)){
                $url .= '?'.implode('&', $params);
            }
        }
        
        return $url;
    }
    
    static function getUrl($uri = null, $get = array()){
        $host = $_SERVER['HTTP_HOST'];
        $folder = trim(SC_Config::getOption('root_folder'), DS);
        $protocol = 'http';
        $get = array_merge($_GET, $get);
        $full_uri = parseUri::getFullUri();
        
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'){
            $protocol = 'https';
        }
        
        $url = $protocol.':'.DS.DS.$host.DS;
        if(strpos($full_uri[0], '~') === 0){
            $url .= $full_uri[0].DS;
            array_shift($full_uri);
        }
        
        if(!SC_Config::getOption('url_rewrite')){
            $url .= 'index.php'.DS;
        }
        
        if($folder){
            $url .= $folder.DS;
        }
        
        if($uri){
            $uri = trim($uri, DS);
            for($i=0; strpos($uri, '*') !== false && isset($full_uri[$i]); $i++)
                $uri = preg_replace('/\*/', $full_uri[$i], $uri, 1);
            $url .= $uri;
        }
        
        if(count($get)){
            foreach($get as $key => $value){
                if($value !== null){
                    $params[] = $key.'='.$value;
                }
            }
            
            if(isset($params)){
                $url .= '?'.implode('&', $params);
            }
        }
        
        return $url;
    }
    
    static function redirect($uri = null, $get = array()){
        header('Location:'.self::getUrl($uri, $get));
        die;
    }
    
    static function redirectUrl($url){
        header('Location:'.$url);
        die;
    }
    
    static function generateFolders($absolute_path)
    {
        if (!is_dir($absolute_path)) {
            @mkdir($absolute_path, 0755, true);
        }
    }
}