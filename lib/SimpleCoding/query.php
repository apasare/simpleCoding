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

class query{
    public $statement;
    
    function __construct($pdo, $query, $params){
    	if(!($pdo instanceof PDO))
    		return;
   		
		$this->statement = $pdo->prepare($query);
		$this->statement->execute($params);
		
		if($this->statement->errorCode() != '00000'){
			$error = $this->statement->errorInfo();
			throw new exception('SQLSTATE '.$error[0].(isset($error[2])?': '.$error[2]:''));
		}
		
		return $this;		
    }
	
	function getRows($fetch_style = PDO::FETCH_OBJ){
		if($this->statement instanceof PDOStatement)
			return $this->statement->fetchAll($fetch_style);
	}
	
	function getRow($fetch_style = PDO::FETCH_OBJ){
		if($this->statement instanceof PDOStatement)
			return $this->statement->fetch($fetch_style);
	}
	
	function getAffectedRows(){
		if($this->statement instanceof PDOStatement)
			return $this->statement->rowCount();
	}
}
