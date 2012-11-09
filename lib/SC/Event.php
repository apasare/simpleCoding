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

class SC_Event{
	private $_hooks;
	
	function add($hook, $event, $params = array()){
		if(!isset($event[2])){
			$event[2] = $params;
        }
        
		$this->_hooks[$hook][] = $event;
	}
	
	function __call($hook, $params){
		if(!isset($this->_hooks[$hook])){
			return;
		}
        
		foreach($this->_hooks[$hook] as $event){
			if(is_array($event) && !method_exists($event[0], $event[1])){
				throw new Exception($event[1]." does not exist");
            }
            
			$_params = $event[2];
			unset($event[2]);
			call_user_func_array($event, $_params);
		}
	}
	
	function remove($hook, $event = null){
		if(isset($this->_hooks[$hook])){
			if(empty($event)){
				unset($this->_hooks[$hook]);
			}else{
				$key = array_search($event, $this->_hooks[$hook]);
				if($key !== false)
					unset($this->_hooks[$hook][$key]);
			}
        }
	}
}
