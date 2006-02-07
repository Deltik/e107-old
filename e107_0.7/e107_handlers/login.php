<?php

/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     ?Steve Dunstan 2001-2002
|     http://e107.org
|     jalist@e107.org
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $Source: /cvs_backup/e107_0.7/e107_handlers/login.php,v $
|     $Revision: 1.33 $
|     $Date: 2006-02-07 13:28:04 $
|     $Author: streaky $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

if(is_readable(e_LANGUAGEDIR.e_LANGUAGE."/lan_login.php")){
	@include_once(e_LANGUAGEDIR.e_LANGUAGE."/lan_login.php");
}else{
	@include_once(e_LANGUAGEDIR."English/lan_login.php");
}

class userlogin {
	function userlogin($username, $userpass, $autologin) {
		/* Constructor
		# Class called when user attempts to log in
		#
		# - parameters #1:                string $username, $_POSTED user name
		# - parameters #2:                string $userpass, $_POSTED user password
		# - return                                boolean
		# - scope                                        public
		*/
		global $pref, $e_event, $sql, $e107, $tp;
		$sql = new db;

		$fip = $e107->getip();
		if($sql -> db_Select("banlist", "*", "banlist_ip='{$fip}' ")) {
			exit;
		}

		$autologin = intval($autologin);

		if ($pref['auth_method'] && $pref['auth_method'] != "e107") {
			$auth_file = e_PLUGIN."alt_auth/".$pref['auth_method']."_auth.php";
			if (file_exists($auth_file)) {
				require_once(e_PLUGIN."alt_auth/alt_auth_login_class.php");
				$result = new alt_login($pref['auth_method'], $username, $userpass);
			}
		}

		if ($pref['logcode'] && extension_loaded("gd")) {
			require_once(e_HANDLER."secure_img_handler.php");
			$sec_img = new secure_image;
			if (!$sec_img->verify_code($_POST['rand_num'], $_POST['code_verify'])) {
				define("LOGINMESSAGE", LAN_303."<br /><br />");
				return FALSE;
			}
		}
		if ($username != "" && $userpass != "") {
			$username = preg_replace("/\sOR\s|\=|\#/", "", $username);
			$ouserpass = $userpass;
			$userpass = md5($ouserpass);

			$username = substr($username, 0, 30);

			// This is only required for upgrades and only for those not using utf-8 to begin with..
			if(isset($pref['utf-compatmode']) && (CHARSET == "utf-8" || CHARSET == "UTF-8")){
				$username = utf8_decode($username);
				$userpass = md5(utf8_decode($ouserpass));
			}

			if (!$sql->db_Select("user", "*", "user_loginname = '".$tp -> toDB($username)."'")) {
				define("LOGINMESSAGE", LAN_300."<br /><br />");
				$sql -> db_Insert("generic", "0, 'failed_login', '".time()."', 0, '{$fip}', 0, '".LAN_LOGIN_14." ::: ".LAN_LOGIN_1.": ".$tp -> toDB($username)."'");
				$this -> checkibr($fip);
				return FALSE;
			}
			else if(!$sql->db_Select("user", "*", "user_loginname = '".$tp -> toDB($username)."' AND user_password = '{$userpass}'")) {
				define("LOGINMESSAGE", LAN_300."<br /><br />");
				return FALSE;
			}
			else if(!$sql->db_Select("user", "*", "user_loginname = '".$tp -> toDB($username)."' AND user_password = '{$userpass}' AND user_ban!=2 ")) {
				define("LOGINMESSAGE", LAN_302."<br /><br />");
                	$sql -> db_Insert("generic", "0, 'failed_login', '".time()."', 0, '{$fip}', 0, '".LAN_LOGIN_15." ::: ".LAN_LOGIN_1.": ".$tp -> toDB($username)."'");
					$this -> checkibr($fip);
				return FALSE;
			} else {
				$ret = $e_event->trigger("preuserlogin", $username);
				if ($ret!='') {
					define("LOGINMESSAGE", $ret."<br /><br />");
					return FALSE;
				} else {
					$lode = $sql -> db_Fetch();
					$user_id = $lode['user_id'];
					$user_name = $lode['user_name'];
					$user_xup = $lode['user_xup'];

					/* restrict more than one person logging in using same us/pw */
					if($pref['disallowMultiLogin']) {
						if($sql -> db_Select("online", "online_ip", "online_user_id='".$user_id.".".$user_name."'")) {
							define("LOGINMESSAGE", LAN_304."<br /><br />");
							$sql -> db_Insert("generic", "0, 'failed_login', '".time()."', 0, '$fip', '$user_id', '".LAN_LOGIN_16." ::: ".LAN_LOGIN_1.": ".$tp -> toDB($username).", ".LAN_LOGIN_17.": ".md5($ouserpass)."' ");
							$this -> checkibr($fip);
							return FALSE;
						}
					}

					$cookieval = $user_id.".".md5($userpass);
					if($user_xup) {
						$this->update_xup($user_id, $user_xup);
					}

					if ($pref['user_tracking'] == "session") {
						$_SESSION[$pref['cookie_name']] = $cookieval;
					} else {
						if ($autologin == 1) {
							cookie($pref['cookie_name'], $cookieval, (time() + 3600 * 24 * 30));
						} else {
							cookie($pref['cookie_name'], $cookieval);
						}
					}
					$edata_li = array("user_id" => $user_id, "user_name" => $username);
					$e_event->trigger("login", $edata_li);
					$redir = (e_QUERY ? e_SELF."?".e_QUERY : e_SELF);
					if (strstr($_SERVER['SERVER_SOFTWARE'], "Apache")) {
						header("Location: ".$redir);
						exit;
					} else {
						echo "<script type='text/javascript'>document.location.href='{$redir}'</script>\n";
					}
				}
			}
		} else {
			define("LOGINMESSAGE", LAN_27."<br /><br />");
			return FALSE;
		}
	}

	function checkibr($fip) {
		global $sql,$pref;
		if($pref['autoban'] == 1 || $pref['autoban'] == 3){ // Flood + Login or Login Only.
	   		$fails = $sql -> db_Count("generic", "(*)", "WHERE gen_ip='$fip' AND gen_type='failed_login' ");
			if($fails > 10) {
				$sql -> db_Insert("banlist", "'$fip', '1', '".LAN_LOGIN_18."' ");
		   		$sql -> db_Insert("generic", "0, 'auto_banned', '".time()."', 0, '$fip', '$user_id', '".LAN_LOGIN_20.": ".$tp -> toDB($username).", ".LAN_LOGIN_17.": ".md5($ouserpass)."' ");
			}
		}
	}

	function update_xup($user_id, $user_xup = "") {
		global $sql, $tp;
		if($user_xup) {
			require_once(e_HANDLER."xml_class.php");
			$xml = new parseXml;
			if($rawData = $xml -> getRemoteXmlFile($user_xup)) {
				preg_match_all("#\<meta name=\"(.*?)\" content=\"(.*?)\" \/\>#si", $rawData, $match);
				$count = 0;
				foreach($match[1] as $value) {
					$$value = $tp -> toDB($match[2][$count]);
					$count++;
				}
				$sql -> db_Update("user", "user_login='{$FN}', user_homepage='{$URL}', user_icq='{$ICQ}', user_aim='{$AIM}', user_msn='{$MSN}', user_location='{$GEO}', user_birthday='{$BDAY}', user_signature='{$SIG}', user_sess='{$PHOTO}', user_image='{$AV}', user_timezone='{$TZ}' WHERE user_id='".intval($user_id)."'");
			}
		}
	}
}

?>