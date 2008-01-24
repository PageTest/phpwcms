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

//calendar specific functions
//PHPWCMS_TEMPLATE.'calendar/calendar.ini'

function initializeCalendar($ini='') {
	$_baseCalVal = getCurrentCalendarDate();
	$_calDates = ($ini && file_exists($ini)) ? parse_ini_file($ini, true) : array();
	$_baseCalVal['days'] = array();
	if(isset($_calDates[ $_baseCalVal['current_month'] ]) && is_array($_calDates[ $_baseCalVal['current_month'] ])) {
		foreach($_calDates[ $_baseCalVal['current_month'] ] as $_calDay => $calLink) {
			$_baseCalVal['days'][ strval($_baseCalVal['current_month'].substr('0'.$_calDay, -2)) ] = array($calLink, NULL, NULL);
		}
	}
	return $_baseCalVal;
}


function buildCalendarLink($date_value='') {
	global $_getVar;
	$_oldCalValue = '';
	if(!empty($_getVar['calendardate'])) $_oldCalValue = $_getVar['calendardate']; //save old value
	if(!empty($date_value)) $_getVar['calendardate'] = $date_value; //set new value
	$link = 'index.php' . returnGlobalGET_QueryString('urlencode'); //build Link
	if($_oldCalValue) $_getVar['calendardate'] = $_oldCalValue; //restore old value
	return $link;
}


function buildCalendarGETValue($value=array(), $spacer='-') {
	if(!is_array($value) || !count($value)) {
		$value = array('year'=>date('Y'), 'month'=>date('n'), 'day'=>date('j'));
	}
	return $value['year'].$spacer.$value['month'].$spacer.$value['day'];
}


function getCurrentCalendarDate() {

	global $_getVar;
	
	$_date = array();
	
	$_date['year']	= date('Y');
	$_date['month']	= date('n');
	$_date['day']	= date('j');
	
	if(!empty($_getVar['calendardate'])) {
		$d = explode('-', $_getVar['calendardate']);
		if(!empty($d[0]) && intval($d[0]))	$_date['year']	= intval($d[0]);
		if(!empty($d[1]) && intval($d[1]))	$_date['month']	= intval($d[1]);
		if(!empty($d[2]) && intval($d[2]))	$_date['day']	= intval($d[2]);
		if (($int_time = strtotime($_date['year'].'/'.$_date['month'].'/'.$_date['day'])) === -1) {
   			$_date['year']	= date('Y');
			$_date['month']	= date('n');
			$_date['day']	= date('j');
		} else {
   			$_date['year']	= date('Y', $int_time);
			$_date['month']	= date('n', $int_time);
			$_date['day']	= date('j', $int_time);
		}
	}
		
	define('THIS_YEAR',		$_date['year']);
	define('THIS_MONTH',	$_date['month']);
	define('THIS_DAY',		$_date['day']);
	

	$_date['next_month']['month']	= ($_date['month'] == 12)				? 1						: $_date['month'] + 1;
	$_date['prev_month']['month']	= ($_date['month'] == 1)				? 12					: $_date['month'] - 1;
	$_date['next_month']['year']	= ($_date['next_month']['month'] == 1)	? $_date['year'] + 1	: $_date['year'];
	$_date['prev_month']['year']	= ($_date['prev_month']['month'] == 12)	? $_date['year'] - 1	: $_date['year'];
	
	$_date['next_year']['month']	= $_date['month'];
	$_date['prev_year']['month']	= $_date['month'];
	$_date['next_year']['year']		= $_date['year'] + 1;
	$_date['prev_year']['year']		= $_date['year'] - 1;
	
	$_date['next_month']['day']		= $_date['day'];
	$_date['prev_month']['day']		= $_date['day'];
	$_date['next_year']['day']		= $_date['day'];
	$_date['prev_year']['day']		= $_date['day'];
	
	$_date['next_link']	= buildCalendarLink( buildCalendarGETValue($_date['next_month']) );
	$_date['prev_link']	= buildCalendarLink( buildCalendarGETValue($_date['prev_month']) );
	
	$_date['current_month'] = THIS_YEAR.substr('0'.THIS_MONTH, -2);
	
	return $_date;

}




?>