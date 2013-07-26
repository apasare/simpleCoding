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

return array(
    'mvc' => array(
        'config' => array(
            'repository' => 'conf'
        ),
        'models' => array(
            'repository' => 'Model'
        ),
        'controllers' => array(
            'repository' => 'Controller',
            'default_controller' => 'Index',
            'trigger_suffix' => 'Trigger',
            'default_trigger' => 'index'
        ),
        'views' => array(
            'repository' => 'View'
        )
    )
);
