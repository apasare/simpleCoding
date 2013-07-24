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

namespace SimpleCoding\Http\Response;

use SimpleCoding\Core;

class Headers extends Core\Object
{
    protected function _updateData()
    {
        $this->_data = \headers_list();
        
        return $this;
    }
    
    public function __construct()
    {
        $this->_updateData();
    }

    public function setHeader($name, $data, $replace = true, $httpResponseCode = null)
    {
        header($name . ': ' . $data, $replace, $httpResponseCode);
        
        return $this->_updateData();
    }
    
    public function removeHeader($name)
    {
        header_remove($name);
        
        return $this->_updateData();
    }
}
