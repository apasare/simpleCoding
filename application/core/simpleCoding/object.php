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

class simpleCoding_object{
	protected $data;

	function __construct($data = array()){
		$this->set($data);
	}
	
	function __call($func, $args){
		$func = preg_replace('/([A-Z]{1})/', '_$1', $func);
		$func = strtolower($func);
		
		if(preg_match('/^get_/', $func)){
			$func = preg_replace('/^get_/', '', $func);
			if(isset($this->data[$func]))
				return $this->data[$func];
			else
				return false;
		}elseif(preg_match('/^set_/', $func)){
			$func = preg_replace('/^set_/', '', $func);
			if(isset($args[0]))
				$this->set($func, $args[0]);
			else
				unset($this->data[$func]);
		}
		
		return $this;
	}
	
	function get($index = null){
		if($index)
			if(isset($this->data[$index]))
				return $this->data[$index];
			else
				return false;				
		
		return $this->data;
	}
	
	function set($index, $data = ''){
		if(is_array($index))
			foreach($index as $key => $value){
				$key = strtolower($key);
				if(is_array($value))
					$this->data[$key] = new simpleCoding_object($value);
				else
					$this->data[$key] = $value;
			}
		else{
			$index = strtolower($index);
			if(is_array($data))
				$this->data[$index] = new simpleCoding_object($data);
			else
				$this->data[$index] = $data;
		}
		
		return $this;
	}
}
