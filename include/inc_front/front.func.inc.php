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


//specific functions for the frontend

function spacer($width=1, $height=1) {
	//creates a placeholder image (transparent gif)
	return '<img src="img/leer.gif" width="'.intval($width).'" height="'.intval($height).'" border="0" alt="" />';
}

function headline(& $head, & $subhead, & $layout) {
	$c = '';
	if($head) {
		$c .= $layout["content_head_before"];
		$c .= html_specialchars($head);
		$c .= $layout["content_head_after"];
	}
	if($subhead) {
		$c .= $layout["content_subhead_before"];
		$c .= html_specialchars($subhead);
		$c .= $layout["content_subhead_after"];
	}
	return $c;
}

//defines multimedia plugin specific values for width or height
function plugin_size($mediatype, $player, $width, $height) {

	switch($mediatype) {
		case 0: //Video
				switch($player) {
					case 0:	//Quicktime
							$width = ($width) ? $width : "";
							$height = ($height) ? $height+16 : "";
							break;

					case 1:	//RealPlayer
							$width = ($width) ? $width : "";
							$width = ($height) ? $height+36 : "";
							break;

					case 2:	//MediaPlayer
							$width = ($width) ? $width : "";
							$width = ($height) ? $height : "";
							break;

					case 3:	//Flash
							$width = ($width) ? $width : "";
							$width = ($height) ? $height : "";
							break;
				}
				break;

		case 1:	//Audio
				break;

		case 2:	//Flash
				break;

	}
}

function must_filled($c) {
	//spaceholder for form fields that have to be filled
	//with some content or has to be marked or like that
	return intval($c) ? '<img src="img/article/fill_in_here.gif" border="0" alt="" />' : '';
}

function add_attribute($baseval, $attribute, $val, $space=" ") {
	//to add all relevant attributes that contains
	//values to a string maybe a html tag
	$attribute = isEmpty(strval($val)) ? '' : $attribute.'="'.$val.'"';
	$attribute = ($baseval && !isEmpty($val)) ? $space.$attribute : $attribute;
	return $attribute;
}

function add_style_attribute($attribute='', $val='') {
	//to add all relevant attributes that contains
	//values to a string maybe a html tag
	$attribute = isEmpty(strval($val)) ? '' : $attribute.': '.$val.';';
	return $attribute;
}

function html_attribute($attribute='', $val='') {
	//to return only 1 well formatted attributes and values
	return isEmpty(strval($val)) ? '' : $attribute.'="'.$val.'"';
}

function html_height_attribute($val=0) {
	//to return only 1 well formatted attributes and values
	return (intval($val)) ? ' style="height:'.$val.'px;" ' : '';
}

function get_body_attributes(& $values) {
	//return a standard list of standard html body attributes
	//based on the pagelayout definitions
	$body_class	= '';
	$link_class	= '';
	$onload_js	= '';
	if(is_array($values)) {
		if(empty($values["layout_noborder"])) {
			$body_class .= add_style_attribute('      margin', '0').LF;
			$body_class .= add_style_attribute('      padding-top',		empty($values["layout_border_top"])		? '0' : intval($values["layout_border_top"])   .'px').LF;
			$body_class .= add_style_attribute('      padding-bottom',	empty($values["layout_border_bottom"])	? '0' : intval($values["layout_border_bottom"]).'px').LF;
			$body_class .= add_style_attribute('      padding-left',	empty($values["layout_border_left"]) 	? '0' : intval($values["layout_border_left"])  .'px').LF;
			$body_class .= add_style_attribute('      padding-right', 	empty($values["layout_border_right"]) 	? '0' : intval($values["layout_border_right"]) .'px').LF;
			$body_class .= LF;
		}
		if(!empty($values["layout_bgcolor"])) {
			$body_class .= add_style_attribute('      background-color', $values["layout_bgcolor"]);
			$body_class .= LF;
		}
		if(!empty($values["layout_bgimage"])) {
			$body_class .= add_style_attribute('      background-image', 'url('.$values["layout_bgimage"].')');
			$body_class .= LF;
		}
		if(!empty($values["layout_textcolor"])) {
			$body_class .= add_style_attribute('      color', $values["layout_textcolor"]);
			$body_class .= LF;
		}
		if(!empty($body_class)) {
			$body_class  = '    body {'.LF.$body_class.'    }'.LF;
		}
		if(!empty($values["layout_linkcolor"])) {
			$link_class .= '    a, a:link, a:active, a:visited, a:hover { color: '.$values["layout_linkcolor"].'; }';
			$link_class .= LF;
		}
		if(!empty($values["layout_vcolor"])) {
			$link_class .= '    a:visited { color: '.$values["layout_vcolor"].'; }';
			$link_class .= LF;
		}
		if(!empty($values["layout_acolor"])) {
			$link_class .= '    a:active { color: '.$values["layout_acolor"].'; }';
			$link_class .= LF;
		}
		if(!empty($values["layout_jsonload"])) {
			$onload_js   = '  <script language="javascript" type="text/javascript">'.LF.SCRIPT_CDATA_START.LF;
			$onload_js  .= '  window.onload = function () {'.LF.'    ';
			$onload_js  .= $values["layout_jsonload"].LF.'  }'.LF.SCRIPT_CDATA_END.LF.'  </script>'.LF;
		}
		if(!empty($body_class) || !empty($link_class)) {
			$body_class  = '  <style type="text/css" media="all">'.LF.SCRIPT_CDATA_START.LF.$body_class;
			$body_class .= $link_class;
			$body_class .= SCRIPT_CDATA_END.LF.'  </style>'.LF;
		}
		return $onload_js.$body_class;
	}
}

function align_base_layout($value) {
	//to get the alignment of the base layout table
	switch($value) {
		case  1: $align = "center"; break;
		case  2: $align = "right"; break;
		default: $align = 0;
	}
	return ($align) ? ' align="'.$align.'"' : '';
}

function get_colspan($value) {
	//returns colspan value back to table row
	if(!isset($value["layout_type"]))				$value["layout_type"] = 0;
	switch($value["layout_type"]) {
		case  0:	$col=3; break;
		case  1:	$col=2; break;
		case  2:	$col=2; break;
		case  3:	$col=0; break;
		default:	$col=3;
	}
	if(!empty($value["layout_leftspace_width"]))  $col++;
	if(!empty($value["layout_rightspace_width"])) $col++;
	return ($col) ? (' colspan="'.$col.'"') : '';
}

function colspan_table_row($val, $block, $colspan="", $rowcontent="&nbsp;") {
	//creates a new table row for header or footer or such rows
	return ($rowcontent) ? "<tr>\n<td".$colspan.td_attributes($val, $block, 0).">".$rowcontent."</td>\n</tr>\n" : '';
}

function get_table_block($val, $content="", $leftblock="", $rightblock="") {
	//creates the string with all relevant main block data
	//$val = $pagelayout array values
	$mainblock  = "<tr>\n"; //start row

	//if 3column or 2column (with left block)
	if($val["layout_type"]==0 || $val["layout_type"]==1) {
		$mainblock .= "<td".td_attributes($val, "left").">".$leftblock."</td>\n";
	}

	//if there is a spacer column between left and main block
	if($val["layout_leftspace_width"]) {
		$mainblock .= "<td".td_attributes($val, "leftspace").">";
		$mainblock .= spacer($val["layout_leftspace_width"]);
		$mainblock .= "</td>\n";
	}

	$mainblock .= "<td".td_attributes($val, "content").">".$content."</td>\n";

	//if there is a spacer column between main block and right column
	if($val["layout_rightspace_width"]) {
		$mainblock .= "<td".td_attributes($val, "rightspace").">";
		$mainblock .= spacer($val["layout_rightspace_width"]);
		$mainblock .= "</td>\n";
	}

	//if 3column or 2column (with right block)
	if($val["layout_type"]==0 || $val["layout_type"]==2) {
		$mainblock .= "<td".td_attributes($val, "right").">".$rightblock."</td>\n";
	}

	$mainblock .= "</tr>\n"; //end row
	return $mainblock;
}

function td_attributes($val, $block, $top=1) {
	//creates a string with all relevant cell attributes like nackground color/image, class
	$td_attrib  = ($top) ? ' valign="top"' : "";
	if(!empty($val["layout_".$block."_height"])) {
		$td_attrib .= html_height_attribute($val["layout_".$block."_height"]);
	}
	if(!empty($val["layout_".$block."_width"])) {
		$td_attrib .= html_attribute(" width", $val["layout_".$block."_width"]);
	}
	if(!empty($val["layout_".$block."_bgcolor"])) {
		$td_attrib .= html_attribute(" bgcolor", $val["layout_".$block."_bgcolor"]);
	}
	if(!empty($val["layout_".$block."_bgimage"])) {
		$td_attrib .= html_attribute(" style", 'background-image:url('.$val["layout_".$block."_bgimage"].')');
	}
	if(!empty($val["layout_".$block."_class"])) {
		$td_attrib .= html_attribute(" class", $val["layout_".$block."_class"]);
	}
	return $td_attrib;
}

function table_attributes($val, $var_part, $top=1, $tr=false) {
	//creates a string with all relevant cell attributes like background color/image, class
	//P.S. it is nearly the same as td_attributes - but it was boring to rewrite code ;-)
	$td_attrib = '';
	if($top) {
		$td_attrib = ' valign="top"';
	}

	if(!$tr) {
		$td_attrib .= html_attribute(" border", (!empty($val[$var_part."_border"])) ? $val[$var_part."_border"] : '0');
		$td_attrib .= html_attribute(" cellspacing", (!empty($val[$var_part."_cspace"])) ? $val[$var_part."_cspace"] : '0');
		$td_attrib .= html_attribute(" cellpadding", (!empty($val[$var_part."_cpad"])) ? $val[$var_part."_cpad"] : '0');
	}

	if(!empty($val[$var_part."_height"])) {
		$td_attrib .= html_height_attribute($val[$var_part."_height"]);
	}
	if(!empty($val[$var_part."_width"])) {
		$td_attrib .= html_attribute(" width", $val[$var_part."_width"]);
	}
	if(!empty($val[$var_part."_bgcolor"])) {
		$td_attrib .= html_attribute(" bgcolor", $val[$var_part."_bgcolor"]);
	}
	if(!empty($val[$var_part."_bgimage"])) {
		$td_attrib .= html_attribute(" background", $val[$var_part."_bgimage"]);
	}
	if(!empty($val[$var_part."_class"])) {
		$td_attrib .= html_attribute(" class", $val[$var_part."_class"]);
	}

	return $td_attrib;
}

function get_breadcrumb ($start_id, &$struct_array) {
	//returns the breadcrumb path starting with given start_id

	$data = array();
	while ($start_id) {
		$data[$start_id] = $struct_array[$start_id]["acat_name"];
		$start_id		 = $struct_array[$start_id]["acat_struct"];
	}
	if(!empty($struct_array[$start_id]["acat_name"])) {
		$data[$start_id] = $struct_array[$start_id]["acat_name"];
	}
	return array_reverse($data, 1);
}

function breadcrumb ($start_id, &$struct_array, $end_id, $spacer=' &gt; ') {
	//builds the breadcrumb menu based on given values
	//$link_to = the page on which the breadcrum part links
	//$root_name = name of the breadcrumb part if empty/false/0 $start_id
	//$spacer = how should breadcrumb parts be divided

	$start_id 	= intval($start_id);
	$end_id 	= intval($end_id);
	$act_id 	= $start_id; //store actual ID for later comparing
	$breadcrumb = '';

	while ($start_id) { //get the breadcrumb path starting with given start_id
		if($end_id && $start_id == $end_id) break;
		$data[$start_id] = $struct_array[$start_id]["acat_name"];
		$start_id		 = $struct_array[$start_id]["acat_struct"];
	}
	$data[$start_id] = $struct_array[$start_id]["acat_name"];
	$crumbs_part = array_reverse($data, 1);
	if(is_array($crumbs_part)) {
		foreach($crumbs_part as $key => $value) {
			$alias = '';
			if($struct_array[$key]["acat_hidden"] != 1) { // check if the structure should be unvisible when active
				if ($act_id != $key) {
					if($breadcrumb) $breadcrumb .= $spacer;
					if(!$struct_array[$key]["acat_redirect"]) {
						$breadcrumb .= '<a href="index.php?';
						$alias 		 = $struct_array[$key]["acat_alias"];
						$breadcrumb .= ($alias) ? html_specialchars($alias) : 'id='.$key.',0,0,1,0,0';
						$breadcrumb .= '">';
					} else {
						$redirect = get_redirect_link($struct_array[$key]["acat_redirect"], ' ', '');
						$breadcrumb .= '<a href="'.$redirect['link'].'"'.$redirect['target'].'>';
					}
					$breadcrumb .= html_specialchars($crumbs_part[$key]).'</a>';
				} else {
					if($breadcrumb) $breadcrumb .= $spacer;
					$breadcrumb .= html_specialchars($crumbs_part[$key]);
				}
			}
		}
	}
	return $breadcrumb;
}

function get_redirect_link($link='#', $pre='', $after=' ') {
	// returns the link var and target var if available
	$link 			= explode(' ', $link);
	$l['link']		= empty($link[0]) ? '#' : $link[0];
	$l['target']	= empty($link[1]) ? ''  : $pre.'target="'.strtolower($link[1]).'"'.$after;
	return $l;
}

function get_struct_data($root_name='', $root_info='') {
	//returns the complete active and public struct data as array
	//so it is reusable by many menu functions -> lower db access

	global $db;
	global $indexpage;
	$data = array();

	$data[0] = array(	"acat_id"		=> 0,
					"acat_name"		=> $indexpage['acat_name'],
					"acat_info"		=> $indexpage['acat_info'],
					"acat_struct"	=> 0,
					"acat_sort"		=> 0,
					"acat_hidden"	=> intval($indexpage['acat_hidden']),
					"acat_regonly"	=> intval($indexpage['acat_regonly']),
					"acat_ssl"		=> intval($indexpage['acat_ssl']),
					"acat_template"	=> intval($indexpage['acat_template']),
					"acat_alias"	=> $indexpage['acat_alias'],
					"acat_topcount"	=> intval($indexpage['acat_topcount']),
					"acat_maxlist"	=> intval($indexpage['acat_maxlist']),
					"acat_redirect"	=> $indexpage['acat_redirect'],
					"acat_order"	=> intval($indexpage['acat_order']),
					"acat_timeout"	=> $indexpage['acat_timeout'],
					"acat_nosearch"	=> $indexpage['acat_nosearch'],
					"acat_nositemap"=> $indexpage['acat_nositemap'],
					"acat_permit"	=> !empty($indexpage['acat_permit']) && is_array($indexpage['acat_permit']) ? $indexpage['acat_permit'] : array(),
					"acat_pagetitle"=> empty($indexpage['acat_pagetitle']) ? '' : $indexpage['acat_pagetitle'],
					"acat_paginate"	=> empty($indexpage['acat_paginate']) ? 0 : 1,
					"acat_overwrite"=> empty($indexpage['acat_overwrite']) ? '' : $indexpage['acat_overwrite']
				);
	$sql  = "SELECT * FROM ".DB_PREPEND."phpwcms_articlecat WHERE ";
	// VISIBLE_MODE: 0 = frontend (all) mode, 1 = article user mode, 2 = admin user mode
	if(VISIBLE_MODE != 2) {
		// for 0 AND 1
		$sql .= "acat_aktiv=1 AND acat_public=1 AND ";
	}
	$sql .= "acat_trash=0 ORDER BY acat_struct, acat_sort";

	if($result = mysql_query($sql, $db)) {
		while($row = mysql_fetch_assoc($result)) {
			$data[$row["acat_id"]] = array(	"acat_id"		=> $row["acat_id"],
										"acat_name"		=> $row["acat_name"],
										"acat_info"		=> $row["acat_info"],
										"acat_struct"	=> $row["acat_struct"],
										"acat_sort"		=> $row["acat_sort"],
										"acat_hidden"	=> $row["acat_hidden"],
										"acat_regonly"	=> $row["acat_regonly"],
										"acat_ssl"		=> $row["acat_ssl"],
										"acat_template"	=> $row["acat_template"],
										"acat_alias"	=> $row["acat_alias"],
										"acat_topcount"	=> $row["acat_topcount"],
										"acat_maxlist"	=> $row["acat_maxlist"],
										"acat_redirect"	=> $row["acat_redirect"],
										"acat_order"	=> $row["acat_order"],
										"acat_timeout"	=> $row["acat_cache"],
										"acat_nosearch"	=> $row["acat_nosearch"],
										"acat_nositemap"=> $row["acat_nositemap"],
										"acat_permit"	=> empty($row["acat_permit"]) ? array() : explode(',', $row["acat_permit"]),
										"acat_pagetitle"=> $row["acat_pagetitle"],
										"acat_paginate"	=> $row["acat_paginate"],
										"acat_overwrite"=> $row["acat_overwrite"]
									  );
		}
		mysql_free_result($result);
	}
	return $data;
}

function get_actcat_articles_data($act_cat_id) {
	//returns the complete active and public article data as array (basic infos only)
	//so it is reusable by many functions -> lower db access

	global $content;
	global $db;

	$data 				= array();
	$ao 				= get_order_sort($content['struct'][ $act_cat_id ]['acat_order']);
	$as					= $content['struct'][ $act_cat_id ];
	$as['acat_maxlist']	= intval($as['acat_maxlist']);

	$sql  = "SELECT *, UNIX_TIMESTAMP(article_tstamp) AS article_date FROM ".DB_PREPEND."phpwcms_article ";
	$sql .= "WHERE article_cid=".$act_cat_id;
	// VISIBLE_MODE: 0 = frontend (all) mode, 1 = article user mode, 2 = admin user mode
	switch(VISIBLE_MODE) {
		case 0: $sql .= " AND article_public=1 AND article_aktiv=1";
				break;
		case 1: $sql .= " AND article_uid=".$_SESSION["wcs_user_id"];
				break;
		//case 2: admin mode no additional neccessary
	}
	$sql .= " AND article_deleted=0 AND article_begin < NOW() AND article_end > NOW() ";
	$sql .= "ORDER BY ".$ao[2];


	if(empty($as['acat_paginate']) && $as['acat_maxlist'] && $as['acat_topcount'] >= $as['acat_maxlist']) {
		$sql .= ' LIMIT '.$as['acat_maxlist'];
	}

	if($result = mysql_query($sql, $db)) {
		while($row = mysql_fetch_assoc($result)) {
			$data[$row["article_id"]] = array(
									"article_id"		=> $row["article_id"],
									"article_cid"		=> $row["article_cid"],
									"article_title"		=> $row["article_title"],
									"article_subtitle"	=> $row["article_subtitle"],
									"article_keyword"	=> $row["article_keyword"],
									"article_summary"	=> $row["article_summary"],
									"article_redirect"	=> $row["article_redirect"],
									"article_date"		=> $row["article_date"],
									"article_username"	=> $row["article_username"],
									"article_sort"		=> $row["article_sort"],
									"article_notitle"	=> $row["article_notitle"],
									"article_created"	=> $row["article_created"],
									"article_image"		=> @unserialize($row["article_image"]),
									"article_timeout"	=> $row["article_cache"],
									"article_nosearch"	=> $row["article_nosearch"],
									"article_nositemap"	=> $row["article_nositemap"],
									"article_aliasid"	=> $row["article_aliasid"],
									"article_headerdata"=> $row["article_headerdata"],
									"article_morelink"	=> $row["article_morelink"],
									"article_begin"		=> $row["article_begin"],
									"article_end"		=> $row["article_end"]
											);
			// now check for article alias ID
			if($row["article_aliasid"]) {
				$aid = $row["article_id"];
				$alias_sql  = "SELECT *, UNIX_TIMESTAMP(article_tstamp) AS article_date FROM ".DB_PREPEND."phpwcms_article ";
				$alias_sql .= "WHERE article_deleted=0 AND article_id=".intval($row["article_aliasid"]);
				if(!$row["article_headerdata"]) {
					switch(VISIBLE_MODE) {
						case 0: $alias_sql .= " AND article_public=1 AND article_aktiv=1";
								break;
						case 1: $alias_sql .= " AND article_uid=".$_SESSION["wcs_user_id"];
								break;
					}
					$alias_sql .= " AND article_begin < NOW() AND article_end > NOW()";
				}
				$alias_sql .= " AND article_deleted=0 LIMIT 1";
				if($alias_result = mysql_query($alias_sql, $db)) {
					if($alias_row = mysql_fetch_assoc($alias_result)) {
						$data[$aid]["article_id"] = $alias_row["article_id"];
						// use alias article header data
						if(!$row["article_headerdata"]) {
							$data[$aid]["article_title"]	= $alias_row["article_title"];
							$data[$aid]["article_subtitle"]	= $alias_row["article_subtitle"];
							$data[$aid]["article_keyword"]	= $alias_row["article_keyword"];
							$data[$aid]["article_summary"]	= $alias_row["article_summary"];
							$data[$aid]["article_redirect"]	= $alias_row["article_redirect"];
							$data[$aid]["article_date"]		= $alias_row["article_date"];
							$data[$aid]["article_image"]	= @unserialize($alias_row["article_image"]);
							$data[$aid]["article_begin"]	= $alias_row["article_begin"];
							$data[$aid]["article_end"]		= $alias_row["article_end"];
						}
					}
					mysql_free_result($alias_result);
				}
			}
		}
		mysql_free_result($result);
	}
	return $data;
}

function setArticleSummaryImageData($img) {
	// used to set correct list image values based on given data

	//first check if lis_image data is set - will not for all old articles
	if(!isset($img['list_usesummary'])) {
		$img['list_usesummary'] = 1;
	}
	if($img['list_usesummary'] && !empty($img['hash'])) {
		$img['list_name']		= $img['name'];
		$img['list_hash']		= $img['hash'];
		$img['list_ext']		= $img['ext'];
		$img['list_id']			= $img['id'];
		$img['list_caption']	= $img['caption'];
		$img['list_zoom']		= empty($img['list_zoom']) ? $img['zoom'] : $img['list_zoom'];
		$img['list_width']		= empty($img['list_width']) ? $img['width'] : $img['list_width'];
		$img['list_height']		= empty($img['list_height']) ? $img['height'] : $img['list_height'];
	}
	return $img;
}

//menu creating
function nav_table_simple_struct(&$struct, $act_cat_id, $link_to="index.php") {
	//returns a simple table based navigation menu of possible
	//structure levels based on current structure level
	$nav_table  = "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" summary=\"\">\n<tr>\n";
	$nav_table .= "<td width=\"10\"><img src=\"img/leer.gif\" width=\"10\" height=\"1\" alt=\"\" /></td>\n";
	$nav_table .= "<td width=\"100%\"><strong>";
	$nav_table .= html_specialchars($struct[$act_cat_id]["acat_name"]);
	$nav_table .= "</strong></td>\n<tr>";
	foreach($struct as $key => $value) {

		//if($GLOBALS['content']['struct'][$key]['acat_struct'] == $start_id && $key && (!$GLOBALS['content']['struct'][$key]['acat_hidden'] || ($GLOBALS['content']['struct'][$key]["acat_hidden"] == 2 && isset($GLOBALS['LEVEL_KEY'][$key])))) {
		if($key != $act_cat_id && _getStructureLevelDisplayStatus($key, $act_cat_id) ) {
		//if(	$struct[$key]["acat_struct"] == $act_cat_id && $key != $act_cat_id && (!$struct[$key]['acat_hidden'] || ($struct[$key]["acat_hidden"] == 2 && isset($GLOBALS['LEVEL_KEY'][$key])))   ) {
			
			$nav_table .= "<tr>\n";
			$nav_table .= "<td width=\"10\"><img src=\"img/leer.gif\" width=\"10\" height=\"1\" alt=\"\" /></td>\n";
			$nav_table .= '<td width="100%">';

			if(!$struct[$key]["acat_redirect"]) {
				$nav_table .= '<a href="index.php?';
				if($struct[$key]["acat_alias"]) {
					$nav_table .= html_specialchars($struct[$key]["acat_alias"]);
				} else {
					$nav_table .= 'id='.$key.',0,0,1,0,0';
				}
				$nav_table .= '">';
			} else {
				$redirect = get_redirect_link($struct[$key]["acat_redirect"], ' ', '');
				$nav_table .= '<a href="'.$redirect['link'].'"'.$redirect['target'].'>';
			}

			$nav_table .= html_specialchars($struct[$key]["acat_name"])."</a></td>\n<tr>";
		}
	}
	$nav_table .= '</table>';
	return $nav_table;
}

function nav_level_row($show_id, $show_home=1) {
	//returns a simple row based navigation

	if(strtoupper($show_id) == 'CURRENT') {
		$act_cat_id = $GLOBALS['content']["cat_id"];
	} else {
		$act_cat_id = intval($show_id);
	}

	$nav = '';

	if($show_home && $GLOBALS['content']['struct'][$act_cat_id]['acat_hidden'] != 1) {
		if($GLOBALS['content']["cat_id"] == $act_cat_id) {
			$before = $GLOBALS['template_default']["nav_row"]["link_before_active"];
			$after  = $GLOBALS['template_default']["nav_row"]["link_after_active"];
			$direct_before	= $GLOBALS['template_default']["nav_row"]["link_direct_before_active"];
			$direct_after	= $GLOBALS['template_default']["nav_row"]["link_direct_after_active"];
		} else {
			$before = $GLOBALS['template_default']["nav_row"]["link_before"];
			$after  = $GLOBALS['template_default']["nav_row"]["link_after"];
			$direct_before	= $GLOBALS['template_default']["nav_row"]["link_direct_before"];
			$direct_after	= $GLOBALS['template_default']["nav_row"]["link_direct_after"];
		}
		$nav .= $before;
		$nav .= '<a href="index.php?';
		$nav .= ($GLOBALS['content']['struct'][$act_cat_id]['acat_alias']) ? html_specialchars($GLOBALS['content']['struct'][$act_cat_id]['acat_alias']) : 'id='.$act_cat_id.',0,0,1,0,0';
		$nav .= '">'.$direct_before;
		$nav .= html_specialchars($GLOBALS['content']['struct'][$act_cat_id]['acat_name']);
		$nav .= $direct_after.'</a>'.$after;
	}

	// check against breadcrumb - active site tree
	if($GLOBALS['content']['struct'][$GLOBALS['content']["cat_id"]]['acat_struct'] != 0) {
		$breadcrumb = get_breadcrumb($GLOBALS['content']["cat_id"], $GLOBALS['content']['struct']);
	}

	foreach($GLOBALS['content']['struct'] as $key => $value) {
	
		if($key != $act_cat_id && _getStructureLevelDisplayStatus($key, $act_cat_id) ) {
		/*
		if($GLOBALS['content']['struct'][$key]["acat_struct"] == $act_cat_id && $key != $act_cat_id
			&& (!$GLOBALS['content']['struct'][$key]['acat_hidden']
			|| (($GLOBALS['content']['struct'][$key]["acat_hidden"]==2 && isset($GLOBALS['LEVEL_KEY'][$key])) ? true : false) )) {
		*/

			if($nav) {
				$nav .= $GLOBALS['template_default']["nav_row"]["between"];
			}

			if($GLOBALS['content']["cat_id"] == $key || isset($breadcrumb[$key])) {
				$before = $GLOBALS['template_default']["nav_row"]["link_before_active"];
				$after  = $GLOBALS['template_default']["nav_row"]["link_after_active"];
				$direct_before	= $GLOBALS['template_default']["nav_row"]["link_direct_before_active"];
				$direct_after	= $GLOBALS['template_default']["nav_row"]["link_direct_after_active"];
			} else {
				$before = $GLOBALS['template_default']["nav_row"]["link_before"];
				$after  = $GLOBALS['template_default']["nav_row"]["link_after"];
				$direct_before	= $GLOBALS['template_default']["nav_row"]["link_direct_before"];
				$direct_after	= $GLOBALS['template_default']["nav_row"]["link_direct_after"];
			}

			$nav .= $before;

			if(!$GLOBALS['content']['struct'][$key]["acat_redirect"]) {
				$nav .= '<a href="index.php?';
				if($GLOBALS['content']['struct'][$key]["acat_alias"]) {
					$nav .= html_specialchars($GLOBALS['content']['struct'][$key]["acat_alias"]);
				} else {
					$nav .= 'id='.$key.',0,0,1,0,0';
				}
				$nav .= '">';
			} else {
				$redirect = get_redirect_link($GLOBALS['content']['struct'][$key]["acat_redirect"], ' ', '');
				$nav .= '<a href="'.$redirect['link'].'"'.$redirect['target'].'>';
			}
			$nav .= $direct_before;
			$nav .= html_specialchars($GLOBALS['content']['struct'][$key]['acat_name']);;
			$nav .= $direct_after.'</a>'.$after;
		}
	}
	if($nav) {
		$nav  = $GLOBALS['template_default']["nav_row"]["before"].$nav;
		$nav .= $GLOBALS['template_default']["nav_row"]["after"];
	}
	return $nav;
}

function nav_table_struct (&$struct, $act_cat_id, $level, $nav_table_struct, $link_to="index.php") {
	// start with home directory for the listing = top nav structure
	// 1. Build the recursive tree for given actual article category ID

	// return the tree starting with given start_id (like breadcrumb)
	// if the $start_id = 0 then this stops because 0 = top level

	$level 			= intval($level);
	$start_id 		= $act_cat_id;
	$data 			= array();
	$c 				= 0;
	$total_levels 	= 0;
	$level_depth 	= 0;
	//$start_level 	= $level;
	while ($start_id) {
		$data[$start_id]	= 1;
		$start_id			= $struct[$start_id]["acat_struct"];
		$total_levels++;
	}

	if(is_array($data) && count($data)) {
		$temp_tree = array_reverse($data, 1);
	} else {
		$temp_tree = false;
	}

	foreach($struct as $key => $value) {
		if($struct[$key]["acat_struct"] == $act_cat_id && $key && (!$struct[$key]["acat_hidden"] || isset($GLOBALS['LEVEL_KEY'][$key]))) {
			$c++;
		}
	}
	$c = (!$c) ? 1 : 0;

	//build image src path and real image tag
	$nav_table_struct["linkimage_over_js"]  = get_real_imgsrc($nav_table_struct["linkimage_over"]);

	$nav_table_struct["linkimage_norm"]		= add_linkid($nav_table_struct["linkimage_norm"],   '#');
	$nav_table_struct["linkimage_over"]		= add_linkid($nav_table_struct["linkimage_over"],   '#');
	$nav_table_struct["linkimage_active"]	= add_linkid($nav_table_struct["linkimage_active"], '#');

	$lc = count($temp_tree);
	$ld = false;
	for($l = 0; $l <= $lc; $l++) {

		if(isset($GLOBALS['LEVEL_ID'][$l])) {

			$curStructID = $GLOBALS['LEVEL_ID'][$l];
			// now all deeper levels can be deleted
			if($ld) {
				unset($temp_tree[$curStructID]);
			}

			if(!isset($nav_table_struct['array_struct'][$l])) {

				$nav_table_struct['array_struct'][$l]["linkimage_over_js"]	= $nav_table_struct["linkimage_over_js"];
				$nav_table_struct['array_struct'][$l]["linkimage_norm"]		= $nav_table_struct["linkimage_norm"];
				$nav_table_struct['array_struct'][$l]["linkimage_over"]		= $nav_table_struct["linkimage_over"];
				$nav_table_struct['array_struct'][$l]["linkimage_active"]	= $nav_table_struct["linkimage_active"];

				$nav_table_struct['array_struct'][$l]["link_before"]		= $nav_table_struct["link_before"];
				$nav_table_struct['array_struct'][$l]["link_after"]			= $nav_table_struct["link_after"];
				$nav_table_struct['array_struct'][$l]["link_active_before"]	= $nav_table_struct["link_active_before"];
				$nav_table_struct['array_struct'][$l]["link_active_after"]	= $nav_table_struct["link_active_after"];

				$nav_table_struct['array_struct'][$l]["row_norm_bgcolor"]	= $nav_table_struct["row_norm_bgcolor"];
				$nav_table_struct['array_struct'][$l]["row_norm_class"]		= $nav_table_struct["row_norm_class"];
				$nav_table_struct['array_struct'][$l]["row_over_bgcolor"]	= $nav_table_struct["row_over_bgcolor"];
				$nav_table_struct['array_struct'][$l]["row_active_bgcolor"]	= $nav_table_struct["row_active_bgcolor"];
				$nav_table_struct['array_struct'][$l]["row_active_class"]	= $nav_table_struct["row_active_class"];

				$nav_table_struct['array_struct'][$l]["space_celltop"]		= $nav_table_struct["space_celltop"];
				$nav_table_struct['array_struct'][$l]["space_cellbottom"]	= $nav_table_struct["space_cellbottom"];

				$nav_table_struct['array_struct'][$l]["cell_height"]		= $nav_table_struct["cell_height"];
				$nav_table_struct['array_struct'][$l]["cell_class"]			= $nav_table_struct["cell_class"];
				$nav_table_struct['array_struct'][$l]["cell_active_height"]	= $nav_table_struct["cell_active_height"];
				$nav_table_struct['array_struct'][$l]["cell_active_class"]	= $nav_table_struct["cell_active_class"];

				//$nav_table_struct['array_struct'][$l]["show_active_hidden"]	= $nav_table_struct["show_active_hidden"];

			} else {

				$nav_table_struct['array_struct'][$l]["linkimage_over_js"]	= get_real_imgsrc($nav_table_struct['array_struct'][$l]["linkimage_over"]);
				$nav_table_struct['array_struct'][$l]["linkimage_norm"]		= add_linkid($nav_table_struct['array_struct'][$l]["linkimage_norm"],   '#');
				$nav_table_struct['array_struct'][$l]["linkimage_over"]		= add_linkid($nav_table_struct['array_struct'][$l]["linkimage_over"],   '#');
				$nav_table_struct['array_struct'][$l]["linkimage_active"]	= add_linkid($nav_table_struct['array_struct'][$l]["linkimage_active"], '#');

			}

			if($struct[$curStructID]['acat_hidden'] == 1) {
				unset($temp_tree[$curStructID]);
				$ld = true;
			}

		}

	}

	$temp_menu = build_levels ($struct, $level, $temp_tree, $act_cat_id, $nav_table_struct, $level_depth, $c, $link_to); //starts at root level
	return ($temp_menu) ? ("<table".table_attributes($nav_table_struct, "table", 0)." summary=\"\">\n".$temp_menu."</table>") : '';
}

function get_real_imgsrc($img='') {
	// strips real src attribute from image tag
	if($img) {
		$img = preg_replace('/.*src=["|\'](.*?)["|\'].*/i', "$1", $img);
	}
	return $img;
}

function add_linkid($img='', $linkid='') {
	//used to add the link ID for js over functions
	$img = preg_replace('/( \/>|>)$/', $linkid."$1", $img);
	return $img;
}

function build_levels ($struct, $level, $temp_tree, $act_cat_id, $nav_table_struct, $count, $div, $link_to) {

	// this returns the level structure based on given arrays
	// it is special for browsing from root levels

	$nav_table_struct["linkimage_over_js"]	= $nav_table_struct['array_struct'][$count]["linkimage_over_js"];
	$nav_table_struct["linkimage_norm"]		= $nav_table_struct['array_struct'][$count]["linkimage_norm"];
	$nav_table_struct["linkimage_over"]		= $nav_table_struct['array_struct'][$count]["linkimage_over"];
	$nav_table_struct["linkimage_active"]	= $nav_table_struct['array_struct'][$count]["linkimage_active"];
	$nav_table_struct["row_norm_bgcolor"]	= $nav_table_struct['array_struct'][$count]["row_norm_bgcolor"];
	$nav_table_struct["row_norm_class"]		= $nav_table_struct['array_struct'][$count]["row_norm_class"];
	$nav_table_struct["row_over_bgcolor"]	= $nav_table_struct['array_struct'][$count]["row_over_bgcolor"];
	$nav_table_struct["row_active_bgcolor"]	= $nav_table_struct['array_struct'][$count]["row_active_bgcolor"];
	$nav_table_struct["row_active_class"]	= $nav_table_struct['array_struct'][$count]["row_active_class"];
	$nav_table_struct["space_celltop"]		= $nav_table_struct['array_struct'][$count]["space_celltop"];
	$nav_table_struct["space_cellbottom"]	= $nav_table_struct['array_struct'][$count]["space_cellbottom"];
	$nav_table_struct["cell_height"]		= $nav_table_struct['array_struct'][$count]["cell_height"];
	$nav_table_struct["cell_class"]			= $nav_table_struct['array_struct'][$count]["cell_class"];
	$nav_table_struct["cell_active_height"]	= $nav_table_struct['array_struct'][$count]["cell_active_height"];
	$nav_table_struct["cell_active_class"]	= $nav_table_struct['array_struct'][$count]["cell_active_class"];
	$nav_table_struct["link_before"]		= $nav_table_struct['array_struct'][$count]["link_before"];
	$nav_table_struct["link_after"]			= $nav_table_struct['array_struct'][$count]["link_after"];
	$nav_table_struct["link_active_before"]	= $nav_table_struct['array_struct'][$count]["link_active_before"];
	$nav_table_struct["link_active_after"]	= $nav_table_struct['array_struct'][$count]["link_active_after"];

	$temp_menu		= '';
	$js 			= '';
	$depth 			= count($temp_tree)-$div;
	$current_level	= $count;
	$count++;
	$depth2 		= $depth-$count+2;
	$right_cell 	= '';
	$left_cell 		= '';
	$cell_top 		= '';
	$cell_bottom 	= '';
	$space_right	= '';
	$space_cell 	= '';
	$space_row 		= '';
	$cell_height 	= $nav_table_struct["cell_height"] ? $nav_table_struct["cell_height"] : 1;

	if($nav_table_struct["space_right"]) {
		$right_cell  = "<td width=\"".$nav_table_struct["space_left"]."\">";
		$right_cell .= spacer($nav_table_struct["space_right"], $cell_height)."</td>\n";
		$space_right = "<td>".spacer(1, 1)."</td>";
	}
	if($nav_table_struct["space_left"]) {
		$colspan	= ($count > 1) ? " colspan=\"".$count."\"" : "";
		$left_cell	= "<td width=\"".$nav_table_struct["space_left"]."\"".$colspan.">";
		$left_cell .= spacer($nav_table_struct["space_left"], $cell_height)."</td>\n";
		$space_cell = "<td".$colspan.">".spacer(1, 1)."</td><td>".spacer(1, 1)."</td>";
	} else {
		if($count > 1) {
			$colspan	= ($count > 2) ? " colspan=\"".($count-1)."\"" : "";
			$left_cell	= "<td ".$colspan.">".spacer(1, 1)."</td>\n";
			$space_cell = "<td".$colspan.">".spacer(1, 1)."</td><td>".spacer(1, 1)."</td>";
		}
	}
	if($nav_table_struct["space_celltop"]) $cell_top = spacer(1, $nav_table_struct["space_celltop"])."<br />";
	if($nav_table_struct["space_cellbottom"]) $cell_bottom = "<br />".spacer(1, $nav_table_struct["space_cellbottom"]);

	//$colspan	= ($depth2-1 > 1) ? ' colspan="'.($depth2-1).'"' : '';
	$colspan	= ($depth2 > 1) ? ' colspan="'.($depth2).'"' : '';

	foreach($struct as $key => $value) {

		if( _getStructureLevelDisplayStatus($key, $level) ) {
		/*
		if(		$struct[$key]["acat_struct"] == $level
			&& 	$key
			//&& 	(!$struct[$key]["acat_hidden"]	|| 	(!empty($nav_table_struct["show_active_hidden"]) && isset($GLOBALS['LEVEL_KEY'][$key])) ? true : false)
			&& (!$struct[$key]['acat_hidden'] || ($struct[$key]["acat_hidden"] == 2 && isset($GLOBALS['LEVEL_KEY'][$key])))) {
		*/

			$link_image_id	= "linkid".randpassword(6);
			$link_name_id 	= ' name="'.$link_image_id.'" id="'.$link_image_id.'"';

			if(!$struct[$key]["acat_redirect"]) {
				$link = 'index.php?';
				if($struct[$key]["acat_alias"]) {
					$link .= html_specialchars($struct[$key]["acat_alias"]);
				} else {
					$link .= 'id='.$key.',0,0,1,0,0';
				}
				$redirect['target'] = '';
			} else {
				$redirect = get_redirect_link($struct[$key]["acat_redirect"], ' ', '');
				$link = $redirect['link'];
			}

			$js		= ' style="cursor:pointer;cursor:hand;"'; //display:block;
			$js_act = $js;
			if($nav_table_struct["js_over_effects"]) {

				if($redirect['target'] != ' target="_blank"') {
					$js .= " onclick=\"location.href='".js_singlequote($link)."';return false;\"";
				} else {
					$js .= " onclick=\"window.open('".js_singlequote($link)."', 'phpwcmnewwin');return false;\"";
				}

				$js_act = $js;
				$js .= ' onmouseover="';
				if($nav_table_struct["linkimage_over_js"]) {
					$js .= "MM_swapImage('".$link_image_id."','','".$nav_table_struct["linkimage_over_js"]."',1);";
				}
				if($nav_table_struct["row_over_bgcolor"]) $js .= "this.bgColor='".$nav_table_struct["row_over_bgcolor"]."';";
				$js .= '" onmouseout="';
				if($nav_table_struct["linkimage_over_js"]) $js .= "MM_swapImgRestore();";
				if($nav_table_struct["row_norm_bgcolor"]) $js .= "this.bgColor='".$nav_table_struct["row_norm_bgcolor"]."';";
				$js .= '"';
			} else {
				$js = '';
			}

			//spacer row
			if($nav_table_struct["row_space"]) {
				$space_row  = "<tr".table_attributes($nav_table_struct, "row_space", 0, true).">\n".$space_cell;
				$space_row .= "<td".$colspan.">".spacer(1, $nav_table_struct["row_space"])."</td>";
				$space_row .= $space_right."\n</tr>\n";
				$temp_menu .= $space_row;
			}

			if(!empty($temp_tree[$key])) {
				//if($act_cat_id == $key) {
				//check if inside active tree structure
				if($act_cat_id == $key || (!empty($nav_table_struct["all_nodes_active"]) && isset($GLOBALS['LEVEL_KEY'][$key]))) {
					$temp_menu .= "<tr".table_attributes($nav_table_struct, "row_active", 0, true).$js_act.">\n".$left_cell;
					$temp_menu .= "<td valign=\"top\">".str_replace('#', $link_name_id, $nav_table_struct["linkimage_active"])."</td>\n";
					$temp_menu .= "<td".table_attributes($nav_table_struct, "cell_active", 1, true).$colspan.">".$cell_top;
					$temp_menu .= '<a href="'.$link.'"'.$redirect['target'].'>';
					$temp_menu .= $nav_table_struct["link_active_before"];
					$temp_menu .= html_specialchars($struct[$key]["acat_name"]);
					$temp_menu .= $nav_table_struct["link_active_after"].'</a>';
				} else {
					$temp_menu .= "<tr".table_attributes($nav_table_struct, "row_norm", 0, true).$js.">\n".$left_cell;
					$temp_menu .= "<td valign=\"top\">".str_replace('#', $link_name_id, $nav_table_struct["linkimage_norm"])."</td>\n";
					$temp_menu .= "<td".table_attributes($nav_table_struct, "cell", 1, true).$colspan.">".$cell_top;
					$temp_menu .= '<a href="'.$link.'"'.$redirect['target'].'>';
					$temp_menu .= $nav_table_struct["link_before"];
					$temp_menu .= html_specialchars($struct[$key]["acat_name"]);
					$temp_menu .= $nav_table_struct["link_after"].'</a>';
				}

				$temp_menu .= $cell_bottom."</td>\n".$right_cell."</tr>\n";
				$temp_menu .= build_levels ($struct, $key, $temp_tree, $act_cat_id, $nav_table_struct, $count, $div, $link_to);
			} else {
				$temp_menu .= "<tr".table_attributes($nav_table_struct, "row_norm", 0, true).$js.">\n".$left_cell;
				$temp_menu .= "<td valign=\"top\">".str_replace('#', $link_name_id, $nav_table_struct["linkimage_norm"])."</td>\n";
				$temp_menu .= "<td".table_attributes($nav_table_struct, "cell", 1, true).$colspan.">".$cell_top;
				$temp_menu .= '<a href="'.$link.'"'.$redirect['target'].'>';
				$temp_menu .= $nav_table_struct["link_before"];
				$temp_menu .= html_specialchars($struct[$key]["acat_name"]);
				$temp_menu .= $nav_table_struct["link_after"].'</a>';
				$temp_menu .= $cell_bottom."</td>\n".$right_cell."</tr>\n";
			}
		}
	}

	if($nav_table_struct["row_space"] && $count == 1) {
		$temp_menu .= $space_row;
	}

	return $temp_menu;
}

function list_articles_summary($alt=NULL, $topcount=99999, $template='') {
	// returns an article listing only with headline and summary text
	// and with an listing of all other available articles of this category

	global $content;
	global $template_default;
	global $_getVar;

	// alternative way to send article listings
	if(isset($alt)) {
		// first save default value of $content["articles"]
		$_old_articles			= $content["articles"];
		$content["articles"]	= $alt;
		$temp_topcount			= intval($topcount);
		if($temp_topcount == 0) {
			$temp_topcount		= $content['struct'][ $content['cat_id'] ]['acat_topcount'];
		}
		$template				= trim($template);
	} else {
		$temp_topcount = $content['struct'][ $content['cat_id'] ]['acat_topcount'];
	}

	$max_articles = count($content["articles"]);

	if(empty($template_default['article_paginate_show'])) {
		$paginate_show = array('bottom'=>1);
	} else {
		$paginate_show = array();
		foreach((explode(' ', $template_default['article_paginate_show'])) as $value) {
			if($value == 'top') {
				$paginate_show['top'] = 1;
			} elseif($value == 'bottom') {
				$paginate_show['bottom'] = 1;
			} elseif(strpos($value, 'rt') !== false) {
				$paginate_show['rt'] = str_replace('rt', '', $value);
			}
		}
		if(!count($paginate_show)) {
			$paginate_show = array('bottom'=>1);
		}
	}

	if($content['struct'][ $content['cat_id'] ]['acat_paginate'] && $content['struct'][ $content['cat_id'] ]['acat_maxlist'] && $max_articles > $content['struct'][ $content['cat_id'] ]['acat_maxlist']) {

		$paginate 		= true;
		$paginate_navi	= empty($template_default['article_paginate_navi']) ? '<div class="phpwcmsArticleListNavi">{PREV:&laquo;} {NEXT:&raquo;}</div>' : $template_default['article_paginate_navi'];
		$max_pages		= ceil($max_articles / $content['struct'][ $content['cat_id'] ]['acat_maxlist']);

		// always do full top article listing because of paginating
		$temp_topcount	= $max_articles+1;

		if(isset($_getVar['listpage'])) {
			$page_current = intval($_getVar['listpage']);
			if($page_current < 1) {
				$page_current = 1;
			} elseif($page_current > $max_pages) {
				$page_current = $max_pages;
			}
		} else {
			$page_current = 1;
		}

		$page_next = $page_current;
		$page_prev = $page_current;
		if($page_current < $max_pages) {
			$page_next = $page_current + 1;
		}
		if($page_current > 1) {
			$page_prev = $page_current - 1;
		}

		// setting pagination navi

		$page_article_max = $content['struct'][ $content['cat_id'] ]['acat_maxlist'] * $page_current;
		$page_article_at  = $content['struct'][ $content['cat_id'] ]['acat_maxlist'] * ($page_current - 1);
		$page_article_at  = $page_article_at + 1;
		if($page_article_max > $max_articles) $page_article_max = $max_articles;

		$paginate_navi = str_replace('#####',	$max_articles, 			$paginate_navi);
		$paginate_navi = str_replace('####',	$page_article_max, 		$paginate_navi);
		$paginate_navi = str_replace('###', 	$page_article_at,		$paginate_navi);
		$paginate_navi = str_replace('##', 		$max_pages, 			$paginate_navi);
		$paginate_navi = str_replace('#', 		$page_current, 			$paginate_navi);

		$GLOBALS['paginate_temp'] = array('next' => '', 'prev' => '', 'navi' => '');

		$paginate_navi = preg_replace_callback('/\{NEXT:(.*?)\}/', create_function('$matches', '$GLOBALS["paginate_temp"]["next"]=$matches[1]; return "{NEXT}";'), $paginate_navi);
		$paginate_navi = preg_replace_callback('/\{PREV:(.*?)\}/', create_function('$matches', '$GLOBALS["paginate_temp"]["prev"]=$matches[1]; return "{PREV}";'), $paginate_navi);
		$paginate_navi = preg_replace_callback('/\{NAVI:(.*?)\}/', create_function('$matches', '$GLOBALS["paginate_temp"]["navi"]=$matches[1]; return "{NAVI}";'), $paginate_navi);

		// next page link
		if($GLOBALS['paginate_temp']['next'] && $page_current < $max_pages) {
			$_getVar['listpage'] = $page_next;
			$page_next_link = '<a href="index.php' . returnGlobalGET_QueryString('htmlentities') . '">' . $GLOBALS['paginate_temp']['next'] . '</a>';
		} else {
			$page_next_link = $GLOBALS['paginate_temp']['next'];
		}

		// previous page link
		if($GLOBALS['paginate_temp']['prev'] && $page_current > 1) {
			$_getVar['listpage'] = $page_prev;
			$page_prev_link = '<a href="index.php' . returnGlobalGET_QueryString('htmlentities') . '">' . $GLOBALS['paginate_temp']['prev'] . '</a>';
		} else {
			$page_prev_link = $GLOBALS['paginate_temp']['prev'];
		}

		// set listpage value to current page

		$paginate_navi = str_replace('{NEXT}', $page_next_link, $paginate_navi);
		$paginate_navi = str_replace('{PREV}', $page_prev_link, $paginate_navi);

		// temporary unset GET listpage setting
		unset($_getVar['listpage']);

		if($GLOBALS['paginate_temp']['navi']) {

			$navi = explode(',', $GLOBALS['paginate_temp']['navi'], 2);
			$navi[0] = trim($navi[0]);

			$navi[1] 		= empty($navi[1])    ? array(0 => ' ') : explode('|', $navi[1]);
			$navi['spacer']	= empty($navi[1][0]) ? ' ' : $navi[1][0]; //spacer
			$navi['prefix']	= empty($navi[1][1]) ? ''  : $navi[1][1]; //prefix
			$navi['suffix']	= empty($navi[1][2]) ? ''  : $navi[1][2]; //suffix
			$navi['link'] 	= 'index.php' . returnGlobalGET_QueryString('htmlentities') . '&amp;listpage=';

			$navi['navi'] 	= $navi['prefix'];

			if($navi[0] == '123') {

				for($i = 1; $i <= $max_pages; $i++) {

					if($i > 1) $navi['navi'] .= $navi['spacer'];
					$navi['navi'] .= ($i == $page_current) ? $i : '<a href="' . $navi['link'] . $i .'">'.$i.'</a>';

				}

			} elseif($navi[0] == '1-3') {

				for($i = 0; $i < $max_pages; $i++) {

					$i_start	= $i * $content['struct'][ $content['cat_id'] ]['acat_maxlist'] + 1;
					$i_end		= $i_start - 1 + $content['struct'][ $content['cat_id'] ]['acat_maxlist'];
					if($i_end > $max_articles) $i_end = $max_articles;

					if($i > 0) $navi['navi'] .= $navi['spacer'];
					$i_entry 	= $i_start.'&ndash;'.$i_end;
					$i_page 	= $i+1;
					$navi['navi'] .= ($i_page == $page_current) ? $i_entry : '<a href="' . $navi['link'] . $i_page .'">'.$i_entry.'</a>';

				}

			}

			$navi['navi'] .= $navi['suffix'];

			// replace navi
			$paginate_navi = str_replace('{NAVI}', $navi['navi'], $paginate_navi);

		}

		// reset GET listpage setting
		$_getVar['listpage'] = $page_current;

		unset($GLOBALS['paginate_temp']);

	} else {
		$paginate 		= false;
		$paginate_navi	= '';
	}


	$tmpllist 		= array(); //temporary array for storing templates to minimize load
	$temp_counter 	= 0;

	$listing 		= $template_default["space_top"]; //start with space at top

	if(isset($paginate_show['top'])) {
		$listing 	   .= $paginate_navi;
	}

	foreach($content["articles"] as $article) {

		if($paginate && $content['struct'][ $content['cat_id'] ]['acat_maxlist']) {
			// get page number based on current article counter
			$page_article = ceil( ($temp_counter + 1) / $content['struct'][ $content['cat_id'] ]['acat_maxlist']);
			if($page_article > $page_current) {
				//stop listing
				break;
			} elseif($page_article != $page_current) {
				//no listing - goto next article
				$temp_counter++;
				continue;
			}
		}

		$link_data		= get_article_morelink($article);
		$article_link 	= $link_data[0];

		//add available keywords to page wide keyword field
		$content['all_keywords'] .= $article["article_keyword"].',';

		if($temp_counter < $temp_topcount) {
			// as long as the counter is lower than the default "top_count" value
			// show the complete article summary listing

			$article["article_image"] = setArticleSummaryImageData($article["article_image"]);
			if($template) {
				$article["article_image"]['tmpllist'] = $template;
			}

			// build image/image link
			$article["article_image"]["poplink"]	= '';
			$thumb_image 							= false;
			$thumb_img 								= '';
			
			$img_thumb_name		= '';
			$img_thumb_rel		= '';
			$img_thumb_abs		= '';
			$img_thumb_width	= 0;
			$img_thumb_height	= 0;
			
			$img_zoom_name		= '';
			$img_zoom_rel		= '';
			$img_zoom_abs		= '';
			$img_zoom_width		= 0;
			$img_zoom_height	= 0;

			if(empty($article["article_image"]["list_caption"])) {
				$article["article_image"]["list_caption"] = '';
			}
			$caption = getImageCaption($article["article_image"]["list_caption"]);

			$article["article_image"]["list_caption"] = $caption[0]; // caption text

			if(!empty($article["article_image"]["list_hash"])) {

				$thumb_image = get_cached_image(
									array(	"target_ext"	=>	$article["article_image"]['list_ext'],
											"image_name"	=>	$article["article_image"]['list_hash'] . '.' . $article["article_image"]['list_ext'],
											"max_width"		=>	$article["article_image"]['list_width'],
											"max_height"	=>	$article["article_image"]['list_height'],
											"thumb_name"	=>	md5($article["article_image"]['list_hash'].$article["article_image"]['list_width'].$article["article_image"]['list_height'].$GLOBALS['phpwcms']["sharpen_level"])
        							  ));

				if($thumb_image != false) {
				
					$img_thumb_name		= $thumb_image[0];
					$img_thumb_rel		= PHPWCMS_IMAGES.$thumb_image[0];
					$img_thumb_abs		= PHPWCMS_URL.PHPWCMS_IMAGES.$thumb_image[0];
					$img_thumb_width	= $thumb_image[1];
					$img_thumb_height	= $thumb_image[2];

					$caption[3] = empty($caption[3]) ? '' : ' title="'.html_specialchars($caption[3]).'"';
					$caption[1] = html_specialchars($caption[1]);

					$thumb_img = '<img src="'.PHPWCMS_IMAGES . $thumb_image[0] .'" border="0" '.$thumb_image[3].' alt="'.$caption[1].'"'.$caption[3].' />';

					if($article["article_image"]["list_zoom"]) {

						$zoominfo = get_cached_image(
											array(	"target_ext"	=>	$article["article_image"]['list_ext'],
													"image_name"	=>	$article["article_image"]['list_hash'] . '.' . $article["article_image"]['list_ext'],
													"max_width"		=>	$GLOBALS['phpwcms']["img_prev_width"],
													"max_height"	=>	$GLOBALS['phpwcms']["img_prev_height"],
													"thumb_name"	=>	md5($article["article_image"]['list_hash'].$GLOBALS['phpwcms']["img_prev_width"].$GLOBALS['phpwcms']["img_prev_height"].$GLOBALS['phpwcms']["sharpen_level"])
        					  						)
												);

						if($zoominfo != false) {
						
							$img_zoom_name		= $zoominfo[0];
							$img_zoom_rel		= PHPWCMS_IMAGES.$zoominfo[0];
							$img_zoom_abs		= PHPWCMS_URL.PHPWCMS_IMAGES.$zoominfo[0];
							$img_zoom_width		= $zoominfo[1];
							$img_zoom_height	= $zoominfo[2];

							$article["article_image"]["poplink"] = 'image_zoom.php?'.getClickZoomImageParameter($zoominfo[0].'?'.$zoominfo[3]);

							if(!empty($caption[2][0])) {
								$open_link = $caption[2][0];
								$return_false = '';
							} else {
								$open_link = $article["article_image"]["poplink"];
								$return_false = 'return false;';
							}

							if(empty($article["article_image"]["list_lightbox"]) && !empty($caption[2][0])) {
								$article["article_image"]["poplink"]  = '<a href="'.$article["article_image"]["poplink"].'" ';
								$article["article_image"]["poplink"] .= 'onclick="checkClickZoom();clickZoom(\''.$open_link;
								$article["article_image"]["poplink"] .= "','previewpic','width=".$zoominfo[1];
								$article["article_image"]["poplink"] .= ",height=".$zoominfo[2]."');".$return_false;
								$article["article_image"]["poplink"] .= '"'.$caption[2][1].'>';
							} else {
								// lightbox
								initializeLightbox();
								
								$article["article_image"]["poplink"]  = '<a href="'.PHPWCMS_IMAGES.$zoominfo[0].'" rel="lightbox" ';
								if($article["article_image"]["list_caption"]) {
									$article["article_image"]["poplink"] .= 'title="'.parseLightboxCaption($article["article_image"]["list_caption"]).'" ';
								}
								$article["article_image"]["poplink"] .= 'target="_blank">';
							}
																   
							$article["article_image"]["poplink"] .= $thumb_img.'</a>';
						}
					}

					unset($caption);
				}
			}


			// set default template
			if(empty($article["article_image"]['tmpllist']) || $article["article_image"]['tmpllist'] == 'default') {

				$article["article_image"]['tmpllist'] = 'default';

				if(empty($tmpllist['default'])) {

					$tmpllist['default'] = file_get_contents(PHPWCMS_TEMPLATE.'inc_default/article_summary_list.tmpl');

				}

			}

			// try to read the template files
			// 1. try to check if template was read
			if(!isset($tmpllist[ $article["article_image"]['tmpllist'] ])) {
				$tmpllist[ $article["article_image"]['tmpllist'] ] = @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/articlesummary/list/'.$article["article_image"]['tmpllist']);
			}
			if($tmpllist[ $article["article_image"]['tmpllist'] ]) {
			
				//rendering
				$tmpl = $tmpllist[ $article["article_image"]['tmpllist'] ];
				$tmpl = render_cnt_template($tmpl, 'TITLE', empty($article['article_notitle']) ? html_specialchars($article["article_title"]) : '' );
				$tmpl = render_cnt_template($tmpl, 'SUB', html_specialchars($article["article_subtitle"]));
				
				// replace thumbnail and zoom image information
				$tmpl = str_replace( array(	'{THUMB_NAME}', '{THUMB_REL}', '{THUMB_ABS}', '{THUMB_WIDTH}', '{THUMB_HEIGHT}',
											'{IMAGE_NAME}', '{IMAGE_REL}', '{IMAGE_ABS}', '{IMAGE_WIDTH}', '{IMAGE_HEIGHT}' ),
									 array(	$img_thumb_name, $img_thumb_rel, $img_thumb_abs, $img_thumb_width, $img_thumb_height,
											$img_zoom_name, $img_zoom_rel, $img_zoom_abs, $img_zoom_width, $img_zoom_height ),
									 $tmpl );
				
				if( preg_match('/\{SUMMARY:(\d+)\}/', $tmpl, $matches) ) {
					if(empty($article['article_image']['list_maxwords'])) {
						$article['article_image']['list_maxwords'] = intval($matches[1]);
					}					
					$tmpl = preg_replace('/\{SUMMARY:\d+\}/', '{SUMMARY}', $tmpl);
				}
				
				$tmpl = render_cnt_template($tmpl, 'SUMMARY', empty($article['article_image']['list_maxwords']) ? $article["article_summary"] : getCleanSubString($article["article_summary"], $article['article_image']['list_maxwords'], '&#8230;', 'word'));
				$tmpl = render_cnt_template($tmpl, 'IMAGE', $thumb_img);
				$tmpl = render_cnt_template($tmpl, 'ZOOMIMAGE', $article["article_image"]["poplink"]);
				$tmpl = render_cnt_template($tmpl, 'CAPTION', nl2br(html_specialchars($article["article_image"]["list_caption"])));
				$tmpl = render_cnt_template($tmpl, 'ARTICLELINK', $article["article_morelink"] ? $article_link : '');
				$tmpl = render_cnt_template($tmpl, 'EDITOR', html_specialchars($article["article_username"]));
				$tmpl = render_cnt_template($tmpl, 'ARTICLEID', $article["article_id"]);
				$tmpl = render_cnt_template($tmpl, 'MORE', $article["article_morelink"] ? $template_default["top_readmore_link"] : '');
				$tmpl = render_cnt_template($tmpl, 'TARGET', ($article["article_morelink"] && $link_data[1]) ? ' target="'.$link_data[1].'"' : '');
				$tmpl = render_cnt_template($tmpl, 'BEFORE', '<!--before//-->');
				$tmpl = render_cnt_template($tmpl, 'AFTER', '<!--after//-->');
				$tmpl = render_cnt_date($tmpl, $article["article_date"], strtotime($article["article_begin"]), strtotime($article["article_end"]) );
				if($temp_counter) {
					$tmpl = render_cnt_template($tmpl, 'SPACE', '<!--space//-->');
				} else {
					$tmpl = render_cnt_template($tmpl, 'SPACE', '');
				}
				$listing .= $tmpl;
				$article["article_image"]['tmpllist'] = 1;
			} else {
				$article["article_image"]['tmpllist'] = 0;
			}

		} else {
			// if "top_count" value is equal or larger
			// show only the article headline listing
			if($temp_counter && $temp_counter == $temp_topcount) {
				$listing .= $template_default["space_aftertop_text"];
			} elseif ($temp_counter) {
				$listing .= $template_default["space_between_list"];
			}
			$listing .= $template_default["list_headline_before"];
			$listing .= '<a href="'.$article_link.'">';
			$listing .= $template_default["list_startimage"];
			$listing .= html_specialchars($article["article_title"]);
			$listing .= '</a>'.$template_default["list_headline_after"];

		}
		$temp_counter++;
	}

	if(isset($paginate_show['bottom'])) {
		$listing .= $paginate_navi;
	}
	if(!empty($paginate_show['rt'])) {
		$content['globalRT'][ $paginate_show['rt'] ] = $paginate_navi;
	}

	// restore original articles
	if(isset($_old_articles)) {
		$content["articles"]	= $_old_articles;
	}

	$listing .= $template_default["space_bottom"]; //ends with space at bottom
	return $listing; //str_replace("<br /><br />", "<br />", $listing)
}

function get_html_part($value, $class="", $link="", $span_or_div=1) {
	// returns a content part for html output like
	// <span class="xxx">html</span>
	if($value) {
		$html_tag  = ($span_or_div) ? 'span' : 'div';
		$html_part = ($link) ? '<a href="'.$link.'">'.html_specialchars($value).'</a>' : html_specialchars($value);
		if($class) {
			$html_part = '<'.$html_tag.' class="'.$class.'">'.$html_part;
		} else {
			$html_part = '<'.$html_tag.'>'.$html_part;
		}
		return $html_part.'</'.$html_tag.'>';
	} else {
		return '';
	}
}

function span_class($value, $class) {
	return !empty($class) ? '<span class="'.$class.'">'.$value.'</span>' : $value;
}

function div_class($value, $class, $tag='div') {
	return !empty($class) ? '<'.$tag.' class="'.$class.'">'.$value.'</'.$tag.'>' : $value;
}

function get_class_attrib($class) {
	return !empty($class) ? ' class="'.$class.'"' : '';
}

function html_parser($string) {
	// parse the $string and replace all possible
	// values with $replace

	// page TOP link
	$search[0]		= '/\[TOP\](.*?)\[\/TOP\]/i';
	$replace[0]		= '<a href="#top" class="phpwcmsTopLink">$1</a>';

	// internal Link to article ID
	$search[1]		= '/\[ID (\d+)\](.*?)\[\/ID\]/';
	$replace[1]		= '<a href="index.php?aid=$1" class="phpwcmsIntLink">$2</a>';

	// external Link (string)
	$search[2]		= '/\[EXT (.*?)\](.*?)\[\/EXT\]/';
	$replace[2]		= '<a href="$1" target="_blank" class="phpwcmsExtLink">$2</a>';

	// internal Link (string)
	$search[3]		= '/\[INT (.*?)\](.*?)\[\/INT\]/';
	$replace[3]		= '<a href="$1" class="phpwcmsIntLink">$2</a>';

	// random GIF Image
	$search[4]		= '/\{RANDOM_GIF:(.*?)\}/';
	$replace[4]		= '<img src="img/random_image.php?type=0&imgdir=$1" border="0" alt="" />';

	// random JPEG Image
	$search[5]		= '/\{RANDOM_JPEG:(.*?)\}/';
	$replace[5]		= '<img src="img/random_image.php?type=1&amp;imgdir=$1" border="0" alt="" />';

	// random PNG Image
	$search[6]		= '/\{RANDOM_PNG:(.*?)\}/';
	$replace[6]		= '<img src="img/random_image.php?type=2&amp;imgdir=$1" border="0" alt="" />';

	// insert non db image standard
	$search[7]		= '/\{IMAGE:(.*?)\}/';
	$replace[7]		= '<img src="picture/$1" border="0" alt="" />';

	// insert non db image left
	$search[8]		= '/\{IMAGE_LEFT:(.*?)\}/';
	$replace[8]		= '<img src="picture/$1" border="0" align="left" alt="" />';

	// insert non db image right
	$search[9]		= '/\{IMAGE_RIGHT:(.*?)\}/';
	$replace[9]		= '<img src="picture/$1" border="0" align="right" alt="" />';

	// insert non db image center
	$search[10]		= '/\{IMAGE_CENTER:(.*?)\}/';
	$replace[10]		= '<div align="center"><img src="picture/$1" border="0" alt="" /></div>';

	// insert non db image right
	$search[11]	 	= '/\{SPACER:(\d+)x(\d+)\}/';
	$replace[11] 	= '<img src="img/leer.gif" border="0" width="$1" height="$2" alt="" />';

	// RSS feed link 
	$search[13]		= '/\[RSS (.*?)\](.*?)\[\/RSS\]/';
	$replace[13]	= '<a href="feeds.php?feed=$1" target="_blank" class="phpwcmsRSSLink">$2</a>';

	// back Link (string)
	$search[14]		= '/\[BACK\](.*?)\[\/BACK\]/i';
	$replace[14] 	= '<a href="#" target="_top" title="go back" onclick="history.back();return false;" class="phpwcmsBackLink">$1</a>';

	// random Image Tag
	$search[15]		= '/\{RANDOM:(.*?)\}/e';
	$replace[15]	= 'get_random_image_tag("$1");';

	// span or div style
	$search[16]		= '/\[(span|div)_style:(.*?)\](.*?)\[\/style\]/s';
	$replace[16]	= '<$1 style="$2">$3</$1>';

	// span or div class
	$search[17]		= '/\[(span|div)_class:(.*?)\](.*?)\[\/class\]/s';
	$replace[17]	= '<$1 class="$2">$3</$1>';

	// typical html formattings
	$search[18]		= '/\[i\](.*?)\[\/i\]/is';			$replace[18]	= '<i>$1</i>';
	$search[19]		= '/\[u\](.*?)\[\/u\]/is';			$replace[19]	= '<u>$1</u>';
	$search[20]		= '/\[s\](.*?)\[\/s\]/is';			$replace[20]	= '<strike>$1</strike>';
	$search[21]		= '/\[b\](.*?)\[\/b\]/is';			$replace[21]	= '<strong>$1</strong>';

	// anchor link
	$search[22]		= '/\{A:(.*?)\}/is';
	$replace[22]	= '<a name="$1" class="phpwcmsAnchorLink"></a>';

	// this parses an E-Mail Link without subject (by Florian, 21-11-2003)
	$search[23]     = '/\[MAIL (.*?)\](.*?)\[\/MAIL\]/i';
	$replace[23]    = '<a href="mailto:$1" class="phpwcmsMailtoLink">$2</a>';

	// this tags out a Mailaddress with an predifined subject (by Florian, 21-11-2003)
	$search[24]     = '/\[MAILSUB (.*?) (.*?)\](.*?)\[\/MAILSUB\]/i';
	$replace[24]    = '<a href="mailto:$1?subject=$2" class="phpwcmsMailtoLink">$3</a>';

	// added simple [br] -> <br />
	$search[25]     = '/\[br\]/i';
	$replace[25]    = '<br />';
	$search[26]     = '/\<br>/i';
	$replace[26]    = '<br />';

	// create "make bookmark" javascript code
	$search[27]     = '/\[BOOKMARK\s{0,}(.*)\](.*?)\[\/BOOKMARK\]/i';
	$replace[27]    = '<a href="#" onclick="BookMark_Page();return false;" title="$1" class="phpwcmsBookmarkLink">$2</a>';

	// ABBreviation
	$search[28]		= '/\[abbr (.*?)\](.*?)\[\/abbr\]/is';
	$replace[28]	= '<abbr title="$1">$2</abbr>';

	$search[29]		= '/\[dfn (.*?)\](.*?)\[\/dfn\]/is';
	$replace[29]	= '<dfn title="$1">$2</dfn>';

	$search[30]		= '/\[em\](.*?)\[\/em\]/is';					$replace[30]	= '<em>$1</em>';
	$search[31]		= '/\[code\](.*?)\[\/code\]/is';				$replace[31]	= '<code>$1</code>';
	$search[32]		= '/\[cite\](.*?)\[\/cite\]/is';				$replace[32]	= '<cite>$1</cite>';
	$search[33]		= '/\[blockquote\](.*?)\[\/blockquote\]/is';	$replace[33]	= '<blockquote>$1</blockquote>';

	$search[34]		= '/\[blockquote (.*?)\](.*?)\[\/blockquote\]/is';
	$replace[34]	= '<blockquote cite="$1">$2</blockquote>';

	$search[35]		= '/\[acronym (.*?)\](.*?)\[\/acronym\]/is';
	$replace[35]	= '<acronym title="$1">$2</acronym>';

	$search[36]		= '/\[ID (.*?)\](.*?)\[\/ID\]/';
	$replace[36]	= '<a href="index.php?$1" class="phpwcmsIntLink">$2</a>';
	
	$search[37]		= '/\[li\](.*?)\[\/li\]/is';					$replace[37]	= '<li>$1</li>';
	$search[38]		= '/\[dt\](.*?)\[\/dt\]/is';					$replace[38]	= '<dt>$1</dt>';
	$search[39]		= '/\[dd\](.*?)\[\/dd\]/is';					$replace[39]	= '<dd>$1</dd>';
	$search[40]		= '/\[ul\](.*?)\[\/ul\]/is';					$replace[40]	= '<ul>$1</ul>';
	$search[41]		= '/\[ol\](.*?)\[\/ol\]/is';					$replace[41]	= '<ol>$1</ol>';
	$search[42]		= '/\[dl\](.*?)\[\/dl\]/is';					$replace[42]	= '<dl>$1</dl>';
	$search[43]		= '/\[h1\](.*?)\[\/h1\]/is';					$replace[43]	= '<h1>$1</h1>';
	$search[44]		= '/\[h2\](.*?)\[\/h2\]/is';					$replace[44]	= '<h2>$1</h2>';
	$search[45]		= '/\[h3\](.*?)\[\/h3\]/is';					$replace[45]	= '<h3>$1</h3>';
	$search[46]		= '/\[h4\](.*?)\[\/h4\]/is';					$replace[46]	= '<h4>$1</h4>';
	$search[47]		= '/\[h5\](.*?)\[\/h5\]/is';					$replace[47]	= '<h5>$1</h5>';
	$search[48]		= '/\[h6\](.*?)\[\/h6\]/is';					$replace[48]	= '<h6>$1</h6>';

	$string = preg_replace($search, $replace, $string);
	$string = str_replace('&#92;&#039;', '&#039;', $string);
	$string = str_replace('&amp;quot;', '&quot;', $string);
	return $string;
}

function include_ext_php($inc_file, $t=0) {
	// includes an external PHP script file and returns
	// the result as string from buffered include content
	$ext_php_content = '';

	//check if this is a local file
	if(is_file($inc_file) && !$t) {

		$this_path = str_replace("\\", '/', dirname(realpath($inc_file)));
		$this_path = preg_replace('/\/$/', '', $this_path);

		$root_path = str_replace("\\", '/', realpath(PHPWCMS_ROOT));
		$root_path = preg_replace('/\/$/', '', $root_path);

		if(strpos($this_path, $root_path) === 0) $t = 1;

	} elseif(!$t && !empty($GLOBALS['phpwcms']['allow_remote_URL'])) {
		//if remote URL is allowed in conf.inc.php
		$t = 1;
	}

	if($t) {
		ob_start();
		@include($inc_file);
		$ext_php_content = ob_get_contents();
		ob_end_clean();
	}

	return $ext_php_content;
}

function international_date_format($language="EN", $format="Y/m/d", $date_now=0) {
	// formats the given date
	// for the specific language
	// use the normal date format options

	if(!$format) {
		$format = "Y/m/d";
	}
	if(!intval($date_now)) {
		$date_now = time();
	}
	if($language == "EN" || !$language) {
		return date($format, $date_now);
	} else {
		$lang_include = PHPWCMS_ROOT.'/include/inc_lang/date/'.substr(strtolower($language), 0, 2).'.date.lang.php';
		if(is_file($lang_include)) {

			include($lang_include);
			$date_format_function = array (	"a" => 1, "A" => 1, "B" => 1, "d" => 1, "g" => 1, "G" => 1,
									"h" => 1, "H" => 1, "i" => 1, "I" => 1, "j" => 1, "m" => 1,
									"n" => 1, "s" => 1, "t" => 1, "T" => 1, "U" => 1, "w" => 1,
									"Y" => 1, "y" => 1, "z" => 1, "Z" => 1,
									"D" => 0, "F" => 0, "l" => 0, "M" => 0, "S" => 0
								   );

			$str_length = strlen($format); $date = "";
			for($i = 0; $i < $str_length; $i++) $date_format[$i] = substr($format, $i, 1);
			foreach($date_format as $key => $value) {
				if(isset($date_format_function[$value])) {
					if($date_format_function[$value]) {
						$date .= date($value, $date_now);
					} else{
						switch($value) {
							case "D":	$date .= $weekday_short[ intval(date("w", $date_now)) ]; break; //short weekday name
							case "l":	$date .= $weekday_long[ intval(date("w", $date_now)) ]; break; //long weekday name
							case "F":	$date .= $month_long[ intval(date("n", $date_now)) ]; break; //long month name
							case "M":	$date .= $month_short[ intval(date("n", $date_now)) ]; break; //long month name
							case "S":	$date .= ""; break;
						}
					}
				} else {
					$date .= $value;
				}
			}

		} else {
			$date = date($format, $date_now);
		}
	}
	return $date;
}

function get_random_image_tag($path) {
	// returns an random image from the give path
	// it looks for image of following type:
	// gif, jpg, jpeg, png

	$imgArray = array();
	$imgpath = str_replace('//', '/', PHPWCMS_ROOT.'/'.$path.'/');
	$imageinfo = false;

	if(is_dir($imgpath)) {
		$handle = opendir( $imgpath );
		while($file = readdir( $handle )) {
   			if( $file != '.' && $file != '..' && preg_match('/(\.jpg|\.jpeg|\.gif|\.png)$/i', $file)) {
				$imgArray[] = $file;
			}
		}
		closedir( $handle );
	}

	if(count($imgArray) && $imageinfo = is_random_image($imgArray, $imgpath)) {
		return '<img src="'.$path.'/'.urlencode($imageinfo['imagename']).'" '.$imageinfo[3].' border="0" alt="'.html_specialchars($imageinfo["imagename"]).'"'.HTML_TAG_CLOSE;
	} else {
		return '';
	}

}

function is_random_image($imgArray, $imagepath, $count=0) {
	// tests if the random choosed image is really an image
	$count++;
	$randval = mt_rand( 0, count( $imgArray ) - 1 );
	$file = $imagepath.$imgArray[ $randval ];
	//gets -> better tests for image info
	$imageinfo = @getimagesize($file);
	//if $imageinfo is not true repeat function and count smaller count all images
	if(!$imageinfo && $count < count($imgArray)) {
		$imageinfo = is_random_image($imgArray, $imagepath, $count);
	} else {
		$imageinfo["imagename"] = $imgArray[ $randval ];
	}
	return $imageinfo;
}

function return_struct_level(&$struct, $struct_id) {
	// walk through the given struct level and returns an array with available levels
	$level_entry = array();
	if(is_array($struct)) {
		foreach($struct as $key => $value) {
			if( _getStructureLevelDisplayStatus($key, $struct_id) ) {
			//if($key && $struct[$key]["acat_struct"] == $struct_id && (!$struct[$key]['acat_hidden'] || ($struct[$key]["acat_hidden"] == 2 && isset($GLOBALS['LEVEL_KEY'][$key])))) {
				$level_entry[$key] = $value;
			}
		}
	}
	return $level_entry;
}

function get_active_categories($struct, $act_struct_id) {
	// returns an array with all active categories/structure levels

	$which_cat_array = array();
	$which_category = $act_struct_id;
	while($which_category) {
		$which_cat_array[$which_category] = 1;
		$which_category = $GLOBALS['content']["struct"][$which_category]["acat_struct"];
	}
	return $which_cat_array;
}

function css_level_list(&$struct, $struct_path, $level, $parent_level_name='', $parent_level=1, $class='') {
	// returns list <div><ul><li></li></ul></div> of the current structure level
	// if $parent_level=1 the first list entry will be the parent level
	// $parent_level=0 - only the list of all levels in this structure
	// if $parent_leve_name != "" then it uses the given string
	// predefined class for this menu is "list_level"
	if(!trim($class)) {
		$class = 'list_level';
	}
	$parent_level_name	= trim($parent_level_name);
	$level 				= intval($level);
	$parent_level 		= intval($parent_level);
	$activated			= 0;
	$css_list			= '';

	//returns the complete level of NON hidden categories
	$level_struct 		= return_struct_level($struct, $level);
	$breadcrumb 		= get_breadcrumb(key($struct_path), $struct);

	foreach($level_struct as $key => $value) {

		if(!$level_struct[$key]["acat_redirect"]) {
			$link = 'index.php?';
			if($level_struct[$key]["acat_alias"]) {
				$link .= html_specialchars($level_struct[$key]["acat_alias"]);
			} else {
				$link .= 'id='.$key.',0,0,1,0,0';
			}
			$redirect['target'] = '';
		} else {
			$redirect = get_redirect_link($level_struct[$key]["acat_redirect"], ' ', '');
			$link = $redirect['link'];
		}
		$css_list .= '	<li';
		if(!empty($breadcrumb[$key])) {
			$css_list .= ' class="active"';
		}
		$css_list .= '><a href="'.$link.'"'.$redirect['target'].'>';
		$css_list .= html_specialchars($level_struct[$key]["acat_name"]);
		$css_list .= '</a></li>'.LF;

	}

	if($parent_level) {
		if(!$struct[$level]["acat_redirect"]) {
			$link = 'index.php?';
			if($struct[$level]["acat_alias"]) {
				$link .= html_specialchars($struct[$level]["acat_alias"]);
			} else {
				$link .= 'id='.$level.',0,0,1,0,0';
			}
			$redirect['target'] = '';
		} else {
			$redirect = get_redirect_link($struct[$level]["acat_redirect"], ' ', '');
			$link = $redirect['link'];
		}

		$css_list_home  = ($GLOBALS['aktion'][0] == $level) ? '	<li class="active">' : '	<li class="parent">';
		$css_list_home .= '<a href="'.$link.'"'.$redirect['target'].'>';
		$css_list_home .= html_specialchars((!$parent_level_name) ? $struct[$level]["acat_name"] : $parent_level_name);
		$css_list_home .= '</a></li>'.LF;
		$css_list = $css_list_home . $css_list;
	}
	if($css_list) {
		$css_list = LF.'<ul class="'.$class.'">'.LF . $css_list .'</ul>'.LF;
	}
	return $css_list;
}

// REWRITE - PATCHED FOR 04/04 // jan212
function url_search($query) {
	if ( substr($query,0,4) == '?id=') {
		$noid = substr($query, 4);
		$file = str_replace(',', '.', $noid).'.'.PHPWCMS_REWRITE_EXT;
	} else {
		$noid = substr($query,1);
		$file = str_replace(',', '.', $noid).'.'.PHPWCMS_REWRITE_EXT;
	}
	$link = ' href="'.$file.'"';
	return($link);
}

function js_url_search($query) {
	if ( substr($query,0,4) == '?id=') {
		$noid = substr($query, 4);
		$file = str_replace(',', '.', $noid).'.'.PHPWCMS_REWRITE_EXT;
		$file = $noid.'.'.PHPWCMS_REWRITE_EXT;
	} else {
		$noid = substr($query,1);
		$file = str_replace(',', '.', $noid).'.'.PHPWCMS_REWRITE_EXT;
		$file = $noid.'.'.PHPWCMS_REWRITE_EXT;
	}
	$link = "onclick=\"location.href='".$file."'";
	return($link);
}

function get_related_articles($keywords, $current_article_id, $template_default, $max_cnt_links=0, $dbcon) {
	// find keyword for current article used for RELATED replacementtag
	// prepared and inspired by Magnar Stav Johanssen

	$keyword_links = "";
	$max_cnt_links = intval($max_cnt_links);

	$keywords = str_replace("ALLKEYWORDS", $GLOBALS['content']['all_keywords'].',', $keywords);

	// replace unwanted chars and convert to wanted
	$keywords = str_replace(";", ",", $keywords);
	$keywords = str_replace("'", "", $keywords);
	$keywords = str_replace(" ", ",", $keywords);
	$keywords = str_replace(",,", ",", $keywords);

	// choose comma separated keywords
	$keywordarray = explode (",", $keywords);
	$keywordarray = array_map('trim', $keywordarray);
	$keywordarray = array_diff($keywordarray, array(''));
	$keywordarray = array_unique($keywordarray);
	$keywordarray = array_map('strtoupper', $keywordarray);
	// check for empty keywords or keywords smaller than 3 chars
	if(is_array($keywordarray) && count($keywordarray)) {
		foreach($keywordarray as $key => $value) {

			if(substr($keywordarray[$key], 0, 1) == '-') {
				$doNotUse = substr($keywordarray[$key], 1);
				foreach($keywordarray as $key2 => $value2) {
					if($doNotUse == $value2) {
						unset($keywordarray[$key2]);
						unset($keywordarray[$key]);
					}
				}
			}

			if(isset($keywordarray[$key]) && (strlen($keywordarray[$key]) < 3 || empty($keywordarray[$key]))) {
				unset($keywordarray[$key]);
			}
		}
	}

	if(is_array($keywordarray) && count($keywordarray)) {
		$where = "";
		foreach($keywordarray as $value) {
				//build where keyword = blabla
				$where .= ($where) ? " OR " : "";
				//replace every "'" to "''" for security reasons with aporeplace()
				$where .= "article_keyword LIKE '%".aporeplace($value)."%'";
		}
		$limit = ($max_cnt_links) ? " LIMIT ".$max_cnt_links : "";
		$sql  =	"SELECT article_id, article_title, article_cid, article_subtitle, article_summary ";
		$sql .=	"FROM ".DB_PREPEND."phpwcms_article WHERE article_deleted=0 AND ";
		$sql .=	"article_id<>".intval($current_article_id)." AND ";
		// VISIBLE_MODE: 0 = frontend (all) mode, 1 = article user mode, 2 = admin user mode
		switch(VISIBLE_MODE) {
			case 0: $sql .=	"article_public=1 AND article_aktiv=1 AND ";
					break;
			case 1: $sql .= "article_uid=".$_SESSION["wcs_user_id"]." AND ";
					break;
			//case 2: admin mode no additional neccessary
		}
		$sql .=	"article_begin < NOW() AND article_end > NOW() AND (".$where.") ";
		
		if(empty($template_default['sort_by'])) $template_default['sort_by'] = '';
		
		switch($template_default['sort_by']) {
		
			case 'title_asc': 
						$sql .=	"ORDER BY article_title";
						break;
						
			case 'title_desc': 
						$sql .=	"ORDER BY article_title DESC";
						break;
						
			case 'ldate_asc': 
						$sql .=	"ORDER BY article_begin";
						break;
						
			case 'ldate_desc': 
						$sql .=	"ORDER BY article_begin DESC";
						break;
						
			case 'kdate_asc': 
						$sql .=	"ORDER BY article_end";
						break;
						
			case 'kdate_desc': 
						$sql .=	"ORDER BY article_end DESC";
						break;
						
			case 'cdate_asc': 
						$sql .=	"ORDER BY article_created";
						break;
						
			case 'cdate_desc': 
						$sql .=	"ORDER BY article_created DESC";
						break;
		
			default:
						$sql .=	"ORDER BY article_tstamp DESC";
		}
		
		$sql .= $limit;

		// related things
		$target = ($template_default["link_target"]) ? ' target="'.$template_default["link_target"].'"' : "";
		if($result = mysql_query($sql, $dbcon)) {
			$count_results = mysql_num_rows($result);
			$count = 0;
			while ($row = mysql_fetch_row($result)) {
				$count++;
				if($template_default["link_length"] && strlen($row[1]) > $template_default["link_length"]) {
					$article_title = substr($row[1], 0, $template_default["link_length"]).$template_default["cut_title_add"];
				} else {
					$article_title = $row[1];
				}
				$keyword_links .= $template_default["link_before"].$template_default["link_symbol"];
				$keyword_links .= '<a href="index.php?id='.$row[2].','.$row[0].',0,0,1,0"';
				$keyword_links .= $target.">".html_specialchars($article_title)."</a>";

				//try to remove possible unwanted after - if not enclosed before.link.after
				if($keyword_links && !$template_default["link_before"] && $count < $count_results) {
					$keyword_links .= $template_default["link_after"];
				}
			}
			mysql_free_result($result);
		}
	}

	//enclose whole
	if($keyword_links) $keyword_links = $template_default["before"].$keyword_links.$template_default["after"];

	return $keyword_links;
}

function get_new_articles(&$template_default, $max_cnt_links=0, $cat, $dbcon) {
	// find all new articles

	$max_cnt_links = intval($max_cnt_links);
	$limit = empty($max_cnt_links) ?  '' : ' LIMIT '.$max_cnt_links;
	$cat = trim($cat);
	$cat = (intval($cat) || $cat == '0') ? 'article_cid='.intval($cat).' AND ' : '';

	switch( (empty($template_default["sort_by"]) ? '' : strtolower($template_default["sort_by"])) ) {

		case 'cdate': 	//use real creation date
						$sql  =	"SELECT article_id, article_title, article_cid, article_created AS article_date ";
						$sorting = 'article_created';
						break;

		case 'ldate': 	//use live/start date
						$sql  =	"SELECT article_id, article_title, article_cid, UNIX_TIMESTAMP(article_begin) AS article_date ";
						$sorting = 'article_begin';
						break;

		case 'kdate': 	//use kill/end date
						$sql  =	"SELECT article_id, article_title, article_cid, UNIX_TIMESTAMP(article_end) AS article_date ";
						$sorting = 'article_end';
						break;

		default:		$sql  =	"SELECT article_id, article_title, article_cid, UNIX_TIMESTAMP(article_tstamp) AS article_date ";
						$sorting = 'article_tstamp';
	}

	$sql .=	"FROM ".DB_PREPEND."phpwcms_article WHERE ".$cat;
	// VISIBLE_MODE: 0 = frontend (all) mode, 1 = article user mode, 2 = admin user mode
	switch(VISIBLE_MODE) {
		case 0: $sql .=	"article_public=1 AND article_aktiv=1 AND ";
				break;
		case 1: $sql .= "article_uid=".$_SESSION["wcs_user_id"]." AND ";
				break;
		//case 2: admin mode no additional neccessary
	}
	$sql .= "article_deleted=0 AND article_begin < NOW() AND article_end > NOW() ";
	$sql .= "ORDER BY ".$sorting." DESC".$limit;

	// new articles list
	$new_links = "";
	$target = ($template_default["link_target"]) ? ' target="'.$template_default["link_target"].'"' : "";
	if($result = mysql_query($sql, $dbcon)) {
		$count_results = mysql_num_rows($result);
		$count = 0;
		while ($row = mysql_fetch_row($result)) {
			$count++;
			if($template_default["link_length"] && strlen($row[1]) > $template_default["link_length"]) {
				$article_title = substr($row[1], 0, $template_default["link_length"]).$template_default["cut_title_add"];
			} else {
				$article_title = $row[1];
			}
			$article_title = html_specialchars($article_title);
			if(trim($template_default["date_format"])) {
				$article_title = 	$template_default["date_before"] .
									html_specialchars(international_date_format(
									$template_default["date_language"],
									$template_default["date_format"],
									$row[3])) .
									$template_default["date_after"] .
									$article_title;
			}
			$new_links .= $template_default["link_before"];
			$new_links .= $template_default["link_symbol"];
			$new_links .= '<a href="index.php?id='.$row[2].','.$row[0].',0,0,1,0"';
			$new_links .= $target.">".$article_title."</a>";
			$new_links .= $template_default["link_after"];
			//try to remove possible unwanted after - if not enclosed before.link.after
			/*
			if($new_links && !$template_default["link_before"] && $count < $count_results) {
				$new_links .= $template_default["link_after"];
			}
			*/
		}
		mysql_free_result($result);
	}

	//enclose whole
	if($new_links) $new_links = $template_default["before"].$new_links.$template_default["after"];

	return $new_links;
}

function get_article_idlink($article_id=0, $link_text="", $db) {
	// returns the internal article link to given article ID/category
	$article_id		= intval($article_id);
	$article_cid	= 0;
	$link_text		= decode_entities($link_text);
	$link_text		= html_specialchars($link_text);
	$article_title	= $link_text;

	if($article_id) {
		$sql =	"SELECT article_id, article_title, article_cid ".
				"FROM ".DB_PREPEND."phpwcms_article WHERE article_id=".$article_id." AND ".
				"article_public=1 AND article_aktiv=1 AND article_deleted=0 AND ".
				"article_begin < NOW() AND article_end > NOW() LIMIT 1;";
		if($result = mysql_query($sql, $db)) {
			if($row = mysql_fetch_row($result)) {
				$article_id		= $row[0];
				$article_cid	= $row[2];
				$article_title	= html_specialchars($row[1]);
			}
			mysql_free_result($result);
		}
	}
	$article_link = '<a href="index.php?aid='.$article_id.'" title="'.$article_title.'">'.$link_text.'</a>';
	return $article_link;
}

function get_keyword_link($keywords="", $db) {
	// returns a link or linklist for special article keywords
	// used for replacement tag {KEYWORD:Charlie}

	$keywords = explode(",", $keywords);
	$where = "";
	$keyword_list = "";
	$link = "";

	if(count($keywords)) {
		foreach($keywords as $value) {
			$value = trim($value);
			if($value) {
				if($where) {
					$where .= " AND ";
					$keyword_list .= ", ";
				}
				$where .= "article_keyword LIKE '%*".aporeplace($value)."*%'";
				//$where .= "article_keyword REGEXP '\\\\*".aporeplace($value)."\\\\*'";
				$keyword_list .= html_specialchars($value);
			}
		}
	} else {
		$keyword_list = $keywords;
	}

	if($where) {

		$x = 0;
		$sql  = "SELECT article_id, article_cid, article_title FROM ".DB_PREPEND."phpwcms_article WHERE ";
		// VISIBLE_MODE: 0 = frontend (all) mode, 1 = article user mode, 2 = admin user mode
		switch(VISIBLE_MODE) {
			case 0: $sql .=	"article_public=1 AND article_aktiv=1 AND ";
					break;
			case 1: $sql .= "article_uid=".$_SESSION["wcs_user_id"]." AND ";
					break;
			//case 2: admin mode no additional neccessary
		}
		$sql .= "article_deleted=0 AND article_begin < NOW() ";
		$sql .=	"AND article_end > NOW() AND (".$where.")";

		if($result = mysql_query($sql, $db)) {
			$article_list = array();
			while($row = mysql_fetch_row($result)) {
				$article_list[$x][0] = $row[0]; //article ID
				$article_list[$x][1] = $row[1]; //article catID
				$article_list[$x][2] = html_specialchars($row[2]); //article title
				$x++;
			}
			mysql_free_result($result);
		}

		if($x) {
			// if keyword(s) found
			if($x == 1) {
				// if only 1 article found
				$link .= '<a href="index.php?aid='.$article_list[0][0].'" title="'.$article_list[0][2].'">'.$keyword_list.'</a>';
			} else {
				// if more than one article found
				foreach($article_list as $key => $value) {
					if($link) $link .= '|';
					$link .= '<a href="index.php?aid='.$article_list[$key][0].'" title="'.$article_list[$key][2].'">'.($key+1).'</a>';
				}
				$link = $keyword_list.' ['.$link.']';
			}
		}
	}
	if(!$link) $link = $keyword_list;
	$link  = $GLOBALS['template_default']["article"]["keyword_before"] . $link;
	$link .= $GLOBALS['template_default']["article"]["keyword_after"];
	return $link;

}

function clean_replacement_tags($text = '', $allowed_tags='<a><b><i><strong>') {
	// strip out special replacement tags
	$text = render_PHPcode($text);
	$text = str_replace('<td>', '<td> ', $text);
	$text = strip_tags($text, $allowed_tags);
	$text = str_replace('|', ' ', $text);
	$text = preg_replace('/\{.*?\}/si', '', $text);
	$text = preg_replace('/\[ID.*?\/ID\]/si', '', $text);
	$text = preg_replace('/(\s+)/i', ' ', $text);
	return trim($text);
}

function get_search_action($id, $dbcon) {
	// return the search form action
	$id = intval($id); $cid = 0;
	if($id) {
		$sql  = "SELECT article_cid FROM ".DB_PREPEND."phpwcms_article WHERE ";
		$sql .=	"article_public=1 AND article_aktiv=1 AND article_deleted=0 AND article_begin < NOW() ";
		$sql .=	"AND article_end > NOW() AND article_id=".$id." LIMIT 1;";
		if($result = mysql_query($sql, $dbcon)) {
			if($row = mysql_fetch_row($result)) {
				$cid = $row[0];
			}
			mysql_free_result($result);
		}
	}
	return ($cid) ? 'index.php?aid='.$id : '';
}

function get_index_link_up($linktext) {
	// return the link to parent category of current category
	$cat_id = $GLOBALS['content']['cat_id'];
	$linktext = trim($linktext);
	$link = '';
	if(!$linktext) $linktext = 'UP';
	if($cat_id && !$GLOBALS['content']['struct'][$cat_id]['acat_hidden']) {
		$link = '<a href="index.php?id='.$GLOBALS['content']['struct'][$cat_id]['acat_struct'].',0,0,1,0,0">';
	}
	return ($link) ? $link.$linktext.'</a>' : $linktext;
}

function get_index_link_next($linktext, $cat_down=0) {
	// return the link to next article in current ctageory
	$a_id = isset($GLOBALS['content']['article_id']) ? $GLOBALS['content']['article_id'] : $GLOBALS['aktion'][1];
	$linktext = trim($linktext);
	if(!$linktext) $linktext = 'NEXT';
	$link = '';

	if(count($GLOBALS['content']['articles']) > 1) {

		$c = 0; //temp counter
		foreach($GLOBALS['content']['articles'] as $key => $value) {
			if($c || !$a_id) {
				$link  = '<a href="index.php?aid='.$key.'">';
				break;
			}
			if($key == $a_id) $c++;
		}
	}

	if($cat_down && !$link) {
		// go cat down or to next cat above

		if($GLOBALS['content']['cat_id']) {
			foreach($GLOBALS['content']['struct'] as $key => $value) {
				if($GLOBALS['content']['struct'][$key]['acat_struct'] == $GLOBALS['content']['cat_id']) {
					$link = '<a href="index.php?id='.$key.',0,0,1,0,0">';
					break;
				}
			}
		} else {
			$c = 0;
			foreach($GLOBALS['content']['struct'] as $key => $value) {
				if($c) {
					$link = '<a href="index.php?id='.$key.',0,0,1,0,0">';
					break;
				}
				$c++;
			}
		}

		if(!$link && $GLOBALS['content']['cat_id']) {
			$c=0;
			$temp_key = array();
			foreach($GLOBALS['content']['struct'] as $key => $value) {
				if($GLOBALS['content']['struct'][$key]['acat_struct'] == $GLOBALS['content']['struct'][ $GLOBALS['content']['cat_id'] ]['acat_struct']) {
					$temp_key[] = $key;
				}
			}
			$count_temp = count($temp_key);
			if($count_temp) {
				$c=0;
				foreach($temp_key as $value) {
					if($value == $GLOBALS['content']['cat_id'] && $c+1 < $count_temp) {
						$link = '<a href="index.php?id='.$temp_key[$c+1].',0,0,1,0,0">';
						break;
					}
					$c++;
				}
				if($c == $count_temp && !$link) {
					// back reverese to higher next structure level
					$current_id = $GLOBALS['content']['cat_id'];

					while($c=1) {
						$parent_id = $GLOBALS['content']['struct'][ $current_id ]['acat_struct'];
						$parent_struct_id = $GLOBALS['content']['struct'][ $parent_id ]['acat_struct'];

						$c=0;
						foreach($GLOBALS['content']['struct'] as $key => $value) {
							if($GLOBALS['content']['struct'][$key]['acat_struct'] == $parent_struct_id) {
								if($c) {
									$link = '<a href="index.php?id='.$key.',0,0,1,0,0">';
									break;
								}
								if($key == $parent_id) $c=1;
							}
						}

						if(!$parent_struct_id) {
							if(!$parent_id) $link = '';
							break;
						} else {
							$current_id = $parent_id;
						}

					}


				}
			}
		}

	}

	return ($link) ? $link.$linktext.'</a>' : $linktext;
}

function get_index_link_prev($linktext, $cat_up=0) {
	// return the link to next article in current ctageory
	$a_id = isset($GLOBALS['content']['article_id']) ? $GLOBALS['content']['article_id'] : $GLOBALS['aktion'][1];
	$linktext = trim($linktext);
	if(!$linktext) $linktext = 'PREV';
	$link = '';
	$c = 0; //temp counter

	if(count($GLOBALS['content']['articles']) > 1 && $a_id) {

		foreach($GLOBALS['content']['articles'] as $key => $value) {
			if($key == $a_id && $c) {
				$link  = '<a href="index.php?aid='.$prev_art_id.'">';
				break;
			}
			$c++;
			$prev_cat_id = $GLOBALS['content']['articles'][$key]['article_cid'];
			$prev_art_id = $key;
		}
	}
	if($cat_up && $a_id && $c && !$link) {
		$link = '<a href="index.php?id='.$GLOBALS['content']['cat_id'].',0,0,1,0,0">';
	}

	if($cat_up && !$link) {
		// go cat down or to next cat above
		$temp_key = array();
		foreach($GLOBALS['content']['struct'] as $key => $value) {
			if($GLOBALS['content']['struct'][$key]['acat_struct'] == $GLOBALS['content']['struct'][ $GLOBALS['content']['cat_id'] ]['acat_struct']) {
				$temp_key[] = $key;
			}
		}
		if(count($temp_key) && $GLOBALS['content']['cat_id']) {
			$c = 0;
			foreach($temp_key as $value) {
				if($value == $GLOBALS['content']['cat_id']) {
					$prev_cat_id = (!$c) ? $GLOBALS['content']['struct'][$value]['acat_struct'] : $temp_key[$c-1];
					$link = '<a href="index.php?id='.$prev_cat_id.',0,0,1,0,0">';
					break;
				}
				$c++;
			}
		}
	}

	return ($link) ? $link.$linktext.'</a>' : $linktext;
}

function include_int_php($string) {
	// return the PHP var value
	$s = html_despecialchars($string[1]);
	if((strpos($s,'$GLOBALS') || strpos($s,'$_'))===false) {
		$s = preg_replace('/^\$(.*?)\[(.*?)/si', '$GLOBALS["$1"][$2', $s);
		if(substr($s,strlen($s)-1) != ']') {
			$s = str_replace('$', '', $s);
			$s = '$GLOBALS["'.$s.'"]';
		}
	}
	$s = str_replace('$phpwcms', '$notavailable', $s);
	$s = str_replace('["phpwcms"]', '["notavailable"]', $s);
	$s = str_replace("['phpwcms']", '["notavailable"]', $s);
	ob_start();
	eval('echo '.$s.';');
	$return = ob_get_contents();
	ob_end_clean();
	return $return;
}

function include_int_phpcode($string) {
	// return the PHP code
	$s = html_despecialchars($string[1]);
	$s = str_replace('<br>', "\n", $s);
	$s = str_replace('<br />', "\n", $s);
	ob_start();
	eval($s.";");
	$return = ob_get_contents();
	ob_end_clean();
	return $return;
}

function build_sitemap($start=0, $counter=0) {
	// create sitemap

	$s = '';
	$c = '';
	$counter++;


	if($GLOBALS['sitemap']['classcount']) {
		if($GLOBALS['sitemap']['catclass']) $c = ' class="'.$GLOBALS['sitemap']['catclass'].$counter.'"';
	} else {
		if($GLOBALS['sitemap']['catclass']) $c = ' class="'.$GLOBALS['sitemap']['catclass'].'"';
	}

	foreach($GLOBALS['content']['struct'] as $key => $value) {

		//if ($key && $GLOBALS['content']['struct'][$key]['acat_nositemap'] && $start == $GLOBALS['content']['struct'][$key]['acat_struct'])
		if( $GLOBALS['content']['struct'][$key]['acat_nositemap'] && _getStructureLevelDisplayStatus($key, $start) ) {

			$s .= '<li'.$GLOBALS['sitemap']['cat_style'].'>';	//$c.

			if(!$GLOBALS['content']['struct'][$key]["acat_redirect"]) {
				$s .= '<a href="index.php?';
				if($GLOBALS['content']['struct'][$key]['acat_alias']) {
					$s .= $GLOBALS['content']['struct'][$key]['acat_alias'];
				} else {
					$s .= 'id='.$key;
				}
				$s .= '"';
			} else {
				$redirect = get_redirect_link($GLOBALS['content']['struct'][$key]["acat_redirect"], ' ', '');
				$s .= '<a href="'.$redirect['link'].'"'.$redirect['target'];
			}

			$s .= '>';
			$s .= html_specialchars($GLOBALS['content']['struct'][$key]['acat_name']);
			$s .= '</a>';
			if($GLOBALS['sitemap']["display"]) $s .= build_sitemap_articlelist($key, $counter);

			$s .= build_sitemap($key, $counter);

			$s .= "</li>\n";
		}
	}


	if($s) $s = "\n<ul".$c.">\n".$s.'</ul>';

	return $s;
}

function build_sitemap_articlelist($cat, $counter=0) {
	// create list of articles for given category

	$ao = get_order_sort($GLOBALS['content']['struct'][ $cat ]['acat_order']);

	$sql  = "SELECT article_id, article_title FROM ".DB_PREPEND."phpwcms_article ";
	$sql .= "WHERE article_cid=".intval($cat)." AND article_nositemap=1 AND ";
	// VISIBLE_MODE: 0 = frontend (all) mode, 1 = article user mode, 2 = admin user mode
	switch(VISIBLE_MODE) {
		case 0: $sql .=	"article_public=1 AND article_aktiv=1 AND ";
				break;
		case 1: $sql .= "article_uid=".$_SESSION["wcs_user_id"]." AND ";
				break;
		//case 2: admin mode no additional neccessary
	}
	$sql .= "article_deleted=0 AND article_begin<NOW() AND article_end>NOW() ";
	$sql .= "ORDER BY ".$ao[2];

	$s = '';
	$c = '';
	if($GLOBALS['sitemap']['classcount']) {
		if($GLOBALS['sitemap']['articleclass']) $c = ' class="'.$GLOBALS['sitemap']['articleclass'].$counter.'"';
	} else {
		if($GLOBALS['sitemap']['articleclass']) $c = ' class="'.$GLOBALS['sitemap']['articleclass'].'"';
	}

	if($result = mysql_query($sql, $GLOBALS['db'])) {
		while($row = mysql_fetch_row($result)) {

			$s .= '<li'.$GLOBALS['sitemap']['article_style'].'>';	//.$c
			$s .= '<a href="index.php?aid='.$row[0].'">';
			$s .= html_specialchars($row[1]);
			$s .= "</a></li>\n";

		}
		mysql_free_result($result);
	}

	if($s) $s = "\n<ul".$c.">\n".$s.'</ul>';

	return $s;

}

function render_cnt_template($text='', $tag='', $value='') {
	// render content part by replacing placeholder tags by value
	$value = strval($value);
	if($value) {
		$text = preg_replace('/\['.$tag.'\](.*?)\[\/'.$tag.'\]/is', '$1', $text);
		$text = preg_replace('/\['.$tag.'_ELSE\](.*?)\[\/'.$tag.'_ELSE\]/is', '', $text);
	} else {
		$text = preg_replace('/\['.$tag.'_ELSE\](.*?)\[\/'.$tag.'_ELSE\]/is', '$1', $text);
		$text = preg_replace('/\['.$tag.'\](.*?)\[\/'.$tag.'\]/is', '', $text);
	}
	$text = str_replace('{'.$tag.'}', $value, $text);
	return $text;
}

function replace_cnt_template($text='', $tag='', $value='') {
	// replace tag by value
	$text = preg_replace('/\['.$tag.'\].*?\[\/'.$tag.'\]/is', strval($value), $text);
	return $text;
}

function render_cnt_date($text='', $date, $livedate=NULL, $killdate=NULL) {
	// render date by replacing placeholder tags by value
	$text = preg_replace('/\{DATE:(.*?) lang=(..)\}/e', 'international_date_format("$2","$1","'.$date.'")', $text);
	$text = preg_replace('/\{DATE:(.*?)\}/e', 'date("$1",'.$date.')', $text);
	if(intval($livedate)) {
		$text = preg_replace('/\{LIVEDATE:(.*?) lang=(..)\}/e', 'international_date_format("$2","$1","'.$livedate.'")', $text);
		$text = preg_replace('/\{LIVEDATE:(.*?)\}/e', 'date("$1",'.$livedate.')', $text);
	}
	if(intval($killdate)) {
		$text = preg_replace('/\{KILLDATE:(.*?) lang=(..)\}/e', 'international_date_format("$2","$1","'.$killdate.'")', $text);
		$text = preg_replace('/\{KILLDATE:(.*?)\}/e', 'date("$1",'.$killdate.')', $text);
	}	
	return $text;
}

function returnTagContent($string='', $tag='', $findall=false, $tagOpen='[', $tagClose=']') {
	// used to exclude a special string sequence from string
	// enclosed by [tag][/tag] or also <tag></tag>
	$data 				= array();
	$data['original']	= $string;
	$tag_open			= preg_quote($tagOpen.$tag.$tagClose, '/');
	$tag_close			= preg_quote($tagOpen.'/'.$tag.$tagClose, '/');
	$data['new']		= preg_replace('/'.$tag_open.'(.*?)'.$tag_close.'/is', '', $string);
	if($findall) {
		preg_match_all('/'.$tag_open.'(.*?)'.$tag_close.'/is', $string, $matches);
	} else {
		preg_match('/'.$tag_open.'(.*?)'.$tag_close.'/is', $string, $matches);
	}
	$data['tag']		= isset($matches[1]) ? $matches[1] : '';
	return $data;
}

function include_url($url) {
	// include given URL but only take content between <body></body>

	global $include_urlparts;
	
	if( is_string($url) ) {
		$url = array( 1 => $url );
	} elseif( ! isset($url[1]) ) {
		return '';
	}
	
	$k				= '';
	$url			= trim($url[1]);
	$url			= explode(' ', $url);
	$cache			= isset($url[1]) ? intval(str_replace('CACHE=', '', strtoupper($url[1]))) : 0;
	$url			= $url[0];
	$cache_status	= 'MISSING';
	
	if($url && $cache) {
	
		$cache_filename	= md5($url).'-url';	// set cache file name
		$cache_file		= PHPWCMS_CONTENT.'tmp/'.$cache_filename;	// set caching file
		$cache_status	= check_cache($cache_file, $cache);	// ceck existence
		
		if($cache_status == 'VALID') {	// read cache
			
			$k	= read_textfile($cache_file);
			$k	= trim($k);
		
			if(empty($k)) {
				$cache_status == 'EXPIRED';	// check if cache content is available
			}
		
		}

	}
	
	if($cache_status != 'VALID' && $url) {	// cache file is missing or outdated
	
		$include_urlparts = parse_url($url);
		if(!empty($include_urlparts['path'])) {
			$include_urlparts['path'] = dirname($include_urlparts['path']);
			$include_urlparts['path'] = str_replace("\\", '/', $include_urlparts['path']);
		}
		$k = @file_get_contents($url);
		if($k) {
			// now check against charset
			if(strpos($k, 'charset=') || strpos($k, 'CHARSET=')) {
				$charset = preg_replace('/.*charset=(.*?)>.*/si', "$1", $k, 1);
				$charset = str_replace(array('"', "'", '/'), '', $charset);
				$charset = strtolower(trim($charset));
			} elseif(preg_match('/http-equiv="{0,1}Content-Type"{0,1}\s{1,}(content="{0,1}.*?"{0,1}.{0,3}>)/i', $k, $match)) {
				$charset = '';
				if(!empty($match[1])) {
					$charset = strtolower($match[1]);
					$charset = trim(str_replace(array('"', "'", '/', 'content=', ' ', '>'), '', $charset));
				}
			}

			$k = preg_replace("/.*<body[^>]*?".">(.*?)<\/body>.*/si", "$1", $k);
			$k = str_replace(array('<?', '?>', '<%', '%>'), array('&lt;?', '?&gt;', '&lt;&#37;', '&#37;&gt;'), $k);
			$k = preg_replace_callback('/(href|src|action)=[\'|"]{0,1}(.*?)[\'|"]{0,1}( .*?){0,1}>/i', 'make_absoluteURL', $k);
			$k = trim($k);
			$k = makeCharsetConversion($k, $charset, PHPWCMS_CHARSET, 1);
			
			// now write or update cache file in case there is timeout or content
			if($cache && $k) {
			
				@write_textfile($cache_file, $k);
			
			}

		}
		$include_urlparts = '';
		
	}
	return $k;
}

function make_absoluteURL($matches) {
	// replaces all relative URLs in href=/src= to absolute paths based on called URI
	$parts = $GLOBALS['include_urlparts'];
	$path  = $matches[2];
	$k = '';
	if(preg_match('/^(http|mailto|ftp|https|skype|itms)/i', $path)) {
		$k = $path;
	} else {
		if(empty($parts['path'])) $parts['path'] = '';
		$k = $parts['host'].$parts['path'].'/'.$path;
		$k = str_replace('///', '/', $k);
		$k = str_replace('//', '/', $k);
		$k = $parts['scheme'].'://'.$k;
	}
	if(empty($matches[3])) $matches[3] = '';
	return $matches[1].'="'.$k.'"'.$matches[3].'>';

}

function render_PHPcode($string='') {
	// combined PHP replace renderer
	// includes external PHP script and returns the content
	$string = preg_replace('/\{PHP:(.*?)\}/e', 'include_ext_php("$1");', $string);
	// do complete PHP code
	$string = preg_replace_callback("/\[PHP\](.*?)\[\/PHP\]/s", 'include_int_phpcode', $string);
	// includes external PHP script and returns the content
	$string = preg_replace_callback("/\{PHPVAR:(.*?)\}/s", 'include_int_php', $string);
	return $string;
}

function nav_list_struct (&$struct, $act_cat_id, $level, $class='') {
	// start with home directory for the listing = top nav structure
	// 1. Build the recursive tree for given actual article category ID

	// return the tree starting with given start_id (like breadcrumb)
	// if the $start_id = 0 then this stops because 0 = top level

	$level		= intval($level);
	$data		= array();
	$start_id 	= $act_cat_id;
	$class 		= trim($class);
	$depth		= 0;

	while ($start_id) {
		$data[$start_id] = 1;
		$start_id		 = $struct[$start_id]["acat_struct"];
	}
	$temp_tree = sizeof($data) ? array_reverse($data, 1) : false;

	$temp_menu = build_list ($struct, $level, $temp_tree, $act_cat_id, $class, $depth);
	$temp_menu = str_replace("\n\n", LF, $temp_menu);
	return $temp_menu ? $temp_menu : '';
}

function build_list ($struct, $level, $temp_tree, $act_cat_id, $class='', $depth=0) {
	// this returns the level structure based on given arrays
	// it is special for browsing from root levels

	if($class != '') {
		$curClass = ' class="'.$class.$depth.'"';
		$curClassNext = ' class="'.$class.($depth+1).'"';
		$curClassActive = ' class="'.$class.'Active'.$depth.'"';
	} else {
		$curClass = '';
		$curClassNext = '';
		$curClassActive = ' class="listActive"';
	}

	$depth++;

	$temp_menu = "\n<ul".$curClass.">\n";
	foreach($struct as $key => $value) {

		//if($struct[$key]["acat_struct"] == $level && $key && (!$struct[$key]['acat_hidden'] || ($struct[$key]["acat_hidden"] == 2 && isset($GLOBALS['LEVEL_KEY'][$key]))) ) {
		if( _getStructureLevelDisplayStatus($key, $level) ) {

			if(!$struct[$key]["acat_redirect"]) {
				$link = 'index.php?';
				if($struct[$key]["acat_alias"]) {
					$link .= html_specialchars($struct[$key]["acat_alias"]);
				} else {
					$link .= 'id='.$key.',0,0,1,0,0';
				}
				$redirect['target'] = '';
			} else {
				$redirect = get_redirect_link($struct[$key]["acat_redirect"], ' ', '');
				$link = $redirect['link'];
			}

			if(!empty($temp_tree[$key])) {

				if($act_cat_id == $key) {
					$temp_menu .= "\n<li".$curClassActive.">";
				} else {
					$temp_menu .= "\n<li>";
				}

				$temp_menu .= '<a href="'.$link.'">'.html_specialchars($struct[$key]["acat_name"]).'</a>';

				$temp_menu .= build_list ($struct, $key, $temp_tree, $act_cat_id, $class, $depth);
				$temp_menu .= '</li>';

			} else {
				$temp_menu .= "\n<li>".'<a href="'.$link.'"'.$redirect['target'].'>';
				$temp_menu .= html_specialchars($struct[$key]["acat_name"])."</a></li>\n";
			}
		}
	}

	$temp_menu = trim($temp_menu);
	return $temp_menu != "<ul".$curClassNext.">" ? $temp_menu."\n</ul>" : '';
}

function combined_POST_cleaning($val) {
	$val = clean_slweg($val);
	$val = remove_unsecure_rptags($val);
	return $val;
}

function get_fe_userinfo($forum_userID) {
	// get frontend userinformation
	$forum_userID = intval($forum_userID);
	$got_the_info = false;
	if($forum_userID != 0 && (!isset($GLOBALS['FE_USER']) || !isset($GLOBALS['FE_USER'][$forum_userID]))) {
		//connect to user db and get information
		$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_user WHERE usr_id=".$forum_userID." LIMIT 1";
		if($result = mysql_query($sql, $GLOBALS['db'])) {
			if($row = mysql_fetch_assoc($result)) {
				$GLOBALS['FE_USER'][$forum_userID] = array(
					'FE_ID'		=> $forum_userID,		'login'	=> $row['usr_login'],
					'pass'		=> $row['usr_pass'],	'email'	=> $row['usr_email'],
					'admin'		=> $row['usr_admin'],	'fe'	=> $row['usr_fe'],
					'aktiv'		=> $row['usr_aktiv'],	'name'	=> $row['usr_name'],
					'lang'		=> empty($row['usr_lang']) ? $GLOBALS['phpwcms']['default_lang'] : $row['usr_lang'],
					'wysiwyg'	=> $row['usr_wysiwyg']
				);
				$got_the_info = true;
			}
			mysql_free_result($result);
		}
	} else {
		$got_the_info = true;
	}
	if(($forum_userID === 0 && !isset($GLOBALS['FE_USER'][$forum_userID])) || !$got_the_info) {
		$forum_userID = 0;
		$GLOBALS['FE_USER'][$forum_userID] = array(
					'FE_ID'		=> $forum_userID,		'login'	=> 'guest',
					'pass'		=> '',					'email'	=> 'noreply@localhost',
					'admin'		=> 0,					'fe'	=> 0,
					'aktiv'		=> 1,					'name'	=> 'Guest',
					'lang'		=> $GLOBALS['phpwcms']['default_lang'],
					'wysiwyg'	=> $GLOBALS['phpwcms']['wysiwyg_editor']
				);
	}
}

function highlightSearchResult($string='', $search, $wrap='<em class="highlight">|</em>') {
	// string will be highlighted by $search - can be string or array
	if(!empty($string) && !empty($search)) {

		// make $wrap[0] prefix and $wrap[1] suffix
		$wrap = explode('|', $wrap);
		if(empty($wrap[1])) $wrap[1] = '';
		$highlight_match = '';

		// make all search values array fields
		if(is_array($search)) {
			// make unique
			$search = array_unique($search);
		} else {
			$search = array(strval($search));
		}
		foreach($search as $key => $value) {
			if($highlight_match != '') $highlight_match .= '|';
			$highlight_match .= preg_quote($value, '/');
		}
		$highlight_match = str_replace("\\?", '.?', $highlight_match);
		$highlight_match = str_replace("\\*", '.*', $highlight_match);
		$highlight_match = trim($highlight_match);
		//$string = preg_replace('/(?!<.*)(?<!\w)(' . $highlight_match . ')(?!\w|[^<>]*>)/is', $wrap[0]."$1".$wrap[1], $string);

		if(false == preg_match('/<.+>/', $string)) {
			$string = preg_replace('/('.$highlight_match.')/i', $wrap[0].'$1'.$wrap[1], $string);
		} else {
			$string = preg_replace('/(?<=>)([^<]+)?('.$highlight_match.')/i', '$1'.$wrap[0].'$2'.$wrap[1], $string);
		}

	}
	return $string;
}
function pregReplaceHighlightWrapper($matches) {
	// just a wrapper for frontend sectional highlighting
	global $highlight_words;
	return highlightSearchResult($matches[1], $highlight_words, '<em class="highlight">|</em>');
}

function buildCascadingMenu($parameter='', $counter=0, $param='string') {

	// @string $parameter = "menu_type, start_id, max_level_depth, class_path, class_active,
	// ul_id_name, wrap_ul_div(0 = off, 1 = <div>, 2 = <div id="">, 3 = <div class="navLevel-0">),
	// wrap_link_text(<em>|</em>)"

	if($param == 'string') {

		$parameter 		= explode(',', $parameter);
		$menu_type		= empty($parameter[0]) ? '' : strtoupper(trim($parameter[0]));

		$unfold 		= 'all';
		$ie_patch		= false; // unused at the moment
		$create_css 	= false;
		$parent			= false; // do not show parent link

		switch($menu_type) {

							// show parent level too
			case 'P':		$parent = true;
							break;

							// vertical, active path unfolded
			case 'FP':		$parent = true;
			case 'F':		$unfold = 'active_path';
							break;
							
							// horizontal, all levels unfolded, add special code for horizontal flyout menu
			case 'HCSSP':	$parent		= true;
			case 'HCSS':	$create_css	= true;
							break;

							// horizontal, all levels unfolded, add special code for vertical flyout menu
			case 'VCSSP':	$parent		= true;
			case 'VCSS':	$create_css = true;
							break;

		}

		$start_id		= empty($parameter[1]) ? 0  : intval($parameter[1]);
		$max_depth		= empty($parameter[2]) ? 0  : intval($parameter[2]);
		$path_class 	= empty($parameter[3]) ? '' : trim($parameter[3]);
		$active_class	= empty($parameter[4]) ? '' : trim($parameter[4]);
		$level_id_name	= empty($parameter[5]) ? '' : trim($parameter[5]);
		$wrap_ul_div	= empty($parameter[6]) ? 0  : intval($parameter[6]);
		if($wrap_ul_div > 3) {
			$wrap_ul_div = 2;
		} elseif($wrap_ul_div < 0) {
			$wrap_ul_div = 0;
		}
		$wrap_link_text	= empty($parameter[7]) ? array(0 => '', 1 => '') : explode('|', $parameter[7]);
		if(empty($wrap_link_text[1])) {
			$wrap_link_text[1] = '';
		}

		$parameter		= array(	 0 => $menu_type, 		 1 => $start_id, 		2 => $max_depth,
									 3 => $path_class,		 4 => $active_class, 	5 => $level_id_name,
									 6 => $wrap_ul_div,		 7 => $wrap_link_text,	8 => $unfold,
									 9 => $ie_patch,		10 => $create_css	);
	} else {

		$menu_type		= $parameter[0];
		$start_id		= $parameter[1];
		$max_depth		= $parameter[2];
		$path_class 	= $parameter[3];
		$active_class	= $parameter[4];
		$level_id_name	= $parameter[5];
		$wrap_ul_div	= $parameter[6];
		$wrap_link_text	= $parameter[7];
		$unfold			= $parameter[8];
		$ie_patch		= $parameter[9];
		$create_css 	= $parameter[10];
		
		$parent			= false; // do not show parent link

	}

	$li				= '';
	$ul				= '';
	$TAB			= str_repeat('	', $counter);
	$_menu_type		= strtolower($menu_type);
	$max_depth		= ($max_depth == 0 || $max_depth-1 > $counter) ? true : false;

	foreach($GLOBALS['content']['struct'] as $key => $value) {

		//if($GLOBALS['content']['struct'][$key]['acat_struct'] == $start_id && $key && (!$GLOBALS['content']['struct'][$key]['acat_hidden'] || ($GLOBALS['content']['struct'][$key]["acat_hidden"] == 2 && isset($GLOBALS['LEVEL_KEY'][$key])))) {
		if( _getStructureLevelDisplayStatus($key, $start_id) ) {

			$li_ul 		= '';
			$li_class	= '';
			$li_ie		= '';

			$li_a  = get_level_ahref($key);
			$li_a .= $wrap_link_text[0];
			$li_a .= html_specialchars($GLOBALS['content']['struct'][$key]['acat_name']);
			$li_a .= $wrap_link_text[1];

			if($max_depth && ($unfold == 'all' || ($unfold == 'active_path' && isset($GLOBALS['LEVEL_KEY'][$key]))) ) {
				$parameter[1] = $key;
				$li_ul = buildCascadingMenu($parameter, $counter+1, 'param_is_array');
			}

			$li .= $TAB.'	<li';

			if($level_id_name) {
				$li .= ' id="li_'.$level_id_name.'_'.$key.'"';
			}
			if($li_ul) {
				$li_class	= 'sub_ul';
			} else {
				$li_class	= getHasSubStructureStatus($key) ? 'sub_no sub_ul_true' : 'sub_no';
			}
			if($path_class != '' && isset($GLOBALS['LEVEL_KEY'][$key])) {
				$li_class .= ' '.$path_class;
				$li_class  = trim($li_class);
			}
			if($active_class != '' && $key == $GLOBALS['aktion'][0]) {
				$li_class = trim($li_class.' '.$active_class);
			}

			//if($li_class) {
			$li .= ' class="'.$li_class.'"';
			//}

			$li .= '>' . $li_a . '</a>';

			$li .= $li_ul.'</li>'.LF; // remove $li_ul from this line of code if $ie_patch is used
		}
	}
				// also check if $parent
	if($li || ($parent && isset($GLOBALS['content']['struct'][$start_id]))) {

		switch($wrap_ul_div) {
			case 1:		$ul = LF.$TAB.'<div>';
						$close_wrap_ul = '</div>'.LF.$TAB;
						break;
			case 2:		$ul = LF.$TAB.'<div id="ul_div_'.$start_id.'">';
						$close_wrap_ul = '</div>'.LF.$TAB;
						break;
			case 3:		$ul = LF.$TAB.'<div class="navLevel-'.$counter.'">';
						$close_wrap_ul = '</div>'.LF.$TAB;
						break;
			default:	$ul = '';
						$close_wrap_ul = '';
		}
		$ul .= LF.$TAB.'<ul';
		if($level_id_name) {
			$ul .= ' id="'.$level_id_name.'_'.$start_id.'"';
		}
		if(isset($GLOBALS['LEVEL_KEY'][$start_id]) && $path_class) {
			$ul .= ' class="'.$path_class.'"';
		}
		$ul .= '>'.LF;
		
		if($parent && isset($GLOBALS['content']['struct'][$start_id])) {
		
			$ul .= LF;
			$ul .= $TAB.'	<li';
			if($level_id_name) {
				$ul .= ' id="li_'.$level_id_name.'_'.$start_id.'"';
			}
			$li_class	= 'sub_parent';
			if($path_class != '' && isset($GLOBALS['LEVEL_KEY'][$start_id])) {
				$li_class .= ' '.$path_class;
				$li_class  = trim($li_class);
			}
			if($active_class != '' && $start_id == $GLOBALS['aktion'][0]) {
				$li_class = trim($li_class.' '.$active_class);
			}
			$ul .= ' class="'.$li_class.'">';
			$ul .= get_level_ahref($start_id);
			$ul .= $wrap_link_text[0];
			$ul .= html_specialchars($GLOBALS['content']['struct'][$start_id]['acat_name']);
			$ul .= $wrap_link_text[1];
			$ul .= '</a></li>'.LF;
					
		}
		
		$ul .= $li;
		$ul .= $TAB . '</ul>' . LF . $TAB . $close_wrap_ul;

		if($create_css && empty($GLOBALS['block']['custom_htmlhead'][$menu_type][$counter])) {

			if($counter) {

				$tmp_css  = '    .'.$_menu_type.'_menu ul li:hover '.str_repeat('ul ', $counter) .'ul { display: none; }'.LF;
				$tmp_css .= '    .'.$_menu_type.'_menu ul '.str_repeat('ul ', $counter) .'li:hover ul { display: block; }';
				$GLOBALS['block']['custom_htmlhead'][$menu_type][$counter] = $tmp_css;

			} else {  //if($counter == 0) {

				$GLOBALS['block']['custom_htmlhead'][$menu_type][-9]  = LF.'  <style type="text/css">'.LF.SCRIPT_CDATA_START;
				$GLOBALS['block']['custom_htmlhead'][$menu_type][-8]  = '    @import url("'.TEMPLATE_PATH.'inc_css/specific/nav_list_ul_'.$_menu_type.'.css");';

				$GLOBALS['block']['custom_htmlhead'][$menu_type][-5]  = '    .'.$_menu_type.'_menu ul ul { display: none; }';
				$GLOBALS['block']['custom_htmlhead'][$menu_type][-4]  = '    .'.$_menu_type.'_menu ul li:hover ul { display: block; }';

				ksort($GLOBALS['block']['custom_htmlhead'][$menu_type]);
				$GLOBALS['block']['custom_htmlhead'][$menu_type][]   = SCRIPT_CDATA_END.LF.'  </style>';
				$GLOBALS['block']['custom_htmlhead'][$menu_type]   = implode(LF, $GLOBALS['block']['custom_htmlhead'][$menu_type]);

				$ul = '<div class="'.$_menu_type.'_menu">'.$ul.'</div>';

			}

		}

	}

	return $ul;
}

function get_level_ahref($key=0, $custom_link_add='') {
	$link = '<a href="';
	//maybe later... if(!isset($GLOBALS['content']['struct'][$key]))
	if(!$GLOBALS['content']['struct'][$key]["acat_redirect"]) {
		$link .= 'index.php?';
		if($GLOBALS['content']['struct'][$key]['acat_alias']) {
			$link .= $GLOBALS['content']['struct'][$key]['acat_alias'];
		} else {
			$link .= 'id='.$key.',0,0,1,0,0';
		}
		$link .= '"';
	} else {
		$redirect = get_redirect_link($GLOBALS['content']['struct'][$key]["acat_redirect"], ' ', '');
		$link .= html_specialchars($redirect['link']).'"'.$redirect['target'];
	}
	return $link.$custom_link_add.'>';
}

function getHasSubStructureStatus($level_id=0) {
	if( !isset($GLOBALS['content']['struct'][$level_id]) ) {
		return false;
	}
	foreach($GLOBALS['content']['struct'] as $key => $value) {
		//if($GLOBALS['content']['struct'][$key]['acat_struct'] == $level_id && $key && (!$GLOBALS['content']['struct'][$key]['acat_hidden'] || ($GLOBALS['content']['struct'][$key]["acat_hidden"] == 2 && isset($GLOBALS['LEVEL_KEY'][$key])))) {
		if( _getStructureLevelDisplayStatus($key, $level_id) ) {
			return true;
		}
	}
	return false;
}

function getStructureChildData($level_id=0) {
	if( !isset($GLOBALS['content']['struct'][$level_id]) ) return array();
	$struct_data = array();
	foreach($GLOBALS['content']['struct'] as $key => $value) {
		//if($GLOBALS['content']['struct'][$key]['acat_struct'] == $level_id && $key	&& (!$GLOBALS['content']['struct'][$key]['acat_hidden'] || ($GLOBALS['content']['struct'][$key]["acat_hidden"] == 2 && isset($GLOBALS['LEVEL_KEY'][$key])))) {
		if( _getStructureLevelDisplayStatus($key, $level_id) ) {
			$struct_data[$key]	= $value;
		}
	}
	return $struct_data;
}

function getStructureChildEntryHref($childData) {

	$a = array('link'=>'', 'target'=>'');
	if(!$childData["acat_redirect"]) {
		$a['link'] .= 'index.php?';
		$a['link'] .= $childData['acat_alias'] ? $childData['acat_alias'] : 'id='.$childData['acat_id'].',0,0,1,0,0';
	} else {
		$redirect = get_redirect_link($childData["acat_redirect"], ' ', '');
		$a['link']   .= $redirect['link'];
		$a['target'] .= $redirect['target'];
	}
	return $a;

}

function getImageCaption($caption='', $array_index='NUM') {
	// splits given image caption and returns an array
	$caption	= explode('|', $caption);

	// following is default for the exploded $caption
	// [0] caption text
	// [1] alt text for image
	// [2] link -> array(0 => link, 1 => target)
	// [3] title text -> if empty alt text will be used
	$caption[0]			= trim($caption[0]);
	$caption[1]			= isset($caption[1]) ? trim($caption[1]) : '';
	$caption[2]			= isset($caption[2]) ? explode(' ', trim($caption[2])) : array(0 => '', 1 => '');
	$caption[2][0]		= trim($caption[2][0]);
	if(empty($caption[2][0]) || empty($caption[2][1])) {
		$caption[2][1]	= '';
	} else {
		$caption[2][1]	= trim($caption[2][1]);
		$caption[2][1]	= empty($caption[2][1]) ? '' : ' target="'.$caption[2][1].'"';
	}
	$caption[3]			= isset($caption[3]) ? trim($caption[3]) : $caption[1];
	if($array_index == 'NUM') {
		return $caption;
	} else {
		return array(	'caption_text'		=> $caption[0],		'caption_alt'		=> $caption[1],
						'caption_link'		=> $caption[2][0],	'caption_target'	=> $caption[2][1],
						'caption_title'		=> $caption[3]		);
	}

}

function getClickZoomImageParameter($string='', $getvar='show') {
	$string = base64_encode($string);
	$string = rawurlencode($string);
	return $getvar.'='.$string;
}

function getPageInfoGetValue($type='string') {
	// type can be
	// 'string' -> 'pageinfo=/...';
	// 'array' -> array('pageinfo'=>'/...')

	return ($type == 'string') ? 'pageinfo=' : array('pageinfo'=>'');
}

function initializeLightbox() {

	// SlimBox 1.3
	$GLOBALS['block']['custom_htmlhead']['lightbox.css']	= '  <link rel="stylesheet" href="'.TEMPLATE_PATH.'slimbox/css/slimbox.css" type="text/css" media="screen" />';
	$GLOBALS['block']['custom_htmlhead']['mootools.js']		= '  <script src="'.TEMPLATE_PATH.'inc_js/mootools/mootools.js" type="text/javascript"></script>';
	$GLOBALS['block']['custom_htmlhead']['slimbox.js']		= '  <script src="'.TEMPLATE_PATH.'slimbox/js/slimbox.js" type="text/javascript"></script>';

}

function _getFeUserLoginStatus() {
	$login_key = session_id();
	if(empty($login_key)) {
		return false;	// user is not logged in
	} elseif(empty($_SESSION[$login_key])) {
		return false;	// this is the false session and/or false user
	} elseif(isset($_GET[$login_key])) {
		return false;	// hm, somebody is trying to inject by GET in case register_globals ON
	} elseif(isset($_POST[$login_key])) {
		return false;	// hm, somebody is trying to inject by POST in case register_globals ON
	}
	return true;
}

function _checkFrontendUserLogin($user='', $pass='', $validate_db=array('userdetail'=>1, 'backenduser'=>1)) {
	if(empty($user) || empty($pass)) return false;
	// check against database
	if(!empty($validate_db['userdetail'])) {
		$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_userdetail WHERE ';
		$sql .= "detail_login='".aporeplace($user)."' AND ";
		$sql .= "detail_password='".aporeplace($pass)."' AND ";
		$sql .= "detail_aktiv=1 LIMIT 1";
		$result = _dbQuery($sql);
	}
	// hm, seems no user found - OK test against cms users
	if(!empty($validate_db['backenduser']) && !isset($result[0])) {
		$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_user ';
		$sql .= 'LEFT JOIN '.DB_PREPEND.'phpwcms_userdetail ON ';
		$sql .= 'usr_id = detail_pid WHERE ';
		$sql .= "usr_login='".aporeplace($user)."' AND ";
		$sql .= "usr_pass='".aporeplace($pass)."' AND ";
		$sql .= "usr_aktiv=1 AND usr_fe IN (0,2) LIMIT 1";
		$result = _dbQuery($sql);
	}
	return (isset($result[0]) && is_array($result)) ? $result[0] : false;
}

function _getFrontendUserBaseData(& $data) {
	// use vaid user data to set some base fe user data
	// like name, login, email
	$userdata = array('login'=>'', 'name'=>'', 'email'=>'', 'url'=>'', 'source'=>'', 'id'=>0);

	if(isset($data['usr_login'])) {
		$userdata['login']	= $data['usr_login'];
		$userdata['name']	= $data['usr_name'];
		$userdata['email']	= $data['usr_email'];
		$userdata['source']	= 'BACKEND';
		$userdata['id']		= $data['usr_id'];
		if(trim($data['detail_firstname'].$data['detail_lastname'].$data['detail_company'])) {
			$t							= trim($data['detail_firstname'].' '.$data['detail_lastname']);
			if(empty($t))	$t			= trim($data['detail_company']);
			if($t) $userdata['name']	= $t;
			$userdata['url']			= trim($data['detail_website']);
		}		
	} elseif($data['detail_login']) {
		$t					= trim($data['detail_firstname'].' '.$data['detail_lastname']);
		if(empty($t)) $t	= $data['detail_company'];
		$userdata['login']	= $data['detail_login'];
		$userdata['name']	= $t;
		$userdata['email']	= $data['detail_email'];
		$userdata['url']	= $data['detail_website'];
		$userdata['source']	= 'PROFILE';
		$userdata['id']		= $data['detail_id'];
	}
	return $userdata;
}

function _checkFrontendUserAutoLogin() {
	if(!_getFeUserLoginStatus() && session_id() && !empty($_COOKIE['phpwcmsFeLoginRemember']) && !isset($_POST['phpwcmsFeLoginRemember']) && !isset($_GET['phpwcmsFeLoginRemember'])) {
		$_loginData = explode('##-|-##', $_COOKIE['phpwcmsFeLoginRemember']);
		if(!empty($_loginData[0]) && !empty($_loginData[1])) {
			$_loginData['validate_db']['userdetail']	= empty($_loginData[2]) ? 0 : 1;
			$_loginData['validate_db']['backenduser']	= empty($_loginData[3]) ? 0 : 1;
			$_loginData['query_result'] = _checkFrontendUserLogin($_loginData[0], $_loginData[1], $_loginData['validate_db']);
			if($_loginData['query_result'] !== false && is_array($_loginData['query_result']) && count($_loginData['query_result'])) {
				if(isset($_loginData['query_result']['usr_login'])) {
					$_SESSION[ session_id() ] = $_loginData['query_result']['usr_login'];
				} elseif($_loginData['query_result']['detail_login']) {
					$_SESSION[ session_id() ] = $_loginData['query_result']['detail_login'];
				}
				$_SESSION[ session_id().'_userdata'] = _getFrontendUserBaseData($_loginData['query_result']);
			} else {
				unset($_COOKIE['phpwcmsFeLoginRemember']);
			}
		}
	}
	// logout
	if(session_id() && (isset($_POST['feLogout']) || isset($_GET['feLogout']))) {
		unset($_SESSION[ session_id() ]); 
		setcookie('phpwcmsFeLoginRemember', '', time()-3600, '/',  getCookieDomain() );
	}
	define('FEUSER_LOGIN_STATUS', _getFeUserLoginStatus() );
}

function _getStructureLevelDisplayStatus(&$level_ID, &$current_ID) {
	if($GLOBALS['content']['struct'][$level_ID]['acat_struct'] == $current_ID && $level_ID) {
		if($GLOBALS['content']['struct'][$level_ID]['acat_regonly'] && !FEUSER_LOGIN_STATUS) {
			return false;
		}
		if(empty($GLOBALS['content']['struct'][$level_ID]['acat_hidden'])) {
			return true;
		} elseif($GLOBALS['content']['struct'][$level_ID]["acat_hidden"] == 2 && isset($GLOBALS['LEVEL_KEY'][$level_ID])) {
			return true;
		}
		return false;
	}
	return false;
}

function setPageTitle($pagetitle, $cattitle, $articletitle, $title_order=NULL) {
	// get default pagetitle order value;
	if($title_order === NULL) {
		$title_order = empty($GLOBALS['pagelayout']['layout_title_order']) ? 0 : intval($GLOBALS['pagelayout']['layout_title_order']);
	}	
	if(empty($GLOBALS['pagelayout']['layout_title_spacer'])) {
		$title_spacer = ' | ';
		$GLOBALS['pagelayout']['layout_title_spacer'] = $title_spacer;
	} else {
		$title_spacer = $GLOBALS['pagelayout']['layout_title_spacer'];
	}
	switch($title_order) {
	
		case 1:		$title = array($pagetitle, $articletitle, $cattitle); break;
		case 2:		$title = array($cattitle, $articletitle, $pagetitle); break;
		case 3:		$title = array($cattitle, $pagetitle, $articletitle); break;
		case 4:		$title = array($articletitle, $cattitle, $pagetitle); break;
		case 5:		$title = array($articletitle, $pagetitle, $cattitle); break;

		case 6:		$title = array($pagetitle, $cattitle); 		break;
		case 7:		$title = array($pagetitle, $articletitle); 	break;
		case 8:		$title = array($cattitle, $articletitle); 	break;
		case 9:		$title = array($cattitle, $pagetitle); 		break;
		case 10:	$title = array($articletitle, $cattitle); 	break;
		case 11:	$title = array($articletitle, $pagetitle); 	break;

		case 12:	$title = array($pagetitle); 	break;
		case 13:	$title = array($cattitle); 		break;
		case 14:	$title = array($articletitle); 	break;
		
		default:	$title = array($pagetitle, $cattitle, $articletitle);
	
	}
	$title = array_diff($title, array('', NULL, false));
	$title = trim(implode($title_spacer, $title));
	return ($title === '') ? $pagetitle : $title;
}

function sanitize_replacement_tags( $string, $rt='', $bracket=array('{}', '[]') ) {
	if( $string === '' ) return '';
	if( is_string($bracket) ) {
		$bracket = array($bracket);
	}
	$tag = array();
	if($rt === '') {
		$tag[] = array('.*?', '.*?');
	} elseif( is_array($rt) ) {
		foreach($rt as $value) {
			$value = trim($value);
			if($value === '') continue;
			$tag[] = array($value . '.*?', $value);
		}
	} elseif( is_string($rt) ) {
		$rt = trim($rt);
		if($rt) {
			$tag[] = array($rt . '.*?', $rt);
		}
	}
	if( is_array($bracket) && count($bracket) && count($tag) ) {
		foreach($bracket as $value) {
			if(strlen($value) < 2) continue;
			$prefix = preg_quote($value{0});
			$suffix = preg_quote($value{1});
			foreach($tag as $row) {
				$string = preg_replace('/' . $prefix . $row[0] . $suffix . '(.*?)' . $prefix . '\/' . $row[1] . $suffix . '/si', '$1', $string);
			}
		}
	}
	return $string;
}

function parseLightboxCaption($caption='') {
	if(empty($caption)) return '';
	$caption = html_parser($caption);
	return html_specialchars($caption);
}

function get_article_morelink(& $article) {
	if($article['article_redirect'] && strpos($article['article_redirect'], ' ')) {
		$link = explode(' ', $article['article_redirect']);
		if($link[0]) {
			$link[0] = str_replace('{SITE}', PHPWCMS_URL, $link[0]);
		}
		if(empty($link[1])) $link[1] = '';
	} else {
		$link[0] = 'index.php?aid='.$article['article_id'];
		$link[1] = '';
	}
	return $link;
}

?>