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

class SC_Controller extends SC_Abstract{
    function loadView($template, $output = true){
        if(!$output){
            ob_start();
        }
        
        require SC_Config::getOption('base_path').DS.
            SC_Config::getOption('views/repository').DS.
            trim($template, DS).'.'.
            SC_Config::getOption('views/file_extension');
        
        if(!$output){
            return ob_get_clean();
        }
    }
}
