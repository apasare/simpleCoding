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

class simpleCoding_default{
	function getModel($model){
		return simpleCoding::getModel($model);
	}
	
	function getSingleton($class){
		return simpleCoding::getSingleton($class);
	}
	
	function getSql(){
		return simpleCoding::getSingleton('sql');
	}
	
	function getRequest(){
		return simpleCoding::getSingleton('request');
	}
	
	function getEvent(){
		return simpleCoding::getSingleton('event');
	}
	
	function getCurrentUrl(){
		return simpleCoding::getCurrentUrl();
	}
	
	function getUrl($uri = null, $get = array()){
		return simpleCoding::getUrl($uri, $get);
	}
	
	function redirect($uri = null, $get = array()){
		simpleCoding::redirect($uri, $get);
	}
	
	function getSession($session = null){
		return session::get($session);
	}
	
	function setSession($session, $value){
		return session::set($session, $value);
	}
	
	function unsetSession($session){
		return session::set($session, null);
	}
	
	function getModule(){
		return parseUri::getModule();
	}
	
	function getController(){
		return parseUri::getController();
	}
	
	function getTrigger(){
		return parseUri::getTrigger();
	}
	
	function getUri($index = null){
		return parseUri::getUri($index);
	}
	
	function getFullUri($index = null){
		return parseUri::getFullUri($index);
	}
	
	function translate(){
		$args = func_get_args();
		return call_user_func_array(array(simpleCoding::getSingleton('translate'), 'l10n'),$args);
	}
}
