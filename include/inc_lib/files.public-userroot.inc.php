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



//Listing eventuell im Verzeichnis enthaltener Dateien
$file_sql = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_pid=0 AND f_uid=".$root_user_id.
			" AND f_public=1 AND f_aktiv=1 AND f_kid=1 AND f_trash=0 ORDER BY f_name;";
			
if($file_result = mysql_query($file_sql, $db) or die ("error while listing files")) {
	$file_durchlauf = 0;
	while($file_row = mysql_fetch_array($file_result)) {
		$filename = html_specialchars($file_row["f_name"]);
		if(!$file_durchlauf) { //Aufbau der Zeile zum Einflie�en der Filelisten-Tabelle
			echo "<tr bgcolor=\"#F5F8F9\"><td colspan=\"2\"><table width=\"538\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n"; 
		} else {
			echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"5\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\"></td></tr>\n";
		}
		echo "<tr>\n";
		echo "<td width=\"55\" class=\"msglist\"><img src=\"img/leer.gif\" height=\"1\" width=\"55\" border=\"0\"></td>\n";
		echo "<td width=\"13\" class=\"msglist\">";
		echo "<img src=\"img/icons/small_".extimg($file_row["f_ext"])."\" border=\"0\"></td>\n";
		echo "<td width=\"455\" class=\"msglist\"><img src=\"img/leer.gif\" height=\"1\" width=\"5\">";
		echo "<a href=\"fileinfo.php?fid=".$file_row["f_id"];
		echo "\" target=\"_blank\" onclick=\"flevPopupLink(this.href,'filedetail','scrollbars=yes,resizable=yes,width=500,height=400',1);return document.MM_returnValue;\">";
		echo $filename."</a>";
		//Wenn f�r das Public File keine Vorschau existiert und Extension passt
		/*
		if(isEmpty($file_row["f_thumb_preview"]) && is_ext_true(strtolower($file_row["f_ext"]))) {
			$file_makethumb	= "thumb=".$file_row["f_id"]."&ext=".$file_row["f_ext"]."&fcat=".$file_row["f_uid"];
			echo " <a href=\"include/inc_act/act_imagick.php?".$file_makethumb."\" ";
			echo " title=\"".$BL['be_ftptakeover_createthumb'].": ".$filename."\">";
			echo " <img src=\"img/button/create_thumbnail_small.gif\" border=\"0\"></a>";
		}
		*/
		echo "</td>\n";

		echo "<td width=\"15\" align=\"right\" class=\"msglist\">";
		echo "<a href=\"include/inc_act/act_download.php?dl=".$file_row["f_id"];
		echo "\" target=\"_blank\" title=\"".$BL['be_fprivfunc_dlfile'].": ".$filename."\" target=\"_blank\">";
		echo "<img src=\"img/button/download_disc.gif\" border=\"0\"></a>";
		echo "<img src=\"img/leer.gif\" width=\"2\" height=\"1\">"; //Spacer
		echo "</td>\n";
		//Ende Aufbau
		echo "</tr>\n";
		
		
		if($_SESSION["wcs_user_thumb"]) {
		
			$thumb_image = get_cached_image(
							array(	"target_ext"	=>	$file_row["f_ext"],
									"image_name"	=>	$file_row["f_hash"] . '.' . $file_row["f_ext"],
									"thumb_name"	=>	md5($file_row["f_hash"].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"])
								  )
							);

			if($thumb_image != false) {
				echo "<tr>\n";
				echo "<td width=\"55\"><img src=\"img/leer.gif\" height=\"1\" width=\"55\" border=\"0\"></td>\n";
				echo "<td width=\"13\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\" border=\"0\"></td>\n<td width=\"";
				echo "505\"><img src=\"img/leer.gif\" height=\"1\" width=\"6\"><a href=\"fileinfo.php?fid=";
				echo $file_row["f_id"]."\" target=\"_blank\" onclick=\"flevPopupLink(this.href,'filedetail','scrollbars=";
				echo "yes,resizable=yes,width=500,height=400',1); return document.MM_returnValue;\">";
				//echo "<img src=\"".$phpwcms["file_tmp"].$phpwcms["dir_thlist"].$file_row["f_thumb_list"]."\" border=\"0\"></a></td>\n";
				echo '<img src="'.PHPWCMS_IMAGES . $thumb_image[0] .'" border="0" '.$thumb_image[3]."></a></td>\n";
				echo "<td width=\"15\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\" border=\"0\"></td>\n</tr>\n";
				echo "<tr><td colspan=\"4\"><img src=\"img/leer.gif\" height=\"2\" width=\"1\" border=\"0\"></td>\n</tr>\n";			
			}
			
		}
		/*
		if($file_row["f_thumb_list"] && $show_thumb) { //Wenn List Preview Image verf�gbar
			echo "<tr>\n";
			echo "<td width=\"55\"><img src=\"img/leer.gif\" height=\"1\" width=\"55\" border=\"0\"></td>\n";
			echo "<td width=\"13\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\" border=\"0\"></td>\n<td width=\"";
			echo "505\"><img src=\"img/leer.gif\" height=\"1\" width=\"6\"><a href=\"fileinfo.php?fid=";
			echo $file_row["f_id"]."\" target=\"_blank\" onclick=\"flevPopupLink(this.href,'filedetail','scrollbars=";
			echo "yes,resizable=yes,width=500,height=400',1); return document.MM_returnValue;\">";
			echo "<img src=\"".$phpwcms["file_tmp"].$phpwcms["dir_thlist"].$file_row["f_thumb_list"]."\" border=\"0\"></a></td>\n";
			echo "<td width=\"15\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\" border=\"0\"></td>\n</tr>\n";
			echo "<tr><td colspan=\"4\"><img src=\"img/leer.gif\" height=\"2\" width=\"1\" border=\"0\"></td>\n</tr>\n";
		}
		*/
		$file_durchlauf++;
	}
	if($file_durchlauf) { //Abschluss der Filelisten-Tabelle
		echo "</table>\n";
		echo "<tr><td colspan=\"2\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\"></td></tr>\n";
	}
} //Ende Liste Dateien
?>