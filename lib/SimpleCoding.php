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

class SimpleCoding{
	private static $singletons = array();
	
	static function start(){
		try{
            SimpleCoding_Config::init();
//			self::prepareModules();
//			session::start();
//			parseUri::start();
		}catch(exception $e){
//			$log = new log;
//			$log->addError($e);
		}
	}
	
	private static function prepareModules(){
		global $config;
		
		foreach($config['folders']['workspaces'] as $workspace){
			$path = $config['base_path'].$config['folders']['modules'].SL.$workspace;
			
			$dh = opendir($path);
			while($dir = readdir($dh)){
				$config_file = $path.SL.$dir.SL.$config['modules']['config_file'];
				if($dir != '.' && $dir != '..' && is_file($config_file))
					self::processesConfigFile($config_file, $dir);
			}
			closedir($dh);
		}
	}
	
	private static function processesConfigFile($path, $module){
		global $routes;
		
		$config = simplexml_load_file($path);
		
		if($route = (string)$config->route)
			if(!isset($routes[$route]))
				$routes[$route] = $module;
	}
	
	static function getModel($model, $singleton = false){
		global $config;
		
		$pieces = explode('/', $model);
		$model_exists = false;
				
		foreach($config['folders']['workspaces'] as $workspace){
			$model_path = $config['base_path'].$config['folders']['modules'].(($workspace)?SL.$workspace:'').SL.$pieces[0].SL.$config['folders']['models'].SL.str_replace(CS, SL, $pieces[1]).$config['extensions']['model_file'];
			if(is_file($model_path)){
				$model_exists = true;
				break;
			}
		}
		
		if($model_exists){
			$class = (($workspace)?$workspace.CS:'').$pieces[0].CS.$config['folders']['models'].CS.$pieces[1];
			if($singleton)
				return self::getSingleton($class);
			else
				return new $class;
		}else
			throw new exception('Model \''.$model.'\' does not exist');
	}
	
	static function getSingleton($class_name){
		if(!isset(self::$singletons[$class_name]))
			self::$singletons[$class_name] = new $class_name;
		
		return self::$singletons[$class_name];
	}
	
	static function getCurrentUrl($get = array()){
		$host = $_SERVER['HTTP_HOST'];
		$protocol = 'http';
		$get = array_merge($_GET, $get);
		
		if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off')
			$protocol = 'https';
		
		$url = $protocol.':'.SL.SL.$host.$_SERVER['REQUEST_URI'];
		if(count($get)){
			foreach($get as $key => $value)
				if($value !== null)
					$params[] = $key.'='.$value;
			if(isset($params))
				$url .= '?'.implode('&', $params);
		}
		
		return $url;
	}
	
	static function getUrl($uri = null, $get = array()){
		global $config;
		
		$host = $_SERVER['HTTP_HOST'];
		$folder = trim($config['folders']['root'], SL);
		$protocol = 'http';
		$get = array_merge($_GET, $get);
		$full_uri = parseUri::getFullUri();
		
		if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off')
			$protocol = 'https';
			
		$url = $protocol.':'.SL.SL.$host.SL;
		if(strpos($full_uri[0], '~') === 0){
			$url .= $full_uri[0].SL;
			array_shift($full_uri);
		}
		if(!$config['url_rewrite'])
			$url .= 'index.php'.SL;
		if($folder)
			$url .= $folder.SL;
		if($uri){
			$uri = trim($uri, SL);
			for($i=0; strpos($uri, '*') !== false && isset($full_uri[$i]); $i++)
				$uri = preg_replace('/\*/', $full_uri[$i], $uri, 1);
			$url .= $uri;
		}
		if(count($get)){
			foreach($get as $key => $value)
				if($value !== null)
					$params[] = $key.'='.$value;
			if(isset($params))
				$url .= '?'.implode('&', $params);
		}
		
		return $url;
	}
	
	static function redirect($uri = null, $get = array()){
		header('Location:'.self::getUrl($uri, $get));
		die;
	}
}
