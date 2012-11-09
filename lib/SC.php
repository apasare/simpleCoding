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

class SC{
    private static $_singletons = array();

    static function start(){
        SC_Config::init();

        try{
            self::prepareModules();
            SC_Session::start();
            SC_ParseUri::start();
        }catch(Exception $e){
            new SC_Exception($e);
        }
    }
    
    static function errorToExceptionHandler($errno, $errmsg, $errfile, $errline){
        throw new ErrorException($errmsg, $errno, 0, $errfile, $errline);
    }

    private static function prepareModules(){
        foreach(SC_Config::getOption('modules/workspaces') as $workspace){
            $path = SC_Config::getOption('base_path').SL.
                SC_Config::getOption('modules/repository').
                ($workspace?SL.$workspace:'');

            $dh = opendir($path);
            while($dir = readdir($dh)){
                if($dir == '.' || $dir == '..'){
                    continue;
                }

                $config_file = $path.SL.$dir.SL.SC_Config::getOption('modules/config_file');
                if(is_file($config_file)){
                    self::processConfigFile($config_file, $dir);
                }
            }
            closedir($dh);
        }
    }

    private static function processConfigFile($path, $module){
        $config = json_decode(file_get_contents($path), true);

        if(isset($config['route'])){
            SC_Config::setOption('_routes/'.$config['route'], $module);
        }
    }

    static function getModel($model, $singleton = false){
        $pieces = explode(SL, $model);
        $model_exists = false;

        foreach(SC_Config::getOption('modules/workspaces') as $workspace){
            $model_path = SC_Config::getOption('base_path').
                SC_Config::getOption('modules/repository').
                (($workspace)?SL.$workspace:'').SL.
                $pieces[0].SL.
                SC_Config::getOption('modules/models/repository').SL.
                str_replace(CS, SL, $pieces[1]).'.php';
            if(is_file($model_path)){
                $model_exists = true;
                break;
            }
        }

        if($model_exists){
            $class = (($workspace)?$workspace.CS:'').
                $pieces[0].CS.
                str_replace(SL, CS, SC_Config::getOption('modules/models/repository')).
                CS.$pieces[1];
            if($singleton)
                return self::getSingleton($class);
            else
                return new $class;
        }else
            throw new Exception('Model \''.$model.'\' does not exist');
    }

    static function getSingleton($class_name){
        if(!isset(self::$_singletons[$class_name]))
            self::$_singletons[$class_name] = new $class_name;

        return self::$_singletons[$class_name];
    }

    static function getCurrentUrl($get = array()){
        $host = $_SERVER['HTTP_HOST'];
        $protocol = 'http';
        $get = array_merge($_GET, $get);

        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'){
            $protocol = 'https';
        }

        $url = $protocol.':'.SL.SL.$host.$_SERVER['REQUEST_URI'];
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
        $folder = trim(SC_Config::getOption('root_folder'), SL);
        $protocol = 'http';
        $get = array_merge($_GET, $get);
        $full_uri = parseUri::getFullUri();

        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'){
            $protocol = 'https';
        }

        $url = $protocol.':'.SL.SL.$host.SL;
        if(strpos($full_uri[0], '~') === 0){
            $url .= $full_uri[0].SL;
            array_shift($full_uri);
        }

        if(!SC_Config::getOption('url_rewrite')){
            $url .= 'index.php'.SL;
        }

        if($folder){
            $url .= $folder.SL;
        }

        if($uri){
            $uri = trim($uri, SL);
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
}