<?php
defined('is_running') or die('Not an entry point...');


class admin_configuration{
	
	var $variables;
	var $defaultVals = array();
	
	function admin_configuration(){
		global $langmessage;
		
		
		$this->variables = array(
		
						// these values exist and are used, but not necessarily needed
		
						// these values aren't used
						//'author'=>'',
						//'timeoffset'=>'',
						//'fromname'=>'',
						//'fromemail'=>'',
						//'contact_message'=>'',
						//'dateformat'=>'',

						'title'=>'',
						'keywords'=>'',
						'desc'=>'',
						'colorbox_style' => array('example1'=>'Example 1', 'example2'=>'Example 2', 'example3'=>'Example 3', 'example4'=>'Example 4', 'example5'=>'Example 5', 'example6'=>'Example 6'),
						'language'=>'',
						'langeditor'=>'',
						'maximgarea'=>'',
						'jquery'=>'',
						'hidegplink'=>'',
						'contact_config'=>false,
						'toemail'=>'',
						'require_email'=>'',
						'recaptcha_public'=>'',
						'recaptcha_private'=>'',
						'recaptcha_language'=>'',
						);
						
		$cmd = common::GetCommand();
		switch($cmd){
			case 'save_config':
				$this->SaveConfig();
			break;
		}
		
		echo '<h2>'.$langmessage['configuration'].'</h2>';
		$this->showForm();
	}
	
	
	function SaveConfig(){
		global $config, $dataDir, $langmessage;
		
		$possible = $this->variables;
		
		if( !is_numeric($_POST['maximgarea']) ){
			unset($_POST['maximgarea']);
		}
	
		foreach($_POST as $key => $value ){
			if( isset($possible[$key]) ){
				$config[$key] = $value;
			}
		}
		
		if( !admin_tools::SaveConfig() ){
			message($langmessage['OOPS']);
			return false;
		}
		message($langmessage['SAVED']);
	}
	
	
	function getValues(){
		global $config;
		
		if( $_SERVER['REQUEST_METHOD'] != 'POST'){
			$show = $config;
		}else{
			$show = $_POST;
		}
		if( empty($show['jquery']) ){
			$show['jquery'] = 'local';
		}
		if( empty($show['recaptcha_language']) ){
			$show['recaptcha_language'] = 'inherit';
		}
		
	
		return $show;
	}
	
	function getPossible(){
		global $rootDir,$langmessage;
		
		$possible = $this->variables;
		
		//$langDir = $rootDir.'/include/thirdparty/fckeditor/editor/lang'; //fckeditor
		$langDir = $rootDir.'/include/thirdparty/ckeditor_32/lang'; //ckeditor
		
		$possible['langeditor'] = gpFiles::readDir($langDir,'js');
		unset($possible['langeditor']['_languages']);
		$possible['langeditor']['inherit'] = ' '.$langmessage['default']; //want it to be the first in the list
		asort($possible['langeditor']);
		
		
		//recaptcha language
		$possible['recaptcha_language'] = array();
		$possible['recaptcha_language']['inherit'] = $langmessage['default'];
		$possible['recaptcha_language']['en'] = 'en';
		$possible['recaptcha_language']['nl'] = 'nl';
		$possible['recaptcha_language']['fr'] = 'fr';
		$possible['recaptcha_language']['de'] = 'de';
		$possible['recaptcha_language']['pt'] = 'pt';
		$possible['recaptcha_language']['ru'] = 'ru';
		$possible['recaptcha_language']['es'] = 'es';
		$possible['recaptcha_language']['tr'] = 'tr';

		
		
		//website language
		$langDir = $rootDir.'/include/languages';
		$possible['language'] = gpFiles::readDir($langDir,1);
		asort($possible['language']);
		
		//jQuery
		$possible['jquery'] = array('local'=>'Local','google'=>'Google');
		
		//jQuery
		$possible['hidegplink'] = array(''=>'Show','hide'=>'Hide');
		
		//
		$possible['require_email'] = array(
										'none'=>'None',
										''=>'Subject & Message',
										'email'=>'Subject, Message & Email');
		
		return $possible;
	}
	
	function showForm(){
		global $langmessage,$languages;
		$possibleValues = $this->getPossible();
		
		
		$array = $this->getValues();
		
		echo '<form action="'.common::GetUrl('Admin_Configuration').'" method="post">';
		echo '<table cellpadding="4" class="bordered">';
		
		//order by the possible values
		foreach($possibleValues as $key => $possibleValue){
			
			if( $possibleValue === false ){
				echo '<tr><th colspan="2">';
				if( isset($langmessage[$key]) ){
					echo $langmessage[$key];
				}else{
					echo $key;
				}
				echo '</th>';
				echo '</tr>';
				continue;
			}
			
			if( isset($array[$key]) ){
				$value = $array[$key];
			}else{
				$value = '';
			}
			
			echo "\n\n";
			echo '<tr><td style="white-space:nowrap">';
			echo '<b>';
			if( isset($langmessage[$key]) ){
				echo $langmessage[$key];
			}else{
				echo $key;
			}
			echo '</b>';
			echo '</td>';
			echo '<td>';
			
			if( $possibleValues[$key] === false ){
				echo 'unavailable';
			}elseif( is_array($possibleValues[$key]) ){
				$this->formSelect($key,$possibleValues[$key],$value);
			}else{
				$this->formInput($key,$value);
			}
			
			if( isset($this->defaultVals[$key]) ){
				echo '<br/> <span class="sm">';
				echo $this->defaultVals[$key];
				echo '</span>';
			}
			

/*
			echo '</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td colspan="2">';

*/
			if( isset($langmessage['about_config'][$key]) ){
				echo '<br/>';
				echo $langmessage['about_config'][$key];
			}
			echo '</td></tr>';
			
			
		}
		
		echo '<tr>';
		echo '<td colspan="3">';
		echo '<div style="text-align:center;margin:1em">';
		echo '<input type="hidden" name="cmd" value="save_config" />';
		echo '<input value="'.$langmessage['save'].'" type="submit" class="submit" name="aaa" accesskey="s" />';
		echo ' &nbsp; ';
 		echo '<input type="reset"  />';
 		echo '</div>';
 		echo '</td>';
 		echo '</tr>';
		
		echo '</table>';
		echo '</form>';
		
	}
	
	
	//
	//	Form Functions
	//

	function formInput($name,$value){
		global $langA;
		
		$len = (strlen($value)+20)/20;
		$len = round($len);
		$len = $len*20;
		
		$value = htmlspecialchars($value);
		
		static $textarea = '<textarea name="%s" cols="30" rows="%d">%s</textarea>';
		if($len > 100 && (strpos($value,' ') != false) ){
			$cols=40;
			$rows = ceil($len/$cols);
			echo sprintf($textarea,$name,$rows,$value);
			return;
		}
		
		$len = min(40,$len);
		static $text = '<input name="%s" size="%d" value="%s" type="text" class="text"/>';
		echo "\n".sprintf($text,$name,$len,$value);
	}
	
	function formSelect($name,$possible,$value=null){
		
		echo "\n".'<select name="'.$name.'">';
		if( !isset($possible[$value]) ){
			echo '<option value="" selected="selected"></option>';
		}
		
		$this->formOptions($possible,$value);
		echo '</select>';
	}
	
	function formOptions($array,$current_value){
		global $languages;
		
		foreach($array as $key => $value){
			if( is_array($value) ){
				echo '<optgroup label="'.$value.'">';
				$this->formOptions($value,$current_value);
				echo '</optgroup>';
				continue;
			}
			
			if($key == $current_value){
				$focus = ' selected="selected" ';
			}else{
				$focus = '';
			}
			if( isset($languages[$value]) ){
				$value = $languages[$value];
			}

			echo '<option value="'.htmlspecialchars($key).'" '.$focus.'>'.$value.'</option>';
			
		}
		
	}
	
}

