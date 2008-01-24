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

$_entry['query']			= '';

// create pagination
if(isset($_GET['c'])) {
	$_SESSION['list_count'] = $_GET['c'] == 'all' ? '99999' : intval($_GET['c']);
}
if(isset($_GET['page'])) {
	$_SESSION['detail_page'] = intval($_GET['page']);
}

// set default values for paginating
if(empty($_SESSION['list_count'])) {
	$_SESSION['list_count'] = 25;
}

// paginate and search form processing
if(isset($_POST['do_pagination'])) {

	$_SESSION['list_active']	= empty($_POST['showactive']) ? 0 : 1;
	$_SESSION['list_inactive']	= empty($_POST['showinactive']) ? 0 : 1;

	$_SESSION['filter']			= clean_slweg($_POST['filter']);
	if(empty($_SESSION['filter'])) {
		unset($_SESSION['filter']);
	} else {
		$_SESSION['filter']	= convertStringToArray($_SESSION['filter'], ' ');
		$_POST['filter']	= $_SESSION['filter'];
	}
	
	$_SESSION['detail_page'] = intval($_POST['page']);

}

if(empty($_SESSION['detail_page'])) {
	$_SESSION['detail_page'] = 1;
}

$_entry['list_active']		= isset($_SESSION['list_active'])	? $_SESSION['list_active']		: 1;
$_entry['list_inactive']	= isset($_SESSION['list_inactive'])	? $_SESSION['list_inactive']	: 1;


// set correct status query
if($_entry['list_active'] != $_entry['list_inactive']) {
	
	if(!$_entry['list_active']) {
		$_entry['query'] .= 'cat_status=0';
	}
	if(!$_entry['list_inactive']) {
		$_entry['query'] .= 'cat_status=1';
	}
	
} else {
	$_entry['query'] .= 'cat_status!=9';
}
$_entry['query'] .= " AND cat_type='module_shop'";

if(isset($_SESSION['filter']) && is_array($_SESSION['filter']) && count($_SESSION['filter'])) {
	
	$_entry['filter_array'] = array();

	foreach($_SESSION['filter'] as $_entry['filter']) {
		//usr_name, usr_login, usr_email
		$_entry['filter_array'][] = "cat_name LIKE '%".aporeplace($_entry['filter'])."%'";
		$_entry['filter_array'][] = "cat_info LIKE '%".aporeplace($_entry['filter'])."%'";
	}
	if(count($_entry['filter_array'])) {
		
		$_SESSION['filter'] = ' AND ('.implode(' OR ', $_entry['filter_array']).')';
		$_entry['query'] .= $_SESSION['filter'];
	
	}

} elseif(isset($_SESSION['filter']) && is_string($_SESSION['filter'])) {

	$_entry['query'] .= $_SESSION['filter'];

}

// paginating values
$_entry['count_total'] = _dbQuery('SELECT COUNT(cat_id) FROM '.DB_PREPEND.'phpwcms_categories WHERE '.$_entry['query'], 'COUNT');
$_entry['pages_total'] = ceil($_entry['count_total'] / $_SESSION['list_count']);
if($_SESSION['detail_page'] > $_entry['pages_total']) {
	$_SESSION['detail_page'] = empty($_entry['pages_total']) ? 1 : $_entry['pages_total'];
}



?>
<h1 class="title" style="margin-bottom:10px"><?php echo $BLM['listing_title'] ?></h1>

<div class="navBarLeft imgButton chatlist">
	&nbsp;&nbsp;
	<a href="<?php echo shop_url(array('controller=cat', 'edit=0')) ?>" title="<?php echo $BLM['create_new'] ?>"><img src="img/famfamfam/silk_icons_gif/tag_blue_add.gif" alt="Add" border="0" /><span><?php echo $BLM['create_new'] ?></span></a>
</div>


<form action="<?php echo shop_url('controller=cat') ?>" method="post" name="paginate" id="paginate"><input type="hidden" name="do_pagination" value="1" />
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="paginate" summary="">
	<tr>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">
			<tr>
				
				<td><input type="checkbox" name="showactive" id="showactive" value="1" onclick="this.form.submit();"<?php is_checked(1, $_entry['list_active'], 1) ?> /></td>
				<td><label for="showactive"><img src="img/button/aktiv_12x13_1.gif" alt="" style="margin:1px 1px 0 1px;" /></label></td>
				<td><input type="checkbox" name="showinactive" id="showinactive" value="1" onclick="this.form.submit();"<?php is_checked(1, $_entry['list_inactive'], 1) ?> /></td>
				<td><label for="showinactive"><img src="img/button/aktiv_12x13_0.gif" alt="" style="margin:1px 1px 0 1px;" /></label></td>

<?php 
if($_entry['pages_total'] > 1) {

	echo '<td class="chatlist">|&nbsp;</td>';
	echo '<td>';
	if($_SESSION['detail_page'] > 1) {
		echo '<a href="'. shop_url( array('controller=cat', 'page='.($_SESSION['detail_page']-1)) ) . '">';
		echo '<img src="img/famfamfam/mini/action_back.gif" alt="" border="0" /></a>';
	} else {
		echo '<img src="img/famfamfam/mini/action_back.gif" alt="" border="0" class="inactive" />';
	}
	echo '</td>';
	echo '<td><input type="text" name="page" id="page" maxlength="4" size="4" value="'.$_SESSION['detail_page'];
	echo '"  class="textinput" style="margin:0 3px 0 5px;width:30px;font-weight:bold;" /></td>';
	echo '<td class="chatlist">/'.$_entry['pages_total'].'&nbsp;</td>';
	echo '<td>';
	if($_SESSION['detail_page'] < $_entry['pages_total']) {
		echo '<a href="'.shop_url( array('controller=cat', 'page='.($_SESSION['detail_page']+1)) ) .'">';
		echo '<img src="img/famfamfam/mini/action_forward.gif" alt="" border="0" /></a>';
	} else {
		echo '<img src="img/famfamfam/mini/action_forward.gif" alt="" border="0" class="inactive" />';
	}
	echo '</td><td class="chatlist">&nbsp;|&nbsp;</td>';

} else {

	echo '<td class="chatlist">|&nbsp;<input type="hidden" name="page" id="page" value="1" /></td>';

}
?>
				<td><input type="text" name="filter" id="filter" size="10" value="<?php 
				
				if(isset($_POST['filter']) && is_array($_POST['filter']) ) {
					echo html_specialchars(implode(' ', $_POST['filter']));
				}
				
				?>" class="textinput" style="margin:0 2px 0 0;width:110px;text-align:left;" title="filter results by username, name or email" /></td>
				<td><input type="image" name="gofilter" src="img/famfamfam/mini/action_go.gif" style="margin-right:3px;" /></td>
			
			</tr>
		</table></td>

	<td class="chatlist" align="right">
		<a href="<?php echo shop_url(array('controller=cat', 'c=10')) ?>">10</a>
		<a href="<?php echo shop_url(array('controller=cat', 'c=25')) ?>">25</a>
		<a href="<?php echo shop_url(array('controller=cat', 'c=50')) ?>">50</a>
		<a href="<?php echo shop_url(array('controller=cat', 'c=100')) ?>">100</a>
		<a href="<?php echo shop_url(array('controller=cat', 'c=250')) ?>">250</a>
		<a href="<?php echo shop_url(array('controller=cat', 'c=all')) ?>"><?php echo $BL['be_ftptakeover_all'] ?></a>
	</td>

	</tr>
</table>
</form>

<table width="100%" border="0" cellpadding="0" cellspacing="0" summary="" class="shop">

	<tr><td colspan="3" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
	
<?php
// loop listing available newsletters
$row_count = 0;

$sql  = "SELECT C1.*, IFNULL(CONCAT(C2.cat_name, ' / ', C1.cat_name), C1.cat_name) AS category FROM ";
$sql .= DB_PREPEND.'phpwcms_categories C1 ';
$sql .= 'LEFT JOIN '.DB_PREPEND.'phpwcms_categories C2 ';
$sql .= 'ON C1.cat_pid=C2.cat_id ';
$sql .= 'WHERE '.str_replace('cat_', 'C1.cat_', $_entry['query']).' ';
$sql .= 'ORDER BY category ASC ';
$sql .= 'LIMIT '.(($_SESSION['detail_page']-1) * $_SESSION['list_count']).','.$_SESSION['list_count'];

//dumpVar($sql);

$data = _dbQuery($sql);

$_controller_link =  shop_url('controller=cat');

foreach($data as $row) {

	echo '<tr';
	if($row_count % 2) echo ' bgcolor="#F3F5F8"';
	if(!$row['cat_pid']) echo " onmouseover=\"Tip('" . $BL['be_admin_page_category'] . " ID: <b>" .$row["cat_id"]. "</b>');\"";
	echo '>'.LF;
	
	echo '<td width="25" style="padding:2px 3px 2px 4px;">';
	echo '<img src="img/famfamfam/silk_icons_gif/tag_';
	echo $row['cat_pid'] ? 'orange' : 'blue';	
	echo '.gif" alt="'.$BLM['shop_category'].'" /></td>'.LF;
	
	echo '<td class="dir" width="88%">';
	echo $row['cat_pid'] ? '&nbsp;&nbsp;&nbsp;&nbsp;' : '&nbsp;';
	echo html_specialchars($row['cat_name'])."</td>\n";


	echo '<td width="10%" align="right" nowrap="nowrap" class="button_td">';
	
		echo '<a href="'.$_controller_link.'&amp;edit='.$row["cat_id"].'">';		
		echo '<img src="img/button/edit_22x13.gif" border="0" alt="" /></a>';
	
		echo '<a href="'.$_controller_link.'&amp;status=' . $row["cat_id"] . '-' . $row["cat_status"] .'">';
		echo '<img src="img/button/aktiv_12x13_'.$row["cat_status"].'.gif" border="0" alt="" /></a>';
	
		echo '<a href="'.$_controller_link.'&amp;delete='.$row["cat_id"];
		echo '" title="delete: '.html_specialchars($row['cat_name']).'"';
		echo ' onclick="return confirm(\''.$BLM['delete_entry'].js_singlequote($row['cat_name']).'\');">';
		echo '<img src="img/button/trash_13x13_1.gif" border="0" alt="" /></a>';
	
	echo '</td>'.LF;
	
	echo '</tr>'.LF;

	$row_count++;
}

if($row_count) {
	echo '<tr><td colspan="3" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>';
}

?>	


</table>