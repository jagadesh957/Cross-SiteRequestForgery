<?php
defined('is_running') or die('Not an entry point...');



/*
what can be moved?
	* .editable_area

How do we position elements?
	* above, below, float:left, float:right in relation to another editable_area

How do we do locate them programatically
	* We need to know the calling functions that output the areas
		then be able to organize a list of output functions within each of the calling functions
		!each area is represented by a list, either a default value if an override hasn't been defined, or the custom list created by the user
		
How To Identify the Output Functions for the Output Lists?
	* Gadgets have:
		$info['script']
		$info['data']
		$info['class']


$gpOutConf = array() of output functions/classes.. to use with the theme content
	==potential values==
	$gpOutConf[-ident-]['script'] = -path relative to datadir or rootdir?
	$gpOutConf[-ident-]['data'] = -path relative to datadir-
	$gpOutConf[-ident-]['class'] = -path relative to datadir or rootdir?
	$gpOutConf[-ident-]['method'] = string or array: string=name of function, array(class,method)
	
	
	$config['theme_handlers']['Tan Header'][-ident-] = array(0=>-ident-,1=>-ident-)
*/

class admin_theme_content{
	
	var $default_layout;
	var $default_color;
	
	function admin_theme_content(){
		global $page,$config;
		
		$GLOBALS['GP_ARRANGE_CONTENT'] = true;
		
		$page->head .= '<script type="text/javascript" language="javascript" src="'.common::GetDir('/include/js/theme_content.js').'"></script>';
		$page->head .= '<script type="text/javascript" language="javascript" src="'.common::GetDir('/include/js/dragdrop.js').'"></script>';
		//$page->head .= '<script type="text/javascript" language="javascript" src="'.common::GetDir('/include/js/dragdrop_w_direction.js').'"></script>';
		$page->head .= '<link rel="stylesheet" type="text/css" href="'.common::GetDir('/include/css/theme_content.css').'" />';

		$this->default_theme = $page->theme;
		$this->default_layout = $page->theme_name;
		$this->default_color = $page->theme_color;
		
		
		if( isset($_GET['theme']) ){
			$this->UseTheme();
		}
		
		$cmd = common::GetCommand();
		switch($cmd){
			
			case 'drag':
				$this->Drag();
			break;
			
			case 'restore':
				$this->Restore();
			break;
			
			
			//links
			case 'edit':
				$this->SelectLinks();
			return;
			case 'save':
				$this->SaveLinks();
			break;
			
			//text
			case 'edittext':
				$this->EditText();
			return;
			case 'savetext':
				$this->SaveText();
			break;
			
			
			case 'saveaddontext':
				$this->SaveAddonText();
			break;
			case 'addontext':
				$this->AddonText();
			return;
			
			//remove
			case 'rm':
				$this->RemoveArea();
			break;
			
			//insert
			case 'insert':
				$this->SelectContent();
			return;
			case 'addcontent':
				$this->AddContent();
			break;

		}
		
		//message(showArray($_GET));
		$this->Show();
	}
	
	function UseTheme(){
		global $langmessage,$page;
		includeFile('admin/admin_theme.php');
		
		$theme = $_GET['theme'];
		if( !admin_theme::IsAvailable($theme) ){
			message($langmessage['OOPS'].' (0)');
			return;
		}
		$page->theme = $theme;
		$page->theme_name = dirname($theme);
		$page->theme_color = basename($theme);
	}
	
	
	function AddContent(){
		global $langmessage;
		
		//prep destination
		if( !$this->GetValues($_GET['where'],$to_container,$to_gpOutKey) ){
			message($langmessage['OOPS'].' (0)');
			return;
		}
		$handlers = $this->GetAllHandlers();
		$this->PrepContainerHandlers($handlers,$to_container,$to_gpOutKey);
		
		
		//new info
		$new_gpOutInfo = gpOutput::GetgpOutInfo($_GET['insert']);
		if( !$new_gpOutInfo ){
			message($langmessage['OOPS'].' (1)');
			return;
		}
		$new_gpOutKey = $new_gpOutInfo['key'].':'.$new_gpOutInfo['arg'];
		
		
		if( !$this->AddToContainer($handlers[$to_container],$to_gpOutKey,$new_gpOutKey,false) ){
			return;
		}
		
		$this->SaveHandlers($handlers);
	}
	
	
	function AddToContainer(&$container,$to_gpOutKey,$new_gpOutKey,$replace=true){
		global $langmessage;
		
		
		
		//add to to_container in front of $to_gpOutKey
		if( !isset($container) || !is_array($container) ){
			message($langmessage['OOPS'].' (1)');
			return false;
		}
		
		//can't have two identical outputs in the same container
		$check = array_search($new_gpOutKey,$container);
		if( ($check !== null) && ($check !== false) ){
			message($langmessage['OOPS']. '(2)');
			return false;
		}
		
		//if empty, just add
		if( count($container) === 0 ){
			$container[] = $new_gpOutKey;
			return true;
		}
		
		$length = 1;
		if( $replace === false ){
			$length = 0;
		}
		
		//insert
		$where = array_search($to_gpOutKey,$container);
		if( ($where === null) || ($where === false) ){
			message($langmessage['OOPS']. '(3)');
			return false;
		}
		
		array_splice($container,$where,$length,$new_gpOutKey);
		
		return true;
	}

	
	function SelectContent(){
		global $dataDir,$langmessage,$config,$gpOutConf,$page;
		
		
		if( !isset($_GET['param']) ){
			message($langmessage['OOPS'].' (0)');
			return;
		}
		$param = $_GET['param'];
			
		
		echo '<div class="inline_box">';
		
		echo '<table>';
		echo '<tr><td>';
		
		echo '<table class="bordered">';
		echo '<tr>';
			echo '<th>';
			echo $langmessage['theme_content'];
			echo '</th>';
			echo '<th>';
			echo $langmessage['options'];
			echo '</th>';
			echo '</tr>';
		
		
		
		//extra content
			$extrasFolder = $dataDir.'/data/_extra';
			$files = gpFiles::ReadDir($extrasFolder);
			asort($files);
			foreach($files as $file){
				$extraName = $file;
				echo '<tr>';
					echo '<td>';
					echo str_replace('_',' ',$extraName);
					echo '</td>';
					echo '<td>';
					echo common::Link('Admin_Theme_Content',$langmessage['add'],'cmd=addcontent&theme='.urlencode($page->theme).'&where='.urlencode($param).'&insert=Extra:'.$extraName,' name="creq" ');
					echo '</td>';
					echo '</tr>';
			}
		
		//gadgets
			echo '<tr>';
				echo '<th>';
				echo $langmessage['gadgets'];
				echo '</th>';
				echo '<th>';
				echo $langmessage['options'];
				echo '</th>';
				echo '</tr>';
				
			$gadgets = false;
			if( isset($config['gadgets']) && is_array($config['gadgets']) ){
				
				foreach($config['gadgets'] as $gadget => $info){
					$gadgets = true;
					echo '<tr>';
						echo '<td>';
						echo str_replace('_',' ',$gadget);
						echo '</td>';
						echo '<td>';
						echo common::Link('Admin_Theme_Content',$langmessage['add'],'cmd=addcontent&theme='.urlencode($page->theme).'&where='.urlencode($param).'&insert='.$gadget,' name="creq" ');
						echo '</td>';
						echo '</tr>';
				}
			}
			if( !$gadgets ){
				echo '<tr>';
					echo '<td>';
					echo '-empty-';
					echo '</td>';
					echo '<td>';
					echo '&nbsp;';
					echo '</td>';
					echo '</tr>';
			}
		echo '</table>';
		echo '</td><td>';
		echo '<table class="bordered">';

		//links
			echo '<tr>';
				echo '<th>';
				echo $langmessage['Links'];
				echo '</th>';
				echo '<th>';
				echo $langmessage['options'];
				echo '</th>';
				echo '</tr>';
				
			foreach($gpOutConf as $outKey => $info){
				
				if( !isset($info['link']) ){
					continue;
				}
				echo '<tr>';
					echo '<td>';
					if( isset($langmessage[$info['link']]) ){
						echo $langmessage[$info['link']];
					}else{
						$info['link'];
					}
					echo '</td>';
					echo '<td>';
					echo common::Link('Admin_Theme_Content',$langmessage['add'],'cmd=addcontent&theme='.urlencode($page->theme).'&where='.urlencode($param).'&insert='.$outKey,' name="creq" ');
					echo '</td>';
					echo '</tr>';
			}
		
		
		echo '</table>';
		
		echo '</td></tr></table>';
		
		echo '</div>';
		
		
	}
	
	
	function RemoveArea(){
		
		if( !$this->ParseHandlerInfo($_GET['param'],$curr_info) ){
			message($langmessage['OOPS'].' (0)');
			return;
		}
		$gpOutKey = $curr_info['gpOutKey'];
		$container = $curr_info['container'];

		
		//prep work
		$handlers = $this->GetAllHandlers();
		$this->PrepContainerHandlers($handlers,$container,$gpOutKey);
		
		
		//remove from $handlers[$container]
		$where = array_search($gpOutKey,$handlers[$container]);
		
		if( ($where === null) || ($where === false) ){
			message($langmessage['OOPS'].' (2)');
			return;
		}
		
		array_splice($handlers[$container],$where,1);
		$this->SaveHandlers($handlers);

		
		
	}

	
	function GetAddonTexts($addon){
		global $dataDir,$langmessage,$config;
		
		$addonDir = $dataDir.'/data/_addoncode/'.$addon;
		if( !is_dir($addonDir) ){
			return false;
		}
		
		//not set up correctly
		if( !isset($config['addons'][$addon]['editable_text']) ){
			return false;
		}
		
		$file = $addonDir.'/'.$config['addons'][$addon]['editable_text'];
		if( !file_exists($file) ){
			return false;
		}
		
		include($file);
		if( !isset($texts) || !is_array($texts) || (count($texts) == 0 ) ){
			return false;
		}
		
		return $texts;
	}
		
	
	function SaveAddonText(){
		global $dataDir,$langmessage,$config;
		
		$addon = gpFiles::CleanArg($_REQUEST['addon']);
		$texts = $this->GetAddonTexts($addon);
		//not set up correctly
		if( $texts === false ){
			message($langmessage['OOPS'].' (0)');
			return;
		}
		
		$configBefore = $config;
		foreach($texts as $text){
			if( !isset($_POST['values'][$text]) ){
				continue;
			}
			
			
			$default = $text;
			if( isset($langmessage[$text]) ){
				$default = $langmessage[$text];
			}
			
			$value = htmlspecialchars($_POST['values'][$text]);
			
			if( ($value === $default) || (htmlspecialchars($default) == $value) ){
				unset($config['customlang'][$text]);
			}else{
				$config['customlang'][$text] = $value;
			}
		}			
		
		if( !admin_tools::SaveConfig() ){
			//these two lines are fairly useless when the ReturnHeader() is used
			$config = $configBefore;
			message($langmessage['OOPS'].' (1)');
		}else{
			
			$this->UpdateAddon($addon);

			message($langmessage['SAVED']);
			
		}
		
		$this->ReturnHeader();
	}
	
	function UpdateAddon($addon){
		if( !function_exists('OnTextChange') ){
			return;
		}
			
		AddonTools::SetDataFolder($addon);
		
		OnTextChange();
		
		AddonTools::ClearDataFolder();
	}
	
	function AddonText(){
		global $dataDir,$langmessage,$config;
		
		$addon = gpFiles::CleanArg($_REQUEST['addon']);
		$texts = $this->GetAddonTexts($addon);
		
		//not set up correctly
		if( $texts === false ){
			$this->EditText();
			return;
		}
		
		
		echo '<div class="inline_box" style="text-align:right">';
		echo '<form action="'.common::GetUrl('Admin_Theme_Content').'" method="post">';
		echo '<input type="hidden" name="cmd" value="saveaddontext" />';
		echo '<input type="hidden" name="return" value="" />'; //will be populated by javascript
		echo '<input type="hidden" name="addon" value="'.htmlspecialchars($addon).'" />'; //will be populated by javascript
		
		
		$count = count($texts);
		if( $count > 5 ){
			
			echo '<table><tr><td>';
			$half = ceil($count/2);
			
			$out = array_slice($texts,0,$half);
			$this->AddonTextFields($out);
			echo '</td><td>';
			$out = array_slice($texts,$half);
			$this->AddonTextFields($out);
			echo '</td></tr>';
			echo '</table>';
			
		}else{
			$this->AddonTextFields($texts);
		}
		echo ' <input type="submit" class="submit" name="aaa" value="'.$langmessage['save'].'" />';
			
			
		echo '</form>';
		echo '</div>';
		
	}
	
	function AddonTextFields($array){
		global $langmessage,$config;
		echo '<table class="bordered">';
			echo '<tr>';
			echo '<th>';
			echo $langmessage['default'];
			echo '</th>';
			echo '<th>';
			echo '</th>';
			echo '</tr>';

		$key =& $_GET['key'];
		foreach($array as $text){
			
			$default = $value = $text;
			if( isset($langmessage[$text]) ){
				$default = $value = $langmessage[$text];
			}
			if( isset($config['customlang'][$text]) ){
				$value = $config['customlang'][$text];
			}
			
			$style = '';
			if( $text == $key ){
				$style = ' style="background-color:#f5f5f5"';
			}
			
			echo '<tr'.$style.'>';
			echo '<td>';
			echo $text;
			echo '</td>';
			echo '<td>';
			echo '<input type="text" class="text" name="values['.htmlspecialchars($text).']" value="'.htmlspecialchars($value).'" />';
			echo '</td>';
			echo '</tr>';
			
		}
		echo '</table>';
	}
		
		

	
	
	function EditText(){
		global $config, $langmessage,$page;
		
		if( !isset($_GET['key']) ){
			message($langmessage['OOPS'].' (0)');
			return;
		}
		
		$default = $value = $key = $_GET['key'];
		if( isset($langmessage[$key]) ){
			$default = $value = $langmessage[$key];
			
		}
		if( isset($config['customlang'][$key]) ){
			$value = $config['customlang'][$key];
		}
		
		
		echo '<div class="inline_box">';
		echo '<form action="'.common::GetUrl('Admin_Theme_Content').'" method="post">';
		echo '<input type="hidden" name="cmd" value="savetext" />';
		echo '<input type="hidden" name="key" value="'.htmlspecialchars($key).'" />';
		echo '<input type="hidden" name="return" value="" />'; //will be populated by javascript
		
		echo '<table class="bordered">';
			echo '<tr>';
			echo '<th>';
			echo $langmessage['default'];
			echo '</th>';
			echo '<th>';
			echo '</th>';
			echo '</tr>';
			echo '<tr>';
			echo '<td>';
			echo $default;
			echo '</td>';
			echo '<td>';
			echo '<input type="text" class="text" name="value" value="'.htmlspecialchars($value).'" />';
			echo ' <input type="submit" class="submit" name="aaa" value="'.$langmessage['save'].'" />';
			echo '</td>';
			echo '</tr>';
		echo '</table>';
		
		echo '</form>';
		echo '</div>';
	}
	
		
	
	function SaveText(){
		global $config, $langmessage,$page;
		
		if( !isset($_POST['key']) ){
			message($langmessage['OOPS'].' (0)');
			return;
		}
		if( !isset($_POST['value']) ){
			message($langmessage['OOPS'].' (1)');
			return;
		}
		
		$default = $key = $_POST['key'];
		if( isset($langmessage[$key]) ){
			$default = $langmessage[$key];
		}
		
		$config['customlang'][$key] = $value = htmlspecialchars($_POST['value']);
		if( ($value === $default) || (htmlspecialchars($default) == $value) ){
			unset($config['customlang'][$key]);
		}
		
		if( admin_tools::SaveConfig() ){
			message($langmessage['SAVED']);
		}else{
			message($langmessage['OOPS'].' (s1)');
		}
		$this->ReturnHeader();
		
	}
	
	
	
	/*
	 * 
	 * 
	 * 
	 */

	
	function Show(){
		global $config,$page,$langmessage,$gptitles;

		echo '<h2>'.$langmessage['content_arrangement'].'</h2>';
		
		echo '<p>';
		echo $langmessage['DRAG-N-DROP-DESC'];
		echo '</p>';
		

		$theme_handlers =& $config['theme_handlers'];
	
		
		echo '<table class="bordered">';
		
		echo '<tr>';
			echo '<th>';
			echo $langmessage['themes'];
			echo '</th>';
			echo '<th>';
			echo $langmessage['modifications'];
			echo '</th>';
			echo '<th>';
			echo $langmessage['options'];
			echo '</th>';
			echo '</tr>';
		
		//collect list of themes
		$themes[$this->default_layout] = $this->default_color;
		$themes[$page->theme_name] = $page->theme_color;
		
		foreach($gptitles as $title => $info){
			if( !empty($info['theme']) ){
				list($layout,$color) = explode('/',$info['theme']);
				if( !isset($themes[$layout]) ){
					$themes[$layout] = $color;
				}
			}
		}
		
		foreach($theme_handlers as $theme => $info){
			if( !isset($themes[$theme]) ){
				$themes[$theme] = false;
			}
		}
		
		//show
		foreach($themes as $theme => $color){
			$this->ShowTheme($theme,$color);
		}
			
			
		echo '</table>';
		echo '<p>';
		echo $langmessage['see_also'];
		
		echo ' '.common::Link('Admin_Theme',$langmessage['theme_manager']);
		echo ', '.common::Link('Admin_Menu',$langmessage['file_manager']);
		echo '</p>';
		
		echo '<p>';
		echo '<< '.common::Link($config['homepath'],str_replace('_',' ',$config['homepath']));
		echo ', '.common::Link('Admin',$langmessage['admin']);
		echo '</p>';
		
	}
	
	function ShowTheme($layout,$color){
		global $page, $langmessage,$config;
		
		$theme_handlers =& $config['theme_handlers'];
		if( isset($theme_handlers[$layout]) ){
			$info = $theme_handlers[$layout];
		}else{
			$info = array();
		}
		
		
		echo '<tr>';

		echo '<td>';
		if( $page->theme_name == $layout ){
			echo ' <img src="'.common::GetDir('/include/imgs/accept.png').'" height="16" width="16"  alt="" float="left" title="'.$langmessage['current_theme'].'"/> ';
		}else{
			echo ' <img src="'.common::GetDir('/include/imgs/blank.gif').'" height="16" width="16"  alt="" float="left"/> ';
		}

		$selector = $layout;
		if( $color ){
			$selector = $layout.'/'.$color;
		}
		echo common::Link('Admin_Theme_Content',str_replace('_',' ',$layout),'theme='.urlencode($selector),' name="creq" ');
		//echo $layout;
		
		if( $this->default_layout == $layout ){
			echo ' <span class="admin_note">(';
			echo $langmessage['default'];
			echo ')</span>';
		}
		echo '</td>';
		echo '<td>';
		$count = 0;
		foreach($info as $val){
			$int = count($val);
			if( $int === 0){
				$count++;
			}
			$count += $int;
		}
		echo $count;
		echo '</td>';
		echo '<td>';
		
		if( is_array($info) && (count($info) > 0) ){
			echo common::Link('Admin_Theme_Content',$langmessage['restore_defaults'],'cmd=restore&theme='.urlencode($selector),' name="creq" ');
		}
			
		echo '</td>';
		echo '</tr>';
		
	}	
	
	
	
	/*
	 * 
	 * Link Specific Functions
	 * 
	 */

	function SaveLinks(){
		global $config,$langmessage,$gpOutConf;
		
		if( !$this->ParseHandlerInfo($_POST['handle'],$curr_info) ){
			message($langmessage['OOPS'].' (0)');
			return;
		}
		
		$new_gpOutKey = $_POST['new_handle'];
		if( !isset($gpOutConf[$new_gpOutKey]) || !isset($gpOutConf[$new_gpOutKey]['link']) ){
			message($langmessage['OOPS'].' (1)');
			return;
		}
		
		//prep
		$handlers = $this->GetAllHandlers();
		$container =& $curr_info['container'];
		$this->PrepContainerHandlers($handlers,$container,$curr_info['gpOutKey']);



		if( !$this->AddToContainer($handlers[$container],$curr_info['gpOutKey'],$new_gpOutKey,true) ){
			return;
		}
		
		$this->SaveHandlers($handlers);
		
	}
	
	function SelectLinks(){
		global $langmessage,$config,$page,$gpOutConf;
		
		if( !$this->ParseHandlerInfo($_GET['handle'],$curr_info) ){
			message($langmessage['OOPS'].' (0)');
			return;
		}
		
		$handlers = $this->GetAllHandlers();
		$curr_gpOutInfo = gpOutput::GetgpOutInfo($curr_info['gpOutKey']);
		
		if( !isset($curr_gpOutInfo['link']) ){
			message($langmessage['OOPS']);
			return;
		}
		
		
		echo '<div class="inline_box">';
		echo '<form action="'.common::GetUrl('Admin_Theme_Content').'" method="post">';
		echo '<input type="hidden" name="handle" value="'.htmlspecialchars($_GET['handle']).'" />';
		echo '<input type="hidden" name="return" value="" />';
		
		echo '<h2>'.$langmessage['link_configuration'].'</h2>';
		echo '<table>';
		echo '<tr>';
			echo '<td>';
			echo '<select name="new_handle">';
			foreach($gpOutConf as $outKey => $info){
				
				if( !isset($info['link']) ){
					continue;
				}

				if( $outKey == $curr_info['key'] ){
					echo '<option value="'.$outKey.'" selected="selected">';
				}else{
					echo '<option value="'.$outKey.'">';
				}
				if( isset($langmessage[$info['link']]) ){
					echo $langmessage[$info['link']];
				}else{
					$info['link'];
				}
				echo '</option>';
			}
			echo '</select>';
			
			echo '</td>';
			echo '</tr>';
			
		echo '<tr>';
			echo '<td>';
			echo '<input type="hidden" name="cmd" value="save" />';
			echo '<input type="submit" class="submit" name="aaa" value="'.$langmessage['save'].'" /> ';
			
			echo '</td>';
			echo '</tr>';
		echo '</table>';
		
		echo '<p class="admin_note">';
		echo $langmessage['see_also'];
		echo ' ';
		echo common::Link('Admin_Menu',$langmessage['file_manager']);
		echo ', ';
		echo common::Link('Admin_Theme_Content',$langmessage['content_arrangement']);
		echo '</p>';
		
		echo '</form>';
		echo '</div>';
	}
	
	
	
	
	/*
	 * 
	 * 
	 * 
	 * General Arrangement Functions
	 * 
	 * 
	 * 
	 * 
	 */
	
	
	function Restore(){
		global $config,$langmessage,$page;
		
		if( !isset( $config['theme_handlers'][$page->theme_name] )  ){
			message($langmessage['OOPS']);
			return;
		}
		
		$this->SaveHandlers(array());
	}
	
	function Drag(){
		global $config,$page,$gpOutConf,$langmessage;
		
		
		if( !$this->GetValues($_GET['dragging'],$from_container,$from_gpOutKey) ){
			message($langmessage['OOPS'].' (0)');
			return;
		}
		if( !$this->GetValues($_GET['to'],$to_container,$to_gpOutKey) ){
			message($langmessage['OOPS'].'(1)');
			return;
		}
		
		//prep work
		$handlers = $this->GetAllHandlers();
		$this->PrepContainerHandlers($handlers,$from_container,$from_gpOutKey);
		$this->PrepContainerHandlers($handlers,$to_container,$to_gpOutKey);
		
		
		//remove from from_container
		if( !isset($handlers[$from_container]) || !is_array($handlers[$from_container]) ){
			message($langmessage['OOPS'].' (2)');
			return;
		}
		$where = array_search($from_gpOutKey,$handlers[$from_container]);
		if( ($where === null) || ($where === false) ){
			message($langmessage['OOPS']. '(3)');
			return;
		}
		array_splice($handlers[$from_container],$where,1);
		

		
		if( !$this->AddToContainer($handlers[$to_container],$to_gpOutKey,$from_gpOutKey,false) ){
			return;
		}
		$this->SaveHandlers($handlers);
		
	}
	
	function SaveHandlers($handlers){
		global $config,$page,$langmessage;
		
		$theme = $page->theme_name;
		
		$oldHandlers = $config['theme_handlers'][$theme];
		if( count($handlers) === 0 ){
			unset($config['theme_handlers'][$theme]);
		}else{
			$config['theme_handlers'][$theme] = $handlers;
		}
		
		if( admin_tools::SaveConfig() ){
			
			message($langmessage['SAVED']);
			
		}else{
			$config['theme_handlers'][$theme] = $oldHandlers;
			message($langmessage['OOPS'].' (s1)');
		}
		
		$this->ReturnHeader();

	}
	
	function ReturnHeader(){
		
		if( empty($_POST['return']) ){
			return;
		}
		
		$return = $_POST['return'];
		//$return = str_replace('cmd=','x=',$return); //some dynamic plugins rely on cmd to show specific pages.
		
		if( strpos($return,'http') == 0 ){
			header('Location: '.$return);
			die();
		}
			
		header('Location: '.common::GetUrl($_POST['return'],false));
		die();
	}
	
	
	function ParseHandlerInfo($str,&$info){
		global $config,$gpOutConf;
		
		if( substr_count($str,'|') !== 1 ){
			return false;
		}
		
		
		list($container,$fullKey) = explode('|',$str);
		
		$arg = '';
		$pos = strpos($fullKey,':');
		$key = $fullKey;
		if( $pos > 0 ){
			$arg = substr($fullKey,$pos+1);
			$key = substr($fullKey,0,$pos);
		}
		
		if( !isset($gpOutConf[$key]) && !isset($config['gadgets'][$key]) ){
			return false;
		}
		
		$info = array();
		$info['gpOutKey'] = $fullKey;
		$info['container'] = $container;
		$info['key'] = $key;
		$info['arg'] = $arg;
		
		return true;
		
	}
	
	
	
	
	function GetAllHandlers(){
		global $page,$config;
		
		if( !isset($config['theme_handlers'][$page->theme_name]) ){
			$config['theme_handlers'][$page->theme_name] = array();
		}
		
		$handlers = $config['theme_handlers'][$page->theme_name];
		if( !is_array($handlers) || count($handlers) < 1 ){
			$handlers = array();
		}
		return $handlers;
	}
	
	
	//set default values if not set
	function PrepContainerHandlers(&$handlers,$container,$gpOutKey){
		if( isset($handlers[$container]) && is_array($handlers[$container]) ){
			return;
		}
		$handlers[$container] = $this->GetDefaultList($container,$gpOutKey);
	}

	
	
	function GetDefaultList($container,$gpOutkey){
		global $config;

		if( $container !== 'GetAllGadgets' ){
			return array($gpOutkey);
		}
		
		$result = array();
		if( isset($config['gadgets']) && is_array($config['gadgets']) ){
			foreach($config['gadgets'] as $gadget => $info){
				$result[] = $gadget;
			}
		}
		return $result;
	}
	
	function GetValues($a,&$container,&$gpOutKey){
		if( substr_count($a,'|') !== 1 ){
			return false;
		}
		
		list($container,$gpOutKey) = explode('|',$a);
		return true;
	}
}
