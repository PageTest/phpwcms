<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2007 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
   This script is part of PHPWCMS. The PHPWCMS web content management system is
   free software; you can redistribute it and/or modify it under the terms of
   the GNU General Public License as published by the Free Software Foundation;
   either version 2 of the License, or (at your option) any later version.
  
   The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html
   A copy is found in the textfile GPL.txt and important notices to the license 
   from the author is found in LICENSE.txt distributed with these scripts.
  
   This script is distributed in the hope that it will be useful, but WITHOUT ANY 
   WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
   PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 
   This copyright notice MUST APPEAR in all copies of the script!
*************************************************************************************/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// frontend login

$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);

if(!empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_cntpart/felogin/'.$crow["acontent_template"])) {

	$_loginData						= @unserialize($crow["acontent_form"]);
			
	$_loginData['template']			= @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/felogin/'.$crow["acontent_template"]);

	$_loginData['form']				= get_tmpl_section('LOGIN_FORM', $_loginData['template']);
	$_loginData['logged_in']		= get_tmpl_section('LOGIN_IS', $_loginData['template']);
	
	$_loginData['register']			= get_tmpl_section('PROFILE_REGISTER', $_loginData['template']);
	$_loginData['manage']			= get_tmpl_section('PROFILE_MANAGE', $_loginData['template']);
	
	$_loginData['reminder']			= get_tmpl_section('REMINDER_FORM', $_loginData['template']);
	$_loginData['reminder_success']	= get_tmpl_section('REMINDER_SUCCESS', $_loginData['template']);
	$_loginData['reminder_email']	= get_tmpl_section('REMINDER_EMAIL', $_loginData['template']);


	$_loginData['session_key']	= session_id();
	
	$_loginData['template']		= $_loginData['form'];
	$_loginData['error']		= false;
	$_loginData['login']		= '';
	$_loginData['password']		= '';
	$_loginData['remember']		= 0;
	$_loginData['remind_data']	= '';
	
	
	$_loginData['felogin_profile_registration']	= empty($_loginData['felogin_profile_registration']) ? 0 : 1;
	$_loginData['felogin_profile_manage']		= empty($_loginData['felogin_profile_manage']) ? 0 : 1;
	$_loginData['validate_db']['userdetail']	= empty($_loginData['felogin_validate_userdetail'])  ? 0 : 1;
	$_loginData['validate_db']['backenduser']	= empty($_loginData['felogin_validate_backenduser']) ? 0 : 1;
	
	// handle Login
	if(isset($_POST['feLogin'])) {
	
		$_loginData['login']						= slweg($_POST['feLogin']);
		$_loginData['password']						= slweg($_POST['fePassword']);
		$_loginData['remember']						= empty($_POST['feRemember']) ? 0 : 1;
		
		$_loginData['query_result'] = _checkFrontendUserLogin($_loginData['login'], md5($_loginData['password']), $_loginData['validate_db']);
		
		// ok, and now check if we got valid login data
		if($_loginData['query_result'] !== false && is_array($_loginData['query_result']) && count($_loginData['query_result'])) {
		
			$_SESSION[ $_loginData['session_key'] ]				= $_loginData['login'];
			$_SESSION[ $_loginData['session_key'].'_userdata']	= _getFrontendUserBaseData($_loginData['query_result']);
			
			if($_loginData['remember'] && !empty($_loginData['felogin_cookie_expire'])) {

				setcookie(	'phpwcmsFeLoginRemember', 
							$_loginData['login'].'##-|-##'.md5($_loginData['password']).'##-|-##'.$_loginData['validate_db']['userdetail'].'##-|-##'.$_loginData['validate_db']['backenduser'], 
							time()+$_loginData['felogin_cookie_expire'], '/', getCookieDomain() );

			}
		
		} else {
		
			$_loginData['error'] = true;
		
		}
	
	} elseif(isset($_POST['feReminder'])) {
	
		$_loginData['remind_data'] = slweg($_POST['feReminder']);
	
		// check if valid email - send login
		if( $_loginData['remind_data'] && is_valid_email($_loginData['remind_data']) ) {
		
			if($_loginData['validate_db']['userdetail']) {
				$sql  = 'SELECT detail_login AS LOGIN, detail_email AS EMAIL FROM '.DB_PREPEND."phpwcms_userdetail WHERE LOWER(detail_email)='";
				$sql .= aporeplace(strtolower($_loginData['remind_data']))."' LIMIT 1";
				$result = _dbQuery($sql);
			}
			
			// hm, seems no user found - OK test against cms users
			if($_loginData['validate_db']['backenduser'] && !isset($result[0])) {
				$sql  = 'SELECT usr_login AS LOGIN, usr_email AS EMAIL FROM '.DB_PREPEND.'phpwcms_user WHERE ';
				$sql .= "LOWER(usr_email)='".aporeplace(strtolower($_loginData['remind_data']))."' LIMIT 1";
				$result = _dbQuery($sql);
			}
			
			if(isset($result[0])) {
				$_loginData['remind_login'] = $result[0];
			}

		// otherwise check login and send password
		} elseif($_loginData['remind_data']) {

			if($_loginData['validate_db']['userdetail']) {
				$sql  = 'SELECT detail_id, detail_login AS LOGIN, detail_email AS EMAIL FROM '.DB_PREPEND."phpwcms_userdetail WHERE ";
				$sql .= "detail_login='".aporeplace($_loginData['remind_data'])."' LIMIT 1";
				$result = _dbQuery($sql);
				
				if(isset($result[0])) {
					$result[0]['PASSWORD'] = generic_string(8);
					_dbUpdate('phpwcms_userdetail', array('detail_password'=>md5($result[0]['PASSWORD'])), 'WHERE detail_id='.$result[0]['detail_id']);
					$_loginData['remind_password'] = $result[0];
				}
			}
			
			// hm, seems no user found - OK test against cms users
			if($_loginData['validate_db']['backenduser'] && !isset($result[0])) {
				$sql  = 'SELECT usr_id, usr_login AS LOGIN, usr_email AS EMAIL FROM '.DB_PREPEND.'phpwcms_user WHERE ';
				$sql .= "usr_login='".aporeplace($_loginData['remind_data'])."' LIMIT 1";
				$result = _dbQuery($sql);
				
				if(isset($result[0])) {
					$result[0]['PASSWORD'] = generic_string(8);
					_dbUpdate('phpwcms_user', array('usr_pass'=>md5($result[0]['PASSWORD'])), 'WHERE usr_id='.$result[0]['usr_id']);
					$_loginData['remind_password'] = $result[0];
				}
			}
		}
	
		if(isset($_loginData['remind_password']) || isset($_loginData['remind_login'])) {
		
			$_loginData['reminder'] = $_loginData['reminder_success'];
		
			$_loginData['LOGIN_URL'] = 'index.php'.returnGlobalGET_QueryString('', array(), array('profile_manage', 'profile_register', 'profile_reminder') );
		
			$_loginData['reminder_email'] = str_replace('{LOGIN_URL}', PHPWCMS_URL . $_loginData['LOGIN_URL'], $_loginData['reminder_email']);
			
			if(isset($_loginData['remind_password'])) {
			
				$_loginData['reminder_email']	= str_replace('{LOGIN}', $_loginData['remind_password']['LOGIN'], $_loginData['reminder_email']);
				$_loginData['reminder_email']	= str_replace('{PASSWORD}', $_loginData['remind_password']['PASSWORD'], $_loginData['reminder_email']);
				$_loginData['reminder_to']		= $_loginData['remind_password']['EMAIL'];
			
				$_loginData['reminder_email_body'] = returnTagContent( $_loginData['reminder_email'], 'PASSWORD_EMAIL' );
				$_loginData['reminder_email_body'] = $_loginData['reminder_email_body']['tag'];
				
			} else {
			
				$_loginData['reminder_email']	= str_replace('{LOGIN}', $_loginData['remind_login']['LOGIN'], $_loginData['reminder_email']);
				$_loginData['reminder_to']		= $_loginData['remind_login']['EMAIL'];
				
				$_loginData['reminder_email_body'] = returnTagContent( $_loginData['reminder_email'], 'LOGIN_EMAIL' );
				$_loginData['reminder_email_body'] = $_loginData['reminder_email_body']['tag'];
			
			}
		
			$_loginData['reminder_email_subject'] =  returnTagContent( $_loginData['reminder_email'], 'SUBJECT' ) ;
			$_loginData['reminder_email_subject'] =  trim( $_loginData['reminder_email_subject']['tag'] );
			
			@sendEmail( array(	'recipient' => $_loginData['reminder_to'], 
								'subject' => $_loginData['reminder_email_subject'],
								'text' => $_loginData['reminder_email_body'] 
							) );			
			
		
		} else {
		
			$_loginData['error'] = true;
		
		}
	
	}
	
	// register profile default
	$_loginData['get_profile_register']	= 'create';
	$_loginData['get_profile_manage']	= 'edit';
	
	
	
	if(_getFeUserLoginStatus()) {
	
		// user is logged in
		if(isset($_POST['feLogin'])) {
			headerRedirect(decode_entities(FE_CURRENT_URL));
		}
		
		// manage account
		if($_loginData['felogin_profile_manage'] && isset($_getVar['profile_manage'])) {
		
			$_loginData['get_profile_manage'] = strval($_getVar['profile_manage']);
		
			$_loginData['template']	 = $_loginData['manage'];
		
		} else {
		
			$_loginData['template']	= $_loginData['logged_in'];
			$_loginData['template']	= str_replace('{LOGIN}', html_specialchars( $_SESSION[ $_loginData['session_key'] ] ), $_loginData['template']);
	
		}
	
	// check if user can register and if register form should be displayed
	} elseif($_loginData['felogin_profile_registration'] && isset($_getVar['profile_register'])) {
	
		$_loginData['get_profile_register'] = strval($_getVar['profile_register']);
	
		$_loginData['template'] = $_loginData['register'];	
		
	} elseif(isset($_POST['feReminder']) || isset($_getVar['profile_reminder'])) {
		
		$_loginData['template'] = render_cnt_template($_loginData['reminder'], 'ERROR', ($_loginData['error'] ? 'login/email wrong' : '') );
		$_loginData['template'] = render_cnt_template($_loginData['template'], 'REMINDER', html_specialchars($_loginData['remind_data']) );
	
	} else {
	
		$_loginData['template'] = render_cnt_template($_loginData['template'], 'ERROR', ($_loginData['error'] ? 'login/pass wrong' : '') );
		$_loginData['template'] = render_cnt_template($_loginData['template'], 'LOGIN', html_specialchars($_loginData['login']));
		$_loginData['template'] = render_cnt_template($_loginData['template'], 'PASSWORD', '');
		$_loginData['template'] = render_cnt_template($_loginData['template'], 'REMEMBER', ($_loginData['remember'] ? ' checked="checked"' : '') );
	
	}
	
	// check register profile
	if($_loginData['felogin_profile_registration']) {
		// possible -> set link to form
		$_loginData['uri'] = 'index.php'.returnGlobalGET_QueryString('htmlentities', array('profile_register'=>$_loginData['get_profile_register']), array('profile_manage', 'profile_reminder') );
		$_loginData['template'] = render_cnt_template($_loginData['template'], 'REGISTER_PROFILE', $_loginData['uri'] );
	} else {
		// not possible
		$_loginData['template'] = render_cnt_template($_loginData['template'], 'REGISTER_PROFILE', '' );
	}
	
	// check manage profile
	if($_loginData['felogin_profile_manage']) {
		// possible -> set link to form
		$_loginData['uri'] = 'index.php'.returnGlobalGET_QueryString('htmlentities', array('profile_manage'=>$_loginData['get_profile_manage']), array('profile_register', 'profile_reminder') );
		$_loginData['template'] = render_cnt_template($_loginData['template'], 'MANAGE_PROFILE', $_loginData['uri'] );
	} else {
		// not possible	
		$_loginData['template'] = render_cnt_template($_loginData['template'], 'MANAGE_PROFILE', '' );
	}
	
	$_loginData['uri'] = 'index.php'.returnGlobalGET_QueryString('htmlentities', array('profile_reminder'=>'1'), array('profile_manage', 'profile_register') );
	$_loginData['template'] = render_cnt_template($_loginData['template'], 'REMINDER_FORM', $_loginData['uri'] );
	
	$_loginData['uri'] = 'index.php'.returnGlobalGET_QueryString('htmlentities', array(), array('profile_manage', 'profile_register', 'profile_reminder') );
	$CNT_TMP .=  str_replace(array('{FORM_TARGET}', '{LOGIN_URL}'), $_loginData['uri'], $_loginData['template']);

}

?>