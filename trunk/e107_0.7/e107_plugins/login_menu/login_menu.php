<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     �Steve Dunstan 2001-2002
|     http://e107.org
|     jalist@e107.org
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $Source: /cvs_backup/e107_0.7/e107_plugins/login_menu/login_menu.php,v $
|     $Revision: 1.40 $
|     $Date: 2005-12-24 14:20:30 $
|     $Author: mcfly_e107 $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

global $eMenuActive, $e107, $tp;
require_once(e_PLUGIN."login_menu/login_menu_shortcodes.php");
$ip = $e107->getip();

$bullet = (defined("BULLET") ? "<img src='".THEME_ABS."images/".BULLET."' alt='' style='vertical-align: middle;' />" : "<img src='".THEME_ABS."images/bullet2.gif' alt='bullet' style='vertical-align: middle;' />");

if (defined('CORRUPT_COOKIE') && CORRUPT_COOKIE == TRUE) {
	$text = "<div style='text-align:center'>".LOGIN_MENU_L7."<br /><br />
	".$bullet." <a href='".e_BASE."index.php?logout'>".LOGIN_MENU_L8."</a></div>";
	$ns->tablerender(LOGIN_MENU_L9, $text, 'login');
}
$use_imagecode = ($pref['logcode'] && extension_loaded('gd'));
if ($use_imagecode) {
	include_once(e_HANDLER.'secure_img_handler.php');
	$sec_img = new secure_image;
}
$text = '';
if (USER == TRUE || ADMIN == TRUE) {
	if (ADMIN == TRUE) {
		$text = ($pref['maintainance_flag'] == 1 ? '<div style="text-align:center"><strong>'.LOGIN_MENU_L10.'</strong></div><br />' : '' );
		$text .= $bullet.' <a href="'.e_ADMIN_ABS.'admin.php">'.LOGIN_MENU_L11.'</a><br />';
	}
	$text .= $bullet.' <a href="'.e_HTTP.'usersettings.php">'.LOGIN_MENU_L12.'</a>
	<br />
	'.$bullet.' <a href="'.e_HTTP.'user.php?id.'.USERID.'">'.LOGIN_MENU_L13.'</a>
	<br />
	'.$bullet.' <a href="'.e_HTTP.'index.php?logout">'.LOGIN_MENU_L8.'</a>';

	if (!$sql->db_Select('online', '*', '`online_ip` = \''.$ip.'\' AND `online_user_id` = \'0\' ')) {
		$sql->db_Delete('online', '`online_ip` = \''.$ip.'\' AND `online_user_id` = \'0\' ');
	}

	$new_total = 0;
	$time = USERLV;

		// ------------ News Stats -----------

		if (isset($menu_pref['login_menu']) && $menu_pref['login_menu']['new_news'] == true) {
			$new_news = $sql->db_Select('news', '*', '`news_datestamp` > '.$time);
			while ($row = $sql->db_Fetch()) {
				if (!check_class($row['news_class'])) {
					$new_news--;
				}
			}
			$new_total += $new_news;
			if (!$new_news) {
				$new_news = LOGIN_MENU_L26;
			}
			$NewItems[] = $new_news.' '.($new_news == 1 ? LOGIN_MENU_L14 : LOGIN_MENU_L15);
		}

		// ------------ Article Stats -----------

		if (isset($menu_pref['login_menu']) && $menu_pref['login_menu']['new_comments'] == true) {
			$new_comments = 0;
			$new_comments = $sql->db_Select('comments', '*', '`comment_datestamp` > '.$time);

			$handle = opendir(e_PLUGIN);
			while (false !== ($file = readdir($handle))) {
				if ($file != '.' && $file != '..' && is_dir(e_PLUGIN.$file)) {
					$plugin_handle = opendir(e_PLUGIN.$file."/");
					while (false !== ($file2 = readdir($plugin_handle))) {
						if ($file2 == 'e_comment.php') {
							require_once(e_PLUGIN.$file.'/'.$file2);
							if ($comment_type == $e_plug_table) {
								$new_comments++;
								break 2;
							}
						}
					}
				}
			}
			$new_total += $new_comments;
			if (!$new_comments) {
				$new_comments = LOGIN_MENU_L26;
			}
			$NewItems[] = $new_comments.' '.($new_comments == 1 ? LOGIN_MENU_L18 : LOGIN_MENU_L19);
		}

		// ------------ Chatbox Stats -----------

		if (isset($menu_pref['login_menu']) && $menu_pref['login_menu']['new_chatbox'] == true) {
			$display_chats = TRUE;

			if(in_array('chatbox_menu',$eMenuActive)){
				$new_chat = $sql->db_Count('chatbox', '(*)', 'WHERE `cb_datestamp` > '.$time);
				$new_total += $new_chat;
			} else {
				$display_chats = FALSE;
			}
			if (isset($new_chat) && !$new_chat) {
				$new_chat = ($display_chats ? LOGIN_MENU_L26 : '');
			}
			if ($display_chats == true) {
				$NewItems[] = $new_chat.' '.($new_chat == 1 ? LOGIN_MENU_L16 : LOGIN_MENU_L17);

			}
		}

		// ------------ Forum Stats -----------

		if (isset($menu_pref['login_menu']) && $menu_pref['login_menu']['new_forum'] == true) {
			$qry = "
			SELECT  count(*) as count FROM #forum_t  as t
			LEFT JOIN #forum as f
			ON t.thread_forum_id = f.forum_id
			WHERE t.thread_datestamp > {$time} and f.forum_class IN (".USERCLASS_LIST.")
			";
			if($sql->db_Select_gen($qry))
			{
				$row = $sql->db_Fetch();
				$new_forum = $row['count'];
				$new_total += $new_forum;
			}
			if (!$new_forum) {
				$new_forum = LOGIN_MENU_L26;
			}
			$NewItems[] = $new_forum.' '.($new_forum == 1 ? LOGIN_MENU_L20 : LOGIN_MENU_L21);
		}

		// ------------ Member Stats -----------

		if (isset($menu_pref['login_menu']) && $menu_pref['login_menu']['new_members'] == true) {
			$new_users = $sql->db_Count('user', '(user_join)', 'WHERE user_join > '.$time);
			$new_total += $new_users;
			if (!$new_users) {
				$new_users = LOGIN_MENU_L26;
			}
			$NewItems[] = $new_users.' '.($new_users == 1 ? LOGIN_MENU_L22 : LOGIN_MENU_L23);
		}
		if (isset($NewItems) && $NewItems) {
			$text .= '<br /><br /><span class="smalltext">'.LOGIN_MENU_L25.'<br />'.implode(',<br />', $NewItems).'</span>';
			if ($new_total) {
				if ($sql -> db_Select("plugin", "plugin_installflag", "plugin_path='list_new' AND plugin_installflag='1'")) {
					$text .= '<br /><a href="'.e_PLUGIN.'list_new/list.php?new">'.LOGIN_MENU_L24.'</a>';
				}
			}
		}

	if (file_exists(THEME.'images/login_menu.png')) {
		$caption = '<img src="'.THEME_ABS.'images/login_menu.png" alt="" />'.LOGIN_MENU_L5.' '.USERNAME;
	} else {
		$caption = LOGIN_MENU_L5.' '.USERNAME;
	}
	$ns->tablerender($caption, $text, 'login');
} else {

	if (!$LOGIN_MENU_FORM) {
		if (file_exists(THEME."login_menu_template.php")){
	   		require_once(THEME."login_menu_template.php");
		}else{
			require_once(e_PLUGIN."login_menu/login_menu_template.php");
		}
	}

	if (LOGINMESSAGE != '') {
		$text = '<div style="text-align: center;">'.LOGINMESSAGE.'</div>';
	}

	$text .= '<form method="post" action="'.e_SELF.(e_QUERY ? '?'.e_QUERY : '').'">';
	$text .= $tp->parseTemplate($LOGIN_MENU_FORM, true, $login_menu_shortcodes);
	$text .= '</form>';


	if (file_exists(THEME.'images/login_menu.png')) {
		$caption = '<img src="'.THEME_ABS.'images/login_menu.png" alt="" />'.LOGIN_MENU_L5;
	} else {
		$caption = LOGIN_MENU_L5;
	}
	$ns->tablerender($caption, $text, 'login');
}

?>