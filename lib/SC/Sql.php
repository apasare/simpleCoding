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

class SC_Sql{
    private $connected = false;
    
	public $pdo;
	
	function __construct($connect_data = array()){
        if(count($connect_data)){
    		$this->connect($connect_data);
        }
	}
	
	function connect($connect_data, $driver_options = array()){
		$dsn = $connect_data['driver'].':host='.$connect_data['host'].';dbname='.$connect_data['database'];
		$this->pdo = new PDO($dsn, $connect_data['username'], $connect_data['password'], $driver_options);
        $this->connected = true;
        
        return $this;
	}
	
	function startTransaction(){
		$this->pdo->beginTransaction();
		
		return $this;
	}
	
	function query($query, $params = array()){
		return new query($this->pdo, $query, $params);
	}
	
	function endTransaction(){
		$this->pdo->commit();
		
		return $this;
	}
	
	function insert($table, $data){
		$query = 'INSERT INTO `'.$table.'`';
		
	    $params = array();
		foreach($data as $column => $value){
			$columns[] = '`'.$column.'`';
			$values[] = '?';
	        $params[] = $value;
		}
		
		if(isset($columns))
			$query .= '('.implode(', ', $columns).') VALUES('.implode(', ', $values).')';
		else
			$query .= '() VALUES()';
		
		return $this->query($query, $params);
	}
	
	function update($table, $data = array(), $where = '', $conditions = array()){
		$query = 'UPDATE `'.$table.'` SET ';
		
	    $params = array();
		foreach($data as $column => $value){
			$sets[] = '`'.$column.'` = ?';
	        $params[] = $value;
		}
	    $params = array_merge($params, $conditions);
	    
		$query .= implode(', ', $sets);
		if(!empty($where))
			$query .= ' WHERE '.$where;
		
		return $this->query($query, $params);
	}
	
	function getLastInsertId($name = ''){
		return $this->pdo->lastInsertId($name);
	}
    
    function isConnected(){
        return $this->connected;
    }
	
	function close(){
		$this->pdo = null;
	}
	
	function __destruct(){
		$this->close();
	}
}
