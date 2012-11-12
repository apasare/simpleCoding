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

class SC_Exception{
    const CRLF = "\r\n";
    
    private $_params = array();
	
	function __construct(Exception $e){
        $this->_params = array_merge($this->_params, SC_Config::getOption('errors'));
        
		$error = (date('H:i:s')).
            ' - Exception in '.$e->getFile().'('.$e->getLine().'): "'.$e->getMessage().'"'.self::CRLF.
            str_replace("\n", "\r\n", $e->getTraceAsString()).self::CRLF.self::CRLF;
		
		if($this->_params['display_errors']){
			echo nl2br($error);
		}else{
			echo 'An error occured.';
		}
        
        $destination = null;
        $headers = null;
        switch($this->_params['message_type']){
            case 1:
                $destination = $this->_params['mail_to'];
                break;
            case 3:
                $destination = SC_Config::getOption('base_path').SL.
                    $this->_params['repository'].SL.time().rand(0, time());
                break;
        }
        
        SC::log($error, $this->_params['message_type'], $destination, $headers);
	}
}
