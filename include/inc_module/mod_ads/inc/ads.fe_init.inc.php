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

// some mod ADS functions only needed in frontend, but done right after opening page
// script contains everything necessary to track ad banner clicks and so on...

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// first check
if(isset($_GET['u']) && $_GET['u'] == PHPWCMS_USER_KEY) {

	$ads_id = intval($_GET['adclickval']);

	$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_ads_campaign ';
	$sql .= 'WHERE adcampaign_id='.$ads_id.' AND adcampaign_status=1 LIMIT 1';
	$ad_data = _dbQuery($sql);
	
	if(!empty($ad_data[0]['adcampaign_data'])) {
		$ad_data = @unserialize($ad_data[0]['adcampaign_data']);

		$ads_userip		= getRemoteIP();
		$ads_useragent	= $_SERVER['HTTP_USER_AGENT'];
		$ads_ref		= isset($_GET['r']) ? trim($_GET['r']) : '';
		$ads_cat		= empty($_GET['c']) ? 0 : intval($_GET['c']);
		$ads_article	= empty($_GET['a']) ? 0 : intval($_GET['a']);
	
		if(empty($_COOKIE['phpwcmsAdsUserId']) || !preg_match('/^[0-9a-f]{32}$/', ($ads_userid = $_COOKIE['phpwcmsAdsUserId']) ) ) {
			$ads_userid	= md5($ads_userip.microtime());
			setcookie('phpwcmsAdsUserId', $ads_userid, time()+63072000, '/', getCookieDomain() );
		}
		
		$sql  =	'INSERT DELAYED INTO '.DB_PREPEND.'phpwcms_ads_tracking (';
		$sql .= 'adtracking_created, adtracking_campaignid, adtracking_ip, adtracking_cookieid, ';
		$sql .= 'adtracking_countclick, adtracking_countview, adtracking_useragent, adtracking_ref, ';
		$sql .= 'adtracking_catid, adtracking_articleid) VALUES (';
		$sql .= "NOW(), ".$ads_id.", '".mysql_escape_string($ads_userip)."', '".mysql_escape_string($ads_userid)."', ";
		$sql .= "1, 0, '".mysql_escape_string($ads_useragent)."', '".mysql_escape_string($ads_ref)."', ".$ads_cat.", ".$ads_article.")";
		
		@_dbQuery($sql, 'INSERT');
		
		$sql  = 'UPDATE LOW_PRIORITY '.DB_PREPEND.'phpwcms_ads_campaign SET ';
		$sql .= 'adcampaign_curclick=adcampaign_curclick+1 WHERE adcampaign_id='.$ads_id;
		
		@_dbQuery($sql, 'UPDATE');
		
		headerRedirect($ad_data['url']);

	}
}

headerRedirect(PHPWCMS_URL);

?>