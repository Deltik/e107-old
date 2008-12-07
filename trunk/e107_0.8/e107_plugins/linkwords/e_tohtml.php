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
|     $Source: /cvs_backup/e107_0.8/e107_plugins/linkwords/e_tohtml.php,v $
|     $Revision: 1.3 $
|     $Date: 2008-12-07 21:55:01 $
|     $Author: e107steved $
|
| *utf - flags functions which need utf-8-aware code
TODO:
	1. Add utf-8 aware routines - done, test a bit more
	2. Add special effects for tooltips
	3. Caching?
|
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }
if (!plugInstalled('linkwords')) exit;


class e_tohtml_linkwords
{
	var $lw_enabled = FALSE;		// Default to disabled to start
	var $lwAjaxEnabled = FALSE;		// Adds in Ajax-compatible links
	var $utfMode	= '';			// Flag to enable utf-8 on regex
	var $word_list 	= array();		// List of link words/phrases
	var $link_list	= array();		// Corresponding list of links to apply
	var $ext_list	= array();		// Flags to determine 'open in new window' for link
	var $tip_list 	= array();		// Store for tooltips
	var $area_opts	= array();		// Process flags for the various contexts
	var $block_list = array();		// Array of 'blocked' pages
	var $LinkID		= array();		// Unique ID for each linkword

	
	/* constructor */
	function e_tohtml_linkwords()
	{
	  global $pref, $tp;
	// See whether they should be active on this page - if not, no point doing anything!
	  if ((strpos(e_SELF, ADMINDIR) !== FALSE) || (strpos(e_PAGE, "admin_") !== FALSE)) return;   // No linkwords on admin directories

// Now see if disabled on specific pages
	  $check_url = e_SELF.(e_QUERY ? "?".e_QUERY : '');
	  $this->block_list = explode("|",substr(varset($pref['lw_page_visibility'],''),2));    // Knock off the 'show/hide' flag
	  foreach ($this->block_list as $p)
	  {
		if ($p=trim($p))
		{
		if(substr($p, -1) == '!')
		{
		  $p = substr($p, 0, -1);
		  if(substr($check_url, strlen($p)*-1) == $p) return;
		}
		else 
		{
		  if(strpos($check_url, $p) !== FALSE) return;
		  }
		}
	  } 

	  // Will probably need linkwords on this page - so get the info
	  $link_sql = new db;
	  if($link_sql -> db_Select("linkwords", "*", "linkword_active!=1"))
	  {
		$this->lw_enabled = TRUE;
		  while ($row = $link_sql->db_Fetch())
		  {
		    extract($row);
//		    $lw = strtolower($linkword_word);					// It was trimmed when saved		*utf	
		    $lw = $tp->uStrToLower($linkword_word);					// It was trimmed when saved		*utf	
			if ($linkword_active == 2) $linkword_link = '';		// Make sure linkword disabled
			if ($linkword_active < 2) $linkword_tooltip = '';	// Make sure tooltip disabled
			$lwID = max($row['linkword_tip_id'], $row['linkword_id']);		// If no specific ID defined, use the DB record ID
			if (strpos($lw,','))
			{  // Several words to same link
			  $lwlist = explode(',',$lw);
			  foreach ($lwlist as $lw)
			  {
		        $this->word_list[] = trim($lw);
			    $this->link_list[] = $linkword_link;
			    $this->tip_list[] = $linkword_tooltip;
			    $this->ext_list[] = $linkword_newwindow;
				$this->LinkID[] = $lwID;
			  }
			}
			else
			{
				$this->word_list[] = $lw;
				$this->link_list[] = $linkword_link;
				$this->tip_list[] = $linkword_tooltip;
				$this->ext_list[] = $linkword_newwindow;
				$this->LinkID[] = $lwID;
			}
		  }
	  }
	  $this->area_opts = $pref['lw_context_visibility'];
	  $this->utfMode = (strtolower(CHARSET) == 'utf-8') ? 'u' : '';		// Flag to enable utf-8 on regex
	  $this->lwAjaxEnabled = varset($pref['lw_ajax_enable'],0);
	}


	function to_html($text,$area = 'olddefault')
	{
		if (!$this->lw_enabled || !array_key_exists($area,$this->area_opts) || !$this->area_opts[$area]) return $text;		// No linkwords in disabled areas

// Split up by HTML tags and process the odd bits here
		$ptext = "";
		$lflag = FALSE;

		// Shouldn't need utf-8 on next line - just looking for HTML tags
		$content = preg_split('#(<.*?>)#mis', $text, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );
		foreach($content as $cont)
		{
			if ($cont[0] == "<")
			{  // Its some HTML
				$ptext .= $cont;
				if (substr($cont,0,2) == "<a") $lflag = TRUE;
				if (substr($cont,0,3) == "</a") $lflag = FALSE;
			} 
			else 
			{  // Its the text in between
				if ($lflag)
				{  // Its probably within a link - leave unchanged
					$ptext .= $cont;
				}
				else
				{
					if (trim($cont))
					{  // Some non-white space - worth word matching
						$ptext .= $this->linksproc($cont,0,count($this->word_list));
//						echo "Check linkwords: ".count($this->word_list).'<br />';
					}
					else
					{
						$ptext .= $cont;
					}
				}
			}
		}
		return $ptext;
	}
	
	function linksproc($text,$first,$limit)
	{  // This function is called recursively - it splits the text up into blocks - some containing a particular linkword
		global $tp;

		// Consider next line - stripos is PHP5, and mb_stripos is PHP >= 5.2 - so may well often require handling
//		while (($first < $limit) && (stripos($text,$this->word_list[$first]) === FALSE))   { $first++; };		// *utf   (stripos is PHP5 - compatibility handler implements)
		while (($first < $limit) && (strpos($tp->uStrToLower($text),$this->word_list[$first]) === FALSE))   { $first++; };		// *utf  
		if ($first == $limit) return $text;		// Return if no linkword found

		// There's at least one occurrence of the linkword in the text
		// Prepare all info once only
		// If supporting Ajax, use the following:
		// <a href='link url' rel='external linkwordId::122' class='linkword-ajax'>
		// linkwordId::122 is a unique ID
		$ret = '';
		$linkwd = '';
		$linkrel = array();
//		$linkwd = "href='#' ";				// Not relevant for Prototype, but needed with 'pure' JS to make tooltip stuff work - doesn't find link elements without href
		$lwClass  = array();
		$lw = $this->word_list[$first];		// This is the word we're matching - in lower case in our 'master' list
		$tooltip = '';
		if ($this->tip_list[$first])
		{	// Got tooltip
			if ($this->lwAjaxEnabled)
			{
				$linkrel[] = 'linkwordID::'.$this->LinkID[$first];
				$lwClass[] = 'lw_ajax';
			}
			else
			{
				$tooltip = " title='{$this->tip_list[$first]}' ";
				$lwClass[] = 'lw_tip';
			}
		}
		if ($this->link_list[$first]) 
		{	// Got link
			$linkwd = " href='".$tp->replaceConstants($this->link_list[$first])."' ";
			if ($this->ext_list[$first]) { $linkrel[] = 'external'; }		// Determine external links
			$lwClass[] = 'lw_link';
		}
		if (!count($lwClass))
		{
			return $this->linksproc($sl,$first+1,$limit);		// Nothing to do - move on to next word (shouldn't really get here)
		}
		if (count($linkrel))
		{
			$linkwd .= " rel='".implode(' ',$linkrel)."'";
		}
		// This splits the text into blocks, some of which will precisely contain a linkword
		$split_line = preg_split('#\b('.$lw.')\b#i'.$this->utfMode, $text, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );		// *utf (selected)
		$class = "class='".implode(' ',$lwClass)."' ";
		foreach ($split_line as $sl)
		{
			if ($tp->uStrToLower($sl) == $lw)			// We know the linkword is already lower case							// *utf 
			{  // Do linkword replace
				$ret .= ' <a '.$class.$linkwd.$tooltip.'>'.$sl.'</a>';
			}
			elseif (trim($sl))
			{  // Something worthwhile left - look for more linkwords in it
				$ret .= $this->linksproc($sl,$first+1,$limit);
			}
			else
			{
				$ret .= $sl;   // Probably just some white space
			}
		}
		return $ret;
	} 
}

?>