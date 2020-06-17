<?php
defined("is_running") or die("Not an entry point...");


class file_editing{
	var $buffer = false;
	
	function file_editing(){
		global $page,$langmessage;
		$cmd = common::GetCommand();
		
		ob_start();
		switch($cmd){
			
			case $langmessage['preview']:
				$this->preview();
			break;
			case 'edit':
				$this->edit();
			break;
			case 'save';
				$this->save();
			break;
			
			case 'continue_rename':
				if( $this->RenameContinue() ){
					break;
				}
			case 'rename':
				$this->rename();
			break;
			
			case 'meta':
				$this->MetaForm();
			break;
			case 'meta_save':
				$this->MetaSave();
			break;
			
		}
		$page->contentBuffer = common::get_clean();
	}
	
	function MetaSave(){
		global $page,$langmessage;
		
		
		//don't send #gpx_content
		$page->ajaxReplace = array();
		//don't finish with empty buffer to prevent file output
		echo '-meta_save-';
		
		
		$array = array();
		$array[0] = 'inner';
		$array[1] = '#meta_save_inner';
		$array[2] = 'in development'; //$langmessage['SAVED'];
		$page->ajaxReplace[] = $array;
		
	}
	
	function MetaForm(){
		global $langmessage,$page;
		
		$meta_data = array();
		if( !$page->file ){
			echo $langmessage['OOPS_TITLE'];
			return;
		}
		ob_start();
		include($page->file);
		ob_end_clean();
		$meta_data += array('keywords'=>'','description'=>'');
		
		
		echo '<div class="inline_box">';
		echo '<h2>'.$langmessage['details'].'</h2>';
		echo '<form action="'.common::GetUrl($page->title).'" method="post">';
		echo '<table class="bordered">';
		echo '<tr>';
			echo '<th colspan="2">';
			echo $langmessage['options'];
			echo '</th>';
			echo '</tr>';

		echo '<tr>';
			echo '<td>';
			echo $langmessage['keywords'];
			echo '</td>';
			echo '<td>';
			echo '<input type="text" class="text" size="40" name="keywords" value="'.htmlspecialchars($meta_data['keywords']).'" />';
			echo '</td>';
			echo '</tr>';
			
		echo '<tr>';
			echo '<td>';
			echo $langmessage['description'];
			echo '</td>';
			echo '<td>';
			echo '<input type="text" class="text" size="40" name="keywords" value="'.htmlspecialchars($meta_data['description']).'" />';
			echo '</td>';
			echo '</tr>';
			
		echo '</table>';
		
		echo ' <input type="hidden" name="cmd" value="meta_save" />';
		echo '<input type="submit" class="gppost submit" name="aaa" value="'.$langmessage['save'].'" />';
		
		
		echo '</form>';
		echo '</div>';
		
		
	}
	
	
	function Preview(){
		global $langmessage;
		
		message($langmessage['preview_warning']);

		$text =& $_POST['gpcontent'];
		gpFiles::cleanText($text);
		
		echo $text;
		
		echo '<p><br/></p>';
		
		$this->edit($text);
	}
	
	
	
	
	function RenameContinue(){
		global $langmessage, $gpmenu, $gptitles, $dataDir, $page;
		
		
		//just relabel?
		if( gpFiles::CleanTitle($_POST['new_title']) == $page->title ){
			if( !isset($gptitles[$page->title]) ){
				message($langmessage['OOPS']);
				return false;
			}
			
			$gptitles[$page->title]['label'] = gpFiles::CleanLabel($_POST['new_title']);
			if( !admin_tools::SavePagesPHP() ){
				message($langmessage['OOPS'].' (N2)');
				return false;
			}
			message($langmessage['RENAMED']);
			return true;
		}

		$new_title = admin_tools::CheckPostedNewPage($_POST['new_title']);
		if( $new_title === false ){
			return false;
		}
		
		$oldTitle = $page->title;
		$oldTitles = $gptitles[$oldTitle];
		
		if( !isset($oldTitles['type']) ){
			$file_type = 'page';
		}else{
			$file_type = $oldTitles['type'];
		}
		
		
		//insert after.. then delete old
		admin_tools::MenuInsert($new_title,$oldTitle,$gpmenu[$oldTitle]);
		admin_tools::TitlesAdd($new_title,$_POST['new_title'],$file_type);

		unset($gpmenu[$oldTitle]);
		unset($gptitles[$oldTitle]);
		if( !admin_tools::SavePagesPHP() ){
			message($langmessage['OOPS'].' (N2)');
			return false;
		}		
		
		//rename the file
		$new_file = $dataDir.'/data/_pages/'.$new_title.'.php';
		$old_file = $page->file;
		
		if( !rename($old_file,$new_file) ){
			message($langmessage['OOPS'].' (N2)');
			return false;
		}
		
		
		
		$page->file = $new_file;
		$page->title = $new_title;
		$page->label = str_replace('_',' ',$new_title);
		message($langmessage['RENAMED']);
		return true;
	}
	
	
	function rename(){
		global $langmessage,$config,$page;
		
		$_POST += array('new_title'=>$page->label);
		
		echo '<div class="inline_box">';
		echo '<form class="renameform" action="'.common::GetUrl($page->title).'" method="post">';
		echo '<h2>'.$langmessage['rename'].'</h2>';
		echo '<table>';
			
		echo '<tr>';
			echo '<td class="formlabel">'.$langmessage['from'].'</td>';
			echo '<td>';
			echo '<input type="text" class="text" name="existing_label" maxlength="80" value="'.htmlspecialchars($page->label).'" readonly="readonly" />';
			echo '</td>';
			echo '</tr>';
			
		echo '<tr>';
			echo '<td class="formlabel">'.$langmessage['to'].'</td>';
			echo '<td>';
			echo '<input type="text" class="text" name="new_title" maxlength="80" value="'.htmlspecialchars($_POST['new_title']).'" />';
			echo '</td>';
			echo '</tr>';
			
		echo '<tr>';
			echo '<td></td>';
			echo '<td>';
			echo ' <input type="hidden" name="cmd" value="continue_rename" />';
			echo '<input type="submit" class="submit" name="aaa" value="'.$langmessage['rename'].'" />';
			echo '</td>';
			echo '</tr>';			
			
		echo '</table>';
		echo '</form>';
		echo '</div>';
		
	}
	
	
	function save(){
		global $langmessage,$page,$gptitles;

		$text =& $_POST['gpcontent'];
		gpFiles::cleanText($text);
		
		
/*		Get most comment words to add to page keywords.. 
		there's an issue of sifting out words like "the" in multiple languages
		if( function_exists('str_word_count') ){
			$words = strip_tags($text);
			$words = str_word_count($words,1);
			$words = array_count_values($words);
			arsort($words);
			foreach($words as $word => $count){
				if( strlen($word) < 4 ){
					continue;
				}
				message('maybe: '.$word);
			}
			message(showArray($words));
		}
*/
		
		
		
		if( gpFiles::SaveTitle($page->title,$text,$page->fileType) ){
			message($langmessage['SAVED']);
			return;
		}
		
		message($langmessage['OOPS']);
		$this->edit($text);
	}
		
	function edit($contents=false){
		global $langmessage,$page,$gptitles;
		
		if( $page->fileType == 'gallery' ){
			includeFile('/tool/editing_gallery.php');
			new editing_gallery();
		}else{
			$this->edit_page($contents);
		}
		
		echo '<h2 style="margin-top:2em">'.$langmessage['options'].'</h2>';
		echo '<ul>';
		
			if( defined('gptesting') ){
				echo '<li>';
				echo common::Link($page->title,$langmessage['details'],'cmd=meta',' name="ajax_box"');
				echo ' <span id="meta_save_inner"></span>';
				echo '</li>';
			}
			
			echo '<li>';
			echo common::Link($page->title,$langmessage['rename'],'cmd=rename',' name="ajax_box" ');
			echo '</li>';
			echo '</ul>';
	}
	
	function edit_page($contents){
		global $page,$langmessage;
		
		echo '<form action="'.common::GetUrl($page->title).'" method="post">';
		echo '<input type="hidden" name="cmd" value="save" />';
		
		if( $contents === false ){
			ob_start();
			include($page->file);
			$contents = common::get_clean();
		}
		common::UseCK( $contents );
		
		echo '<input type="submit" class="submit" name="" value="'.$langmessage['save'].'" />';
		echo ' <input type="submit" class="submit" name="cmd" value="'.$langmessage['preview'].'" />';
		echo ' <input type="submit" class="submit" name="cmd" value="'.$langmessage['cancel'].'" />';
		echo '</form>';
	}
	
	
}
