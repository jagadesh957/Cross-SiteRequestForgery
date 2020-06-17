<?php
defined("is_running") or die("Not an entry point...");


class gpupgrade{
	
	function gpupgrade(){
		global $config;

		if( version_compare($config['gpversion'],'1.6RC4','<') ){
			$this->to16RC4(); //1.6rc4
		}
		
		if( version_compare($config['gpversion'],'1.6','<') ){
			$this->to16();
		}
	}
	
	
	function to16(){
		global $dataDir,$config;
		
		$startDir = $dataDir.'/data';
		$this->indexDirs($startDir);
		
		//version
		require_once($GLOBALS['rootDir'].'/include/admin/admin_tools.php');
		$config['gpversion'] = $GLOBALS['gpversion'];
		admin_tools::SaveConfig();
	}
	
	function indexDirs($dir){
		$folders = gpFiles::ReadDir($dir,1);
		
		foreach($folders as $folder){
			$fullPath = $dir.'/'.$folder;
			if( is_link($fullPath) ){
				continue;
			}
			
			if( is_dir($fullPath) ){
				$this->indexDirs($fullPath);
			}
		}
		gpFiles::CheckDir($dir);
	}
	
	
	//FIX GALLERIES
	function to16RC4(){
		global $gptitles,$config;
		
		require_once($GLOBALS['rootDir'].'/include/admin/admin_tools.php');
		require_once($GLOBALS['rootDir'].'/include/tool/editing_gallery.php');
		
		foreach($gptitles as $title => $info){
			if( !isset($info['type']) || $info['type'] != 'gallery' ){
				continue;
			}
			$this->UpdateGallery($title);
		}		
		//version
		$config['gpversion'] = $GLOBALS['gpversion'];
		admin_tools::SaveConfig();
		
	}
	
	function UpdateGallery($title){
		global $dataDir;
		$file = $dataDir.'/data/_pages/'.gpFiles::CleanTitle($title).'.php';
		if( !file_exists($file) ){
			return false;
		}
		
		//
		$file_array = array();
		$caption_array = array();
		ob_start();
		require($file);
		common::get_clean();
		
		editing_gallery::SaveFileArray($title,$file_array,$caption_array);
	}
	
	
}
	
