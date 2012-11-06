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

class parseUri{
	private static $full_uri;
	private static $uri;
	private static $workspace;
	private static $module;
	private static $controller;
	private static $trigger;
	
	static function start(){
		global $config;
		
		self::$module = $config['default']['module'];
		self::$controller = $config['default']['controller'];
		self::$trigger = $config['default']['trigger'];
		
		self::stripUri();
		self::parse();
		self::loadController();
	}
	
	private static function stripUri(){
		global $config;
		
		self::$uri = $_SERVER['REQUEST_URI'];
		self::$uri = preg_replace('/(\?)(.*)/', '', self::$uri);
		self::$uri = preg_replace('/^('.BSL.SL.str_replace(array(SL, BSL), BSL.SL, $config['folders']['root']).')/', '', self::$uri);
		self::$uri = str_replace('index.php', '', self::$uri);
		self::$uri = preg_replace('/('.BSL.SL.'){2,}/', SL, self::$uri);
		self::$uri = trim(self::$uri, SL);
		self::$uri = explode(SL, self::$uri);
		self::$full_uri = self::$uri;
		if(strpos(self::$uri[0], '~') === 0)
			array_shift(self::$uri);
	}
	
	private static function parse(){
		global $config, $routes;
		
		foreach($config['folders']['workspaces'] as $workspace){
			$uri = self::$uri;
			self::$workspace = $workspace;
			$controller = array();
			$path = $config['base_path'].$config['folders']['modules'].(($workspace)?SL.$workspace:'');
			
			if(!empty($uri[0]))
				if(isset($routes[$uri[0]]))
					if(is_dir($path.SL.$routes[$uri[0]])){
						self::$module = $routes[$uri[0]];
						array_shift($uri);
					}
				
			$path .= SL.self::$module.SL.$config['folders']['controllers'];
			
			$is_last_dir = false;
			$_path = $path;
			for($i=0; $i<count($uri); $i++){
				if(empty($uri[$i]))
					continue;
				$_path .= SL.$uri[$i];
		
				if(is_dir($_path)){
					$is_last_dir = true;
					$controller[] = $uri[$i];
				}elseif(is_file($_path.$config['extensions']['controller_file'])){
					$is_last_dir = false;
					$controller[] = $uri[$i];
					self::$controller = implode(SL, $controller);
					while($i+1){
						array_shift($uri);
						$i--;
					}
					break;
				}else{
					$is_last_dir = false;
					$controller[] = $config['default']['controller'];
					self::$controller = implode(SL, $controller);
					break;
				}
			}
			foreach($uri as $part)
				if(in_array($part, $controller))
					array_shift($uri);
				else
					break;
			if($is_last_dir){
				$controller[] = $config['default']['controller'];
				self::$controller = implode(SL, $controller);
			}
						
			if(file_exists($path.SL.self::$controller.$config['extensions']['controller_file']))
				break;
		}
		
		self::$uri = $uri;
	}
	
	private static function loadController(){
		global $config;
		
		$class = ((self::$workspace)?self::$workspace.CS:'').self::$module.CS.$config['folders']['controllers'].CS.str_replace(SL, CS, self::$controller);
		$class = new $class;
		
		if(isset(self::$uri[0]) && method_exists($class, self::$uri[0].$config['extensions']['controller_trigger'])){
			self::$trigger = self::$uri[0];
			array_shift(self::$uri);
		}
		
		if(method_exists($class, self::$trigger.$config['extensions']['controller_trigger']))
			$class->{self::$trigger.$config['extensions']['controller_trigger']}();
		else
			throw new exception('Trigger \''.self::$trigger.$config['extensions']['controller_trigger'].'\' does not exist in controller \''.self::$module.SL.$config['folders']['controllers'].SL.self::$controller.'\'');
	}
	
	static function getModule(){
		return self::$module;
	}
	
	static function getController(){
		return self::$controller;
	}
	
	static function getTrigger(){
		return self::$trigger;
	}
	
	static function getUri($index = null){
		if($index)
			if(isset(self::$uri[$index-1]))
				return self::$uri[$index-1];
			else
				return false;
		
		return self::$uri;
	}
	
	static function getFullUri($index = null){
		if($index)
			if(isset(self::$full_uri[$index-1]))
				return self::$full_uri[$index-1];
			else
				return false;
		
		return self::$full_uri;
	}
}
