<?php

class SC_Log{
    static function add($error, $type = 3, $destination = null, $headers = null){
        if(!is_dir(dirname($destination))){
            $relative_path = str_replace(
                SC_Config::getOption('base_path').SL,
                '',
                dirname($destination)
            );
            
            SC_Log::generateFolders($relative_path);
        }
        
        error_log($error, $type, $destination, $headers);
    }
	
	static private function generateFolders($relative_path){
        $folder_path = SC_Config::getOption('base_path');
        
        $folders = explode(SL, $relative_path);
        foreach($folders as $folder){
            $folder_path .= SL.$folder.SL;
            if(!is_dir($folder_path)){
                mkdir($folder_path, 0755);
            }
        }
	}
}