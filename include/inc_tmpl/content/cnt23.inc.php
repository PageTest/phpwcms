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


// email contact form

$field_counter = 0;
$BE['HEADER']['contentpart.js'] = getJavaScriptSourceLink('include/inc_js/contentpart.js');

if(empty($content['form']) || !is_array($content['form'])) $content['form'] = array();

$content['form'] = array_merge( array(
	'subject'				=> '',
	'startup'				=> '',
	'startup_html'			=> 0,
	'targettype'			=> 'email',
	'class'					=> '',
	'target'				=> '',
	"copyto"				=> '',
	"sendcopy"				=> 0,
	"onsuccess_redirect"	=> 0,
	"onsuccess"				=> '',
	"onerror_redirect"		=> 0,
	"onerror"				=> '',
	"template_format"		=> 0,
	"template"				=> '',
	"customform"			=> '',
	'sender'				=> '',
	'sendertype'			=> 'email',
	'sendername'			=> '',
	'sendernametype'		=> 'custom',
	'cc'					=> '',
	'subjectselect'			=> '',
	'savedb'				=> 0,
	'saveprofile'			=> 0,
	'verifyemail'			=> ''		)   , $content['form']);

$content['profile_fields'] = array(
				"title"			=> $BL['be_profile_label_title'],
				"firstname"		=> $BL['be_profile_label_firstname'],
				"lastname"		=> $BL['be_profile_label_name'],
				"company"		=> $BL['be_profile_label_company'],
				"street"		=> $BL['be_profile_label_street'],
				"add"			=> $BL['be_profile_label_add'],
				"city"			=> $BL['be_profile_label_city'],
				"zip"			=> $BL['be_profile_label_zip'],
				"region"		=> $BL['be_profile_label_state'],
				"country"		=> $BL['be_profile_label_country'],
				"fon"			=> $BL['be_profile_label_phone'],
				"fax"			=> $BL['be_profile_label_fax'],
				"mobile"		=> $BL['be_profile_label_cellphone'],
				"signature"		=> $BL['be_profile_label_signature'],
				'notes'			=> $BL['be_profile_label_notes'],
				"prof"			=> $BL['be_profile_label_profession'],
				"newsletter"	=> $BL['be_profile_label_newsletter'],
				"website"		=> $BL['be_profile_label_website'],
				'gender'		=> $BL['be_profile_label_gender'],
				'birthday'		=> $BL['be_profile_label_birthday'],
				"varchar1"		=> $BL['be_cnt_field']['text'].' 1',
				"varchar2"		=> $BL['be_cnt_field']['text'].' 2',
				"varchar3"		=> $BL['be_cnt_field']['text'].' 3',
				"varchar4"		=> $BL['be_cnt_field']['text'].' 4',
				"varchar5"		=> $BL['be_cnt_field']['text'].' 5',
				"text1"			=> $BL['be_cnt_field']['textarea'].' 1',
				"text2"			=> $BL['be_cnt_field']['textarea'].' 2',
				"text3"			=> $BL['be_cnt_field']['textarea'].' 3'
			);
			
$content['profile_fields_varchar'] = array(
				"title"			=> $BL['be_profile_label_title'],
				"firstname"		=> $BL['be_profile_label_firstname'],
				"lastname"		=> $BL['be_profile_label_name'],
				"company"		=> $BL['be_profile_label_company'],
				"street"		=> $BL['be_profile_label_street'],
				"add"			=> $BL['be_profile_label_add'],
				"city"			=> $BL['be_profile_label_city'],
				"zip"			=> $BL['be_profile_label_zip'],
				"region"		=> $BL['be_profile_label_state'],
				"country"		=> $BL['be_profile_label_country'],
				"fon"			=> $BL['be_profile_label_phone'],
				"fax"			=> $BL['be_profile_label_fax'],
				"mobile"		=> $BL['be_profile_label_cellphone'],
				"email"			=> $BL['be_profile_label_email'],
				"password"		=> $BL['be_cnt_field']['password'],
				"signature"		=> $BL['be_profile_label_signature'],
				"prof"			=> $BL['be_profile_label_profession'],
				"website"		=> $BL['be_profile_label_website'],
				'gender'		=> $BL['be_profile_label_gender'],
				"varchar1"		=> $BL['be_cnt_field']['text'].' 1',
				"varchar2"		=> $BL['be_cnt_field']['text'].' 2',
				"varchar3"		=> $BL['be_cnt_field']['text'].' 3',
				"varchar4"		=> $BL['be_cnt_field']['text'].' 4',
				"varchar5"		=> $BL['be_cnt_field']['text'].' 5'
			);
$content['profile_fields_longtext'] = array(
				'notes'			=> $BL['be_profile_label_notes'],
				"text1"			=> $BL['be_cnt_field']['textarea'].' 1',
				"text2"			=> $BL['be_cnt_field']['textarea'].' 2',
				"text3"			=> $BL['be_cnt_field']['textarea'].' 3'
			);


$for_select 	= '';
$for_select_2	= '';

// always disable switching content part for form - too complex settings and better to safe the user for himself
$BE['BODY_CLOSE'][] = '<script language="javascript" type="text/javascript">document.getElementById("target_ctype").disabled = true;</script>';

?>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /><input type="hidden" name="target_ctype" value="23" /></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
<tr>
<td align="right" class="chatlist"><?php echo $BL['be_msg_subject'] ?>:&nbsp;</td>
<td valign="top"><table summary="" cellpadding="0" cellspacing="0" border="0">
  <tr>
  	<td><select name="cform_subjectselect" class="v11" style="width:200px">
	
		<option value=""><?php echo $BL['be_msg_subject'] ?></option>
<?php

$cc_listing 		= '';
$recipient_option	= '';
$sender_option 		= '';
$sendername_option	= '';
$subject_option		= '';

if(isset($content['form']["fields"]) && is_array($content['form']["fields"]) && count($content['form']["fields"])) {
	foreach($content['form']["fields"] as $key => $value) {
	
		$for_copy			= false;
		$for_sendername		= false;
		$for_email			= false;
		$for_placeholder	= true;
		$for_subject		= false;
		$for_newsletter		= false;
		$for_name			= html_specialchars($content['form']["fields"][$key]['name']);
  
  		switch($content['form']["fields"][$key]['type']) {
		
			case 'text':	$for_copy 		= true;
							$for_sendername	= true;
							$for_subject	= true;
							break;
							
			case 'email':	$for_copy 		= true;
							$for_email 		= true;
							$for_sendername	= true;
							break;
							
			case 'hidden':	$for_copy 		= true;
							$for_subject	= true;
							break;
							
			case 'newsletter':
							$for_newsletter		= true;
							break;
							
			case 'select':
			case 'list':	$for_subject	= true;
							break;
		}
		
		if($for_subject) {
		
			$subject_option .= '	<option value="formfield_'.$for_name.'"';
			$subject_option .= is_selected($content['form']['subjectselect'], 'formfield_'.$content['form']['fields'][$key]['name'], 0, 0);
			$subject_option .= '>'.$BL['be_cnt_guestbook_form'].': '.$for_name.'</option>'.LF;
		
		}
		
		if($for_copy) {
		
			$cc_listing .= '	<option value="'.$for_name.'"';
			$cc_listing .= is_selected($content['form']["copyto"], $content['form']['fields'][$key]['name'], 0, 0);
			$cc_listing .= '>'.$for_name.'</option>'.LF;
			
			if($for_email) {
			
				$recipient_option .= '	<option value="emailfield_'.$for_name.'"';
				$recipient_option .= is_selected($content['form']['targettype'], 'emailfield_'.$content['form']['fields'][$key]['name'], 0, 0);
				$recipient_option .= '>'.$BL['be_cnt_guestbook_form'].': '.$for_name.'</option>'.LF;
				
				$sender_option .= '	<option value="emailfield_'.$for_name.'"';
				$sender_option .= is_selected($content['form']['sendertype'], 'emailfield_'.$content['form']['fields'][$key]['name'], 0, 0);
				$sender_option .= '>'.$BL['be_cnt_guestbook_form'].': '.$for_name.'</option>'.LF;

			}
			
			if($for_sendername) {
			
				$sendername_option .= '	<option value="formfield_'.$for_name.'"';
				$sendername_option .= is_selected($content['form']['sendernametype'], 'formfield_'.$content['form']['fields'][$key]['name'], 0, 0);
				$sendername_option .= '>'.$BL['be_cnt_guestbook_form'].': '.$for_name.'</option>'.LF;

			}
			
		}
		
		
		// parallel building of the placeholder tag menu for the template
		switch($content['form']["fields"][$key]['type']) {
		
			case 'submit':		$for_placeholder = false;
								break;
								
			case 'reset':		$for_placeholder = false;
								break;
								
			case 'break':		$for_placeholder = false;
								break;
								
			case 'breaktext':	$for_placeholder = false;
								break;

		}
		
		$for_select_2   .= '<option value="';
		$for_tempselect  = '';
		if($for_placeholder) {
		
			$for_select   .= '<option value="{'.$for_name.'}">';
			if(!empty($content['form']["fields"][$key]['label'])) {
				$for_select     .= html_specialchars($content['form']["fields"][$key]['label']).' ';
				$for_tempselect .= html_specialchars($content['form']["fields"][$key]['label']).' ';
			}
			$for_select   .= '{'.$for_name."}</option>\n";
			
			$for_select_2 .= '{ERROR:'.$for_name.'}{LABEL:'.$for_name.'}';
		
		}
		$for_select_2 .= '{'.$for_name.'}">'.$for_tempselect.'{'.$for_name."}</option>\n";
		
	}
}

echo $subject_option;
  
?>  
 	</select></td>
	<td>&nbsp;</td>
  	<td><input name="cform_subject" type="text" id="cform_subject" class="f11b" style="width:230px" value="<?php echo  html_specialchars($content['form']["subject"]) ?>" size="40" /></td>
  </tr>
  </table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td>
</tr>
<tr>
<td align="right" class="chatlist"><?php echo $BL['be_cnt_recipient'] ?>:&nbsp;</td>
<td valign="top"><table summary="" cellpadding="0" cellspacing="0" border="0">
  <tr>
  <td><select name="cform_targettype" class="v11" style="width:200px">
<?php 
  
	echo '	<option value="email"'. is_selected('email', $content['form']['targettype'],0,0) .'>'.$BL['be_profile_label_email'].'</option>'.LF;
	echo $recipient_option;
?>  

  </select></td>
  <td>&nbsp;</td>
  <td><input name="cform_target" type="text" id="cform_target" class="f11b" style="width:230px" value="<?php echo  html_specialchars($content['form']["target"]) ?>" size="40" /></td>
  </tr>
</table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td>
</tr>
<tr>
<td align="right" class="chatlist"><?php echo $BL['be_newsletter_fromemail'] ?>:&nbsp;</td>
<td valign="top"><table summary="" cellpadding="0" cellspacing="0" border="0">
  <tr>
  <td><select name="cform_sendertype" class="v11" style="width:200px">
<?php
  	echo '	<option value="email"'. is_selected('email', $content['form']['sendertype'],0,0) .'>'.$BL['be_profile_label_email'].'</option>'.LF;
	echo '	<option value="system"'. is_selected('system', $content['form']['sendertype'],0,0) .'>'.$BL['be_cnt_sysadmin_system'].': '.html_specialchars($phpwcms['SMTP_FROM_EMAIL']).'</option>'.LF;
  
  	echo $sender_option;
?>
    </select></td>
  <td>&nbsp;</td>
  <td><input name="cform_sender" type="text" id="cform_sender" class="f11b" style="width:230px" value="<?php echo  html_specialchars($content['form']['sender']) ?>" size="40" /></td>
  </tr>
</table></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td>
</tr>
<tr>
<td align="right" class="chatlist"><?php echo $BL['be_newsletter_fromname'] ?>:&nbsp;</td>
<td valign="top"><table summary="" cellpadding="0" cellspacing="0" border="0">
  <tr>
  <td><select name="cform_sendernametype" class="v11" style="width:200px">
<?php
  	echo '	<option value="custom"'. is_selected('custom', $content['form']['sendernametype'],0,0) .'>'.$BL['be_cnt_ecardform_name'].'</option>'.LF;
	echo '	<option value="system"'. is_selected('system', $content['form']['sendernametype'],0,0) .'>'.$BL['be_cnt_sysadmin_system'].': '.html_specialchars($phpwcms['SMTP_FROM_NAME']).'</option>'.LF;
  
  	echo $sendername_option;
?>
    </select></td>
  <td>&nbsp;</td>
  <td><input name="cform_sendername" type="text" id="cform_sendername" class="f11b" style="width:230px" value="<?php echo  html_specialchars($content['form']['sendername']) ?>" size="40" /></td>
  </tr>
</table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="4" /></td>
</tr>
<tr>
<td align="right" class="chatlist"><?php echo $BL['be_cnt_send_copy_to']?>:&nbsp;</td>
<td valign="top"><table summary="" cellpadding="0" cellspacing="0" border="0">
  <tr>
  <td bgcolor="#E7E8EB"><input type="checkbox" name="cform_sendcopy" value="1"<?php echo is_checked('1', $content['form']["sendcopy"], 0, 0) ?> title="send copy to selected field" /></td>
  <td><select name="cform_copyto" class="v11" style="width:180px;">
<?php echo $cc_listing; ?>
  </select></td>
  <td>&nbsp;</td>
  <td><input name="cform_cc" type="text" id="cform_cc" class="f11b" style="width:230px" value="<?php echo  html_specialchars($content['form']['cc']) ?>" size="40" /></td>
  </tr>
</table></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td>
</tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td>
</tr>


<tr>
	<td align="right" class="chatlist" style="padding-top:5px;" valign="top"><?php echo $BL['be_cnt_database'] ?>:&nbsp;</td>
	<td><table summary="" cellpadding="0" cellspacing="0" border="0" width="100%">
  		<tr>
  			<td bgcolor="#E7E8EB" style="padding-bottom:1px;padding-top:1px"><input type="checkbox" name="cform_savedb" id="cform_savedb" value="1" <?php echo is_checked(1, $content['form']["savedb"], 0, 0) ?> /></td>
  			<td class="v10" bgcolor="#E7E8EB" nowrap="nowrap"><label for="cform_savedb">&nbsp;<?php echo $BL['be_cnt_formsave_in_db'] ?></label>&nbsp;&nbsp;</td>
			<td width="70%" align="right" class="v10"><?php
			
// check form entries
if($content['form']["savedb"] && $content["id"]) {

	$content['form']["savedb"] = _dbQuery('SELECT COUNT(*) FROM '.DB_PREPEND.'phpwcms_formresult WHERE formresult_pid='.$content['id'], 'ROW');
	$content['form']["savedb"] = $content['form']["savedb"][0][0];
	
	// yepp - available - link to export script
	if($content['form']["savedb"]) {
	
		echo "<button onclick=\"window.open('include/inc_act/act_export.php?action=exportformresult&amp;fid=";
		echo $content['id']."', 'Zweitfenster');\" class=\"f11b\" style=\"padding: 2px 6px 2px 4px\">";
		echo '<img src="img/icons/small_icon_xls.gif" alt="Excel Sheet" style="position: relative; top:1;" />&nbsp;';
		echo $BL['be_cnt_download'].'<span style="font-weight:normal;">&nbsp;('.$content['form']["savedb"].')</span></button>&nbsp;&nbsp;';
		
	}
	
}
			?></td>
		</tr>
  		<tr>
  		  <td colspan="3"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
	  </tr>
  		<tr>
  		  <td bgcolor="#E7E8EB" style="padding-bottom:1px;padding-top:1px"><input type="checkbox" name="cform_saveprofile" id="cform_saveprofile" value="1" <?php echo is_checked(1, $content['form']["saveprofile"], 0, 0) ?> onchange="this.form.submit();" /></td>
  		  <td class="v10" bgcolor="#E7E8EB" nowrap="nowrap"><label for="cform_saveprofile">&nbsp;<?php echo $BL['be_cnt_formsave_profile'] ?></label>&nbsp;&nbsp;</td>
  		  <td align="right" class="v10">&nbsp;</td>
	  </tr>
		</table>
  </td>
</tr>



<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td>
</tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
</tr>

<tr>
	<td align="right" class="chatlist" valign="top"><img src="img/leer.gif" alt="" width="1" height="15" /><?php echo $BL['be_admin_tmpl_default'] ?>:&nbsp;</td>
	<td>
	<table summary="" cellpadding="1" cellspacing="0" border="0">
	<tr bgcolor="#E7E8EB">
		<td><input type="radio" name="cform_startup_html" id="cform_startup_html0" value="0"<?php echo is_checked('0', $content['form']["startup_html"], 0, 0) ?> title="Text" /></td>
		<td class="v10"><label for="cform_startup_html0">Text&nbsp;</label>&nbsp;</td>
		<td><input type="radio" name="cform_startup_html" id="cform_startup_html1" value="1"<?php echo is_checked('1', $content['form']["startup_html"], 0, 0) ?> title="HTML" /></td>
		<td class="v10"><label for="cform_startup_html1">HTML&nbsp;</label>&nbsp;</td>
	</tr>
	</table>
	<textarea name="cform_startup" id="cform_startup" rows="5" class="f11" style="width:440px;"><?php echo  html_specialchars($content['form']["startup"]) ?></textarea></td>
</tr>



<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
</tr>

<tr>
<td align="right" class="chatlist" valign="top"><img src="img/leer.gif" alt="" width="1" height="15" /><?php echo $BL['be_cnt_onsuccess'] ?>:&nbsp;</td>
<td><table summary="" cellpadding="0" cellspacing="0" border="0">
	<tr bgcolor="#E7E8EB">
		<td><input type="radio" name="cform_onsuccess_redirect" id="cform_onsuccess_redirect0" value="0"<?php echo is_checked('0', $content['form']["onsuccess_redirect"], 0, 0) ?> title="redirect on success" /></td>
		<td class="v10"><label for="cform_onsuccess_redirect0">Text&nbsp;</label>&nbsp;</td>
		<td><input type="radio" name="cform_onsuccess_redirect" id="cform_onsuccess_redirect2" value="2"<?php echo is_checked('2', $content['form']["onsuccess_redirect"], 0, 0) ?> title="redirect on success" /></td>
		<td class="v10"><label for="cform_onsuccess_redirect2">HTML&nbsp;</label>&nbsp;</td>
		<?php
		if($for_select != '') {
			echo '<td style="padding:2px;"><select name="successInfo" id="successInfo" class="v10" ';
			echo 'onChange="insertAtCursorPos(document.articlecontent.cform_onsuccess, ';
			echo 'document.articlecontent.successInfo.options[document.articlecontent.successInfo.selectedIndex].value);">';
			echo $for_select;
			echo '<option value="{REMOTE_IP}">{REMOTE_IP}</option>'.LF;
			echo '</select></td>';
			echo '<td style="padding-right:3px;"><img src="img/button/go04.gif" alt="" width="15" height="15" title="insert field placeholder" border="0" ';
			echo 'onclick="insertAtCursorPos(document.articlecontent.cform_onsuccess, ';
			echo 'document.articlecontent.successInfo.options[document.articlecontent.successInfo.selectedIndex].value);" style="margin:3px;" /></td>';
		}
		?>
		<td bgcolor="#FFFFFF">&nbsp;</td>
		<td><input type="radio" name="cform_onsuccess_redirect" id="cform_onsuccess_redirect1" value="1"<?php echo is_checked('1', $content['form']["onsuccess_redirect"], 0, 0) ?> title="redirect on success" /></td>
		<td class="v10"><label for="cform_onsuccess_redirect1">Redirect</label>&nbsp;&nbsp;</td>

		
		
	</tr>
</table>
<textarea name="cform_onsuccess" id="cform_onsuccess" rows="3" class="f11" style="font-size:11px;width:440px;"><?php echo  html_specialchars($content['form']["onsuccess"]) ?></textarea>
</td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2" /></td>
</tr>
<tr>
<td align="right" class="chatlist" valign="top"><img src="img/leer.gif" alt="" width="1" height="15" /><?php echo $BL['be_cnt_onerror'] ?>:&nbsp;</td>
<td><table summary="" cellpadding="1" cellspacing="0" border="0">
	<tr bgcolor="#E7E8EB">
		<td><input type="radio" name="cform_onerror_redirect" id="cform_onerror_redirect0" value="0"<?php echo is_checked('0', $content['form']["onerror_redirect"], 0, 0) ?> title="redirect on success" /></td>
		<td class="v10"><label for="cform_onerror_redirect0">Text&nbsp;</label>&nbsp;</td>
		<td><input type="radio" name="cform_onerror_redirect" id="cform_onerror_redirect2" value="2"<?php echo is_checked('2', $content['form']["onerror_redirect"], 0, 0) ?> title="redirect on success" /></td>
		<td class="v10"><label for="cform_onerror_redirect2">HTML&nbsp;</label>&nbsp;</td>
		<td bgcolor="#FFFFFF" style="padding-bottom: 5px;">&nbsp;</td>
		<td><input type="radio" name="cform_onerror_redirect" id="cform_onerror_redirect1" value="1"<?php echo is_checked('1', $content['form']["onerror_redirect"], 0, 0) ?> title="redirect on success" /></td>
		<td class="v10"><label for="cform_onerror_redirect1">Redirect</label>&nbsp;&nbsp;</td>
	</tr>
</table>
<textarea name="cform_onerror" rows="3" class="f11" style="font-size:11px;width:440px;"><?php echo  html_specialchars($content['form']["onerror"]) ?></textarea>
</td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td>
</tr>

<tr><td colspan="2">

<table summary="" cellpadding="0" cellspacing="1" border="0">

<tr bgcolor="#DAE4ED"><td colspan="8"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
</tr>

<tr bgcolor="#E7E8EB">
<td colspan="2" class="chatlist" align="right"><?php echo $BL['be_cnt_reference_basis'] ?>:&nbsp;</td>
<td colspan="6" style="padding:3px 0 3px 0"><table summary="" cellpadding="0" cellspacing="0" border="0">
	<tr>
	<?php
	if(!isset($content['form']["labelpos"])) {
		$content['form']["labelpos"] = 0;
		// 0 = default = in fornt of form field
		// 1 = above form field
	}
	?>
	<td><input type="radio" name="cform_labelpos" id="cform_labelpos0" value="0"<?php echo  is_checked(0, $content['form']["labelpos"], 0, 1) ?> /></td>
	<td><label for="cform_labelpos0"><img src="img/symbole/label_1.gif" width="70" height="22" alt="" /></label></td>

	<td>&nbsp;&nbsp;</td>
	<td><input type="radio" name="cform_labelpos" id="cform_labelpos1" value="1"<?php echo  is_checked(1, $content['form']["labelpos"], 0, 1) ?> /></td>
	<td><label for="cform_labelpos1"><img src="img/symbole/label_2.gif" width="60" height="22" alt="" /></label></td>

	<td>&nbsp;&nbsp;</td>
	<td><input type="radio" name="cform_labelpos" id="cform_labelpos2" value="2"<?php echo  is_checked(2, $content['form']["labelpos"], 0, 1) ?> /></td>
	<td><label for="cform_labelpos2"><img src="img/symbole/label_3.gif" width="60" height="22" alt="" /></label></td>
	</tr>
</table></td>
</tr>
<tr bgcolor="#DAE4ED"><td colspan="8"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
</tr>
<tr><td colspan="8"><img src="img/leer.gif" alt="" width="1" height="3" /></td>
</tr>


<tr>
<td colspan="2" class="chatlist" align="right"><?php echo $BL['be_cnt_form_class'] ?>:&nbsp;</td>
<td><input type="text" name="cform_class" class="v10" style="width:120px;" value="<?php echo  (isset($content['form']["class"]) ? html_specialchars($content['form']["class"]) : '') ?>" /></td>
<td class="chatlist" align="right">&nbsp;<?php echo $BL['be_cnt_label_wrap'] ?>:&nbsp;</td>
<td colspan="4"><input type="text" name="cform_label_wrap" class="v10" style="width:81px;" value="<?php echo  (isset($content['form']["label_wrap"]) ? html_specialchars($content['form']["label_wrap"]) : '|') ?>" /></td>
</tr>

<tr>
<td colspan="2" class="chatlist" align="right"><?php echo $BL['be_cnt_req_mark'] ?>:&nbsp;</td>
<td><input type="text" name="cform_reqmark" class="v10" style="width:120px;" value="<?php echo  (isset($content['form']["cform_reqmark"]) ? html_specialchars($content['form']["cform_reqmark"]) : '*') ?>" /></td>
<td class="chatlist" align="right">&nbsp;<?php echo $BL['be_cnt_error_class'] ?>:&nbsp;</td>
<td colspan="4"><input type="text" name="cform_error_class" class="v10" style="width:81px;" value="<?php echo  (isset($content['form']["error_class"]) ? html_specialchars($content['form']["error_class"]) : '') ?>" /></td>
</tr>

<tr><td colspan="8"><img src="img/leer.gif" alt="" width="1" height="3" /></td>
</tr>
<tr bgcolor="#DAE4ED"><td colspan="8"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
</tr>
<tr bgcolor="#E6ECF2">
	<td style="width:30px">&nbsp;</td>
	<td class="chatlist" style="padding: 1px">&nbsp;<?php echo $BL['be_cnt_type'] ?>:</td>
	<td class="chatlist" style="padding: 1px">&nbsp;<?php echo $BL['be_newsletter_name'] ?>:</td>	
	<td class="chatlist" style="padding: 1px">&nbsp;<?php echo $BL['be_cnt_label'] ?>:</td>
	<td class="chatlist" title="size/columns" style="padding: 1px">&nbsp;S/C:</td>
	<td class="chatlist" title="maxlength/rows" style="padding: 1px">&nbsp;M/R:</td>
	<td align="center" style="padding: 1px"><img src="img/article/fill_in_here.gif" alt="<?php echo $BL['be_cnt_needed'] ?>" title="<?php echo $BL['be_cnt_needed'] ?>" border="0" /></td>
	<td align="center" style="padding: 1px"><img src="img/button/trash_13x13_1.gif" alt="<?php echo $BL['be_cnt_delete'] ?>" title="<?php echo $BL['be_cnt_delete'] ?>" border="0" /></td>
</tr>
<?php
if(isset($content['form']["fields"]) && is_array($content['form']["fields"]) && count($content['form']["fields"])) {

	$field_counter			= 1;
	$field_max				= count($content['form']["fields"]);
	$field_js				= array( 'showAll' => array(), 'hideAll' => array(), 'varcharFields' => array(), 'longtextFields' => array() );
	
	foreach($content['form']["fields"] as $key => $value) {
	
		$field_bg				= ($field_counter % 2) ? '' : ' bgcolor="#F3F5F8"';
		$field_row4				= '';
	
		// generate javascript code part 1
		$field_js['showAll'][$key]  = '	showHide_CntFormfieldRow(\'formRow_'.$field_counter.'\', \'block\'';
		$field_js['hideAll'][$key]  = '	showHide_CntFormfieldRow(\'formRow_'.$field_counter.'\', \'none\'';
	
		echo '<tr'.$field_bg.'>'.LF;
		echo '<td align="center" id="formRow_'.$field_counter.'">';
		echo '<a href="#" onclick="return showHide_CntFormfieldRow(\'formRow_'.$field_counter.'\', \'none\'';

		// some field specific checks and settings
		switch($content['form']["fields"][$key]['type']) {
		
			case 'newsletter':		// default hide/show
									echo ', 4';
									
									$field_row4  = '<tr'.$field_bg.' id="formRow_'.$field_counter.'_4">'.LF;
									$field_row4 .= '<td colspan="2" class="chatlist" align="right" valign="top">&nbsp;<img src="img/leer.gif" width="1" height="15" alt="" />';
									$field_row4 .= $BL['be_cnt_bid_verifyemail'].':&nbsp;</td>'.LF;
									$field_row4 .= '<td colspan="6"><textarea name="cform_field_verifyemail" ';
									$field_row4 .= 'id="cform_field_verifyemail" rows="5" class="code" style="font-size:11px;width:323px;" wrap="off">';
									$field_row4 .= html_specialchars($content['form']['verifyemail']).'</textarea></td>';
									$field_row4 .= LF.'</tr>'.LF;
									
									$field_js['showAll'][$key] .= ', 4';
									$field_js['hideAll'][$key] .= ', 4';
									
									break;
									
			case 'text':
			case 'special':
			case 'email':
			case 'password':
			case 'hidden':
			case 'select':
			case 'radio':			// default hide/show
									if($content['form']["saveprofile"]) {
										echo ', 4';
	
										$field_row4  = '<tr'.$field_bg.' id="formRow_'.$field_counter.'_4">'.LF;
										$field_row4 .= '<td colspan="2" class="chatlist" align="right">'.$BL['be_cnt_store_in'].':&nbsp;</td>'.LF;
										$field_row4 .= '<td colspan="6" id="cform_field_profile_'.$field_counter.'_td">';
										
										if(!empty($content['form']["fields"][$key]['profile']) && isset($content['profile_fields_varchar'][ $content['form']["fields"][$key]['profile'] ])) {
										
											$field_js['varcharFields'][$field_counter]  = '<"+"option value=\"'.$content['form']["fields"][$key]['profile'].'\" selected=\"selected\">';
											$field_js['varcharFields'][$field_counter] .= $content['profile_fields_varchar'][ $content['form']["fields"][$key]['profile'] ].'<"+"/option>';
											unset($content['profile_fields_varchar'][ $content['form']["fields"][$key]['profile'] ]);
										
										} else {
										
											$field_js['varcharFields'][$field_counter] = '';
										
										}
										
										$field_row4 .= '</td>'.LF.'</tr>'.LF;
	
										$field_js['showAll'][$key] .= ', 4';
										$field_js['hideAll'][$key] .= ', 4';
									}
									break;


			case 'textarea':
			case 'checkbox':
			case 'list':			// default hide/show
									if($content['form']["saveprofile"]) {
										echo ', 4';
	
										$field_row4  = '<tr'.$field_bg.' id="formRow_'.$field_counter.'_4">'.LF;
										$field_row4 .= '<td colspan="2" class="chatlist" align="right">'.$BL['be_cnt_store_in'].':&nbsp;</td>'.LF;
										$field_row4 .= '<td colspan="6" id="cform_field_profile_'.$field_counter.'_td">';
										
										if(!empty($content['form']["fields"][$key]['profile']) && isset($content['profile_fields_longtext'][ $content['form']["fields"][$key]['profile'] ])) {
										
											$field_js['longtextFields'][$field_counter]  = '<"+"option value=\"'.$content['form']["fields"][$key]['profile'].'\" selected=\"selected\">';
											$field_js['longtextFields'][$field_counter] .= $content['profile_fields_longtext'][ $content['form']["fields"][$key]['profile'] ].'<"+"/option>';
											unset($content['profile_fields_longtext'][ $content['form']["fields"][$key]['profile'] ]);
										
										} else {
										
											$field_js['longtextFields'][$field_counter] = '';
										}
										
										$field_row4 .= '</td>'.LF.'</tr>'.LF;
	
										$field_js['showAll'][$key] .= ', 4';
										$field_js['hideAll'][$key] .= ', 4';
									}
									break;

/*
			case 'upload':			// default hide/show
									if($content['form']["saveprofile"]) {
										echo ', 4';
	
										$field_row4  = '<tr'.$field_bg.' id="formRow_'.$field_counter.'_4">'.LF;
										$field_row4 .= '<td colspan="2" class="chatlist" align="right">&nbsp;';
										$field_row4 .= 'profile file:&nbsp;</td>'.LF;
										$field_row4 .= '<td colspan="6">';
										
										$field_row4 .= '	<select name="cform_field_profile['.$field_counter.']" id="cform_field_profile_'.$field_counter.'" class="v10">';
										$field_row4 .= LF.'		<option value="">&nbsp;</option>';
										$field_row4 .= '	</select>';
										
										$field_row4 .= '</td>'.LF.'</tr>'.LF;
	
										$field_js['showAll'][$key] .= ', 4';
										$field_js['hideAll'][$key] .= ', 4';
									}
									break;
*/
									
		}
		
		echo ')"><img src="img/button/arrow_opened.gif" alt="" border="0" /></a>';
		echo '</td>'.LF.'<td>';
		echo '<select name="cform_field_type['.$field_counter.']" class="v10" style="width:140px">'."\n";
		echo '<option value="text"'. 		is_selected('text', 		$content['form']["fields"][$key]['type'], 0, 0) .'>'.$BL['be_cnt_field']['text'].'</option>'."\n";
		echo '<option value="textarea"'. 	is_selected('textarea', 	$content['form']["fields"][$key]['type'], 0, 0) .'>'.$BL['be_cnt_field']['textarea'].'</option>'."\n";
		echo '<option value="special"'. 	is_selected('special', 		$content['form']["fields"][$key]['type'], 0, 0) .'>'.$BL['be_cnt_field']['special'].'</option>'."\n";
		echo '<option value="hidden"'. 		is_selected('hidden', 		$content['form']["fields"][$key]['type'], 0, 0) .'>'.$BL['be_cnt_field']['hidden'].'</option>'."\n";
		echo '<option value="password"'. 	is_selected('password', 	$content['form']["fields"][$key]['type'], 0, 0) .'>'.$BL['be_cnt_field']['password'].'</option>'."\n";
		echo '<option value="email"'. 		is_selected('email', 		$content['form']["fields"][$key]['type'], 0, 0) .'>'.$BL['be_cnt_field']['email'].'</option>'."\n";
		echo '<option value="select"'. 		is_selected('select', 		$content['form']["fields"][$key]['type'], 0, 0) .'>'.$BL['be_cnt_field']['select'].'</option>'."\n";
		echo '<option value="list"'. 		is_selected('list', 		$content['form']["fields"][$key]['type'], 0, 0) .'>'.$BL['be_cnt_field']['list'].'</option>'."\n";
		echo '<option value="newsletter"'. 	is_selected('newsletter', 	$content['form']["fields"][$key]['type'], 0, 0) .'>'.$BL['be_cnt_field']['newsletter'].'</option>'."\n";
		echo '<option value="checkbox"'. 	is_selected('checkbox', 	$content['form']["fields"][$key]['type'], 0, 0) .'>'.$BL['be_cnt_field']['checkbox'].'</option>'."\n";
		echo '<option value="radio"'. 		is_selected('radio',		$content['form']["fields"][$key]['type'], 0, 0) .'>'.$BL['be_cnt_field']['radio'].'</option>'."\n";
		echo '<option value="upload"'. 		is_selected('upload', 		$content['form']["fields"][$key]['type'], 0, 0) .'>'.$BL['be_cnt_field']['upload'].'</option>'."\n";
		echo '<option value="captcha"'. 	is_selected('captcha',		$content['form']["fields"][$key]['type'], 0, 0) .'>'.$BL['be_cnt_field']['captcha'].'</option>'."\n";
		echo '<option value="captchaimg"'.	is_selected('captchaimg', 	$content['form']["fields"][$key]['type'], 0, 0) .'>'.$BL['be_cnt_field']['captchaimg'].'</option>'."\n";
		echo '<option value="submit"'. 		is_selected('submit', 		$content['form']["fields"][$key]['type'], 0, 0) .'>'.$BL['be_cnt_field']['submit'].'</option>'."\n";
		echo '<option value="reset"'. 		is_selected('reset', 		$content['form']["fields"][$key]['type'], 0, 0) .'>'.$BL['be_cnt_field']['reset'].'</option>'."\n";
		echo '<option value="break"'. 		is_selected('break', 		$content['form']["fields"][$key]['type'], 0, 0) .'>'.$BL['be_cnt_field']['break'].'</option>'."\n";
		echo '<option value="breaktext"'. 	is_selected('breaktext',	$content['form']["fields"][$key]['type'], 0, 0) .'>'.$BL['be_cnt_field']['breaktext'].'</option>'."\n";
		echo '</select></td>';
		
		echo '<td><input type="text" name="cform_field_name['.$field_counter.']" class="v10" style="width:120px;" value="';
		echo html_specialchars($content['form']["fields"][$key]['name']).'"></td>'."\n";
		echo '<td><input type="text" name="cform_field_label['.$field_counter.']" class="v10" style="width:120px;" value="';
		echo html_specialchars($content['form']["fields"][$key]['label']).'"></td>'."\n";
		echo '<td><input type="text" name="cform_field_size['.$field_counter.']" class="v10" style="width:40px;" value="';
		echo html_specialchars($content['form']["fields"][$key]['size']).'"title="SIZE for Text/COLUMNS for Textarea"></td>'."\n";
		echo '<td><input type="text" name="cform_field_max['.$field_counter.']" class="v10" style="width:40px;" value="';
		echo html_specialchars($content['form']["fields"][$key]['max']).'" title="MAXLENGTH for Text/ROWS for Textarea and List"></td>'."\n";
		echo '<td><input type="checkbox" name="cform_field_required['.$field_counter.']"';
		echo is_checked('1', $content['form']["fields"][$key]['required'], 0, 0).' value="1" title="'.$BL['be_cnt_mark_as_req'].'"></td>'."\n";
		echo '<td><input type="checkbox" name="cform_field_delete['.$field_counter.']" value="1" title="'.$BL['be_cnt_mark_as_del'].'"></td>';
		echo "\n</tr>\n";
		
		
		echo '<tr'.$field_bg.' id="formRow_'.$field_counter.'_1"><td>&nbsp;</td>';
		echo '<td valign="top"><table summary="" cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td valign="top">';
		echo '<img src="img/leer.gif" width="3" height="17" alt="" />';
		if($field_counter != 1) {
			echo '<a href="#" onclick="document.articlecontent.cform_order_'.$field_counter.'.value=\'';
			echo ($field_counter-1).'\';document.articlecontent.cform_order_'.($field_counter-1);
			echo '.value=\''.$field_counter.'\';document.articlecontent.submit();return false;">';
			echo '<img src="img/button/sort_top_1.gif" border="0" alt="" width="11" height="11" />';
			echo '</a>';
		} else {
			echo '<img src="img/button/sort_top_0.gif" border="0" alt="" width="11" height="11" />';
		} 
		echo '<img src="img/leer.gif" width="1" height="1" alt="" />';
		if($field_max != $field_counter) {
			echo '<a href="#" onclick="document.articlecontent.cform_order_'.$field_counter.'.value=\'';
			echo ($field_counter+1).'\';document.articlecontent.cform_order_'.($field_counter+1);
			echo '.value=\''.$field_counter.'\';document.articlecontent.submit();return false;">';
			echo '<img src="img/button/sort_bottom_1.gif" border="0" alt="" width="11" height="11" />';
			echo '</a>';
		} else {
			echo '<img src="img/button/sort_bottom_0.gif" border="0" alt="" width="11" height="11" />';
		} 
		echo '<input type="hidden" name="cform_order['.$field_counter.']" id="cform_order_'.$field_counter.'" value="'.$field_counter.'">';
		echo '</td><td class="chatlist" align="right" valign="top"><a name="field_value_'.$field_counter.'"></a>';
		echo '<img src="img/leer.gif" width="1" height="15" alt="" />'.$BL['be_cnt_value'].':&nbsp;';
		echo "</td></tr></table></td>\n";
		
		echo '<td colspan="4"><textarea name="cform_field_value['.$field_counter.']" ';
		echo 'id="cform_field_value_'.$field_counter.'" rows="5" class="code" style="font-size:11px;width:323px;">';
		echo html_specialchars($content['form']["fields"][$key]['value']).'</textarea>';
		
		echo '</td>';
		echo '<td colspan="2" valign="bottom"><div style="padding:2px"><a href="#field_value_'.$field_counter.'" ';
		echo "onclick=\"contractField('cform_field_value_".$field_counter."', 'V')\">";
		echo '<img src="img/button/minus_11x11.gif" border="0" alt="-" width="11" height="11"></a><br />';
		echo '<a href="#field_value_'.$field_counter.'" ';
		echo "onclick=\"growField('cform_field_value_".$field_counter."', 'V')\">";
		echo '<img src="img/button/add_11x11.gif" border="0" alt="+" width="11" height="11"></a></div></td>';		
		echo '</tr>'."\n";

		echo '<tr'.$field_bg.' id="formRow_'.$field_counter.'_2">';
		echo '<td colspan="2" class="chatlist" align="right">&nbsp;'.$BL['be_cnt_error_text'].':&nbsp;</td>';
		echo '<td colspan="6"><input type="text" name="cform_field_error['.$field_counter.']" value="';
		echo  html_specialchars($content['form']["fields"][$key]['error']).'" class="v10" style="width:323px;"';
		if($content['form']["fields"][$key]['type'] == 'upload') {
			echo ' title="{MAXLENGTH}, {FILESIZE}, {FILENAME}, {FILEEXT}"';
		}
		echo '></td>'.LF.'</tr>'."\n".'<tr'.$field_bg.' id="formRow_'.$field_counter.'_3">';
		echo '<td colspan="2" class="chatlist" align="right">&nbsp;'.$BL['be_cnt_css_class'].':&nbsp;</td>';
		echo '<td><input type="text" name="cform_field_class['.$field_counter.']" value="';
		echo  html_specialchars($content['form']["fields"][$key]['class']).'" class="v10" style="width:120px;"></td>'."\n";
		echo '<td colspan="5"><table summary="" cellpadding="0" cellspacing="0" border="0" style="width:202px;"><tr>
			 <td class="chatlist" style="width:82px;" align="right">&nbsp;'.$BL['be_cnt_css_style'].':&nbsp;</td>
			 <td style="width:120px;"><input type="text" name="cform_field_style['.$field_counter.']" value="';
		echo html_specialchars($content['form']["fields"][$key]['style']).'" class="v10" style="width:120px;"></td></tr></table></td>';
		
		echo "\n</tr>\n";
		
		// if field row 4 
		echo $field_row4;
		
		echo '<tr bgcolor="#DAE4ED"><td colspan="8"><img src="img/leer.gif" width="1" height="1" alt="" /></td></tr>';
		
		
		// generate javascript code part 2
		$field_js['showAll'][$key] .= ');';
		$field_js['hideAll'][$key] .= ');';

		$field_counter++;
	}

}

?>
<tr bgcolor="#E7E8EB">
	<td>&nbsp;</td>
	<td><select name="cform_field_type[0]" class="v10" style="width:140px">
	<option value="text"><?php echo $BL['be_cnt_field']['text'] ?></option>
	<option value="textarea"><?php echo $BL['be_cnt_field']['textarea'] ?></option>
	<option value="special"><?php echo $BL['be_cnt_field']['special'] ?></option>
	<option value="hidden"><?php echo $BL['be_cnt_field']['hidden'] ?></option>
	<option value="password"><?php echo $BL['be_cnt_field']['password'] ?></option>
	<option value="email"><?php echo $BL['be_cnt_field']['email'] ?></option>
	<option value="select"><?php echo $BL['be_cnt_field']['select'] ?></option>
	<option value="list"><?php echo $BL['be_cnt_field']['list'] ?></option>
	<?php
	if(empty($for_newsletter)) {
	?>
	<option value="newsletter"><?php echo $BL['be_cnt_field']['newsletter'] ?></option>
	<?php
	}
	?>
	<option value="checkbox"><?php echo $BL['be_cnt_field']['checkbox'] ?></option>
	<option value="radio"><?php echo $BL['be_cnt_field']['radio'] ?></option>
	<option value="upload"><?php echo $BL['be_cnt_field']['upload'] ?></option>
	<option value="captcha"><?php echo $BL['be_cnt_field']['captcha'] ?></option>
	<option value="captchaimg"><?php echo $BL['be_cnt_field']['captchaimg'] ?></option>
	<option value="submit"><?php echo $BL['be_cnt_field']['submit'] ?></option>
	<option value="reset"><?php echo $BL['be_cnt_field']['reset'] ?></option>
	<option value="break"><?php echo $BL['be_cnt_field']['break'] ?></option>
	<option value="breaktext"><?php echo $BL['be_cnt_field']['breaktext'] ?></option>
	</select></td>
	<td><input type="text" name="cform_field_name[0]" class="v10" style="width:120px;" /></td>	
	<td><input type="text" name="cform_field_label[0]" class="v10" style="width:120px;" /></td>
	<td><input type="text" name="cform_field_size[0]" class="v10" style="width:40px;" title="SIZE for Text/COLUMNS for Textarea" /></td>
	<td><input type="text" name="cform_field_max[0]" class="v10" style="width:40px;" title="MAXLENGTH for Text/ROWS for Textarea and List" /></td>
	<td><input type="checkbox" name="cform_field_required[0]" value="1" title="mark as required field" /></td>
	<td>&nbsp;
	  <input type="hidden" name="cform_order[0]" value="<?php echo $field_counter?>" /></td>
</tr>
<tr bgcolor="#E7E8EB">
	<td colspan="2" class="chatlist" valign="top" align="right"><a name="field_value_0" id="field_value_0"></a>&nbsp;<img src="img/leer.gif" alt="" width="1" height="15" /><?php echo $BL['be_cnt_value'] ?>:&nbsp;</td>
	<td colspan="4"><textarea name="cform_field_value[0]" id="cform_field_value_0" rows="5" class="code" style="font-size:11px;width:323px;"></textarea></td>
	<td colspan="2" valign="bottom"><div style="padding:2px"><a href="#field_value_0" onclick="contractField('cform_field_value_0', 'V')"><img src="img/button/minus_11x11.gif" border="0" alt="-" width="11" height="11" /></a><br />
	  <a href="#field_value_0" onclick="growField('cform_field_value_0', 'V')"><img src="img/button/add_11x11.gif" border="0" alt="+" width="11" height="11" /></a></div></td>
</tr>
<tr bgcolor="#E7E8EB">
	<td colspan="2" class="chatlist" align="right">&nbsp;<?php echo $BL['be_cnt_error_text'] ?>:&nbsp;</td>
	<td colspan="6"><input type="text" name="cform_field_error[0]" class="v10" style="width:323px;" /></td>
</tr>
<tr bgcolor="#E7E8EB">
	<td colspan="2" class="chatlist" align="right">&nbsp;<?php echo $BL['be_cnt_css_class']	?>:&nbsp;</td>
	<td><input type="text" name="cform_field_class[0]" class="v10" style="width:120px;" /></td>
	<td colspan="5"><table summary="" cellpadding="0" cellspacing="0" border="0" style="width:202px;">
		<tr>
		<td class="chatlist" style="width:82px;" align="right">&nbsp;<?php echo $BL['be_cnt_css_style'] ?>:&nbsp;</td>
		<td style="width:120px;"><input type="text" name="cform_field_style[0]" class="v10" style="width:120px;" /></td>
		</tr>
	</table></td>
</tr>
<tr bgcolor="#DAE4ED"><td colspan="8"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
</tr>
<tr><td colspan="8"><img src="img/leer.gif" alt="" width="1" height="2" /></td>
</tr>
<tr><td colspan="2">&nbsp;</td><td colspan="6"><input type="submit" value="<?php echo $BL['be_article_cnt_button1'] ?>" class="v09" /></td></tr>

</table><?php

if(!empty($field_counter) && $field_counter > 1) {
	
	echo '<script language="javascript" type="text/javascript">'.LF.'<!--'.LF;
	
	echo 'function hideAllFormFields() {'.LF;
	echo implode(LF, $field_js['hideAll']);
	echo LF.'}'.LF;
	
	echo 'function showAllFormFields() {'.LF;
	echo implode(LF, $field_js['showAll']);
	echo LF.'}'.LF.LF;
	
	echo 'hideAllFormFields();'.LF.LF;
	
	
	// set options lists
	if($content['form']["saveprofile"]) {
	
		$field_js['options'] = '';
		foreach($content['profile_fields_varchar'] as $fieldKey => $fieldValue) {
			$field_js['options'] .= '<"+"option value=\"'.$fieldKey.'\">'.$fieldValue.'<"+"/option>';
		}
		
		foreach($field_js['varcharFields'] as $tdID => $tdIDvalue) {
		
			$field_value  = 'document.getElementById("cform_field_profile_'.$tdID.'_td").innerHTML = "';
			$field_value .= '<"+"select name=\"cform_field_profile['.$tdID.']\" id=\"cform_field_profile_'.$tdID.'\" class=\"v10\">';
			$field_value .= '<"+"option value=\"\">-<"+"/option>';
			$field_value .= $tdIDvalue;
			$field_value .= $field_js['options'];
			$field_value .= '<"+"/select>";'.LF;
			
			echo $field_value;
		
		}
		
		$field_js['options'] = '';
		foreach($content['profile_fields_longtext'] as $fieldKey => $fieldValue) {
			$field_js['options'] .= '<"+"option value=\"'.$fieldKey.'\">'.$fieldValue.'<"+"/option>';
		}
		
		foreach($field_js['longtextFields'] as $tdID => $tdIDvalue) {
		
			$field_value  = 'document.getElementById("cform_field_profile_'.$tdID.'_td").innerHTML = "';
			$field_value .= '<"+"select name=\"cform_field_profile['.$tdID.']\" id=\"cform_field_profile_'.$tdID.'\" class=\"v10\">';
			$field_value .= '<"+"option value=\"\">-<"+"/option>';
			$field_value .= $tdIDvalue;
			$field_value .= $field_js['options'];
			$field_value .= '<"+"/select>";'.LF;
			
			echo $field_value;
		
		}
		
		
	
	}
	
	
	echo '//-->'.LF.'</script>';
}

?></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
</tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
</tr>

<tr><td class="chatlist" colspan="2"><a name="anchor_template" id="anchor_template"></a><?php echo $BL['be_cnt_labelemail'] ?>:&nbsp;</td><!-- be_admin_struct_template -->
</tr>
<tr>
	<td colspan="2"><table summary="" cellpadding="0" cellspacing="0" border="0" bgcolor="#E7E8EB" style="margin-top:3px;">
		<tr>
		<td><input type="radio" name="cform_template_format" id="cform_template_text" value="0"<?php echo is_checked('0', $content['form']["template_format"], 0, 0) ?> /></td>
		<td class="f10"><label for="cform_template_text">TEXT</label>&nbsp;&nbsp;</td>
		<td><input type="radio" name="cform_template_format" id="cform_template_html" value="1"<?php echo is_checked('1', $content['form']["template_format"], 0, 0) ?> /></td>
		<td class="f10"><label for="cform_template_html">HTML</label>&nbsp;</td>
		<?php
		if(!$content['form']["template_format"] && $for_select != '') {
			echo '<td style="padding:2px;"><select name="ph" id="ph" class="v10" ';
			echo 'onChange="insertAtCursorPos(document.articlecontent.cform_template, ';
			echo 'document.articlecontent.ph.options[document.articlecontent.ph.selectedIndex].value);">';
			echo $for_select;
			echo '<option value="{FORM_URL}">{FORM_URL}</option>'.LF;
			echo '<option value="{REMOTE_IP}">{REMOTE_IP}</option>'.LF;
			echo '<option value="{DATE:y/m/d H:i:s}">{DATE:y/m/d H:i:s}</option>'.LF;
			echo '</select></td>';
			echo '<td><img src="img/button/go04.gif" width="15" height="15" title="insert field placeholder" border="0" ';
			echo 'onclick="insertAtCursorPos(document.articlecontent.cform_template, ';
			echo 'document.articlecontent.ph.options[document.articlecontent.ph.selectedIndex].value);" style="margin:3px;" alt="" /></td>';
		}
		?>
		</tr>
	</table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2" /></td>
</tr>
<tr>
	<td colspan="2"><?php
if($content['form']["template_format"]) {
	$wysiwyg_editor = array(
		'value'		=> $content['form']["template"],
		'field'		=> 'cform_template',
		'height'	=> '250px',
		'width'		=> '536px',
		'rows'		=> '15',
		'editor'	=> $_SESSION["WYSIWYG_EDITOR"],
		'lang'		=> 'en'
	);
	include('include/inc_lib/wysiwyg.editor.inc.php');
} else {

	echo '<textarea name="cform_template" id="cform_template" rows="5" class="code" style="width:536px;" ';
	echo 'onselect="setCursorPos(this);" onclick="setCursorPos(this);" onkeyup="setCursorPos(this);">';
	echo html_specialchars($content['form']["template"]).'</textarea>';
	?>
	<div style="text-align:right;padding:2px;padding-right:5px;">
	<a href="#anchor_template" onclick="contractField('cform_template', 'V')"><img src="img/button/minus_11x11.gif" border="0" alt="-" width="11" height="11" /></a><a href="#anchor_template" onclick="growField('cform_template', 'V')"><img src="img/button/add_11x11.gif" border="0" alt="+" width="11" height="11" /></a>	</div>
	<?php
}

?></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
</tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
</tr>

<!-- custom form template -->
<tr>
	<td class="chatlist" colspan="2" style="padding-bottom:3px;"><a name="anchor_customform" id="anchor_customform"></a><?php echo $BL['be_admin_struct_template'] ?>:&nbsp;</td>
</tr>
<?php
	 
if($for_select_2 != '') { //!$content['form']["template_format"] && 
	echo '<tr><td colspan="2"><table summary="" cellpadding="0" cellspacing="0" border="0" bgcolor="#E7E8EB"><tr><td style="padding:2px;">';
	echo '<select name="ph1" id="ph1" class="v10" ';
	echo 'onChange="insertAtCursorPos(document.articlecontent.cform_customform, ';
	echo 'document.articlecontent.ph1.options[document.articlecontent.ph1.selectedIndex].value);">';
	echo $for_select_2.'</select></td>';
	echo '<td><img src="img/button/go04.gif" width="15" height="15" title="insert field placeholder" border="0" ';
	echo 'onclick="insertAtCursorPos(document.articlecontent.cform_customform, ';
	echo 'document.articlecontent.ph1.options[document.articlecontent.ph1.selectedIndex].value);" style="margin:3px;" alt="" />';
	echo '</td></tr></table></td></tr>';
}

?>


<tr>
	<td colspan="2">
	<textarea name="cform_customform" id="cform_customform" rows="5" class="code" style="width:536px;" onselect="setCursorPos(this);" onclick="setCursorPos(this);" onkeyup="setCursorPos(this);"><?php echo html_specialchars($content['form']["customform"]) ?></textarea>
	<div style="text-align:right;padding:2px;padding-right:5px;">
	<a href="#anchor_customform" onclick="contractField('cform_customform', 'V')"><img src="img/button/minus_11x11.gif" border="0" alt="-" width="11" height="11" /></a><a href="#anchor_customform" onclick="growField('cform_customform', 'V')"><img src="img/button/add_11x11.gif" border="0" alt="+" width="11" height="11" /></a>	</div>
	</td>
</tr>


<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td>
</tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
</tr>