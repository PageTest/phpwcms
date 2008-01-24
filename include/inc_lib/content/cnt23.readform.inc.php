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



// email form new
$content["form"]['subject'] 		= clean_slweg($_POST["cform_subject"]);
$content["form"]['startup'] 		= slweg($_POST["cform_startup"]);
$content["form"]['startup_html']	= intval($_POST["cform_startup_html"]) ? 1 : 0;
$content["form"]["class"]			= slweg($_POST["cform_class"]);
$content["form"]["error_class"]		= slweg($_POST["cform_error_class"]);
$content["form"]["label_wrap"]		= slweg($_POST["cform_label_wrap"]);
$content["form"]["cform_reqmark"]	= slweg($_POST["cform_reqmark"]);



$content["form"]["cc"]				= convertStringToArray(str_replace(array(' ',','), ';', clean_slweg($_POST["cform_cc"])),';');
foreach($content["form"]["cc"] as $e_key => $e_value) {
	if(!is_valid_email($content["form"]["cc"][$e_key])) {
		unset($content["form"]["cc"][$e_key]);
	}
}
$content["form"]["cc"] = implode(';', $content["form"]["cc"]);			

$content["form"]["targettype"]	= clean_slweg($_POST["cform_targettype"]);

$content["form"]["target"]		= clean_slweg($_POST["cform_target"]);
$content["form"]["target"]		= sanitize_multiple_emails($content["form"]["target"]);
$content["form"]["target"]		= strtolower($content["form"]["target"]);
$content["form"]["target"]		= explode(';', $content["form"]["target"]);
if(!empty($content["form"]["target"]) && is_array($content["form"]["target"]) && count($content["form"]["target"])) {
	foreach($content["form"]["target"] as $e_key => $e_value) {
		if(!is_valid_email($content["form"]["target"][$e_key])) {
			unset($content["form"]["target"][$e_key]);
		}
	}
	$content["form"]["target"] = implode(';', $content["form"]["target"]);
} else {
	$content["form"]["target"] = '';
}
if(empty($content["form"]["target"]) && $content["form"]["targettype"] == 'email') {
	$content["form"]["target"] = $phpwcms['SMTP_FROM_EMAIL'];
}

$content["form"]["subjectselect"]	= clean_slweg($_POST["cform_subjectselect"]);

$content["form"]["sendertype"]		= clean_slweg($_POST["cform_sendertype"]);
$content["form"]["sender"]			= clean_slweg($_POST["cform_sender"]);
$content["form"]["sender"]			= str_replace(' ', ';', $content["form"]["sender"]);
list($content["form"]["sender"])	= explode(';', $content["form"]["sender"]);
$content["form"]["sender"]			= trim($content["form"]["sender"]);
if(!is_valid_email($content["form"]["sender"])) {
	$content["form"]["sender"]		= '';
	if($content["form"]["sendertype"] == 'email') {
		$content["form"]["sendertype"] = 'system';
	}
} elseif($content["form"]["sendertype"] == 'system' && $content["form"]["sender"]) {
	$content["form"]["sendertype"] = 'email';
}

$content["form"]["sendernametype"]	= clean_slweg($_POST["cform_sendernametype"]);
$content["form"]["sendername"]		= clean_slweg($_POST["cform_sendername"]);
if($content["form"]["sendernametype"] == 'system' && $content["form"]["sendername"]) {
	$content["form"]["sendernametype"] = 'custom';
}

$content['form']['verifyemail']		= isset($_POST['cform_field_verifyemail']) ? clean_slweg($_POST['cform_field_verifyemail']) : '';

$content["form"]["labelpos"]		= intval($_POST["cform_labelpos"]);
$content['form']["sendcopy"]		= empty($_POST["cform_sendcopy"]) ? 0 : 1;
$content['form']["copyto"]			= isset($_POST["cform_copyto"]) ? clean_slweg($_POST["cform_copyto"]) : '';

$content['form']["onsuccess_redirect"] = empty($_POST["cform_onsuccess_redirect"]) ? 0 : intval($_POST["cform_onsuccess_redirect"]);
switch($content['form']["onsuccess_redirect"]) {
	case 1:
	case 2:	break;
	default: $content['form']["onsuccess_redirect"] = 0;
}
$content['form']["onerror_redirect"]   = empty($_POST["cform_onerror_redirect"]) ? 0 : intval($_POST["cform_onerror_redirect"]);
switch($content['form']["onerror_redirect"]) {
	case 1:
	case 2:	break;
	default: $content['form']["onerror_redirect"] = 0;
}
$content['form']["onsuccess"] = $content['form']["onsuccess_redirect"] == 2 ? slweg($_POST["cform_onsuccess"]) : clean_slweg($_POST["cform_onsuccess"]);
$content['form']["onerror"]   = $content['form']["onerror_redirect"]   == 2 ? slweg($_POST["cform_onerror"])   : clean_slweg($_POST["cform_onerror"]);

$content['form']["template_format"] = intval($_POST["cform_template_format"]) ? 1 : 0;
//$content['form']["template"]	= $content['form']["template_format"] ? slweg($_POST["cform_template"]) : clean_slweg($_POST["cform_template"]);
$content['form']["template"]	= slweg($_POST["cform_template"]);

$content['form']["customform"]	= slweg($_POST["cform_customform"]);

$content['form']["savedb"]		= empty($_POST["cform_savedb"]) ? 0 : 1;
$content['form']["saveprofile"]	= empty($_POST["cform_saveprofile"]) ? 0 : 1;

//$field_counter = 0;
$content["form"]["fields"] = array();
/*
 * now retrieve all form entities and check based on type
 */
foreach($_POST['cform_field_type'] as $key => $value) {

	if(!isset($_POST['cform_field_delete'][$key])) {
	
		$value = clean_slweg($value);
		$field_counter = intval($_POST['cform_order'][$key]);
		$content["form"]["fields"][$field_counter]['type'] 		= $value;
		$content['form']["fields"][$field_counter]['name']		= trim(remove_accents(clean_slweg($_POST['cform_field_name'][$key])));
		$content['form']["fields"][$field_counter]['label']		= clean_slweg($_POST['cform_field_label'][$key]);
		$content['form']["fields"][$field_counter]['required']	= isset($_POST['cform_field_required'][$key]) ? 1 : 0;
		$content['form']["fields"][$field_counter]['value']		= slweg($_POST['cform_field_value'][$key]);
		$content['form']["fields"][$field_counter]['error']		= clean_slweg($_POST['cform_field_error'][$key]);
		$content['form']["fields"][$field_counter]['style']		= clean_slweg($_POST['cform_field_style'][$key]);
		$content['form']["fields"][$field_counter]['class']		= clean_slweg($_POST['cform_field_class'][$key]);
		
		$content['form']["fields"][$field_counter]['profile']	= empty($_POST['cform_field_profile'][$key]) ? '' : clean_slweg($_POST['cform_field_profile'][$key]);
		
		switch($value) {
	
			case 'text'		:	/*
								 * Text
								 */
								$content['form']["fields"][$field_counter]['value'] = str_replace("\r\n", ' ', $content['form']["fields"][$field_counter]['value']);
								$content['form']["fields"][$field_counter]['value'] = str_replace("\r", ' ', $content['form']["fields"][$field_counter]['value']);
								$content['form']["fields"][$field_counter]['value'] = str_replace("\n", ' ', $content['form']["fields"][$field_counter]['value']);
								
								$content['form']["fields"][$field_counter]['size']	= intval($_POST['cform_field_size'][$key]) ? intval($_POST['cform_field_size'][$key]) : '';
								$content['form']["fields"][$field_counter]['max']	= intval($_POST['cform_field_max'][$key]) ? intval($_POST['cform_field_max'][$key]) : '';
								break;
								
			case 'special'	:	/*
								 * Special
								 */
								$content['form']["fields"][$field_counter]['value']	= slweg($_POST['cform_field_value'][$key]);
								$content['form']["fields"][$field_counter]['value'] = str_replace('"', '', $content['form']["fields"][$field_counter]['value']);
								$content['form']["fields"][$field_counter]['value'] = str_replace("'", '', $content['form']["fields"][$field_counter]['value']);
								$content['form']["fields"][$field_counter]['value']	= explode("\n", $content['form']["fields"][$field_counter]['value']);
								if(is_array($content['form']["fields"][$field_counter]['value']) && count($content['form']["fields"][$field_counter]['value'])) {
									foreach($content['form']["fields"][$field_counter]['value'] as $_special) {
										$_special = trim($_special);
										$_special = explode('=', $_special);
										if(isset($_special[0])) {
											$_special[0] = strtolower(trim($_special[0]));
											switch($_special[0]) {
											
												case 'type': 		if(!empty($_special[1])) {
																		$_special[1] = strtoupper(trim($_special[1]));
																		switch($_special[1]) {
																			case 'MIX':
																			case 'INT':
																			case 'FLOAT':
																			case 'DEC':
																			case 'IDENT':
																			case 'STRING':
																			case 'DATE':
																				$special_attribute['type'] = $_special[1];
																				break;
																		}
																	}
																	if(!isset($special_attribute['type'])) {
																		$special_attribute['type'] = 'MIX';
																	}
																	break;
																
												case 'default': 	$special_attribute['default'] = isset($_special[1]) ? trim($_special[1]) : '';
																	break;
																	
												case 'dateformat': 	$special_attribute['dateformat'] = isset($_special[1]) ? trim($_special[1]) : 'm/d/Y';
																	break;
																	
											}
										}
									}
								}
								$content['form']["fields"][$field_counter]['value'] = '';
								if(isset($special_attribute)) {
									foreach($special_attribute as $_special_key => $_special) {
										if($_special) {
											$content['form']["fields"][$field_counter]['value'] .= $_special_key.'="'.$_special.'"'."\n";
										}
									}
									$content['form']["fields"][$field_counter]['value'] = trim($content['form']["fields"][$field_counter]['value']);
									unset($special_attribute, $_special, $_special_key);
								}
								$content['form']["fields"][$field_counter]['size']	= intval($_POST['cform_field_size'][$key]) ? intval($_POST['cform_field_size'][$key]) : '';
								$content['form']["fields"][$field_counter]['max']	= intval($_POST['cform_field_max'][$key]) ? intval($_POST['cform_field_max'][$key]) : '';
								break;
								
			case 'email'	:	/*
								 * Email
								 */
								$content['form']["fields"][$field_counter]['value'] = str_replace("\r\n", ' ', $content['form']["fields"][$field_counter]['value']);
								$content['form']["fields"][$field_counter]['value'] = str_replace("\r", ' ', $content['form']["fields"][$field_counter]['value']);
								$content['form']["fields"][$field_counter]['value'] = str_replace("\n", ' ', $content['form']["fields"][$field_counter]['value']);
								
								$content['form']["fields"][$field_counter]['size']	= intval($_POST['cform_field_size'][$key]) ? intval($_POST['cform_field_size'][$key]) : '';
								$content['form']["fields"][$field_counter]['max']	= intval($_POST['cform_field_max'][$key]) ? intval($_POST['cform_field_max'][$key]) : '';
								break;

			case 'textarea'	:	/*
								 * Textarea
								 */								
								$content['form']["fields"][$field_counter]['size']	= intval($_POST['cform_field_size'][$key]) ? intval($_POST['cform_field_size'][$key]) : '';
								$content['form']["fields"][$field_counter]['max']	= intval($_POST['cform_field_max'][$key]) ? intval($_POST['cform_field_max'][$key]) : 3;
								break;

			case 'hidden'	:	/*
								 * Hidden
								 */
								$content['form']["fields"][$field_counter]['size']	= '';
								$content['form']["fields"][$field_counter]['max']	= '';
								$content['form']["fields"][$field_counter]['value'] = str_replace("\r\n", ' ', $content['form']["fields"][$field_counter]['value']);
								$content['form']["fields"][$field_counter]['value'] = str_replace("\r", ' ', $content['form']["fields"][$field_counter]['value']);
								$content['form']["fields"][$field_counter]['value'] = str_replace("\n", ' ', $content['form']["fields"][$field_counter]['value']);
								break;

			case 'password'	:	/*
								 * Password
								 */
								$content['form']["fields"][$field_counter]['value'] = str_replace("\r\n", ' ', $content['form']["fields"][$field_counter]['value']);
								$content['form']["fields"][$field_counter]['value'] = str_replace("\r", ' ', $content['form']["fields"][$field_counter]['value']);
								$content['form']["fields"][$field_counter]['value'] = str_replace("\n", ' ', $content['form']["fields"][$field_counter]['value']);
								
								$content['form']["fields"][$field_counter]['size']	= intval($_POST['cform_field_size'][$key]) ? intval($_POST['cform_field_size'][$key]) : '';
								$content['form']["fields"][$field_counter]['max']	= intval($_POST['cform_field_max'][$key]) ? intval($_POST['cform_field_max'][$key]) : '';
								break;

			case 'select'	:	/*
								 * Men�
								 */
								$content['form']["fields"][$field_counter]['size']	= ''; //mutiple or not
								$content['form']["fields"][$field_counter]['max']	= '';
								break;

			case 'list'		:	/*
								 * Liste
								 */
								$content['form']["fields"][$field_counter]['size']	= intval($_POST['cform_field_size'][$key]) ? intval($_POST['cform_field_size'][$key]) : 3;
								$content['form']["fields"][$field_counter]['max']	= intval($_POST['cform_field_max'][$key]) ? 1 : 0; //mutiple or not
								break;
								
			case 'newsletter':	/*
								 * Newsletter
								 */								
								$content['form']["fields"][$field_counter]['size']	= intval($_POST['cform_field_size'][$key]) ? intval($_POST['cform_field_size'][$key]) : '';
								$content['form']["fields"][$field_counter]['max']	= '';
								$content['form']["fields"][$field_counter]['value']	= convertStringToArray($content['form']["fields"][$field_counter]['value'], "\n", 'UNIQUE', false);
								$newletter_array 									= array();
								$newletter_array['double_optin']					= 0;
								$newletter_array['subject']							= 'Verify your newsletter subscription';
								
								foreach($content['form']["fields"][$field_counter]['value'] as $newsletter) {
								
									$newsletter		= explode('=', $newsletter, 2);
									$newsletter[0]	= trim($newsletter[0]);
									$newsletter[1]	= empty($newsletter[1]) ? '' : trim($newsletter[1]);
									
									if(empty($newsletter[0]) || empty($newsletter[1])) {
									
										continue;
									
									} else {
									
										switch($newsletter[0]) {
										
											case 'all':				$newletter_array['all'] 			= $newsletter[1];					break;
											case 'email_field':		$newletter_array['email_field'] 	= $newsletter[1];					break;
											case 'name_field':		$newletter_array['name_field'] 		= $newsletter[1];					break;
											case 'sender_email':	$newletter_array['sender_email'] 	= $newsletter[1];					break;
											case 'sender_name':		$newletter_array['sender_name'] 	= $newsletter[1];					break;
											case 'url_subscribe':	$newletter_array['url_subscribe'] 	= $newsletter[1];					break;
											case 'url_unsubscribe':	$newletter_array['url_unsubscribe']	= $newsletter[1];					break;
											case 'double_optin':	$newletter_array['double_optin'] 	= intval($newsletter[1]) ? 1 : 0;	break;
											case 'subject':			$newletter_array['subject']			= $newsletter[1];					break;
											
											default:	if(intval($newsletter[0])) {
															$newsletter[0]  = intval($newsletter[0]);
															$newsletter[2]  = "SELECT COUNT(*) FROM ".DB_PREPEND."phpwcms_newsletter WHERE ";
															$newsletter[2] .= "newsletter_id=".$newsletter[0]." AND newsletter_trashed=0";
															if(_dbQuery($newsletter[2], 'COUNT')) {
																$newletter_array[ $newsletter[0] ] = $newsletter[1];
															} else {
																continue;
															}
														} else {
														
															continue;
														
														}
						
										}
									
									}
	
								}

								$content['form']["fields"][$field_counter]['value'] = '';
								foreach($newletter_array as $newsletter['key'] => $newsletter['value']) {
									$content['form']["fields"][$field_counter]['value'] .= $newsletter['key'].'='.$newsletter['value'].LF;
								}
								$content['form']["fields"][$field_counter]['value'] = trim($content['form']["fields"][$field_counter]['value']);
								break;

			case 'checkbox'	:	/*
								 * Checkbox
								 */								
								$content['form']["fields"][$field_counter]['size']	= intval($_POST['cform_field_size'][$key]) ? intval($_POST['cform_field_size'][$key]) : '';
								$content['form']["fields"][$field_counter]['max']	= '';
								break;

			case 'radio'	:	/*
								 * Radiobutton
								 */
								$content['form']["fields"][$field_counter]['size']	= intval($_POST['cform_field_size'][$key]) ? intval($_POST['cform_field_size'][$key]) : '';
								$content['form']["fields"][$field_counter]['max']	= '';
								break;

			case 'upload'	:	/*
								 * Upload
								 */
								$content['form']["fields"][$field_counter]['value']	= slweg($_POST['cform_field_value'][$key]);
								$content['form']["fields"][$field_counter]['value'] = str_replace('"', '', $content['form']["fields"][$field_counter]['value']);
								$content['form']["fields"][$field_counter]['value'] = str_replace("'", '', $content['form']["fields"][$field_counter]['value']);
								$content['form']["fields"][$field_counter]['value']	= explode("\n", $content['form']["fields"][$field_counter]['value']);
								if(is_array($content['form']["fields"][$field_counter]['value']) && count($content['form']["fields"][$field_counter]['value'])) {
									foreach($content['form']["fields"][$field_counter]['value'] as $upload) {
										$upload = trim($upload);
										$upload = explode('=', $upload);
										if(isset($upload[0])) {
											$upload[0] = strtolower(trim($upload[0]));
											switch($upload[0]) {
											
												case 'maxlength': 	$upload_value['maxlength'] = isset($upload[1]) ? intval($upload[1]) : '';
																	break;
																	
												case 'folder':		$upload_value['folder'] = isset($upload[1]) ? trim($upload[1]) : 'content/form/';
																	$upload_value['folder'] = preg_replace('/\/{1,}$/', '', $upload_value['folder']);
																	$upload_value['folder'] = preg_replace('/^\//', '', $upload_value['folder']);
																	if(!is_dir(PHPWCMS_ROOT.'/'.$upload_value['folder']) || !is_writable(PHPWCMS_ROOT.'/'.$upload_value['folder'])) {
																		$upload_value['folder'] = 'content/form/';
																	}
																	break;
																	
												case 'accept':		$upload_value['accept'] = isset($upload[1]) ? trim($upload[1]) : '';
																	break;
																	
												case 'attachment':	$upload_value['attachment'] = isset($upload[1]) && intval($upload[1]) ? 1 : 0;
																	break;
																	
												case 'exclude':		if(isset($upload[1])) {
																		$upload_value['exclude'] = strtolower(trim($upload[1]));
																		$upload_value['exclude'] = str_replace(' ', '', $upload_value['exclude']);
																		$upload_value['exclude'] = str_replace(';', ',', $upload_value['exclude']);
																	} else {
																		$upload_value['exclude'] = 'php,asp,php3,php4,php5,aspx,cfm,js';
																	}
																	
											}
										}
									}
								}
								$content['form']["fields"][$field_counter]['value'] = '';
								if(!isset($upload_value['exclude'])) {
									$upload_value['exclude'] = 'php,asp,php3,php4,php5,aspx,cfm,js';
								}
								if(isset($upload_value)) {
									foreach($upload_value as $upload_key => $upload) {
										if($upload) {
											$content['form']["fields"][$field_counter]['value'] .= $upload_key.'="'.$upload.'"'."\n";
										}
									}
									$content['form']["fields"][$field_counter]['value'] = trim($content['form']["fields"][$field_counter]['value']);
									unset($upload_value, $upload, $upload_key);
								}
								$content['form']["fields"][$field_counter]['size']	= intval($_POST['cform_field_size'][$key]) ? intval($_POST['cform_field_size'][$key]) : '';
								$content['form']["fields"][$field_counter]['max']	= intval($_POST['cform_field_max'][$key]) ? intval($_POST['cform_field_max'][$key]) : '';
								
								break;

			case 'submit'	:	/*
								 * Submit
								 */
								$src_pos = strpos(strtolower($_POST['cform_field_value'][$key]), 'src=');
								if($src_pos === 0 || $src_pos) {
									$content['form']["fields"][$field_counter]['value']	= slweg($_POST['cform_field_value'][$key]);
								}
								$content['form']["fields"][$field_counter]['value'] = str_replace("\r\n", ' ', $content['form']["fields"][$field_counter]['value']);
								$content['form']["fields"][$field_counter]['value'] = str_replace("\r", ' ', $content['form']["fields"][$field_counter]['value']);
								$content['form']["fields"][$field_counter]['value'] = str_replace("\n", ' ', $content['form']["fields"][$field_counter]['value']);
								$content['form']["fields"][$field_counter]['size']	= '';
								$content['form']["fields"][$field_counter]['max']	= '';
								break;

			case 'reset'	:	/*
								 * Reset
								 */
								$src_pos = strpos(strtolower($_POST['cform_field_value'][$key]), 'src=');
								if($src_pos === 0 || $src_pos) {
									$content['form']["fields"][$field_counter]['value']	= slweg($_POST['cform_field_value'][$key]);
								}
								$content['form']["fields"][$field_counter]['value'] = str_replace("\r\n", ' ', $content['form']["fields"][$field_counter]['value']);
								$content['form']["fields"][$field_counter]['value'] = str_replace("\r", ' ', $content['form']["fields"][$field_counter]['value']);
								$content['form']["fields"][$field_counter]['value'] = str_replace("\n", ' ', $content['form']["fields"][$field_counter]['value']);
								$content['form']["fields"][$field_counter]['size']	= '';
								$content['form']["fields"][$field_counter]['max']	= '';
								break;
	
			case 'break'	:	/*
								 * Break
								 */
								$content['form']["fields"][$field_counter]['size']	= '';
								$content['form']["fields"][$field_counter]['max']	= '';
								$content['form']["fields"][$field_counter]['value']	= slweg($_POST['cform_field_value'][$key]);
								break;
	
			case 'breaktext':	/*
								 * Breaktext
								 */
								$content['form']["fields"][$field_counter]['size']	= '';
								$content['form']["fields"][$field_counter]['max']	= '';
								break;
								
			case 'captcha':		/*
								 * Captcha Code Input Field
								 */
								$content['form']["fields"][$field_counter]['size']	= intval($_POST['cform_field_size'][$key]) ? intval($_POST['cform_field_size'][$key]) : '';
								$content['form']["fields"][$field_counter]['max']	= intval($_POST['cform_field_max'][$key]) ? intval($_POST['cform_field_max'][$key]) : '';
								$content['form']["fields"][$field_counter]['value']	= '';
								$content['form']["fields"][$field_counter]['required'] = 1;
								break;
								
			case 'captchaimg':	/*
								 * Captcha Image
								 */
								$content['form']["fields"][$field_counter]['size']	= intval($_POST['cform_field_size'][$key]) ? intval($_POST['cform_field_size'][$key]) : '';
								$content['form']["fields"][$field_counter]['max']	= '';
								$content['form']["fields"][$field_counter]['value']	= slweg($_POST['cform_field_value'][$key]);
								break;
								
								 
		}
		
		/*
		 * Test if values are filled in
		 */
		$all_fields_empty  = $content['form']["fields"][$field_counter]['name'];
		$all_fields_empty .= $content['form']["fields"][$field_counter]['label'];
		$all_fields_empty .= $content['form']["fields"][$field_counter]['value'];
		$all_fields_empty .= $content['form']["fields"][$field_counter]['error'];
		$all_fields_empty .= $content['form']["fields"][$field_counter]['style'];

		
		if(trim($all_fields_empty) == '') {
			unset($content['form']["fields"][$field_counter]);
		} else {
		
			if($content['form']["fields"][$field_counter]['name'] == '') {
				$content['form']["fields"][$field_counter]['name'] = $content["form"]["fields"][$field_counter]['type'];
			}
			if($content['form']["fields"][$field_counter]['name'] == 'reset' || $content['form']["fields"][$field_counter]['name'] == 'submit') {
				$content['form']["fields"][$field_counter]['name'] .= 'It';
			}
			
			$current_field_name = preg_replace('/(.*?)(\d+){1,}$/', '$1', $content['form']["fields"][$field_counter]['name']);
			
			if(!isset($field_name[$current_field_name])) {
				$field_name[$current_field_name] = 0;
			} else {
				$content['form']["fields"][$field_counter]['name'] = $current_field_name . $field_name[$current_field_name];
				$field_name[$current_field_name]++;
			}
					
			//$field_counter++;
		}
	
	}

}

// sort form fields
ksort($content["form"]["fields"], SORT_NUMERIC);


?>