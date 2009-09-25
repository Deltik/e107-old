<?php
/*
 * e107 website system
 *
 * Copyright (C) 2001-2008 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * Tagwords Shortcodes
 *
 * $Source: /cvs_backup/e107_0.8/e107_plugins/tagwords/tagwords_shortcodes.php,v $
 * $Revision: 1.7 $
 * $Date: 2009-09-25 20:13:12 $
 * $Author: secretr $
 *
*/
if (!defined('e107_INIT')) { exit; }

/*
$codes = array(
'tag_search', 'tag_area_heading', 'tag_link', 'tag_cloud', 'tag_word', 'tag_number', 'tag_sort', 'tag_type', 'tag_area', 'tag_button', 'tag_options', 'tag_opt_min', 'tag_opt_class', 'tag_opt_default_sort', 'tag_opt_default_style', 'tag_opt_view_sort', 'tag_opt_view_style', 'tag_opt_view_area', 'tag_opt_view_search', 'tag_opt_view_freq', 'tag_opt_caption', 'tag_opt_seperator', 'tag_opt_activeareas', 'tag_opt_button'
);
*/

$codes = array();
/*
$tmp = get_class_methods('tagwords_shortcodes');
foreach($tmp as $c)
{
	if(strpos($c, 'sc_') === 0)
	{
		$codes[] = substr($c, 3);
	}
}
*/
register_shortcode('tagwords_shortcodes', TRUE);

initShortcodeClass('tagwords_shortcodes');

class tagwords_shortcodes
{
	var $e107;

	function tagwords_shortcodes()
	{
		$this->e107 = e107::getInstance();
	}

	function sc_tag_search($parm, $sc_mode)
	{
		global $tag;

		$value = (isset($_GET['q']) ? $this->e107->tp->toForm($_GET['q']) : '');
		switch($sc_mode)
		{
			case 'menu':
				if(varsettrue($tag->pref['tagwords_menu_view_search'])!=1)
				{
					return;
				}
				$id = 'tagwords_searchform_menu';

				return "
				<form id='".$id."' method='get' action='".e_PLUGIN_ABS."tagwords/tagwords.php'>
				<div>
				<input class='tbox' style='width:100px;' type='text'  name='q' size='35' value='".$value."' maxlength='50' />
				<input class='button' type='submit' name='se' value='".LAN_TAG_SEARCH_2."' />
				</div>
				</form>";
				break;

			case 'search':
				if(varsettrue($tag->pref['tagwords_view_search'])!=1)
				{
					return;
				}
				$id = 'tagwords_searchform';
				return "
				<form id='".$id."' method='get' action='".e_PLUGIN_ABS."tagwords/tagwords.php'>
				<div>
				<input class='tbox' style='width:100px;' type='text'  name='q' size='35' value='".$value."' maxlength='50' />
				<input class='button' type='submit' name='s' value='".LAN_TAG_SEARCH_2."' />
				</div>
				</form>";
				break;

			default:
				if(varsettrue($tag->pref['tagwords_view_search'])!=1)
				{
					return;
				}
				return "
				<input class='tbox' style='width:100px;' type='text'  name='q' size='35' value='".$value."' maxlength='50' />
				<input class='button' type='submit' name='s' value='".LAN_TAG_SEARCH_2."' />";
				break;
		}
	}

	function sc_tag_area_heading()
	{
		global $tag;
		return varsettrue($tag->area->settings['caption']);
	}

	function sc_tag_link($parm, $sc_mode)
	{
		global $tag;
		switch($sc_mode)
		{
			case 'menu':
				return "<a href='".e107::getUrl()->createTagwords()."'>".LAN_TAG_MENU_1."</a>";
				break;
			case 'home':
				return "<a href='".e107::getUrl()->createTagwords()."'>".LAN_TAG_7."</a>";
				break;
			default:
				if(method_exists($tag->area, 'getLink'))
				{
					return $tag->area->getLink($tag->id);
				}
				break;
		}
	}

	function sc_tag_cloud($parm, $sc_mode)
	{
		global $tag;
		switch($sc_mode)
		{
			case 'menu':
				return $tag->TagCloud('menu');
				break;
			case 'list':
				return $tag->TagCloudList();
				break;
			default:
				return $tag->TagCloud();
				break;
		}
	}

	function sc_tag_word($parm, $sc_mode)
	{
		global $tag;
		switch($sc_mode)
		{
			case 'result':
				return "<b>".$tag->num."</b> ".($tag->num==1 ? LAN_TAG_8 : LAN_TAG_9)." '<b>".$this->e107->tp->toHTML($_GET['q'],true)."</b>'";
				break;
			case 'form':
			default:
				return $tag->word;
				break;
		}
	}

	function sc_tag_number($parm, $sc_mode)
	{
		global $tag;
		switch($sc_mode)
		{
			case 'list':
				return $tag->number;
				break;
			case 'menu':
				if(varsettrue($tag->pref['tagwords_menu_view_freq'])==1)
				{
					return $tag->number;
				}
				break;
			default:
				if(varsettrue($tag->pref['tagwords_view_freq'])==1)
				{
					return $tag->number;
				}
				break;
		}
	}

	function sc_tag_sort()
	{
		global $tag;

		if(varsettrue($tag->pref['tagwords_view_sort'])==1)
		{
			$s = varset($_GET['sort'],'');
			switch($s)
			{
				case 'alpha':
					$sel = 'alpha';
					break;
				case 'freq':
					$sel = 'freq';
					break;
				default:
					$sel = '';
					break;
			}

			$text = "
			<select id='sort' name='sort' class='tbox'>
				<option value=''>".LAN_TAG_19."</option>
				<option value='alpha' ".($sel=='alpha' ? "selected='selected'" : '')." >".LAN_TAG_10."</option>
				<option value='freq' ".($sel=='freq' ? "selected='selected'" : '')." >".LAN_TAG_11."</option>
			</select>";
			return $text;
		}
		return;
	}

	function sc_tag_type()
	{
		global $tag;

		if(varsettrue($tag->pref['tagwords_view_style'])==1)
		{
			$t = varset($_GET['type'],'');
			switch($t)
			{
				case 'cloud':
					$sel = 'cloud';
					break;
				case 'list':
					$sel = 'list';
					break;
				default:
					$sel = '';
					break;
			}

			$text = "
			<select id='type' name='type' class='tbox'>
				<option value=''>".LAN_TAG_20."</option>
				<option value='cloud' ".($sel=='cloud' ? "selected='selected'" : '')." >".LAN_TAG_13."</option>
				<option value='list' ".($sel=='list' ? "selected='selected'" : '')." >".LAN_TAG_12."</option>
			</select>";
			return $text;
		}
		return;
	}

	function sc_tag_area()
	{
		global $tag;
		if(varsettrue($tag->pref['tagwords_view_area'])==1)
		{
			$text = "
			<select id='area' name='area' class='tbox'>
				<option value='' >".LAN_TAG_15."</option>";
				foreach($tag->tagwords as $area)
				{
					if(array_key_exists($area,$tag->pref['tagwords_activeareas']))
					{
						$name = "e_tagwords_{$area}";
						$selected = (varsettrue($_GET['area'])==$area ? "selected=selected" : '');
						$text .= "<option value='".$area."' ".$selected." >".$tag->$name->settings['caption']."</option>";
					}
				}
			$text .= "
			</select>";
			return $text;
		}
		return;
	}

	function sc_tag_button()
	{
		return "<input class='button' type='submit' name='so' value='".LAN_TAG_SEARCH_3."' />";
	}

	function sc_tag_options()
	{
		global $tag;
		if( varsettrue($tag->pref['tagwords_view_search'])==1 || varsettrue($tag->pref['tagwords_view_sort'])==1 || varsettrue($tag->pref['tagwords_view_style'])==1 || varsettrue($tag->pref['tagwords_view_area'])==1 )
		{
			return $this->e107->tp->parseTemplate($tag->template['options'], true, $tag->shortcodes);
		}
	}

	//##### ADMIN OPTIONS -------------------------

	function sc_tag_opt_min($parm, $sc_mode)
	{
		global $tag;
		$id = ($sc_mode=='menu' ? 'tagwords_menu_min' : 'tagwords_min');
		return "<input class='tbox' type='text' id='".$id."' name='".$id."' value='".$tag->pref[$id]."' size='3' maxlength='3' />";
	}

	function sc_tag_opt_class()
	{
		global $tag;
		$id = 'tagwords_class';
		return r_userclass($id,$tag->pref[$id],"","admin,public,guest,nobody,member,classes");
	}

	function sc_tag_opt_default_sort($parm, $sc_mode)
	{
		global $tag;
		$id = ($sc_mode=='menu' ? 'tagwords_menu_default_sort' : 'tagwords_default_sort');
		return "<label><input type='radio' name='".$id."' value='1' ".($tag->pref[$id] ? "checked='checked'" : "")." /> ".LAN_TAG_OPT_5."</label>&nbsp;&nbsp;
		<label><input type='radio' name='".$id."' value='0' ".($tag->pref[$id] ? "" : "checked='checked'")." /> ".LAN_TAG_OPT_6."</label>";
	}

	function sc_tag_opt_default_style()
	{
		global $tag;
		$id = 'tagwords_default_style';
		return "<label><input type='radio' name='".$id."' value='1' ".($tag->pref[$id] ? "checked='checked'" : "")." /> ".LAN_TAG_OPT_8."</label>&nbsp;&nbsp;
		<label><input type='radio' name='".$id."' value='0' ".($tag->pref[$id] ? "" : "checked='checked'")." /> ".LAN_TAG_OPT_9."</label>";
	}

	function sc_tag_opt_view_sort()
	{
		global $tag;
		$id = 'tagwords_view_sort';
		$sel = ($tag->pref[$id] ? "checked='checked'" : "");
		return "
		<label for='".$id."'>
			<input type='checkbox' name='".$id."' id='".$id."' value='1' ".$sel." /> ".LAN_TAG_OPT_12."
		</label>";
	}

	function sc_tag_opt_view_style()
	{
		global $tag;
		$id = 'tagwords_view_style';
		$sel = ($tag->pref[$id] ? "checked='checked'" : "");
		return "
		<label for='".$id."'>
			<input type='checkbox' name='".$id."' id='".$id."' value='1' ".$sel." /> ".LAN_TAG_OPT_13."
		</label>";
	}

	function sc_tag_opt_view_area()
	{
		global $tag;
		$id = 'tagwords_view_area';
		$sel = ($tag->pref[$id] ? "checked='checked'" : "");
		return "
		<label for='".$id."'>
			<input type='checkbox' name='".$id."' id='".$id."' value='1' ".$sel." /> ".LAN_TAG_OPT_14."
		</label>";
	}

	function sc_tag_opt_view_search($parm, $sc_mode)
	{
		global $tag;
		$id = ($sc_mode=='menu' ? 'tagwords_menu_view_search' : 'tagwords_view_search');
		$sel = ($tag->pref[$id] ? "checked='checked'" : "");
		return "
		<label for='".$id."'>
			<input type='checkbox' name='".$id."' id='".$id."' value='1' ".$sel." /> ".LAN_TAG_OPT_19."
		</label>";
	}

	function sc_tag_opt_view_freq($parm, $sc_mode)
	{
		global $tag;
		if($sc_mode=='menu')
		{
			$id = 'tagwords_menu_view_freq';
		}
		else
		{
			$id = 'tagwords_view_freq';
		}
		$sel = ($tag->pref[$id] ? "checked='checked'" : "");
		return "
		<label for='".$id."'>
			<input type='checkbox' name='".$id."' id='".$id."' value='1' ".$sel." /> ".LAN_TAG_OPT_20."
		</label>";
	}

	function sc_tag_opt_caption()
	{
		global $tag;
		$id = 'tagwords_menu_caption';
		return "<input class='tbox' type='text' id='".$id."' name='".$id."' value='".$this->e107->tp->toForm($tag->pref[$id],"","defs")."' size='30' maxlength='100' />";
	}

	function sc_tag_opt_seperator()
	{
		global $tag;
		$id = 'tagwords_word_seperator';
		return "<input class='tbox' type='text' id='".$id."' name='".$id."' value='".$this->e107->tp->toForm($tag->pref[$id])."' size='3' maxlength='10' />";
	}

	function sc_tag_opt_activeareas()
	{
		global $tag;
		$id = 'tagwords_activeareas';
		$text = "";
		foreach($tag->tagwords as $area)
		{
			$sel = (array_key_exists($area,$tag->pref[$id]) ? "checked='checked'" : '');
			$name = "e_tagwords_{$area}";

			$text .= "
			<label for='".$id."[".$area."]'>
			<input type='checkbox' name='".$id."[".$area."]' id='".$id."[".$area."]' value='1' ".$sel." />
			".$tag->$name->settings['caption']."
			</label><br />";

		}
		return $text;
	}

	function sc_tag_opt_button()
	{
		return "<input class='button' type='submit' name='updatesettings' value='".LAN_UPDATE."' />";
	}

}
?>