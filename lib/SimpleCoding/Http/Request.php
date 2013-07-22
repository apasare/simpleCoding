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

namespace SimpleCoding\Http;

use SimpleCoding\Http\Request;

class Request
{
    private $_headers = null;
    private $_post = null;
    private $_get = null;
    
    public function getHeaders()
    {
        if (null == $this->_headers) {
            $this->_headers = new Request\Headers();
        }
        
        return $this->_headers;
    }
    
    public function getPost()
    {
        if (null == $this->_post) {
            $this->_post = new Request\Post();
        }

        return $this->_post;
    }
    
    public function getGet()
    {
        if (null == $this->_get) {
            $this->_get = new Request\Get();
        }

        return $this->_get;
    }
}
