<?php
defined('is_running') or die('Not an entry point...');

includeFile('tool/recaptcha.php');

class special_contact{
	var $sent = false;
	
	function special_contact(){
		global $page,$langmessage,$config;
		
		$page->label = $langmessage['contact'];
		
		if( empty($config['toemail']) ){
			
			if( common::LoggedIn() ){
				$url = common::GetUrl('Admin_Configuration');
				message($langmessage['enable_contact'],$url);
			}

			echo $langmessage['not_enabled'];
			return;
		}
		
		$cmd = common::GetCommand();
		switch($cmd){
			case 'send':
				if( $this->SendMessage() ){
					$this->sent = true;
					break;
				}
			default:
			break;
		}
		
		$this->ShowForm();
		
	}
	
	
	function SendMessage(){
		global $langmessage,$config;
		
		$headers = array();
		
		//captcha
		if( !gp_recaptcha::Check() ){
			return;
		}
		
		//subject
		$_POST += array('subject'=>'');
		$_POST['subject'] = strip_tags($_POST['subject']);
		
		//message
		$tags = '<p><div><span><font><b><i><tt><em><i><a><strong><blockquote>';
		$message = nl2br(strip_tags($_POST['message'],$tags));
		
		
		//reply name
		if( !empty($_POST['email']) ){
			
			//check format
			if( !eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $_POST['email']) ){
				message($langmessage['invalid_email']);
				return false;
			}
			
			$replyName = str_replace(array("\r","\n"),array(' '),$_POST['name']);
			$replyName = strip_tags($replyName);
			$headers[] = 'Reply-To: '.$replyName.'<'.$_POST['email'].'>';
		}
		
		
		//
		//check for required values
		//
		if( strpos($config['require_email'],'email') !== false ){
			if( empty($_POST['email']) ){
				$field = gpOutput::SelectText('your_email');
				message($langmessage['OOPS_REQUIRED'],$field);
				return false;
			}
		}
		if( strpos($config['require_email'],'none') === false ){
			
			if( empty($_POST['subject']) ){
				$field = gpOutput::SelectText('subject');
				message($langmessage['OOPS_REQUIRED'],$field);
				return false;
			}
			if( empty($message) ){
				$field = gpOutput::SelectText('message');
				message($langmessage['OOPS_REQUIRED'],$field);
				return false;
			}
		}
			
		
		

		

		includeFile('tool/email.php');
		if( gp_email::SendEmail($config['toemail'], $_POST['subject'], $message, $headers) ){
			message($langmessage['message_sent']);
			return true;
		}
		
		message($langmessage['OOPS']);
		return false;
	}
	
	function ShowForm(){
		global $page,$langmessage,$config,$rootDir;
		
		$attr = '';
		if( $this->sent ){
			$attr = ' readonly="readonly" ';
		}
			
		$_GET += array('name'=>'','email'=>'','subject'=>'','message'=>'');
		$_POST += array('name'=>$_GET['name'],'email'=>$_GET['email'],'subject'=>$_GET['subject'],'message'=>$_GET['message']);
		
		
		$require_email =& $config['require_email'];
		
		echo '<form class="contactform" action="'.common::GetUrl('Special_Contact').'" method="post">';
		echo gpOutput::GetExtra('Contact');
		echo '<table>';
		echo '<tr>';
			echo '<td class="left">';
			echo gpOutput::ReturnText('your_name');
			//echo $langmessage['your_name'];
			echo '</td>';
			echo '<td>';
			echo '<input class="input text" class="text" name="name" value="'.htmlspecialchars($_POST['name']).'" '.$attr.' />';
			echo '</td>';
			echo '</tr>';
			
		echo '<tr>';
			echo '<td class="left">';
			echo gpOutput::ReturnText('your_email');
			if( strpos($require_email,'email') !== false ){
				echo '*';
			}
			echo '</td>';
			echo '<td>';
			echo '<input class="input text" type="text" name="email" value="'.htmlspecialchars($_POST['email']).'" '.$attr.'/>';
			echo '</td>';
			echo '</tr>';
			
		echo '<tr>';
			echo '<td class="left">';
			echo gpOutput::ReturnText('subject');
			if( strpos($require_email,'none') === false ){
				echo '*';
			}
			echo '</td>';
			echo '<td>';
			echo '<input class="input text" type="text" name="subject" value="'.htmlspecialchars($_POST['subject']).'" '.$attr.'/>';
			echo '</td>';
			echo '</tr>';

		echo '<tr>';
			echo '<td class="left">';
			echo gpOutput::ReturnText('message');
			if( strpos($require_email,'none') === false ){
				echo '*';
			}
			echo '</td>';
			echo '<td>';
			echo '<textarea name="message" '.$attr.'>';
			echo htmlspecialchars($_POST['message']);
			echo '</textarea>';
			echo '</td>';
			echo '</tr>';
			
			
		if( gp_recaptcha::isActive() ){
			echo '<tr>';
			echo '<td class="left">';
			echo gpOutput::ReturnText('captcha');
			echo '</td>';
			echo '<td>';
			gp_recaptcha::Form();
			echo '</td>';
			echo '</tr>';
		}
		
		echo '<tr>';
			echo '<td class="left">';
			echo '</td>';
			echo '<td>';
			if( $this->sent ){
				echo gpOutput::ReturnText('message_sent');
			}else{
				echo '<input type="hidden" name="cmd" value="send" />';
				//echo '<input type="submit" class="submit" name="aaa" value="'.$langmessage['send_message'].'" />';
				$html = '<input type="submit" class="submit" name="aaa" value="%s" />';
				echo gpOutput::ReturnText('send_message',$html);
			}
			echo '</td>';
			echo '</tr>';
			
		echo '</table>';
		echo '</form>';
	}
	
}
