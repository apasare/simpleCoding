<?php

namespace SimpleCoding\Framework\Core;

class Config
{
    protected $_data = array();
    protected $_configFolder = 'conf';

    public function __construct($mainConfigFolder = null)
    {
        if ($mainConfigFolder) {
            $this->_configFolder = $mainConfigFolder;
        }
        
        $this->_processMainConfigFolder()
            ->_processModules();
        
    print_r($this->_data);
    }
    
    protected function _processMainConfigFolder()
    {
        $configPattern = SIMPLECODING_ROOT . DS . $this->_configFolder . DS . '*.php';
        $configFiles = glob($configPattern);
        foreach ($configFiles as $configFile) {
            $configs = require_once $configFile;

            $this->_data = array_merge($this->_data, $configs);
        }
        
        return $this;
    }
    
    protected function _processModules()
    {
        $this->_data['modules'] = array();
        
        $vendorsPattern = SIMPLECODING_ROOT . DS . MODULES_REPOSITORY . DS . '*';
        $vendors = glob($vendorsPattern, GLOB_ONLYDIR);
        foreach ($vendors as $vendor) {
            $modulesPattern = $vendor . DS . '*';
            $modules = glob($modulesPattern, GLOB_ONLYDIR);
            foreach ($modules as $module) {
                $key = basename($vendor) . '_' . basename($module);
                $configPattern = $module . DS . $this->_configFolder . DS . '*';
                $configFiles = glob($configPattern);
                foreach ($configFiles as $configFile) {
                    $this->_data['modules'] = array_merge_recursive(
                        $this->_data['modules'],
                        array(
                            $key => $this->_processConfigFile($configFile)
                        )
                    );
                }
            }
        }
        
        return $this;
    }
    
    protected function _processConfigFile($configFile)
    {
        if (!file_exists($configFile)) {
            return array();
        }
        
        $key = strtolower(basename($configFile, '.php'));
        $data = require $configFile;
        
        if ($key != 'default'){
            return array(
                $key => $data
            );
        }
        
        return $data;
    }
}
