<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2009 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 *
 *
 * $Source: /cvs_backup/e107_0.8/e107_plugins/links_page/admin_linkspage_config.php,v $
 * $Revision: 1.6 $
 * $Date: 2009-11-18 01:05:46 $
 * $Author: e107coders $
 */

require_once("../../class2.php");
if (!getperms("P") || !plugInstalled('links_page')) 
{
	header("location:".e_BASE."index.php");
	exit;
}
require_once(e_PLUGIN.'links_page/link_shortcodes.php');
require_once(e_PLUGIN.'links_page/link_defines.php');
require_once(e_ADMIN.'auth.php');
require_once(e_HANDLER.'userclass_class.php');
require_once(e_HANDLER.'form_handler.php');
$rs = new form;
require_once(e_HANDLER.'file_class.php');
$fl = new e_file;
e107_require_once(e_HANDLER.'arraystorage_class.php');
$eArrayStorage = new ArrayData();
require_once(e_PLUGIN.'links_page/link_class.php');
$lc = new linkclass;

include_lan($plugindir."languages/".e_LANGUAGE."_admin_links_page.php");

$linkspage_pref = $lc -> getLinksPagePref();

$deltest = array_flip($_POST);

if(e_QUERY)
{
	$qs = explode(".", e_QUERY);

	if(is_numeric($qs[0]))
	{
		$from = array_shift($qs);
	}
	else
	{
		$from = "0";
	}
}

if(isset($_POST['delete']))
{
	$tmp = array_pop($tmp = array_flip($_POST['delete']));
	list($delete, $del_id) = explode("_", $tmp);
	$del_id = intval($del_id);
}

if (isset($_POST['create_category'])) 
{
	$lc -> dbCategoryCreate();
}

if (isset($_POST['update_category'])) 
{
	$lc -> dbCategoryUpdate();
}

if (isset($_POST['updateoptions'])) 
{
	$linkspage_pref = $lc -> UpdateLinksPagePref();
	$lc -> show_message(LCLAN_ADMIN_6);
}

if (isset($_POST['add_link'])) 
{
	$lc -> dbLinkCreate();
}

//upload link icon
if(isset($_POST['uploadlinkicon']))
{
	$lc -> uploadLinkIcon();
}

//upload category icon
if(isset($_POST['uploadcatlinkicon']))
{
	$lc -> uploadCatLinkIcon();
}

//update link order
if (isset($_POST['update_order'])) 
{
	$lc -> dbOrderUpdate($_POST['link_order']);
}

//update link category order
if (isset($_POST['update_category_order'])) 
{
	$lc -> dbOrderCatUpdate($_POST['link_category_order']);
}

if (isset($_POST['inc'])) 
{
	$lc -> dbOrderUpdateInc($_POST['inc']);
}

if (isset($_POST['dec'])) 
{
	$lc -> dbOrderUpdateDec($_POST['dec']);
}

//delete link
if (isset($delete) && ($delete == 'main') && $del_id) 
{
	$sql->db_Select("links_page", "link_order", "link_id=".$del_id);
	$row = $sql->db_Fetch();
	$sql2 = new db;
	$sql->db_Select("links_page", "link_id", "link_order>'".$row['link_order']."' && link_category=".intval($row['link_category']));
	while ($row = $sql->db_Fetch()) 
	{
		$sql2->db_Update("links_page", "link_order=link_order-1 WHERE link_id='".$row['link_id']."'");
	}
	if ($sql->db_Delete("links_page", "link_id=".$del_id)) 
	{
		$msg = LCLAN_ADMIN_10." #".$del_id." ".LCLAN_ADMIN_11;
		$data = array('method'=>'delete', 'table'=>'links_page', 'id'=>$del_id, 'plugin'=>'links_page', 'function'=>'delete');
		$msg .= $e_event->triggerHook($data);
		$admin_log->log_event('LINKS_02','ID: '.$del_id,E_LOG_INFORMATIVE,'');
		$lc->show_message($msg);
	}
}

//delete category
if (isset($delete) && ($delete == 'category')  && $del_id)
{
	//check if links are present for this category
	if($sql->db_Select("links_page", "*", "link_category=".$del_id )) 
	{
		$lc->show_message(LCLAN_ADMIN_12." #".$del_id." ".LAN_DELETED_FAILED."<br />".LCLAN_ADMIN_15);
	//no? then we can safely remove this category
	}
	else
	{
		if ($sql->db_Delete("links_page_cat", "link_category_id=".$del_id)) 
		{
			$admin_log->log_event('LINKS_03','ID: '.$del_id,E_LOG_INFORMATIVE,'');
			$lc->show_message(LCLAN_ADMIN_12." #".$del_id." ".LCLAN_ADMIN_11);
			unset($id);
		}
	}
}

//delete submitted link
if (isset($delete) && ($delete == 'sn') && $del_id)
{
	if ($sql->db_Delete("tmp", "tmp_time=".$del_id)) 
	{
		$admin_log->log_event('LINKS_04','ID: '.$del_id,E_LOG_INFORMATIVE,'');
		$lc->show_message(LCLAN_ADMIN_13);
	}
}


//show link categories (cat edit)
if (!e_QUERY) 
{
	$lc->show_categories("cat");
}


//show cat edit form
if (isset($qs[0]) && $qs[0] == 'cat' && isset($qs[1]) && $qs[1] == 'edit' && isset($qs[2]) && is_numeric($qs[2])) 
{
	$lc->show_cat_create();
}

//show cat create form
if (isset($qs[0]) && $qs[0] == 'cat' && isset($qs[1]) && $qs[1] == 'create' && !isset($qs[2]) ) 
{
	$lc->show_cat_create();
}

if (isset($qs[0]) && $qs[0] == 'link') 
{
	$gotVal = FALSE;
	switch(varset($qs[1],''))
	{
		case 'view' :		//view links in cat
			if (isset($qs[2]) && (is_numeric($qs[2]) || $qs[2] == "all") ) 
			{
				$lc->show_links();
				$gotVal = TRUE;
			}
			break;
		case 'edit' :			//edit link
			if (isset($qs[2]) && is_numeric($qs[2])) 
			{
				$lc->show_link_create();
				$gotVal = TRUE;
			}
			break;
		case 'create' :			//create link
			if (!isset($qs[2]) ) 
			{
				$lc->show_link_create();
				$gotVal = TRUE;
			}
			break;
		case 'sn' :				//post submitted
			if (isset($qs[2]) && is_numeric($qs[2]) ) 
			{
				$lc->show_link_create();
				$gotVal = TRUE;
			}
			break;
		default :
	}
	if (!$gotVal)
	{
		$lc->show_categories("link");
	}
}

//view submitted links
if (isset($qs[0]) && $qs[0] == 'sn') 
{
	$lc->show_submitted();
}

//options
if (isset($qs[0]) && $qs[0] == 'opt') 
{
	$lc->show_pref_options();
}

// ##### Display options --------------------------------------------------------------------------
function admin_linkspage_config_adminmenu()
{
	global $qs, $sql;
	$act = varset($qs[0],'cat');
	if($act == 'cat' && isset($qs[1]))
	{
		if($qs[1] == "create")
		{
			$act .= ".create";
		}
		elseif ($qs[1] == "edit")
		{
			$act .= "";
		}
		elseif ($qs[1] == "view")
		{
			$act .= "";
		}
	}

	$var['cat']['text'] = LCLAN_ADMINMENU_2;
	$var['cat']['link'] = e_SELF;

	$var['cat.create']['text'] = LCLAN_ADMINMENU_3;
	$var['cat.create']['link'] = e_SELF."?cat.create";

	$var['link']['text'] = LCLAN_ADMINMENU_4;
	$var['link']['link'] = e_SELF."?link";

	$var['link.create']['text'] = LCLAN_ADMINMENU_5;
	$var['link.create']['link'] = e_SELF."?link.create";
		
	if ($tot = $sql->db_Select("tmp", "*", "tmp_ip='submitted_link' ")) 
	{
		$var['sn']['text'] = LCLAN_ADMINMENU_7." (".$tot.")";
		$var['sn']['link'] = e_SELF."?sn";
	}
		
	$var['opt']['text'] = LCLAN_ADMINMENU_6;
	$var['opt']['link'] = e_SELF."?opt";
		
	show_admin_menu(LCLAN_ADMINMENU_1, $act, $var);
		
	if($qs[0] != "opt")
	{
		unset($var);
		$var=array();
		if ($sql->db_Select("links_page_cat", "*")) 
		{
			while ($row = $sql->db_Fetch()) {
				$var[$row['link_category_id']]['text'] = $row['link_category_name'];
				$var[$row['link_category_id']]['link'] = e_SELF."?link.view.".$row['link_category_id'];
			}
			$active = ($qs[0] == 'link') ? $id : FALSE;
			show_admin_menu(LCLAN_ADMINMENU_8, $active, $var);
		}
	}
	if(isset($qs[0]) && $qs[0] == "opt")
	{
		unset($var);
		$var=array();
		$var['optgeneral']['text']	= LCLAN_OPT_MENU_1;
		$var['optmanager']['text']	= LCLAN_OPT_MENU_2;
		$var['optcategory']['text']	= LCLAN_OPT_MENU_3;
		$var['optlinks']['text']	= LCLAN_OPT_MENU_4;
		$var['optrefer']['text']	= LCLAN_OPT_MENU_5;
		$var['optrating']['text']	= LCLAN_OPT_MENU_6;
		$var['optmenu']['text']		= LCLAN_OPT_MENU_7;
		show_admin_menu(LCLAN_ADMINMENU_6, $qs[0], $var, TRUE);
	}
}

require_once(e_ADMIN.'footer.php');
exit;

// End ---------------------------------------------------------------------------------------------------------

?>