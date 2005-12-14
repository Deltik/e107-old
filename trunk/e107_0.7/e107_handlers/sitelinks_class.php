<?php
/*
+---------------------------------------------------------------+
|     e107 website system
|     /sitelinks_class.php
|
|     �Steve Dunstan 2001-2002
|     http://e107.org
|     jalist@e107.org
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $Source: /cvs_backup/e107_0.7/e107_handlers/sitelinks_class.php,v $
|     $Revision: 1.78 $
|     $Date: 2005-12-14 17:37:34 $
|     $Author: sweetas $
+---------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

@include_once(e_LANGUAGEDIR.e_LANGUAGE."/lan_sitelinks.php");
@include_once(e_LANGUAGEDIR."English/lan_sitelinks.php");

/**
* @return void
* @desc Outputs sitelinks
*/

class sitelinks
{

	var $eLinkList;

	function getlinks($cat=1)
	{
		global $sql;
		if ($sql->db_Select('links', '*', "link_category = $cat and link_class IN (".USERCLASS_LIST.") ORDER BY link_order ASC")){
			while ($row = $sql->db_Fetch())
			{
			//	if (substr($row['link_name'], 0, 8) == 'submenu.'){
			//		$tmp=explode('.', $row['link_name'], 3);
			 //		$this->eLinkList[$tmp[1]][]=$row;
				if (isset($row['link_parent']) && $row['link_parent'] != 0){
					$this->eLinkList['sub_'.$row['link_parent']][]=$row;
				}else{
					$this->eLinkList['head_menu'][] = $row;
				}
			}
		}

	}

	function get($cat=1,$style='')
	{
		global $pref, $ns, $e107cache;

		if (($data = $e107cache->retrieve('sitelinks_'.$cat)) && !strpos(e_SELF,e_ADMIN) ) {
			return $data;
		}

		if (LINKDISPLAY == 4) {
			require_once(e_PLUGIN.'ypslide_menu/ypslide_menu.php');
			return;
		}

		$this->getlinks($cat);

		// are these defines used at all ?

		if(!defined('PRELINKTITLE')){
			define('PRELINKTITLE', '');
		}
		if(!defined('PRELINKTITLE')){
			define('POSTLINKTITLE', '');
		}
		// -----------------------------

		if(!$style){
			$style['prelink'] = PRELINK;
			$style['linkdisplay'] = LINKDISPLAY;
			$style['postlink'] = POSTLINK;
			$style['linkclass'] = defined('LINKCLASS') ? LINKCLASS : "";
			$style['linkclass_hilite'] = defined('LINKCLASS_HILITE') ? LINKCLASS_HILITE : "";
			$style['linkstart_hilite'] = defined('LINKSTART_HILITE') ? LINKSTART_HILITE : "";
			$style['linkstart'] = LINKSTART;
			$style['linkdisplay'] = LINKDISPLAY;
			$style['linkend'] = LINKEND;
		}

    // Sublink styles.- replacing the tree-menu.
        if(isset($style['sublinkdisplay']) || isset($style['subindent']) || isset($style['sublinkclass']) || isset($style['sublinkstart']) || isset($style['sublinkend']) || isset($style['subpostlink'])){
			foreach($style as $key=>$val){
			  	$substyle[$key] = ($style["sub".$key]) ? $style["sub".$key] : $style[$key];
			}
        }else{
				$style['subindent'] = "&nbsp;&nbsp;";
				$substyle = $style;
		}

		$text = "\n\n\n<!-- Sitelinks ($cat) -->\n\n\n".$style['prelink'];

		if ($style['linkdisplay'] != 3)	{
			foreach ($this->eLinkList['head_menu'] as $link){
				$main_linkid = "sub_".$link['link_id'];

				$link['link_expand'] = (isset($pref['sitelinks_expandsub']) && isset($this->eLinkList[$main_linkid]) && is_array($this->eLinkList[$main_linkid])) ?  TRUE : FALSE;

				$text .= $this->makeLink($link,'', $style);

				if(!defined("LINKSRENDERONLYMAIN"))	/* if this is defined in theme.php only main links will be rendered */
				{

					// if there's a submenu. :
					if (isset($this->eLinkList[$main_linkid]) && is_array($this->eLinkList[$main_linkid])){
						$substyle = (strpos(e_SELF, $link['link_url']) !== FALSE || strpos(e_SELF, $link['link_name']) !== FALSE || $link['link_expand'] == FALSE) ? "visible" : "none";   // expanding sub-menus.
						$text .= "\n\n<div id='{$main_linkid}' style='display:$substyle'>\n";
						foreach ($this->eLinkList[$main_linkid] as $sub){
							$text .= $this->makeLink($sub, TRUE, $style);
						}
						$text .= "\n</div>\n\n";
					}
				}
			}
			$text .= $style['postlink'];
			if ($style['linkdisplay'] == 2)	{
				$text = $ns->tablerender(LAN_183, $text, 'sitelinks', TRUE);
			}
		}
		else
		{
			foreach($this->eLinkList['head_menu'] as $link)
			{
				if (!count($this->eLinkList['sub_'.$link['link_id']]))
				{
					$text .= $this->makeLink($link,'', $style);
				}
				$text .= $style['postlink'];
			}
			$text = $ns->tablerender(LAN_183, $text, 'sitelinks_main', TRUE);
			foreach(array_keys($this->eLinkList) as $k)
			{
				$mnu = $style['prelink'];
				foreach($this->eLinkList[$k] as $link)
				{
					if ($k != 'head_menu')
					{
						$mnu .= $this->makeLink($link, TRUE, $style);
					}
				}
				$mnu .= $style['postlink'];
				$text .= $ns->tablerender($k, $mnu, 'sitelinks_sub', TRUE);
			}
		}
		$text .= "\n\n\n<!--- end Site Links -->\n\n\n";
		$e107cache->set('sitelinks_'.$cat, $text);
	 	return $text;
	}

	function makeLink($linkInfo, $submenu = FALSE, $style='')
	{
		global $pref,$tp;

		// Start with an empty link
		$linkstart = $indent = $linkadd = $screentip = $href = $link_append = '';

		// If submenu: Fix Name, Add Indentation.
		if ($submenu == TRUE) {
			if(substr($linkInfo['link_name'],0,8) == "submenu."){
				$tmp = explode('.', $linkInfo['link_name'], 3);
				$linkInfo['link_name'] = $tmp[2];
			}
			$indent = ($style['linkdisplay'] != 3) ? $style['subindent'] : "";
		}

		// By default links are not highlighted.
		$linkstart = $style['linkstart'];
		$linkadd = ($style['linkclass']) ? " class='".$style['linkclass']."'" : "";

		// Check for screentip regardless of URL.
		if (isset($pref['linkpage_screentip']) && $pref['linkpage_screentip'] && $linkInfo['link_description']){
			$screentip = " title = '".$tp->toHTML($linkInfo['link_description'],"","defs")."'";
		}

		// Check if its expandable first. It should override its URL.
		if (isset($linkInfo['link_expand']) && $linkInfo['link_expand']){
			$href = " href=\"javascript:expandit('sub_".$linkInfo['link_id']."')\"";
		} elseif ($linkInfo['link_url']){

			// Only add the e_BASE if it actually has an URL.
			$linkInfo['link_url'] = (strpos($linkInfo['link_url'], '://') === FALSE && strpos($linkInfo['link_url'], 'mailto:') !== 0 ? e_HTTP.$linkInfo['link_url'] : $linkInfo['link_url']);

			// Only check if its highlighted if it has an URL
			if ($this->hilite($linkInfo['link_url'], $style['linkstart_hilite'])== TRUE) {
				$linkstart = $style['linkstart_hilite'];
			}
			if ($this->hilite($linkInfo['link_url'], $style['linkclass_hilite'])== TRUE) {
				$linkadd = " class='".$style['linkclass_hilite']."'";
			}

			if ($linkInfo['link_open'] == 4 || $linkInfo['link_open'] == 5){
				$dimen = ($linkInfo['link_open'] == 4) ? "600,400" : "800,600";
				$href = " href=\"javascript:open_window('".$linkInfo['link_url']."',{$dimen})\"";
			} else {
				$href = " href='".$linkInfo['link_url']."'";
			}

			// Open link in a new window.  (equivalent of target='_blank' )
			$link_append = ($linkInfo['link_open'] == 1) ? " rel='external'" : "";
		}

		// Remove default images if its a button and add new image at the start.
		if ($linkInfo['link_button']){
			$linkstart = preg_replace('/\<img.*\>/si', '', $linkstart);
			$linkstart .= "<img src='".e_IMAGE_ABS."icons/".$linkInfo['link_button']."' alt='' style='vertical-align:middle' />";
		}

		// If its a link.. make a link
		$_link = "";
		if (!empty($href)){
			$_link .= "<a".$linkadd.$screentip.$href.$link_append.">".$tp->toHTML($linkInfo['link_name'],"","emotes_off defs")."</a>";
		// If its not a link, but has a class or screentip do span:
		}elseif (!empty($linkadd) || !empty($screentip)){
			$_link .= "<span".$linkadd.$screentip.">".$tp->toHTML($linkInfo['link_name'],"","emotes_off defs")."</span>";
			// Else just the name:
		}else {
			$_link .= $tp->toHTML($linkInfo['link_name'],"","emotes_off defs");
		}

		$_link = $linkstart.$indent.$_link."\n";

		return $_link.$style['linkend'];
	}





function hilite($link,$enabled=''){
	global $PLUGINS_DIRECTORY,$tp,$pref;
    if(!$enabled){ return FALSE; }


// ----------- highlight overriding - set the link matching in the page itself.

	if(defined("HILITE")){
		if(strpos($link,HILITE)){
        	return TRUE;
		}
	}


// --------------- highlighting for 'HOME'. ----------------
	global $pref;
	if(strpos(e_SELF,$pref['frontpage']['all']) && $link == e_HTTP."index.php"){
		return TRUE;
	}

// --------------- highlighting for plugins. ----------------
		if(stristr($link, $PLUGINS_DIRECTORY) !== FALSE && stristr($link, "custompages") === FALSE){
			if(str_replace("?","",$link)){  // plugin links with queries
                $subq = explode("?",$link);

				if(strpos(e_SELF,$subq[0]) && e_QUERY == $subq[1]){
			   		return TRUE;
				}else{
					return FALSE;
				}
			}else{  // plugin links without queries
				$link = str_replace("../", "", $link);
		   		if(stristr(dirname(e_SELF), dirname($link)) !== FALSE){
 			 		return TRUE;
				}

			}
            return FALSE;
		}

		// --------------- highlight for news items.----------------
		// eg. news.php?list.1 or news.php?cat.2 etc

		if (strpos($link, "news.php?") !== FALSE && strpos(e_SELF,"/news.php")) {
            $tmp = explode("?",$link);
			$lnk = explode(".",$tmp[1]); // link queries.
			$qry = explode(".",e_QUERY); // current page queries.

			if($qry[0] == "item"){
				return ($qry[2] == $lnk[1]) ? TRUE : FALSE;
     		}

			if($lnk[0] == $qry[0] && $lnk[1] == $qry[1]){
            	return TRUE;
			}

		}

		// --------------- highlight default ----------------
		if(strpos($link, "?") !== FALSE){
			$subq = explode("?",$link);
			$linkq = $subq[1];
			$thelink = str_replace("../", "", $link);
			if((strpos(e_SELF,$thelink) !== false) && (strpos(e_QUERY,$linkq) !== false)){
		  		return true;
			}
		}
		if(!preg_match("/all|item|cat|list/", e_QUERY) && (strpos(e_SELF, str_replace("../", "",$link)) !== false)){
			return true;
		}

		return false;
	}
}
?>