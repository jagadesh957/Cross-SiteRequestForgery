<?php
defined('is_running') or die('Not an entry point...');


class special_galleries{
	var $galleries = array();
	
	function special_galleries(){
		$this->galleries = special_galleries::GetData();
		
		if( common::LoggedIn() ){
			$cmd = common::GetCommand();
			switch($cmd){
				case 'drag':
					$this->Drag();
				case 'edit':
					$this->EditGalleries();
				return;
			}
		}
		
		$this->GenerateOutput();
	}
	
	function Drag(){
		global $langmessage;
		
		$to =& $_GET['to'];
		$title =& $_GET['title'];
		
		if( !isset($this->galleries[$to]) ){
			message($langmessage['OOPS']);
			return;
		}
		if( !isset($this->galleries[$title]) ){
			message($langmessage['OOPS']);
			return;
		}
		
		$title_info = $this->galleries[$title];
		unset($this->galleries[$title]);
		
		
		if( !gpFiles::ArrayInsert($to,$title,$title_info,$this->galleries) ){
			message($langmessage['OOPS']);
			return;
		}
		
		special_galleries::SaveIndex($this->galleries);
	}
	
	function EditGalleries(){
		global $page;
		
		$page->head .= '<script type="text/javascript" language="javascript" src="'.common::GetDir('/include/js/dragdrop.js').'"></script>';
		$page->head .= '<link rel="stylesheet" type="text/css" href="'.common::GetDir('/include/css/edit_gallery.css').'" />';
		$page->head .= '<link rel="stylesheet" type="text/css" href="'.common::GetDir('/include/css/browser.css').'" />';
		
		echo '<h2>';
		echo gpOutput::ReturnText('galleries');
		echo '</h2>';
		echo '<div id="admincontent">';
		echo '<div class="browser_list draggable_droparea">';
		
		foreach($this->galleries as $title => $info ){
			
			if( is_array($info) ){
				$icon = $info['icon'];
			}else{
				$icon = $info;
			}
			
			if( empty($icon) ){
				$thumbPath = common::GetDir('/include/imgs/blank.gif');
			}elseif( strpos($icon,'/thumbnails/') === false ){
				$thumbPath = common::GetDir('/data/_uploaded/image/thumbnails'.$icon.'.jpg');
			}else{
				$thumbPath = common::GetDir('/data/_uploaded'.$icon);
			}
			echo '<div class="draggable_element list_item expand_child">';
			//echo common::Link('Special_Galleries',$title,'cmd=drag&from='.$title,' name="gpajax" style="display:none" ');
			echo common::Link('Special_Galleries',htmlspecialchars($title),'cmd=drag&to=%s&title='.urlencode($title),' name="gpajax" style="display:none" ');

			echo '<div class="gen_links">';
			echo ' <img src="'.$thumbPath.'" height="100" width="100"  alt=""/>';
			echo '<div class="caption">';
			echo str_replace('_',' ',$title);
			echo '</div>';
			echo '</div>';
			echo '</div>';

			
		}
		echo '</div>';
		echo '</div>';
		
	}
	
	
	//get gallery index
	function GetData(){
		global $dataDir;
		
		$file = $dataDir.'/data/_site/galleries.php';
		if( !file_exists($file) ){
			return special_galleries::DataFromFiles();
		}
		require($file);
		
		//pages.php is update when pages are deleted/added/renamed/hidden
		if( $GLOBALS['fileModTimes']['pages.php'] > $fileModTime ){
			return special_galleries::DataFromFiles($galleries);
		}
		
		return $galleries;
	}
	
	
	function GenerateOutput(){
		global $langmessage,$gptitles;
		
		
		echo '<h2>';
		echo gpOutput::ReturnText('galleries');
		echo '</h2>';


		$wrap = common::LoggedIn();
		if( $wrap ){
			echo '<div class="editable_area">'; // class="edit_area" added by javascript
			echo common::Link('Special_Galleries',$langmessage['edit'],'cmd=edit',' class="ExtraEditLink" ');
		}
		
		echo '<ul class="gp_gallery">';
		foreach($this->galleries as $title => $info ){
				
			
			$count = '';
			if( is_array($info) ){
				$icon = $info['icon'];
				if( $info['count'] == 1 ){
					$count = $info['count'].' '.gpOutput::ReturnText('image');
				}elseif( $info['count'] > 1 ){
					$count = $info['count'].' '.gpOutput::ReturnText('images');
				}
			}else{
				$icon = $info;
			}
			if( empty($icon) ){
				continue;
			}
			
			
			if( strpos($icon,'/thumbnails/') === false ){
				$thumbPath = common::GetDir('/data/_uploaded/image/thumbnails'.$icon.'.jpg');
			}else{
				$thumbPath = common::GetDir('/data/_uploaded'.$icon);
			}
			
			echo '<li style="clear:both">';
			$label = ' <img src="'.$thumbPath.'" height="100" width="100"  alt=""/>';
			echo common::Link($title,$label);
			echo '</a>';
			echo '<div>';
			echo common::Link($title, str_replace('_',' ',$title));
			echo '<p>';
			echo $count;
			echo '</p>';
			echo '</div>';
			echo '</li>';
		}
		echo '</ul>';
		echo '<div style="clear:both"></div>';
		if( $wrap ){
			echo '</div>';
		}
		
		
	}
	
	/*
	
	Updating Functions
	
	*/
	
	function DataFromFiles($galleries=array()){
		global $gptitles;
		
		
		//
		//	Check Current
		//
		foreach($galleries as $title => $info){
			if( !isset($gptitles[$title]) ){
				unset($galleries[$title]);
			}
		}
		
		
		//
		//	Add New Galleries
		//
		foreach($gptitles as $title => $info){
			
			if( !isset($info['type']) || ($info['type'] != 'gallery') ){
				continue;
			}
			
			if( !isset($galleries[$title]) ){
				$info = special_galleries::GetIcon($title);
				$galleries[$title] = $info;
			}
		}
		
		
		special_galleries::SaveIndex($galleries);
		return $galleries;
	}
	
	function SaveIndex($galleries){
		global $dataDir;
		
		includeFile('admin/admin_tools.php');

		$file = $dataDir.'/data/_site/galleries.php';
		gpFiles::SaveArray($file,'galleries',$galleries);
	}
	
	function RenameGallery($from,$to){
		global $dataDir;
		$newgalleries = array();
		$galleries = special_galleries::GetData();

		foreach($galleries as $gallery => $info){
			if( $gallery === $from ){
				$newgalleries[$to] = $info;
			}else{
				$newgalleries[$gallery] = $info;
			}
		}
		special_galleries::SaveIndex($newgalleries);
	}
	
	function GetIcon($title){
		global $dataDir;
		
		$array = array('icon'=>false,'count'=>0);
		
		$file = $dataDir.'/data/_pages/'.$title.'.php';
		if( !file_exists($file) ){
			return $array;
		}
		
		ob_start();
		include_once($file);
		common::get_clean();
		
		if( !isset($file_array) || !isset($file_array[0]) ){
			return $array;
		}
		
		return array('icon'=>$file_array[0],'count'=>count($file_array));
	}	
	
	
	
}
