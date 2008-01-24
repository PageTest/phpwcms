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

/*
if(ini_get('zlib.output_compression') && function_exists('ini_set')) {
	ini_set('zlib.output_compression', 'Off');
}
*/

$phpwcms = array();

require_once ('config/phpwcms/conf.inc.php');
require_once ('include/inc_lib/default.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');

require_once (PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/functions.file.inc.php');

// try to get hash for file download
$success	= false;
$hash		= false;
$countonly	= empty($_GET['countonly']) ? false : true;
$hash 		= empty($_GET['f']) ? '' : clean_slweg($_GET['f']);

if(empty($phpwcms["inline_download"])) {
	$phpwcms["inline_download"]		= empty($_GET['target']) ? 0 : 1;
}

if(!empty($hash) && strlen($hash) == 32) {

	// get file info - limit 1 entry
	$download = _getFileInfo($hash, 1);
	
	if(is_array($download) && count($download)) {
		// all we need is the first array value

		$download = current($download);

		// ok fine - we have download information
		// then count up download try for this file
		$sql  = "UPDATE ".DB_PREPEND."phpwcms_file ";
		$sql .= "SET f_dlstart=f_dlstart+1 WHERE ";
		$sql .= "f_hash='".aporeplace($download["f_hash"])."' LIMIT 1";
		@mysql_query($sql, $db);


		$fileinfo = array();
		
		$fileinfo['filename'] = $download["f_hash"];
		if($download["f_ext"]) {
			$fileinfo['filename'] .= '.'.$download["f_ext"];
		}

		// just count up a download
		if($countonly) {

			$success = true;
			
		// just use built-in download
		} else {
		

			$fileinfo['path'] 		= PHPWCMS_ROOT.$phpwcms["file_path"];
			$fileinfo['filesize']	= $download['f_size'];
			$fileinfo['method']		= empty($phpwcms["inline_download"]) ? 'attachment' : 'inline';
			$fileinfo['mimetype']	= $download["f_type"];
			$fileinfo['file']		= $fileinfo['path'].$fileinfo['filename'];
			$fileinfo['extension']	= $download["f_ext"];
			$fileinfo['realfname']	= $download["f_name"];
					
			// start download
			$success = dl_file_resume($fileinfo['file'], $fileinfo, true);
		
		}
		

	}

}

if($success) {

	$sql  = "UPDATE ".DB_PREPEND."phpwcms_file ";
	$sql .= "SET f_dlfinal=f_dlfinal+1 WHERE f_hash='".aporeplace($download["f_hash"])."' LIMIT 1";
	@mysql_query($sql, $db);

	if($countonly) {
	
		headerRedirect(PHPWCMS_URL . PHPWCMS_FILES . $fileinfo['filename']);

	}

} else {

	header("HTTP/1.0 404 Not Found");
	echo '<strong>404 File Not Found</strong>';

}

exit();

?>