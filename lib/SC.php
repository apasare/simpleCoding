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
    static function start(){
        SC_Config::init();

        try{
            if(SC_Config::getOption('sessions/repository')){
                $params['save_path'] = SC_Config::getOption('base_path').SL.
                    SC_Config::getOption('sessions/repository');
            }
            $params['lifetime'] = SC_Config::getOption('sessions/lifetime');
            SC_Session::init($params);

            self::prepareModules();

            SC_ParseUri::start();
        }catch(Exception $e){
            new SC_Exception($e);
        }
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
            if($singleton){
                return SC_Singleton::get($class);
            }else{
                return new $class;
            }
        }else{
            throw new Exception('Model \''.$model.'\' does not exist');
        }
    }

    static function log($error, $type = 3, $destination = null, $headers = null){
        if(!is_dir(dirname($destination))){
            SC_Helper::generateFolders(dirname($destination));
        }

        error_log($error, $type, $destination, $headers);
    }

    static function errorToExceptionHandler($errno, $errmsg, $errfile, $errline){
        throw new ErrorException($errmsg, $errno, 0, $errfile, $errline);
    }
}