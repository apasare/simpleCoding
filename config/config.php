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

$config['url_rewrite'] = true;
$config['timezone'] = 'Europe/Bucharest';
$config['modules']['config_file'] = 'config.xml';

$config['extensions']['controller_file'] = '.php';
$config['extensions']['view_file'] = '.phtml';
$config['extensions']['model_file'] = '.php';
$config['extensions']['plugin_file'] = '.php';
$config['extensions']['script_file'] = '.php';
$config['extensions']['locale_file'] = '.csv';
$config['extensions']['controller_trigger'] = 'Trigger';

$config['default']['module'] = 'site';
$config['default']['controller'] = 'index';
$config['default']['trigger'] = 'index';
$config['default']['locale'] = 'en_US';

$config['logs']['display_errors'] = true;
$config['logs']['message_type'] = 3;
$config['logs']['mail_to'] = '';

$config['session']['lifetime'] = 14*24*3600;

$config['base_path'] = str_replace('config', '', realpath(dirname(__FILE__)));
$config['folders']['root'] = trim(str_replace(realpath($_SERVER['DOCUMENT_ROOT']), '', $config['base_path']), BSL.SL);
	$config['folders']['core'] = 'application'.SL.'core';
	$config['folders']['extends'] = 'application'.SL.'extends';
	$config['folders']['modules'] = 'application'.SL.'modules';
		$config['folders']['workspaces'][] = '';
			$config['folders']['controllers'] = 'controllers';
			$config['folders']['models'] = 'models';
	$config['folders']['views'] = 'application'.SL.'views';
	$config['folders']['locale'] = 'application'.SL.'locale';
	$config['folders']['assets'] = 'assets';
	$config['folders']['logs'] = 'temp'.SL.'logs';
	$config['folders']['sessions'] = 'temp'.SL.'sessions';
