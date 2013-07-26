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

use SimpleCoding\Framework\Core;

class Headers extends Core\Object
{
    protected function _updateData()
    {
        $headers = \headers_list();
        
        foreach ($headers as $header) {
            $data = explode(':', $header, 2);
            $this->_data[$data[0]] = trim($data[1]);
        }

        return $this;
    }

    public function __construct()
    {
        $this->_updateData();
    }

    public function setHeader($header, $replace = true, $httpResponseCode = null)
    {
        header($header, $replace, $httpResponseCode);

        return $this->_updateData();
    }

    public function setHeaders($headers = array())
    {
        foreach ($headers as $_header) {
            $header = $header[0];
            $replace = isset($header[1]) ? $header[1] : true;
            $httpResponseCode = isset($header[2]) ? $header[2] : null;

            header($header, $replace, $httpResponseCode);
        }

        return $this->_updateData();
    }

    public function removeHeader($name)
    {
        header_remove($name);

        return $this->_updateData();
    }
}
