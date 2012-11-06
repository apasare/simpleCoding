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

class log{
	private $logs_folder;
	private $display_errors = false;
	
	function __construct(){
		global $config;
		
		$this->logs_folder = trim($config['folders']['logs'], SL);
		$this->display_errors = $config['logs']['display_errors'];
		
		if(!is_dir($config['base_path'].$this->logs_folder))
			$this->generateFolders();
	}
	
	private function generateFolders(){
		global $config;
		
		$folders = explode(SL, $this->logs_folder);
		$folder_path = $config['base_path'];
		foreach($folders as $folder){
			$folder_path .= $folder.SL;
			if(!is_dir($folder_path))
				mkdir($folder_path, 0755);
		}
	}
	
	function addError($e){
		global $config;
		
		$error = (date('H:i:s'))." - Exception in {$e->getFile()}({$e->getLine()}): \"{$e->getMessage()}\"\r\n".str_replace("\n", "\r\n", $e->getTraceAsString())."\r\n\r\n";
		
		if($this->display_errors)
			echo nl2br($error);
		else
			echo 'An error occured.';
				
		if($config['logs']['message_type'] == 3){
			$file_path = $config['base_path'].$this->logs_folder.SL.time().rand(0, time());
			error_log($error, $config['logs']['message_type'], $file_path);
		}elseif($config['logs']['message_type'] == 1)
			error_log($error, $config['logs']['message_type'], $config['logs']['mail_to']);
	}
}
