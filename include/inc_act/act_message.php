<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2016, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

session_start();
$phpwcms = array();

require_once '../../include/config/conf.inc.php';
require_once '../inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';
checkLogin();
validate_csrf_tokens();
require_once PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';

list($do, $id, $wert) = explode(".", $_GET["do"]);
$do		= intval($do);
$id		= intval($id);

//Message in den Papierkorb bewegen
if($do == 1) {
	if(intval($wert)) {
		$sql = 	"UPDATE ".DB_PREPEND."phpwcms_message SET ".
				"msg_deleted=1, msg_tstamp=msg_tstamp, msg_read=1 WHERE ".
				"msg_uid=".$_SESSION["wcs_user_id"]." AND ".
				"msg_id=".$id.";";
		mysql_query($sql, $db) or die("error");
	}
}

//Durch User versendete Message in den Papierkorb bewegen
if($do == 2) {
	if(intval($wert)) {
		$sql = 	"UPDATE ".DB_PREPEND."phpwcms_message SET ".
				"msg_from_del=1, msg_tstamp=msg_tstamp  WHERE ".
				"msg_from=".$_SESSION["wcs_user_id"]." AND ".
				"msg_id=".$id.";";
		mysql_query($sql, $db) or die("error");
	}
}

//Undo Normale Message
if($do == 3) {
	if(intval($wert) == 0) {
		$sql = 	"UPDATE ".DB_PREPEND."phpwcms_message SET ".
				"msg_deleted=0, msg_tstamp=msg_tstamp WHERE ".
				"msg_uid=".$_SESSION["wcs_user_id"]." AND ".
				"msg_id=".$id.";";
		mysql_query($sql, $db) or die("error");
	}
}

//Undo Sent Message
if($do == 4) {
	if(intval($wert) == 0) {
		$sql = 	"UPDATE ".DB_PREPEND."phpwcms_message SET ".
				"msg_from_del=0, msg_tstamp=msg_tstamp  WHERE ".
				"msg_from=".$_SESSION["wcs_user_id"]." AND ".
				"msg_id=".$id.";";
		mysql_query($sql, $db) or die("error");
	}
}

//Delete Normale Message
if($do == 5) {
	if(intval($wert) == 9) {
		$sql = 	"UPDATE ".DB_PREPEND."phpwcms_message SET ".
				"msg_deleted=9, msg_tstamp=msg_tstamp WHERE ".
				"msg_uid=".$_SESSION["wcs_user_id"]." AND ".
				"msg_id=".$id." AND msg_deleted=1;";
		mysql_query($sql, $db) or die("error");
	}
}

//Delete sent message (Set del to 9)
if($do == 6) {
	if(intval($wert) == 9) {
		$sql = 	"UPDATE ".DB_PREPEND."phpwcms_message SET ".
				"msg_from_del=9, msg_tstamp=msg_tstamp  WHERE ".
				"msg_from=".$_SESSION["wcs_user_id"]." AND ".
				"msg_id=".$id." AND msg_from_del=1;";
		mysql_query($sql, $db) or die("error");
	}
}

$ref = empty($_SESSION['REFERER_URL']) ? PHPWCMS_URL.'phpwcms.php?'.get_token_get_string('csrftoken') : $_SESSION['REFERER_URL'];

headerRedirect($ref);
