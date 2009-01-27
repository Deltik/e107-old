<?php
/*
 * e107 website system
 *
 * Copyright (C) 2001-2008 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * List Shortcodes
 *
 * $Source: /cvs_backup/e107_0.8/e107_plugins/list_new/list_shortcodes.php,v $
 * $Revision: 1.2 $
 * $Date: 2009-01-27 21:33:52 $
 * $Author: lisa_ $
 *
*/

if (!defined('e107_INIT')) { exit; }

register_shortcode('list_shortcodes', true);
initShortcodeClass('list_shortcodes');

class list_shortcodes
{
	var $rc;
	var $e107;
	var $row;
	var $list_pref;

	function list_shortcodes()
	{
		$this->e107 = e107::getInstance();
	}

	function load_globals()
	{
		global $rc;
		$e107 = e107::getInstance();
		$e107->tp->e_sc->scClasses['list_shortcodes']->rc = $rc;
		$e107->tp->e_sc->scClasses['list_shortcodes']->row = $rc->row;
		$e107->tp->e_sc->scClasses['list_shortcodes']->list_pref = $rc->list_pref;
	}

	function get_list_date()
	{
		return $this->row['date'];
	}

	function get_list_icon()
	{
		return $this->row['icon'];
	}

	function get_list_heading()
	{
		return $this->row['heading'];
	}

	function get_list_author()
	{
		return $this->row['author'];
	}

	function get_list_category()
	{
		return $this->row['category'];
	}

	function get_list_info()
	{
		return $this->row['info'];
	}

	function get_list_caption()
	{
		return $this->rc->data['caption'];
	}

	function get_list_displaystyle()
	{
		//open sections if content exists ? yes if true, else use individual setting of section
		return (varsettrue($this->list_pref[$this->rc->mode."_openifrecords"]) && is_array($this->rc->data['records']) ? "" : $this->rc->data['display']);
	}

	function get_list_col_cols()
	{
		return $this->list_pref[$this->rc->mode."_colomn"];
	}

	function get_list_col_welcometext()
	{
		return $this->e107->tp->toHTML($this->list_pref[$this->rc->mode."_welcometext"]);
	}

	function get_list_col_cellwidth()
	{
		return round((100/$this->list_pref[$this->rc->mode."_colomn"]),0);
	}

	function get_list_timelapse()
	{
		return $this->row['timelapse'];
	}
}
?>