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

class request{
	function isPost(){
		return $_SERVER['REQUEST_METHOD']=='POST';
	}
	
	function isGet(){
		return $_SERVER['REQUEST_METHOD']=='GET';		
	}
	
	function get($global_var, $key){
		if($key)
			if(isset($global_var[$key]))
				if(is_array($global_var[$key]))
					return new simpleCoding_object($global_var[$key]);
				else
					return $global_var[$key];
			else
				return false;
		
		return new simpleCoding_object($global_var);
	}
	
	function getRequest($key = '', $params = array()){
		$data = $this->get($_REQUEST, $key);
		
		return $data;
	}
	
	function getPost($key = '', $params = array()){
		$data = $this->get($_POST, $key);
		
		return $data;
	}
	
	function getGet($key = '', $params = array()){
		$data = $this->get($_GET, $key);
		
		return $data;
	}
}
