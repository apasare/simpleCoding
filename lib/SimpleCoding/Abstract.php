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

class SC_Abstract{
	function getModel($model){
		return SC::getModel($model);
	}
	
	function getSingleton($class){
		return SC_Singleton::get($class);
	}
	
	function getSql($bypass_connection = false){
		$sql = $this->getSingleton('SC_Sql');
        
        if(!$sql->isConnected() || $bypass_connection){
            $sql->connect(SC_Config::getOption('sql/default'));
        }
        
        return $sql;
	}
	
	function getRequest(){
		return $this->getSingleton('SC_Request');
	}
	
	function getEvent(){
		return $this->getSingleton('SC_Event');
	}
	
	function getCurrentUrl(){
		return SC_Helper::getCurrentUrl();
	}
	
	function getUrl($uri = null, $get = array()){
		return SC_Helper::getUrl($uri, $get);
	}
	
	function redirect($uri = null, $get = array()){
		SC_Helper::redirect($uri, $get);
	}
    
    function redirectUrl($url){
        SC_Helper:redirectUrl($url);
    }
	
	function getSession($session = null){
		return SC_Session::get($session);
	}
	
	function setSession($session, $value){
		return SC_Session::set($session, $value);
	}
	
	function unsetSession($session){
		return SC_Session::set($session, null);
	}
	
	function getModule(){
		return SC_ParseUri::getModule();
	}
	
	function getController(){
		return SC_ParseUri::getController();
	}
	
	function getTrigger(){
		return SC_ParseUri::getTrigger();
	}
	
	function getUri($index = null){
		return SC_ParseUri::getUri($index);
	}
	
	function getFullUri($index = null){
		return SC_ParseUri::getFullUri($index);
	}
}
