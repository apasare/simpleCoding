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

class session{
	static function start($name = null){
		global $config;
		
		if(isset($config['folders']['sessions']) && !empty($config['folders']['sessions'])){
			self::generateFolders();
			session_save_path($config['base_path'].$config['folders']['sessions']);
        }
		if(isset($config['session']['lifetime']) && !empty($config['folders']['sessions']))
			session_set_cookie_params($config['session']['lifetime']);
		
		if($name)
			session_name($name);
		if(isset($_COOKIE[session_name()]))
			session_id($_COOKIE[session_name()]);
		
		session_start();
	}
	
	static private function generateFolders(){
		global $config;
		
		$sessions_folder = $config['folders']['sessions'];
		$folder_path = $config['base_path'];
		
		if(!is_dir($folder_path.$sessions_folder)){
			$folders = explode(SL, $sessions_folder);
			foreach($folders as $folder){
				$folder_path .= $folder.SL;
				if(!is_dir($folder_path))
					@mkdir($folder_path, 0755);
			}
		}
	}
	
	static function get($session = null){
		if(!session_id())
			self::start();
		
		if($session === null)
			return $_SESSION;
		else if(isset($_SESSION[$session]))
			return $_SESSION[$session];
		else
			return false;
	}
	
	static function set($session, $value = null){
		if(!session_id())
			self::start();
			
		if($value === null)
			unset($_SESSION[$session]);
		else
			$_SESSION[$session] = $value;
	}
}
