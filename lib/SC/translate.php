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

class translate{
	private $locale;
	private $translations = array();
	
	function __construct(){
		$this->setLocale();
		$this->addTranslations();
	}
	
	private function setLocale(){
		global $config;
		
		if(!$this->locale = session::get('locale'))
			$this->locale = $config['default']['locale'];
	}
	
	private function addTranslations(){
		global $config;
		
		$file = $config['base_path'].$config['folders']['locale'].SL.$this->locale.SL.parseUri::getModule().$config['extensions']['locale_file'];
		
		if(is_file($file)){
			$file = fopen($file, 'r');
			$data = fgetcsv($file);
			if(count($data) == 2)
				$this->translations[$data[0]] = $data[1];
			fclose($file);
		}
	}
	
	function l10n(){
		$args = func_get_args();
		if(count($args)){
			$msg = $args[0];
			array_shift($args);
			if(!isset($this->translations[$msg]))
				if(!count($args))
					return $msg;
				else
					return vsprintf($msg, $args);				
			else
				if(!count($args))
					return $this->translations[$msg];
				else
					return vsprintf($this->translations[$msg], $args);
		}
	}
}
