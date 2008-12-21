<?php
/*
 * e107 website system
 *
 * Copyright (C) 2001-2008 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * Administration Area - Languages
 *
 * $Source: /cvs_backup/e107_0.8/e107_admin/language.php,v $
 * $Revision: 1.14 $
 * $Date: 2008-12-21 12:53:11 $
 * $Author: secretr $
 *
*/

require_once("../class2.php");
if (!getperms('0'))
{
	header("location:".e_BASE."index.php");
	exit;
}

$e_sub_cat = 'language';

require_once("auth.php");
require_once(e_HANDLER."form_handler.php");
require_once(e_HANDLER."message_handler.php");

$frm = new e_form();
$emessage = &eMessage::getInstance();

$tabs = table_list(); // array("news","content","links");
$lanlist = explode(",",e_LANLIST);
$message = '';

if (e_QUERY)
{
	$tmp = explode('.', e_QUERY);
	$action = $tmp[0];
	$sub_action = $tmp[1];
	$id = $tmp[2];
	unset($tmp);
}

if (isset($_POST['submit_prefs']) && isset($_POST['mainsitelanguage']))
{
	unset($temp);
	$changes = array();

	$temp['multilanguage']	= $_POST['multilanguage'];
    $temp['multilanguage_subdomain'] = $_POST['multilanguage_subdomain'];
	$temp['sitelanguage'] = $_POST['mainsitelanguage'];
	$temp['noLanguageSubs'] = $_POST['noLanguageSubs'];

	if ($admin_log->logArrayDiffs($temp, $pref, 'LANG_01'))
	{
		save_prefs();		// Only save if changes
		$emessage->add(LAN_SETSAVED, E_MESSAGE_SUCCESS);

	}
	else
	{
		$emessage->add(LAN_NO_CHANGE);
	}
}


// ----------------- delete tables ---------------------------------------------
if (isset($_POST['del_existing']) && $_POST['lang_choices'])
{
	$lang = strtolower($_POST['lang_choices']);
	foreach ($tabs as $del_table)
	{
		if (db_Table_exists($lang."_".$del_table))
		{
			$qry = "DROP TABLE ".$mySQLprefix."lan_".$lang."_".$del_table;
			if (mysql_query($qry))
			{
				$message .= sprintf(LANG_LAN_28, $_POST['lang_choices'].' '.$del_table).'[!br!]' ;
				$emessage->add(sprintf(LANG_LAN_28, $_POST['lang_choices'].' '.$del_table), E_MESSAGE_SUCCESS);
			}
			else
			{
				$message .= sprintf(LANG_LAN_29, $_POST['lang_choices'].' '.$del_table).'[!br!]';
				$emessage->add(sprintf(LANG_LAN_29, $_POST['lang_choices'].' '.$del_table), E_MESSAGE_WARNING);
			}
		}
	}
	$admin_log->log_event('LANG_02', $message, E_LOG_INFORMATIVE, '');
	global $cachevar;
	unset($cachevar['table_list']);
	if($action == 'modify') $action = 'db';//FIX - force db action when deleting all lan tables
}

// ----------create tables -----------------------------------------------------

if (isset($_POST['create_tables']) && $_POST['language'])
{
	$table_to_copy = array();
	$lang_to_create = array();

	foreach ($tabs as $value)
	{
		$lang = strtolower($_POST['language']);
		if (isset($_POST[$value]))
		{
            $copdata = ($_POST['copydata_'.$value]) ? 1 : 0;
			if (copy_table($value, "lan_".$lang."_".$value, $_POST['drop'],$copdata))
			{
				$message .= sprintf(LANG_LAN_30, $_POST['language'].' '.$value).'[!br!]';
				$emessage->add(sprintf(LANG_LAN_30, $_POST['language'].' '.$value), E_MESSAGE_SUCCESS);
			}
			else
			{
				if(!$_POST['drop'])
				{
					$message .= sprintf(LANG_LAN_00, $_POST['language'].' '.$value).'[!br!]';
					$emessage->add(sprintf(LANG_LAN_00, $_POST['language'].' '.$value), E_MESSAGE_WARNING);
				}
				else
				{
					$message .= sprintf(LANG_LAN_01, $_POST['language'].' '.$value).'[!br!]';
					$emessage->add(sprintf(LANG_LAN_01, $_POST['language'].' '.$value), E_MESSAGE_WARNING);
				}
			}
		}
		elseif (db_Table_exists($lang."_".$value))
		{
			if ($_POST['remove'])
			{
				// Remove table.
				if(mysql_query("DROP TABLE ".$mySQLprefix."lan_".$lang."_".$value))
				{
					$message .= $_POST['language'].' '.$value.' '.LAN_DELETED.'[!br!]';
					$emessage->add($_POST['language'].' '.$value.' '.LAN_DELETED, E_MESSAGE_SUCCESS);
				}
				else
				{
					$message .= sprintf(LANG_LAN_02, $_POST['language'].' '.$value).'[!br!]';
					$emessage->add(sprintf(LANG_LAN_02, $_POST['language'].' '.$value), E_MESSAGE_WARNING);
				}
			}
			else
			{
				// leave table. LANG_LAN_32
				$message .= sprintf(LANG_LAN_32, $_POST['language'].' '.$value).'[!br!]';
				$emessage->add(sprintf(LANG_LAN_32, $_POST['language'].' '.$value));
			}
		}
	}
	$admin_log->log_event('LANG_03', $message, E_LOG_INFORMATIVE, '');
    global $cachevar;
	unset($cachevar['table_list']);
}


/*
	if(isset($message) && $message)
	{
  		$ns->tablerender(LAN_OK, $message);
	}
*/


unset($text);



if (!e_QUERY || $action == 'main' && !$_POST['language'] && !$_POST['edit_existing'])
{
	multilang_prefs();
}

if ($action == 'db')
{
	multilang_db();
}

if($_POST['ziplang'] && $_POST['language'])
{
 	$text = zip_up_lang($_POST['language']);
	$admin_log->log_event('LANG_04', $_POST['language'], E_LOG_INFORMATIVE, '');
    //$ns -> tablerender(LANG_LAN_25, $text);
    $emessage->add(LANG_LAN_25.': '.$text);
}

if($action == "tools")
{
	show_tools();
}




//FIX - create or edit check
if(isset($_POST['create_edit_existing'])) $_POST['edit_existing'] = true;

// Grab Language configuration. ---
if (isset($_POST['edit_existing']))
{
	//XXX - JS ok with the current functionality?
	$text .= "
	<form method='post' action='".e_SELF."?db'>
		<fieldset id='core-language-edit'>
			<legend class='e-hideme'>".$_POST['lang_choices']."</legend>
			<table cellpadding='0' cellspacing='0' class='adminlist'>
				<colgroup span='2'>
					<col class='col-label' />
					<col class='col-control' />
				</colgroup>
				<tbody>
	";

	foreach ($tabs as $table_name)
	{
		$installed = strtolower($_POST['lang_choices'])."_".$table_name;
		if (stristr($_POST['lang_choices'], $installed) === FALSE)
		{
			$text .= "
					<tr>
						<td class='label'>".ucfirst(str_replace("_", " ", $table_name))."</td>
						<td class='control'>
							<div class='auto-toggle-area f-left e-pointer'>
			";
			$selected = (db_Table_exists($installed)) ? " checked='checked'" : "";
			$text .= "
								<input type='checkbox' class='checkbox' id='language-action-{$table_name}' name='{$table_name}' value='1'{$selected} onclick=\"if(document.getElementById('language-action-{$table_name}').checked){document.getElementById('language-datacopy-{$table_name}').style.display = '';}\" />
							</div>

							<div class='f-left'>
								<span id='language-datacopy-{$table_name}' class='e-hideme e-pointer'>
									<input type='checkbox' class='checkbox' name='copydata_{$table_name}' id='copydata-{$table_name}' value='1' />
									&nbsp;&nbsp;<label for='copydata-{$table_name}'>".LANG_LAN_15."</label>
								</span>
							</div>
						</td>
					</tr>
			";
		}
	}

	// ===========================================================================

	// Drop tables ? isset()
	if(varset($_POST['create_edit_existing']))
	{
		$baction = 'create';
		$bcaption = LANG_LAN_06;
	}
	else
	{
		$baction = 'update';
		$bcaption = LAN_UPDATE;
	}

	$text .= "
					<tr>
						<td class='label'><strong>".LANG_LAN_07."</strong></td>
						<td class='control'>
							".$frm->checkbox('drop', 1)."
							<div class='smalltext field-help'>".$frm->label(LANG_LAN_08, 'drop', 1)."</div>
						</td>
					</tr>
					<tr>
						<td class='label'><strong>".LANG_LAN_10."</strong></td>
						<td class='control'>
							".$frm->checkbox('remove', 1)."
							<div class='smalltext field-help'>".$frm->label(LANG_LAN_11, 'remove', 1)."</div>
						</td>
					</tr>
				</tbody>
			</table>
			<div class='buttons-bar center'>
				<input type='hidden' name='language' value='{$_POST['lang_choices']}' />
				<button class='{$baction}' type='submit' name='create_tables' value='{$bcaption}'><span>{$bcaption}</span></button>
			</div>
		</fieldset>
	</form>
	";

	$ns->tablerender($_POST['lang_choices'], $emessage->render().$text);
}

require_once(e_ADMIN."footer.php");

// ---------------------------------------------------------------------------
function multilang_prefs()
{
	global $e107, $pref, $lanlist, $emessage;

	$text = "
	<form method='post' action='".e_SELF."' id='linkform'>
		<fieldset id='core-language-settings'>
			<legend class='e-hideme'>".LANG_LAN_13."</legend>
			<table cellpadding='0' cellspacing='0' class='adminform'>
				<colgroup span='2'>
					<col class='col-label' />
					<col class='col-control' />
				</colgroup>
				<tbody>
					<tr>
						<td class='label'>".LANG_LAN_14.": </td>
						<td class='control'>
							<select name='mainsitelanguage' class='box select'>
	";

	$sellan = preg_replace("/lan_*.php/i", "", $pref['sitelanguage']);
	foreach($lanlist as $lan)
	{
			$sel =  ($lan == $sellan) ? " selected='selected'" : "";
    		$text .= "
								<option value='{$lan}'{$sel}>".$lan."</option>
			";
	}
	$text .= "				</select>
						</td>
					</tr>
					<tr>
						<td class='label'>".LANG_LAN_12.": </td>
						<td class='control'>
							<div class='auto-toggle-area autocheck'>
	";
	$checked = ($pref['multilanguage'] == 1) ? " checked='checked'" : "";
	$text .= "
								<input class='checkbox' type='checkbox' name='multilanguage' value='1'{$checked} />
							</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".LANG_LAN_26.":</td>
						<td class='control'>
							<div class='auto-toggle-area autocheck'>
	";
	$checked = ($pref['noLanguageSubs'] == 1) ? " checked='checked'" : "";
	$text .= "
								<input class='checkbox' type='checkbox' name='noLanguageSubs' value='1'{$checked} />
								<div class='smalltext field-help'>".LANG_LAN_27."</div>
							</div>
						</td>
					</tr>
					<tr>
						<td class='label'>
							".LANG_LAN_18."
							<div class='label-note'>".LANG_LAN_19."</div>
						</td>
						<td class='control'>
							<textarea name='multilanguage_subdomain' rows='5' cols='15'>{$pref['multilanguage_subdomain']}</textarea>
							<div class='smalltext field-help'>".LANG_LAN_20."</div>
						</td>
					</tr>
				</tbody>
			</table>
			<div class='buttons-bar center'>
				<button class='update' type='submit' name='submit_prefs' value='".LAN_SAVE."'><span>".LAN_SAVE."</span></button>
			</div>
		</fieldset>
	</form>
	";

	$e107->ns->tablerender(LANG_LAN_PAGE_TITLE.' - '.LANG_LAN_13, $emessage->render().$text);  // "Language Preferences";
}

// ----------------------------------------------------------------------------

function db_Table_exists($table)
{
	global $mySQLdefaultdb;
	$tables = getcachedvars("table_list");
	if(!$tables)
	{
		$tablist = mysql_list_tables($mySQLdefaultdb);
		while($tmp = mysql_fetch_array($tablist))
		{
			$tables[] = $tmp[0];
		}
		cachevars("table_list", $tables);
	}
	return in_array(strtolower(MPREFIX."lan_".$table), $tables);
}
// ----------------------------------------------------------------------------

function copy_table($oldtable, $newtable, $drop = FALSE, $data = FALSE)
{
	global $sql;
	$old = MPREFIX.strtolower($oldtable);
	$new = MPREFIX.strtolower($newtable);
	if($drop)
	{
		$sql->db_Select_gen("DROP TABLE IF EXISTS {$new}");
	}

	//Get $old table structure
	$sql->db_Select_gen('SET SQL_QUOTE_SHOW_CREATE = 1');
	$qry = "SHOW CREATE TABLE {$old}";
	if($sql->db_Select_gen($qry))
	{
		$row = $sql->db_Fetch();
		$qry = $row[1];
		$qry = str_replace($old, $new, $qry);
	}
	$result = mysql_query($qry);
	if(!$result)
	{
		return FALSE;
	}
	if ($data)  //We need to copy the data too
	{
		$qry = "INSERT INTO {$new} SELECT * FROM {$old}";
		$sql->db_Select_gen($qry);
	}
	return TRUE;
}

// ----------------------------------------------------------------------------

function table_list() {
	// grab default language lists.
	global $mySQLdefaultdb;

	$exclude[] = "banlist";		$exclude[] = "banner";
	$exclude[] = "cache";		$exclude[] = "core";
	$exclude[] = "online";		$exclude[] = "parser";
	$exclude[] = "plugin";		$exclude[] = "user";
	$exclude[] = "upload";		$exclude[] = "userclass_classes";
	$exclude[] = "rbinary";		$exclude[] = "session";
	$exclude[] = "tmp";	 		$exclude[] = "flood";
	$exclude[] = "stat_info";	$exclude[] = "stat_last";
	$exclude[] = "submit_news";	$exclude[] = "rate";
	$exclude[] = "stat_counter";$exclude[] = "user_extended";
	$exclude[] = "user_extended_struc";
	$exclude[] = "pm_messages";
	$exclude[] = "pm_blocks";

	$tables = mysql_list_tables($mySQLdefaultdb);

	while (list($temp) = mysql_fetch_array($tables))
	{
        if ((MPREFIX=='') ||(strpos($temp, MPREFIX) === 0))
		{
			$e107tab = str_replace(MPREFIX, "", $temp);
			if (!in_array($e107tab, $exclude) && stristr($e107tab, "lan_") === FALSE)
			{
				$tabs[] = $e107tab;
			}
		}
	}

	return $tabs;
}


// ------------- render form ---------------------------------------------------
function multilang_db(){
	global $pref, $e107, $tp, $frm, $emessage, $lanlist, $tabs;

	if(isset($pref['multilanguage']) && $pref['multilanguage']){

		// Choose Language to Edit:
		$text = "
			<fieldset id='core-language-list'>
				<legend class='e-hideme'>".LANG_LAN_16."</legend>
				<table cellpadding='0' cellspacing='0' class='adminlist'>
					<colgroup span='3'>
						<col style='width:20%' />
						<col style='width:60%' />
						<col style='width:20%' />
					</colgroup>
					<thead>
						<tr>
							<th>".ADLAN_132."</th>
							<th>".LANG_LAN_03."</th>
							<th class='last'>".LAN_OPTIONS."</th>
						</tr>
					</thead>
					<tbody>
		";
		sort($lanlist);
		for($i = 0; $i < count($lanlist); $i++)
		{
			$installed = 0;

			$text .= "
						<tr>
							<td>{$lanlist[$i]}</td>
							<td>
			";
			foreach ($tabs as $tab_name) {
				if (db_Table_exists(strtolower($lanlist[$i])."_".$tab_name)) {
					$text .= $tab_name.", ";
					$installed++;
				}
			}
        	if($lanlist[$i] == $pref['sitelanguage']){
        		$text .= "
								<span>".LANG_LAN_17."</span>
				";
			}else{
				$text .= (!$installed)? "<span>".LANG_LAN_05."</span>" : "";
			}
			$text .= "
							</td>
							<td>
								<form id='core-language-form-".str_replace(" ", "-", $lanlist[$i])."' action='".e_SELF."?modify' method='post'>
			";
			$text .= "
								<div>
			";
   			if ($installed)
   			{
				$text .= "
								<button class='edit' type='submit' name='edit_existing' value='".LAN_EDIT."'><span>".LAN_EDIT."</span></button>
								<button class='delete' type='submit' name='del_existing' value='".LAN_DELETE."' title='".sprintf(LANG_LAN_33, $lanlist[$i]).' '.LANG_LAN_09."'><span>".LAN_DELETE."</span></button>
				";
			}
			elseif($lanlist[$i] != $pref['sitelanguage'])
			{
				$text .= "
								<button class='create' type='submit' name='create_edit_existing' value='".LAN_CREATE."'><span>".LAN_CREATE."</span></button>
				";
			}
			$text .= "
								<input type='hidden' name='lang_choices' value='".$lanlist[$i]."' />
								</div>
								</form>
							</td>
						</tr>
			";
		}

		$text .= "
					</tbody>
				</table>
			</fieldset>
		";

		$e107->ns->tablerender(LANG_LAN_PAGE_TITLE.' - '.LANG_LAN_16, $emessage->render().$text);
	}
}


// ----------------------------------------------------------------------------

function show_tools()
{
	global $e107, $emessage;

	include_lan(e_LANGUAGEDIR.e_LANGUAGE."/admin/lan_lancheck.php");

	$text .= "
		<form id='core-language-lancheck-form' method='post' action='".e_ADMIN."lancheck.php'>
			<fieldset id='core-language-lancheck'>
				<legend class='e-hideme'>".LAN_CHECK_1."</legend>
				<table cellpadding='0' cellspacing='0' class='adminform'>
					<colgroup span='3'>
						<col class='col-label' />
						<col class='col-control' />
					</colgroup>
					<tbody>
						<tr>
							<td class='label'>".LAN_CHECK_1."</td>
							<td class='control'>
								<select name='language' class='tbox select'>
									<option value=''>".LAN_SELECT."</option>";

	$languages = explode(",",e_LANLIST);
	sort($languages);

	foreach($languages as $lang)
	{
		if($lang != "English")
		{
	   		$text .= "
									<option value='{$lang}' >{$lang}</option>
			";
		}
	}

	$text .= "
								</select>
								<button class='submit' type='submit' name='language_sel' value='".LAN_CHECK_2."'><span>".LAN_CHECK_2."</span></button>
							</td>
						</tr>
					</tbody>
				</table>
			</fieldset>
		</form>
	";

	$text .= "
		<form id='ziplang' method='post' action='".e_SELF."?tools'>
			<fieldset id='core-language-package'>
				<legend class='e-hideme'>".LANG_LAN_23."</legend>
				<table cellpadding='0' cellspacing='0' class='adminform'>
					<colgroup span='2'>
						<col class='col-label' />
						<col class='col-control' />
					</colgroup>
					<tbody>
						<tr>
							<td class='label'>".LANG_LAN_23."</td>
							<td class='control'>
								<select name='language' class='tbox select'>
									<option value=''>".LAN_SELECT."</option>";

	$languages = explode(",",e_LANLIST);
	sort($languages);

	foreach($languages as $lang)
	{
		if($lang != "English")
		{
	   		$text .= "
									<option value='{$lang}' >{$lang}</option>
			";
		}
	}

	$text .= "
								</select>
								<button class='submit' type='submit' name='ziplang' value='".LANG_LAN_24."'><span>".LANG_LAN_24."</span></button>
							</td>
						</tr>
					</tbody>
				</table>
			</fieldset>
		</form>
	";

	$e107->ns->tablerender(LANG_LAN_PAGE_TITLE.' - '.LANG_LAN_21, $emessage->render().$text);
}


// ----------------------------------------------------------------------------

function language_adminmenu()
{
	global $action,$pref;
	if ($action == "")
	{
		$action = "main";
	}

	if($action == "modify")
	{
    	$action = "db";
	}
	$var['main']['text'] = LAN_PREFS;
	$var['main']['link'] = e_SELF;

	if(isset($pref['multilanguage']) && $pref['multilanguage'])
	{
		$var['db']['text'] = LANG_LAN_03;
		$var['db']['link'] = e_SELF."?db";
	}

	$lcnt = explode(",",e_LANLIST);
    if(count($lcnt) > 1)
	{
		$var['tools']['text'] = ADLAN_CL_6;
		$var['tools']['link'] = e_SELF."?tools";
    }

	e_admin_menu(ADLAN_132, $action, $var);
}



// Zip up the language pack.

// ===================================================
function zip_up_lang($language)
{
    if (is_readable(e_ADMIN."ver.php"))
	{
		include(e_ADMIN."ver.php");
	}
/*
    $core_plugins = array(
    "alt_auth","banner_menu","blogcalendar_menu","calendar_menu","chatbox_menu",
    "clock_menu","comment_menu","content","featurebox","forum","gsitemap",
    "links_page","linkwords","list_new","log","login_menu",
    "newforumposts_main","newsfeed","newsletter","online",
    "other_news_menu","pdf","pm","poll","rss_menu",
    "search_menu","siteinfo_menu","trackback","tree_menu","user_menu","userlanguage_menu",
    "usertheme_menu"
    );

    $core_themes = array("crahan","e107v4a","human_condition","interfectus","jayya",
    "khatru","kubrick","lamb","leaf","newsroom","reline","sebes","vekna_blue");
*/

	require_once(e_HANDLER.'pclzip.lib.php');

	list($ver,$tmp) = explode(" ",$e107info['e107_version']);

	$newfile = e_UPLOAD."e107_".$ver."_".$language."_utf8.zip";
  	$archive = new PclZip($newfile);

	$core = grab_lans(e_LANGUAGEDIR.$language."/",$language);
    $plugs = grab_lans(e_PLUGIN,$language);
    $theme = grab_lans(e_THEME,$language);

	$file = array_merge($core,$plugs,$theme);
	$data = implode(",",$file);

  	if ($archive->create($data) == 0)
	{
    	return $archive->errorInfo(true);
  	}
	else
	{
    	return LANG_LAN_22." (".str_replace("../","",e_UPLOAD)."<a href='".$newfile."' >".basename($newfile)."</a>).";
	}


}

function grab_lans($path,$language,$filter = "")
{
   	require_once(e_HANDLER."file_class.php");
    $fl = new e_file;

    if($lanlist = $fl->get_files($path,"", "standard",4)){
    	sort($lanlist);
    }else{
    	return;
	}

    $pzip = array();
 	foreach($lanlist as $p)
	{
		$fullpath = $p['path'].$p['fname'];
    	if(strpos($fullpath,$language)!== FALSE)
	 	{
			$pzip[] = $fullpath;
		}
	}
    return $pzip;

}

/**
 * Handle page DOM within the page header
 *
 * @return string JS source
 */
function headerjs()
{
	require_once(e_HANDLER.'js_helper.php');
	$ret = "
		<script type='text/javascript' src='".e_FILE_ABS."jslib/core/admin.js'></script>
		<script type='text/javascript'>
			//add required core lan - delete confirm message
			(".e_jshelper::toString(LANG_LAN_09).").addModLan('core', 'delete_confirm');

			//core object
			e107Admin.CoreLanguage = {};
			//show Table Copy option
			e107Admin.CoreLanguage.dataCopy = function(table) {
				if($('language-datacopy-' + table)) {
					$('language-datacopy-' + table).show();
				}
			}

			//registry - selected by default
			e107Admin.CoreLanguage._def_checked = {}

			//document observer
			document.observe('dom:loaded', function() {
				//find lan action checkboxes
				\$\$('input[type=checkbox][id^=language-action-]').each( function(element) {
					if(element.checked) e107Admin.CoreLanguage._def_checked[element.id] = true;// already checked, don't allow data copy
					var carea = element.up('div.auto-toggle-area');

					//clickable container - autocheck + allow data copy
					if(carea) {
						carea.observe('click', function(e) {
							element.checked = !(element.checked);
							if(element.checked && !e107Admin.CoreLanguage._def_checked[element.id]) {
								e107Admin.CoreLanguage.dataCopy(element.id.replace(/language-action-/, ''));
							}
						});
					}

					//checkbox observer
					element.observe('click', function(e) {
						if(e.element().checked && !e107Admin.CoreLanguage._def_checked[element.id])
							e107Admin.CoreLanguage.dataCopy(e.element().id.replace(/language-action-/, ''));
					});
				});
			});
		</script>
	";

	return $ret;
}
?>