<?php

if(!empty($step)) {

	if ($step == 1 && $do) {
	
		if(!empty($_POST['user_account'])) {
			
			// fine continue with step 2		
			session_write_close();
			if(!empty($_SERVER['HTTP_HOST']) && !empty($_SERVER['REQUEST_URI'])) {
				header('Location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['REQUEST_URI']).'/setup.php?step=2');
			} else {
				header("Location: setup.php?step=2");
			}
			exit();
		}
		
		
		//superuser settings
		if(isset($_POST['admin_name'])) {
		
			$phpwcms['admin_name']		= empty($_POST['admin_name']) ? $phpwcms['admin_name'] : slweg($_POST['admin_name']);
			$phpwcms['admin_user']		= empty($_POST['admin_user']) ? $phpwcms['admin_user'] : slweg($_POST['admin_user']);
			
			if($_POST["admin_pass"] !== $_POST["admin_passrepeat"] || empty($phpwcms["admin_pass"])) {
				$admin_err_pass			= 1;
			} elseif(!empty($_POST["admin_pass"])) {
				$phpwcms["admin_pass"]	= md5(slweg($_POST["admin_pass"]));
			}			
			
			$phpwcms["admin_email"]		= clean_slweg($_POST["admin_email"]);
		
			if(empty($admin_err_pass) && empty($_SESSION['admin_save'])) {
				write_conf_file($phpwcms);
				$_SESSION['admin_save']	= 1;
			}
		
		}
		
		// main settings
		
		$phpwcms["db_host"]           = slweg($_POST["db_host"]);
		$phpwcms["db_user"]           = slweg($_POST["db_user"]);
		$phpwcms["db_pass"]           = slweg($_POST["db_pass"]);
		$phpwcms["db_table"]          = slweg($_POST["db_table"]);
		$phpwcms["db_prepend"]        = slweg($_POST["db_prepend"]);
		$phpwcms["db_pers"]           = empty($_POST["db_pers"]) ? 0 : 1;		
	
		if(isset($_POST["charset"])) {
		
			$_POST["charset"]			= clean_slweg($_POST["charset"]);
			
			$phpwcms["charset"]			= explode('-', $available_languages[ $_POST["charset"] ][1], 2);
			$phpwcms["charset"]			= $phpwcms["charset"][1];
			$phpwcms['db_charset']      = $mysql_charset_map[$phpwcms["charset"]];
			$phpwcms['default_lang']	= substr($_POST["charset"], 0, 2);
			$phpwcms['db_collation'] 	= $phpwcms['db_charset'].'_bin';
			
			if(isset($_POST['collation'])) {
			
				$_POST['collation']			= clean_slweg($_POST['collation']);
				$phpwcms['db_collation'] 	= $_POST['collation'];
				
				// if collation is not part of the db charset set to "_bin" default
				if(strpos($phpwcms['db_collation'], $phpwcms['db_charset'].'_') !== 0) {
					$phpwcms['db_collation'] 	= $phpwcms['db_charset'].'_bin';
				}
				
				// check if there is a difference!! and warn again
				if($phpwcms['db_collation'] != $_POST['collation']) {
					$_collation_warning = true;
					$_SESSION['admin_save'] = 0;
				} else {
					$_collation_warning = false;
					$db_sql = empty($_POST["db_sql"]) ? 0 : 1;	

				}
				
			} else {
			
				$_collation_warning = false;
				$db_sql = empty($_POST["db_sql"]) ? 0 : 1;
			
			}
		
		}
		write_conf_file($phpwcms);
		$err = 0;
		
		$prepend = $phpwcms["db_prepend"];
		
		
		if(isset($_POST["dbsavesubmit"])) {
		
			// make db connect
			if($phpwcms["db_pers"] == 1) {
				$db = @mysql_pconnect($phpwcms["db_host"], $phpwcms["db_user"], $phpwcms["db_pass"]);
			} else {
				$db = @mysql_connect($phpwcms["db_host"], $phpwcms["db_user"], $phpwcms["db_pass"]);
			}
			
			if(@mysql_select_db($phpwcms["db_table"], $db)) {;
	
				if($result = mysql_query("SELECT VERSION()", $db)) {
					
					if($row = mysql_fetch_row($result))	{
						$phpwcms["db_version"]		= explode('.', $row[0]);
						$phpwcms["db_version"][0]	= intval($phpwcms["db_version"][0]);
						$phpwcms["db_version"][1]	= empty($phpwcms["db_version"][1]) ? '00' : intval($phpwcms["db_version"][1]);
						$phpwcms["db_version"][2]	=  empty($phpwcms["db_version"][2]) ? '00' : intval($phpwcms["db_version"][2]);
						$phpwcms["db_version"]		= (int)sprintf('%d%02d%02d', $phpwcms["db_version"][0], $phpwcms["db_version"][1], $phpwcms["db_version"][2]);
						
						write_conf_file($phpwcms);
						
					}
					mysql_free_result($result);
					
					if($result = @mysql_query('SELECT * FROM '. ($phpwcms["db_prepend"] ? $phpwcms["db_prepend"].'_' : '').'phpwcms_user', $db)) {
					
						$_db_prepend_error = true;
						mysql_free_result($result);
					
					}
				
				} else {
				
					$err = 1;
					$_SESSION['admin_save'] = 0;
				
				}
				
			
			} else {
			
				$err = 1;
				$_SESSION['admin_save'] = 0;
			
			}
			
			
			// enable additional db settings like collation and charset
			if(empty($err)) {
				
				
				$db_additional = true;
				
				if(isset($_collation_warning) && $_collation_warning === false) {
					
					$db_init = true;
					
					if(isset($_POST['db_sql_hidden'])) {
					
						if(empty($db_sql)) {
						
							
							$_SESSION['admin_set']	= true;
							$db_no_create			= true;
							
						} else {
					
							// now read and display sql queries
							
							$_db_prepend = ($phpwcms["db_prepend"] ? $phpwcms["db_prepend"].'_' : '');
				
							$sql_data = 'default_sql/'.(($phpwcms['db_version'] > 40100) ? 'phpwcms_init_410.sql' : 'phpwcms_init_323.sql');
							$sql_data = read_textfile($sql_data);
							$sql_data = $sql_data . read_textfile('default_sql/phpwcms_inserts.sql');
							$sql_data = preg_replace("/(#|--).*.\n/", '', $sql_data );
							$sql_data = preg_replace('/ `phpwcms/', ' `'.$_db_prepend.'phpwcms', $sql_data );
							$sql_data = str_replace("\r", '', $sql_data);
							$sql_data = str_replace("\n\n", "\n", $sql_data);
							$sql_data = trim($sql_data);
							
							// if True create initial database
							if(isset($_POST['db_create'])) {
							
								$db_create_err = array();
								
								if($phpwcms['db_version'] > 40100) {
									$value = "SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO'";
									@mysql_query($value, $db);
									$value = "SET NAMES '".$phpwcms['db_charset']."'".(empty($phpwcms['db_collation']) ? '' : " COLLATE '".$phpwcms['db_collation']."'");
									@mysql_query($value, $db);
								}							
								
								$db_create_sql = explode(';', $sql_data);
								foreach($db_create_sql as $key => $value) {
								
									$value = trim($value);
									
									if(empty($value)) {
										unset($db_create_sql[$key]);
										continue;
									}
									
									if($phpwcms['db_version'] > 40100 && strpos(strtoupper($value), 'INSERT') !== 0) {
										$value .= ' DEFAULT';
										$value .= ' CHARACTER SET '.$phpwcms['db_charset'];
										$value .= ' COLLATE '.$phpwcms['db_collation'];
									}

									// send sql query
									if(!mysql_query($value, $db)) {
										$db_create_err[] = $value;
										unset($db_create_sql[$key]);
									}
								}
								
							}
						}
					}
				}
			}
		}
	}
	
	if ($step == 2 && $do) {
		
		$phpwcms["site"]           = clean_slweg($_POST["site"]);
		
		$phpwcms['SMTP_FROM_EMAIL']   = clean_slweg($_POST["smtp_from_email"]);
		if(!$phpwcms['SMTP_FROM_EMAIL']) $phpwcms['SMTP_FROM_EMAIL'] = $phpwcms["admin_email"];
		$phpwcms['SMTP_FROM_NAME']    = clean_slweg($_POST["smtp_from_name"]);
		if(!$phpwcms['SMTP_FROM_NAME']) $phpwcms['SMTP_FROM_NAME'] = 'webmaster';
		$phpwcms['SMTP_HOST']         = clean_slweg($_POST["smtp_host"]);
		if(!$phpwcms['SMTP_HOST']) $phpwcms['SMTP_HOST'] = 'localhost';
		$phpwcms['SMTP_PORT']         = intval($_POST["smtp_port"]);
		if(!$phpwcms['SMTP_PORT']) $phpwcms['SMTP_PORT'] = 25;
		$phpwcms['SMTP_MAILER']       = clean_slweg($_POST["smtp_mailer"]);
		if(!$phpwcms['SMTP_MAILER']) $phpwcms['SMTP_MAILER'] = 'mail';
		$phpwcms['SMTP_AUTH']         = empty($_POST["smtp_auth"]) ? 0 : 1;
		$phpwcms['SMTP_USER']         = slweg($_POST["smtp_user"]);
		$phpwcms['SMTP_PASS']         = slweg($_POST["smtp_pass"]);
		
		write_conf_file($phpwcms);
		
		if(!empty($_POST["admin_create"])) {
			$db = mysql_connect($phpwcms["db_host"],$phpwcms["db_user"],$phpwcms["db_pass"]);
			mysql_select_db($phpwcms["db_table"],$db);
			mysql_query("SET NAMES '".$phpwcms["charset"]."'", $db);
			$phpwcms["db_prepend"] = ($phpwcms["db_prepend"]) ? $phpwcms["db_prepend"]."_" : "";
			$sql =	"INSERT INTO ".$phpwcms["db_prepend"]."phpwcms_user (usr_login, usr_pass, usr_email, ".
					"usr_admin, usr_aktiv, usr_name, usr_fe, usr_wysiwyg ) VALUES ('".
					aporeplace($phpwcms["admin_user"])."', '".
					aporeplace(md5($phpwcms["admin_pass"]))."', '".
					aporeplace($phpwcms["admin_email"])."', 1, 1, '".aporeplace($phpwcms['SMTP_FROM_NAME'])."', 2, 2);";
			mysql_query($sql,$db) or $err = 1;
		}
		
		if(!$err) {
			header("Location: setup.php?step=3");
			exit();
		}
	}
	
	if ($step == 3 && $do) {
		
		$phpwcms['DOC_ROOT']       = clean_slweg($_POST["doc_root"]);
		$phpwcms["root"]           = clean_slweg($_POST["root"]);
		$phpwcms["file_path"]      = clean_slweg($_POST["file_path"]);
		$phpwcms["templates"]      = clean_slweg($_POST["templates"]);
		$phpwcms["ftp_path"]       = clean_slweg($_POST["ftp_path"]);
		
		$phpwcms["file_path"]      = ($phpwcms["file_path"]) ? $phpwcms["file_path"] : "phpwcms_filestorage";
		$phpwcms["templates"]      = ($phpwcms["templates"]) ? $phpwcms["templates"] : "phpwcms_template";
		$phpwcms["content_path"]   = ($phpwcms["content_path"]) ? $phpwcms["content_path"] : "content";
		$phpwcms["cimage_path"]    = ($phpwcms["cimage_path"]) ? $phpwcms["cimage_path"] : "images";
		$phpwcms["ftp_path"]       = ($phpwcms["ftp_path"]) ? $phpwcms["ftp_path"] : "phpwcms_ftp";
			
		write_conf_file($phpwcms);
		header("Location: setup.php?step=4");
		exit();
	}
	
	if ($step == 4 && $do) {
		$phpwcms["file_maxsize"]     = intval($_POST["file_maxsize"]);
		$phpwcms["content_width"]    = intval($_POST["content_width"]);
		$phpwcms["img_list_width"]   = intval($_POST["img_list_width"]);
		$phpwcms["img_list_height"]  = intval($_POST["img_list_height"]);
		$phpwcms["img_prev_width"]   = intval($_POST["img_prev_width"]);
		$phpwcms["img_prev_height"]  = intval($_POST["img_prev_height"]);
		$phpwcms["max_time"]         = intval($_POST["max_time"]);
		$phpwcms["compress_page"]    = empty($_POST["compress_page"]) ? 0 : 1;
		//$phpwcms["charset"]          = clean_slweg($_POST["charset"]);
		
		$phpwcms["file_maxsize"]     = ($phpwcms["file_maxsize"]) ? $phpwcms["file_maxsize"] : 2097152;
		$phpwcms["content_width"]    = ($phpwcms["content_width"]) ? $phpwcms["content_width"] : 538;
		$phpwcms["img_list_width"]   = ($phpwcms["img_list_width"]) ? $phpwcms["img_list_width"] : 100;
		$phpwcms["img_list_height"]  = ($phpwcms["img_list_height"]) ? $phpwcms["img_list_height"] : 75;
		$phpwcms["img_prev_width"]   = ($phpwcms["img_prev_width"]) ? $phpwcms["img_prev_width"] : 538;
		$phpwcms["img_prev_height"]  = ($phpwcms["img_prev_height"]) ? $phpwcms["img_prev_height"] : 400;
		$phpwcms["max_time"]         = ($phpwcms["max_time"]) ? $phpwcms["max_time"] : 1800;
		$phpwcms["compress_page"]    = ($phpwcms["compress_page"]) ? $phpwcms["compress_page"] : 0;
			
		write_conf_file($phpwcms);
		header("Location: setup.php?step=5");
		exit();
	}


}

?>