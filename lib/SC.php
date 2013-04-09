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

class SC
{
    static function start()
    {
        SC\Config::init();

        try {
            if (SC\Config::getOption('sessions/repository')) {
                $params['save_path'] = SC\Config::getOption('base_path') . DS .
                    SC\Config::getOption('sessions/repository');
            }
            $params['lifetime'] = SC\Config::getOption('sessions/lifetime');
            SC\Session::init($params);

            self::prepareModules();

            SC_ParseUri::start();
        } catch (Exception $e) {
            new SC\Exception($e);
        }
    }

    private static function prepareModules()
    {
        foreach(SC\Config::getOption('modules/workspaces') as $workspace)
        {
            $configFiles = glob(
                MODULES_REPOSITORY . DS . 
                ($workspace ? $workspace . DS : '') .
                '*' . DS . SC\Config::getOption('modules/config_file')
            );
            foreach ($configFiles as $configFile) {
                self::processConfigFile($configFile);
            }
        }
    }

    private static function processConfigFile($path)
    {
        $config = parse_ini_file($path, true);
        
        $module = str_replace(
            array(
                MODULES_REPOSITORY . DS,
                DS . SC\Config::getOption('modules/config_file')
            ), 
            '', 
            $path
        );

        if (isset($config['route'])) {
            SC\Config::setOption('_routes/' . $config['route'], $module);
        }
    }

    static function getModel($model, $singleton = false){
        $pieces = explode(DS, $model);
        $model_exists = false;

        foreach(SC\Config::getOption('modules/workspaces') as $workspace){
            $model_path = SC\Config::getOption('base_path').
                SC\Config::getOption('modules/repository').
                (($workspace)?DS.$workspace:'').DS.
                $pieces[0].DS.
                SC\Config::getOption('modules/models/repository').DS.
                str_replace(CS, DS, $pieces[1]).'.php';
            if(is_file($model_path)){
                $model_exists = true;
                break;
            }
        }

        if($model_exists){
            $class = (($workspace)?$workspace.CS:'').
                $pieces[0].CS.
                str_replace(DS, CS, SC\Config::getOption('modules/models/repository')).
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

    static function log($error, $type = 3, $destination = null, $headers = null)
    {
        if (!is_dir(dirname($destination))) {
            SC\Helper::generateFolders(dirname($destination));
        }

        error_log($error, $type, $destination, $headers);
    }

    static function errorToExceptionHandler($errno, $errmsg, $errfile, $errline)
    {
        throw new ErrorException($errmsg, $errno, 0, $errfile, $errline);
    }
}