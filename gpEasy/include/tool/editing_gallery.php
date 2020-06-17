<?php
defined("is_running") or die("Not an entry point...");


includeFile('admin/admin_uploaded.php');



class editing_gallery extends admin_uploaded{
	
	var $file_array = array();
	var $caption_array = array();
	
	function editing_gallery(){
		global $page,$langmessage;
		
		$this->browseString = $page->title;
		$this->queryString = 'cmd=edit&';
		
		$page->head .= '<script type="text/javascript" language="javascript" src="'.common::GetDir('/include/js/dragdrop.js').'"></script>';
		$page->head .= '<link rel="stylesheet" type="text/css" href="'.common::GetDir('/include/css/edit_gallery.css').'" />';
		
		$this->Init();
				
		ob_start();
		include($page->file);
		ob_end_clean();
		
		if( isset($file_array) ){
			$this->file_array = $file_array;
		}
		if( isset($caption_array) ){
			$this->caption_array = $caption_array;
		}
		
		//version 1.0b4 update of gallery
		//message('hmm: '.showArray(array_keys($this->file_array)));
			
			if( !isset($fileVersion) ){
				
				foreach($this->file_array as $i => $file){
					$this->file_array[$i] = '/image'.$file;
					if( !isset($this->caption_array[$i]) ){
						$this->caption_array[$i] = '';
					}
				}
			}
	
		
		//Check First Index
		$firstIndexBefore = false;
		$countBefore = 0;
		if( isset($this->file_array[0]) ){
			$firstIndexBefore = $this->file_array[0];
			$countBefore = count($this->file_array);
		}
		
		$file_cmd =& $_REQUEST['file_cmd'];
		switch($file_cmd){
			
			case 'caption':
				$this->CaptionForm();
			return;
			
			case 'savecaption':
				$this->SaveCaption();
			break;
			case 'grm':
				$this->RmFromGallery();
			break;
			
			case 'drag':
				$this->DragMove();
			break;
			case 'dragadd':
				$this->DragAdd();
			break;
			
			default:
				$this->AdminCommands();
			break;
		}
		
		
		//Check to see if first index changed
		$firstIndexAfter = false;
		$countAfter = 0;
		if( isset($this->file_array[0]) ){
			$firstIndexAfter = $this->file_array[0];
			$countAfter = count($this->file_array);
		}
		
		if( ($firstIndexBefore !== $firstIndexAfter) || ($countBefore !== $countAfter) ){
			$this->UpdateGalleryIndex($firstIndexAfter,$countAfter);
		}

		echo '<div id="admincontent">';
		$this->ShowEditor();
		echo '</div>';
	}
	
	function CaptionForm(){
		global $langmessage;
		
		
		
		if( !isset($_GET['file']) ){
			message($langmessage['OOPS']);
			return;
		}
		$index = $_GET['file'];
		
		if( !isset($this->caption_array[$index]) ){
			message($langmessage['OOPS']);
			return;
		}
		
		
		
		echo '<div class="inline_box">';
		echo '<h2>'.$langmessage['caption'].'</h2>';
		echo '<form action="'.common::GetUrl($this->browseString,$this->queryString.'dir='.$this->subdir).'" method="post">';
		echo '<textarea name="caption" cols="40" rows="4">';
		echo htmlspecialchars($this->caption_array[$index]);
		echo '</textarea>';
		echo '<p>';
			echo '<input type="hidden" name="cmd" value="edit" />';
			echo '<input type="hidden" name="file_cmd" value="savecaption" />';
			echo '<input type="hidden" name="file" value="'.htmlspecialchars($index).'" />';
			echo '<input type="submit" class="gppost submit" name="aaa" value="'.$langmessage['save'].'" />';
			//echo '<input type="submit" class="submit" name="aaa" value="'.$langmessage['save'].'" />';
		echo '</p>';
		echo '</form>';
		echo '</div>';
		
		
		//so the options link isn't shown at the bottom
		// it also prevents messages from being displayed
		if( isset($_REQUEST['gpreq']) && $_REQUEST['gpreq']=='flush'){
			die();
		}
	}
	
	function ShowEditor(){
		global $langmessage,$page;
		
		$this->ShowPanel();
		
		echo '<table cellpadding="0" cellspacing="0">';
		echo '<tr><td style="width:370px">';
		
		$this->ShowCurrent();
		
		echo '</td><td style="width:350px;" class="drag_from">';
		
		echo '<b>'.$langmessage['available_images'].'</b>';
		
		$this->ShowFolder();
		
		echo '</tr>';
		echo '</table>';
	}
	
	
	function UpdateGalleryIndex($img,$count){
		global $page;
		includeFile('special/special_galleries.php');
		
		
		$galleries = special_galleries::GetData();
		$galleries[$page->title] = array();
		$galleries[$page->title]['icon'] = $img;
		$galleries[$page->title]['count'] = $count;
		
		special_galleries::SaveIndex($galleries);
	}

	
	function GetIndex(){
		$index = $_REQUEST['index'];
		if( !isset($this->file_array[$index]) ){
			return false;
		}
		
		$fileAtIndex = $this->file_array[$index];
		if( $fileAtIndex != $_REQUEST['file'] ){
			return false;
		}
		return $index;
	}
	
	function DragAdd(){
		global $langmessage,$dataDir,$page;
		
		$relativePath = $this->subdir.'/'.$_REQUEST['file'];
		$fullpath = $dataDir.'/data/_uploaded'.$this->subdir.'/'.$_REQUEST['file'];
		
		if( !file_exists($fullpath) ){
			message($langmessage['OOPS'].' (1)');
			return;
		}
		
		if( in_array($relativePath,$this->file_array) ){
			message($langmessage['image_already_added']);
			return;
		}
		
		
		$to = 0;
		if( isset($_GET['to']) && is_numeric($_GET['to']) && ($_GET['to'] <= count($this->file_array)) ){
			$to = $_GET['to'];
		}
		
	
		array_splice($this->file_array,$to,0,$relativePath); //put it
		array_splice($this->caption_array,$to,0,'');
		$this->SaveThisFileArray();

	}
	
	function DragMove(){
		$from = $_GET['from'];
		if( !isset($this->file_array[$from]) ){
			return;
		}
		
		if( isset($this->file_array[$_GET['to']]) ){
			$to = $_GET['to'];
		}elseif( $_GET['to'] == count($this->file_array) ){
			$to = $_GET['to']-1;
		}else{
			return;
		}
		
		$file = $this->file_array[$from];
		array_splice($this->file_array,$from,1); //remove at current spot
		array_splice($this->file_array,$to,0,$file); //put back
		
		
		$caption = $this->caption_array[$from];
		array_splice($this->caption_array,$from,1);
		array_splice($this->caption_array,$to,0,$caption);
		
		$this->SaveThisFileArray();

	
	}

	
	function RmFromGallery(){
		$index = $this->GetIndex();
		if( $index === false ){
			return;
		}
		array_splice($this->file_array,$index,1);
		array_splice($this->caption_array,$index,1);
		$this->SaveThisFileArray();

	}
	
	
	function SaveCaption(){
		global $langmessage;
		
		if( $_SERVER['REQUEST_METHOD'] != 'POST'){
			message($langmessage['OOPS'].' (0)');
			return false;
		}
		
		$index = (int)$_REQUEST['file'];
		if( !isset($this->file_array[$index]) ){
			message($langmessage['OOPS'].' (2)');
			return;
		}
		
		$this->caption_array[$index] = $_REQUEST['caption'];
		gpFiles::cleanText($this->caption_array[$index]);
		//admin_tools::tidyFix($this->caption_array[$index]);
		//gpFiles::rmPHP($this->caption_array[$index]);

		
		$this->SaveThisFileArray();
	}
	
	function SaveThisFileArray(){
		global $page;
		if( !editing_gallery::SaveFileArray($page->title,$this->file_array,$this->caption_array) ){
			message($langmessage['OOPS'],'4');
		}
	}
	
	function SaveFileArray($title,&$file_array,&$caption_array){
		global $page,$langmessage;
		
		$file_type = 'gallery';
		$data = '<'.'?'.'php '."\n". gpFiles::ArrayToPHP('file_array',$file_array);
		$data .= "\n".gpFiles::ArrayToPHP('caption_array',$caption_array);
		$data .= "\n".'?'.'>';
		$data .= editing_gallery::GetGalleryHtml($title,$file_array,$caption_array);
		
		$mess = '<textarea style="width:100%" rows="5">';
		$mess .= htmlspecialchars($data);
		$mess .= '</textarea>';
		
		return gpFiles::SaveTitle($title,$data,$file_type);
	}
	
	function GetGalleryHtml(&$title,&$file_array,&$caption_array){
		
		ob_start();
		
		if( !isset($file_array) ){
			$file_array = array();
		}
		if( !isset($caption_array) ){
			$caption_array = array();
		}
		
		echo '<ul class="gp_gallery">';
		foreach($file_array as $index => $file){
			echo '<li>';
			
			$caption = '';
			if( !empty($caption_array[$index]) ){
				$caption = $caption_array[$index];
			}
			

			if( strpos($file,'/thumbnails/') === false ){
				$imgPath = common::GetDir('/data/_uploaded'.$file);
				$thumbPath = common::GetDir('/data/_uploaded/image/thumbnails'.$file.'.jpg');
			}else{
				$imgPath = common::GetDir('/data/_uploaded'.$file);
				$thumbPath = common::GetDir('/data/_uploaded'.$file);
			}
			
			echo '<a href="'.$imgPath.'" name="gallery" rel="gallery_gallery" title="'.htmlspecialchars($caption).'">';
			echo ' <img src="'.$thumbPath.'" height="100" width="100"  alt=""/>';
			echo '</a>';
			echo '<div>';
			echo $caption;
			echo '</div>';
			echo '</li>';
		}
		echo '</ul>';
		
		return common::get_clean();
	}

	
	
	function ShowCurrent(){
		global $page,$langmessage;
		
		echo '<b>'.$langmessage['current_images'].'</b>';
		echo '<div class="browser_list draggable_droparea">';
		
		if( count($this->file_array) == 0 ){
			echo '<div class="draggable_element list_item">';
			echo common::Link($page->title,0,'',' style="display:none" ');
			echo '-- empty --';
			echo '</div>';
			echo '</div>';
			return;
		}
		
		foreach($this->file_array as $index => $file){
			echo '<div class="draggable_element list_item expand_child">';
			echo common::Link($page->title,$index,'cmd=edit&file_cmd=drag&from='.$index.'&to=%s&dir='.$this->subdir,' name="gpajax" style="display:none" ');
			echo '<div class="gen_links">';
				$caption = '';
				if( isset($this->caption_array[$index]) ){
					$caption = $this->caption_array[$index];
				}
				
				if( strpos($file,'/thumbnails') === false ){
					$imgPath = common::GetDir('/data/_uploaded'.$file);
					$thumbPath = common::GetDir('/data/_uploaded/image/thumbnails'.$file.'.jpg');
				}else{
					$imgPath = common::GetDir('/data/_uploaded'.$file);
					$thumbPath = common::GetDir('/data/_uploaded'.$file);
				}
				
				echo '<a href="'.$imgPath.'" name="gallery" rel="gallery_current" title="'.htmlspecialchars($caption).'" class="thumb" >';
				echo ' <img src="'.$thumbPath.'" height="100" width="100" alt="" />';
				echo '</a>';
				
				
				echo '<div class="caption">';
				$caption = strip_tags($caption);
				if( strlen($caption) > 100 ){
					$caption = substr($caption,0,100);
				}
				echo $caption;
				echo '</div>';
				
				
				
			echo '</div>';
			echo '<div class="more_links">';
			
				echo '<a>';
				echo '<img src="'.common::GetDir('/include/imgs/arrow_out.png').'" alt="'.$langmessage['drag_drop'].'" height="16" width="16" /> ';
				echo $langmessage['drag_drop'];
				echo '</a>';
				
				$label = '<img src="'.common::GetDir('/include/imgs/page_edit.png').'" alt="" height="16" width="16" /> ';
				echo common::Link($page->title,$label.$langmessage['caption'],'cmd=edit&file_cmd=caption&file='.$index.'&dir='.$this->subdir,' name="ajax_box" ');
				
				
				$label = '<img src="'.common::GetDir('/include/imgs/delete.png').'" alt="" height="16" width="16" /> ';
				echo common::Link($page->title,$label.$langmessage['remove'],'cmd=edit&file_cmd=grm&dir='.$this->subdir.'&index='.$index.'&file='.urlencode($file),' name="gpajax" title="'.$langmessage['remove'].'" ');

				
			echo '</div>';
				
				
			echo '</div>';
		}
		
		echo '<div class="draggable_hidden list_item">';
		echo common::Link($page->title,$index+1,'',' style="display:none" ');
		echo '</div>';
		
		echo '</div>';
		echo '</div>';
		
	}
	
	
	
	function DraggableLink($file,$is_img){
		global $page;
		
		if( !$is_img ){
			return false;
		}
		
		$relativePath = $this->subdir.'/'.$file;
		if( !in_array($relativePath,$this->file_array) ){
			return common::Link($page->title,$file,'cmd=edit&file_cmd=dragadd&file='.urlencode($file).'&to=%s&dir='.$this->subdir,' name="gpajax" style="display:none" ');
		}
		return false;
	}
	
	function File_Link_Right($file,$is_img){
		global $langmessage,$page;
		
		if( $is_img){
			
			$relativePath = $this->subdir.'/'.$file;
			if( !in_array($relativePath,$this->file_array) ){
				echo '<a>';
				echo '<img src="'.common::GetDir('/include/imgs/arrow_out.png').'" alt="'.$langmessage['drag_drop'].'" height="16" width="16" style="vertical-align:middle" /> ';	
				echo $langmessage['drag_drop'];
				echo '</a>';
			}
		}
		parent::File_Link_Right($file,$is_img);
	}
	

	
}
