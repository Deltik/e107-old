<?php

// default preferences for e107
$pref['sitename'] = "e107 powered website";
$pref['siteurl'] = $e_HTTP;
$pref['sitebutton'] = "button.png";
$pref['sitetag'] = "e107 website system";
$pref['sitedescription'] = "";
$pref['siteadmin'] = $_POST['admin_name'];
$pref['siteadminemail'] = $_POST['admin_email'];
$pref['sitetheme'] = "e107v4a";
$pref['image_preload'] = "0";
$pref['admintheme'] = "jayya";
$pref['adminstyle'] = "compact";
$pref['sitedisclaimer'] = "All trademarks are &copy; their respective owners, all other content is &copy; e107 powered website.<br />e107 is &copy; e107.org 2002-2005 and is released under the <a href=&#39;http://www.gnu.org/&#39;>GNU GPL license</a>.";
$pref['newsposts'] = "10";
$pref['flood_protect'] = "1";
$pref['flood_timeout'] = "5";
$pref['flood_time'] = "30";
$pref['flood_hits'] = "100";
$pref['anon_post'] = "1";
$pref['user_reg'] = "1";
$pref['use_coppa'] = "1";
$pref['profanity_filter'] = "1";
$pref['profanity_replace'] = "[censored]";
$pref['smiley_activate'] = "";
$pref['log_activate'] = "";
$pref['log_refertype'] = "1";
$pref['longdate'] = "%A %d %B %Y - %H:%M:%S";
$pref['shortdate'] = "%d %b : %H:%M";
$pref['forumdate'] = "%a %b %d %Y, %I:%M%p";
$pref['sitelanguage'] = (isset($_POST['installlanguage']) ? $_POST['installlanguage'] :  "English");
$pref['maintainance_flag'] = "0";
$pref['time_offset'] = "0";
$pref['log_lvcount'] = "10";
$pref['meta_tag'] = "";
$pref['user_reg_veri'] = "1";
$pref['email_notify'] = "0";
$pref['forum_poll'] = "0";
$pref['forum_popular'] = "10";
$pref['forum_track'] = "0";
$pref['forum_eprefix'] = "[forum]";
$pref['forum_enclose'] = "1";
$pref['forum_title'] = "Forums";
$pref['forum_postspage'] = "10";
$pref['forum_highlightsticky'] = "1";
$pref['user_tracking'] = "cookie";
$pref['cookie_name'] = "e107cookie";
$pref['resize_method'] = "gd2";
$pref['im_path'] = "/usr/X11R6/bin/convert";
$pref['im_quality'] = "80";
$pref['im_width'] = "120";
$pref['im_height'] = "100";
$pref['upload_enabled'] = "0";
$pref['upload_allowedfiletype'] = ".zip\n.gz\n.jpg\n.png\n.gif\n.txt";
$pref['upload_storagetype'] = "2";
$pref['upload_maxfilesize'] = "";
$pref['upload_class'] = "999";
$pref['cachestatus'] = "";
$pref['displayrendertime'] = "1";
$pref['displaysql'] = "";
$pref['displaythemeinfo'] = "1";
$pref['timezone'] = "GMT";
$pref['search_restrict'] = "1";
$pref['antiflood1'] = "1";
$pref['antiflood_timeout'] = "10";
$pref['autoban'] = "1";
$pref['sitelang_init'] = (isset($_POST['installlanguage']) ? $_POST['installlanguage'] :  "English");
$pref['linkpage_screentip'] = "0";
$pref['plug_latest'] = ",links_page";
$pref['wmessage_sc'] = "0";
$pref['frontpage'] = "news.php";

// Links page plugin pre-installed
$pref['link_submit'] = "1";
$pref['link_submit_class'] = "0";
$pref['linkpage_categories'] = "0";	

// Chatbox plugin pre-installed
$pref['chatbox_posts'] = "10";
$pref['cb_linkc'] = " -link- ";
$pref['cb_wordwrap'] = "20";
$pref['cb_linkreplace'] = "1";
$pref['cb_layer'] = "0";
$pref['cb_layer_height'] = "200";
$pref['cb_emote'] = "0";

// Added
$pref['signup_text'] = "";
$pref['admin_alerts_ok'] = 1;
$pref['real'] = 1;
$pref['url'] = 1;
$pref['icq'] = 1;
$pref['aim'] = 1;
$pref['msn'] = 1;
$pref['dob'] = 1;
$pref['loc'] = 1;
$pref['sig'] = 1;
$pref['avt'] = 1;
$pref['zone'] = 1;
$pref['usrclass'] = 1;
$pref['link_replace'] = 1;
$pref['link_text'] = "";
$pref['signcode'] = 1;
$pref['logcode'] = 1;
$pref['signup_options'] = "1.1.1.1.1.1.1.1.1.1.1";
$pref['newsposts_archive'] = 0;
$pref['newsposts_archive_title'] = "";
$pref['news_cats'] = "";
$pref['nbr_cols'] = 1;
$pref['subnews_attach'] = "";
$pref['subnews_resize'] = "";
$pref['subnews_class'] = 0;
$pref['subnews_htmlarea'] = 0;
$pref['subnews_hide_news'] = "";
$pref['news_newdateheader'] = 0;
$pref['email_text'] = "";
$pref['plug_status'] = "";
$pref['useGeshi'] = 0;
$pref['search_sort'] = "php";
$pref['wysiwyg'] = 0;
$pref['old_np'] = 0;
$pref['make_clickable'] = 0;



?>