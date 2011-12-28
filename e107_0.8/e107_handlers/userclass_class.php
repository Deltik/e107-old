<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2012 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * User class functions
 *
 * $URL$
 * $Id$
 */

/**
 *
 *	@package     e107
 *	@subpackage	e107_handlers
 *	@version 	$Id$;
 *
 *	This class handles all user-related user class functions.  Admin functions inherit from it.
 */

if (!defined('e107_INIT')) { exit; }

require_once(e_HANDLER.'arraystorage_class.php');

include_lan(e_LANGUAGEDIR.e_LANGUAGE.'/lan_userclass.php');


/*
Fixed classes occupy a numeric block from e_UC_SPECIAL_BASE to e_UC_SPECIAL_END, plus zero = e_UC_PUBLIC
(Note that in 0.7, class numbers stopped at 255. Now they can be up to 65535).
For info:
define("e_UC_PUBLIC", 0);
define("e_UC_MAINADMIN", 250);
define("e_UC_READONLY", 251);
define("e_UC_GUEST", 252);
define("e_UC_MEMBER", 253);
define("e_UC_ADMIN", 254);
define("e_UC_NOBODY", 255);
*/
define('e_UC_ADMINMOD',249);
define('e_UC_MODS',248);
define('e_UC_NEWUSER',247);					// Users in 'probationary' period
define('e_UC_BOTS',246);					// Reserved to identify search bots
											// 243..245 reserved for future predefined user classes
define('e_UC_SPECIAL_BASE',243);			// Assign class IDs 243 and above for fixed/special purposes
define('e_UC_SPECIAL_END',255);				// Highest 'special' class

define('UC_ICON_DIR',e_IMAGE_ABS.'generic/');		// Directory for the icons used in the admin tree displays

define('e_UC_BLANK','-1');
define('UC_TYPE_STD', '0');
define('UC_TYPE_GROUP', '1');

define('UC_CACHE_TAG', 'nomd5_classtree');


class user_class
{
	public $class_tree;						// Simple array, filled with current tree. Additional field class_children is an array of child user classes (by ID)
	protected $class_parents;				// Array of class IDs of 'parent' (i.e. top level) classes

	public $fixed_classes = array();		// The 'predefined' core classes (constants beginning 'e_UC_')  (would be nice to have this R/O outside)
	public $text_class_link = array();		// List of 'core' user classes and the related constants

	protected $sql_r;						// We'll use our own DB to avoid interactions
	protected $isAdmin;						// Set true if we're an instance of user_class_admin


	// Constructor
	public function __construct()
	{
		$this->sql_r = e107::getDb('sql_r');
		$this->isAdmin = FALSE;

		$this->fixed_classes = array(e_UC_PUBLIC => UC_LAN_0,
							e_UC_GUEST => UC_LAN_1,
							e_UC_NOBODY => UC_LAN_2,
							e_UC_MEMBER => UC_LAN_3,
							e_UC_ADMIN => UC_LAN_5,
							e_UC_MAINADMIN => UC_LAN_6,
							e_UC_READONLY => UC_LAN_4,
							e_UC_NEWUSER => UC_LAN_9,
							e_UC_BOTS => UC_LAN_10
							);

		$this->text_class_link = array('public' => e_UC_PUBLIC, 'guest' => e_UC_GUEST, 'nobody' => e_UC_NOBODY, 'member' => e_UC_MEMBER,
									'admin' => e_UC_ADMIN, 'main' => e_UC_MAINADMIN, 'new' => e_UC_NEWUSER, 'mods' => e_UC_MODS,
									'bots' => e_UC_BOTS, 'readonly' => e_UC_READONLY);

		$this->readTree(TRUE);			// Initialise the classes on entry
	}

	/**
 	*  Return value of isAdmin
 	*/
	public function isAdmin()
	{
		return $this->isAdmin;
	}

	/**
	 *	Ensure the tree of userclass data is stored in our object ($this->class_tree).
	 *	Only read if its either not present, or the $force flag is set.
	 *	Data is cached if enabled
	 *
	 *	@param boolean $force - set to TRUE to force a re-read of the info regardless.
	 *	@return none
	*/
	public function readTree($force = FALSE)
	{
		if (isset($this->class_tree) && count($this->class_tree) && !$force) return;

		$e107 = e107::getInstance();

		$this->class_tree = array();
		$this->class_parents = array();

		$array = new ArrayData;
		if ($temp = $e107->ecache->retrieve_sys(UC_CACHE_TAG))
		{
			$this->class_tree = $array->ReadArray($temp);
			unset($temp);
		}
		else
		{
			$this->sql_r->db_Select('userclass_classes', '*', "ORDER BY userclass_parent", 'nowhere');		// The order statement should give a consistent return

			while ($row = $this->sql_r->db_Fetch(MYSQL_ASSOC))
			{
				$this->class_tree[$row['userclass_id']] = $row;
				$this->class_tree[$row['userclass_id']]['class_children'] = array();		// Create the child array in case needed
			}


			// Add in any fixed classes that aren't already defined
			foreach ($this->fixed_classes as $c => $d)
			{
				if (!isset($this->class_tree[$c]))
				{
					switch ($c)
					{
						case e_UC_ADMIN :
						case e_UC_MAINADMIN :
							$this->class_tree[$c]['userclass_parent'] = e_UC_NOBODY;
							break;
						case e_UC_NEWUSER :
							$this->class_tree[$c]['userclass_parent'] = e_UC_MEMBER;
							break;
						default :
							$this->class_tree[$c]['userclass_parent'] = e_UC_PUBLIC;
					}
					$this->class_tree[$c]['userclass_id'] = $c;
					$this->class_tree[$c]['userclass_name'] = $d;
					$this->class_tree[$c]['userclass_description'] = 'Fixed class';
					$this->class_tree[$c]['userclass_visibility'] = e_UC_PUBLIC;
					$this->class_tree[$c]['userclass_editclass'] = e_UC_MAINADMIN;
					$this->class_tree[$c]['userclass_accum'] = $c;
					$this->class_tree[$c]['userclass_type'] = UC_TYPE_STD;
				}
			}

			$userCache = $array->WriteArray($this->class_tree, FALSE);
			$e107->ecache->set_sys(UC_CACHE_TAG,$userCache);
			unset($userCache);
		}


		// Now build the tree.
		// There are just two top-level classes - 'Everybody' and 'Nobody'
		$this->class_parents[e_UC_PUBLIC] = e_UC_PUBLIC;
		$this->class_parents[e_UC_NOBODY] = e_UC_NOBODY;
		foreach ($this->class_tree as $uc)
		{
			if (($uc['userclass_id'] != e_UC_PUBLIC) && ($uc['userclass_id'] != e_UC_NOBODY))
			{
				if (!isset($this->class_tree[$uc['userclass_parent']]))
				{
					echo "Orphaned class record: ID=".$uc['userclass_id']." Name=".$uc['userclass_name']."  Parent=".$uc['userclass_parent']."<br />";
				}
				else
				{	// Add to array
					$this->class_tree[$uc['userclass_parent']]['class_children'][] = $uc['userclass_id'];
				}
			}
		}
	}



	/** Given the list of 'base' classes a user belongs to, returns a comma separated list including ancestors. Duplicates stripped
	 *
	 *	@param string $startList - comma-separated list of classes user belongs to
	 *	@param boolean $asArray - if TRUE, result returned as array; otherwise result returned as string
	 *	@return string|array of user classes; format determined by $asArray
	 */
	public function get_all_user_classes($startList, $asArray = FALSE)
	{
		$is = array();
		$start_array = explode(',', $startList);
		foreach ($start_array as $sa)
		{	// Merge in latest values - should eliminate duplicates as it goes
			$is[] = $sa; // add parent to the flat list first
			if (isset($this->class_tree[$sa]))
			{
				if($this->class_tree[$sa]['userclass_accum'])
				{
					$is = array_merge($is,explode(',',$this->class_tree[$sa]['userclass_accum']));
				}
			}
		}
		if ($asArray)
		{
			return array_unique($is);
		}
		return implode(',',array_unique($is));
	}



	/** Returns a list of user classes which can be edited by the specified classlist
	 *
	 *	@param string $classList - comma-separated list of classes to consider - default current user's class list
	 *	@param boolean $asArray - if TRUE, result returned as array; otherwise result returned as string
	 *	@return string|array of user classes; format determined by $asArray
	 */
	public function get_editable_classes($classList = USERCLASS_LIST, $asArray = FALSE)
	{
		$ret = array();
		$blockers = array(e_UC_PUBLIC => 1, e_UC_READONLY => 1, e_UC_MEMBER => 1, e_UC_NOBODY => 1, e_UC_GUEST => 1, e_UC_NEWUSER => 1, e_UC_BOTS => 1);
		$possibles = array_flip(explode(',',$classList));
		unset($possibles[e_UC_READONLY]);
		foreach ($this->class_tree as $uc => $uv)
		{
			if (!isset($blockers[$uc]))
			{
				$ec = $uv['userclass_editclass'];
				if (isset($possibles[$ec]))
				{
					$ret[] = $uc;
				}
			}
		}
		if ($asArray) { return $ret; }
		return implode(',',$ret);
	}



	/**
	 *	Combines the selected editable classes into the main class list for a user.
	 *	@param array|string $combined - the complete list of current class memberships
	 *	@param array|string $possible - the classes which are being edited
	 *	@param array|string $actual - the actual membership of the editable classes
	 *	@param boolean $asArray - if TRUE, result returned as array; otherwise result returned as string
	 *	@return string|array of user classes; format determined by $asArray
	 */
	public function mergeClassLists($combined, $possible, $actual, $asArray = FALSE)
	{
		if (!is_array($combined)) { $combined = explode(',',$combined);  }
		if (!is_array($possible)) { $possible = explode(',',$possible);  }
		if (!is_array($actual)) 	{ $actual = explode(',',$actual);  }
		$combined = array_flip($combined);
		foreach ($possible as $p)
		{
			if (in_array($p,$actual))
			{	// Class must be in final array
				$combined[$p] = 1;
			}
			else
			{
				unset($combined[$p]);
			}
		}
		$combined = array_keys($combined);
		if ($asArray) { return $combined; }
		return implode(',', $combined);
	}



	/** Remove the fixed classes from a class list
	 *	Removes all classes in the reserved block, as well as e_UC_PUBLIC
	 *	@param array|string $inClasses - the complete list of current class memberships
	 *	@return string|array of user classes; format is the same as $inClasses
	 */
	public function stripFixedClasses($inClasses)
	{
		$asArray = TRUE;
		if (!is_array($inClasses))
		{
			$asArray = FALSE;
			$inClasses = explode(',',$inClasses);
		}
		/*
		$inClasses = array_flip($inClasses);
		foreach ($this->fixed_classes as $k => $v)
		{
			if (isset($inClasses[$k])) { unset($inClasses[$k]); }
		}
		$inClasses = array_keys($inClasses);
		*/
		foreach ($inClasses as $k => $uc)
		{
			if ((($uc >= e_UC_SPECIAL_BASE) && ($uc <= e_UC_SPECIAL_END)) || ($uc == e_UC_PUBLIC))
			{
				unset($inClasses[$k]);
			}
		}
		if ($asArray) { return ($inClasses); }
		return implode(',',$inClasses);
	}



	/** Given a comma separated list, returns the minimum number of class memberships required to achieve this (i.e. strips classes 'above' another in the tree)
	 *	Requires the class tree to have been initialised
	 *	@param array|string $classList - the complete list of current class memberships
	 *	@return string|array of user classes; format is the same as $inClasses
	 */
	public function normalise_classes($classList)
	{
		$asArray = TRUE;
		if (!is_array($inClasses))
		{
			$asArray = FALSE;
			$oldClasses = explode(',',$classList);
		}
		$dropClasses = array();
		foreach ($oldClasses as $c)
		{  // Look at our parents (which are in 'userclass_accum') - if any of them are contained in oldClasses, we can drop them.
			$tc = array_flip(explode(',',$this->class_tree[$c]['userclass_accum']));
			unset($tc[$c]);		// Current class should be in $tc anyway
			foreach ($tc as $tc_c => $v)
			{
				if (in_array($tc_c,$oldClasses))
				{
					$dropClasses[] = $tc_c;
				}
			}
		}
		$newClasses = array_diff($oldClasses,$dropClasses);
		if ($asArray) { return $newClasses; }
		return implode(',',$newClasses);
	}




	/** Generate a dropdown list of user classes from which to select - virtually as the deprecated r_userclass() function did
	 *	[ $mode parameter of r_userclass() removed - $optlist is more flexible) ]
	 * 	@param string $fieldname - name of select list
	 *	@param mixed $curval - current selected value (empty string if no current value)
	 *	@param string $optlist - comma-separated list of classes/class types to be included in the list
			It allows selection of the classes to be shown in the dropdown. All or none can be included, separated by comma. Valid options are:
			public
			guest
			nobody
			member
			readonly
			admin
			main - main admin
			new - new users
			bots - search bot class
			classes - shows all classes
			matchclass - if 'classes' is set, this option will only show the classes that the user is a member of
			blank - puts an empty option at the top of select dropdowns

			filter - only show those classes where member is in a class permitted to view them - e.g. as the new 'visible to' field - added for 0.8
			force  - show all classes (subject to the other options, including matchclass) - added for 0.8

			no-excludes - if present, doesn't show the 'not member of' list

			editable - can only appear on its own - returns list of those classes the user can edit (manage)

	 *	@param string $extra_js - can add JS handlers (e.g. 'onclick', 'onchange') if required
	*/
	public function uc_dropdown($fieldname, $curval = 0, $optlist = '', $extra_js = '')
	{
		$show_classes = self::uc_required_class_list($optlist);		// Get list of classes which meet criteria

		$text = '';
		foreach ($show_classes as $k => $v)
		{
			if ($k == e_UC_BLANK)
			{
				$text .= "<option value=''>&nbsp;</option>\n";
			}
			else
			{
				$s = ($curval == $k && $curval !== '') ?  "selected='selected'" : '';
				$text .= "<option class='uc-select'  value='".$k."' ".$s.">".$v."</option>\n";
			}
		}

		// Inverted Classes
		if(strpos($optlist, 'no-excludes') !== TRUE)
		{
			$text .= "\n<optgroup label=\"".UC_LAN_INVERTLABEL."\">\n";
			foreach ($show_classes as $k => $v)
			{
				if($k != e_UC_PUBLIC && $k != e_UC_NOBODY && $k != e_UC_READONLY)  // remove everyone, nobody and readonly from list.
				{
					$s = ($curval == ('-'.$k) && $curval !== '') ?  "selected='selected'" : '';
					$text .= "<option class='uc-select-inverted' value='-".$k."' ".$s.">".str_replace("--CLASS--", $v, UC_LAN_INVERT)."</option>\n";
				}
			}
			$text .= "</optgroup>\n";
		}

		// Only return the select box if we've ended up with some options
		if ($text) $text = "\n<select class='tbox select' name='{$fieldname}' {$extra_js}>\n".$text."</select>\n";
		return $text;
	}



	/**
	 *	Generate an ordered array  classid=>classname - used for dropdown and check box lists
	 *
	 *	@param string $optlist - comma-separated list of classes/class types to include (see uc_dropdown for details)
	 *	@param boolean $just_ids - if TRUE, each returned array value is '1'; otherwise it is the class name
	 *	@return array of user classes; ky is numeric class id, value is '1' or class name according to $just_ids
	 */
	public function uc_required_class_list($optlist = '', $just_ids = FALSE)
	{
		$ret = array();
		if (!$optlist) $optlist = 'public,guest,nobody,member,classes';		// Set defaults to simplify ongoing processing

		if ($optlist == 'editable')
		{
			$temp = array_flip(explode(',',$this->get_editable_classes()));
			if ($just_ids) return $temp;
			foreach ($temp as $c => $t)
			{
				$temp[$c] = $this->class_tree[$c]['userclass_name'];
			}
			return $temp;
		}

		//TODO - $optlist == all
		$opt_arr = explode(',',$optlist);
		foreach ($opt_arr as $k => $v)
		{
			$opt_arr[$k] = trim($v);
		}

		$opt_arr = array_flip($opt_arr);		// This also eliminates duplicates which could arise from applying the other options, although shouldn't matter

		if (isset($opt_arr['force'])) unset($opt_arr['filter']);

		if (isset($opt_arr['blank']))
		{
			$ret[e_UC_BLANK] = 1;
		}

		// Do the 'fixed' classes next
		foreach ($this->text_class_link as $k => $v)
		{
			//		if (isset($opt_arr[$k]) || isset($opt_arr['force']))
			if (isset($opt_arr[$k]))
			{
				$ret[$v] = $just_ids ? '1' : $this->fixed_classes[$v];
			}
		}

		// Now do the user-defined classes
		if (isset($opt_arr['classes']) || isset($opt_arr['force']))
		{	// Display those classes the user is allowed to:
			//	Main admin always sees the lot
			//	a) Mask the 'fixed' user classes which have already been processed
			//  b) Apply the visibility option field ('userclass_visibility')
			//  c) Apply the matchclass option if appropriate
			foreach($this->class_tree as $uc_id => $row)
			{
				if (!array_key_exists($uc_id,$this->fixed_classes)
				&& (   getperms('0')
					|| (
						(!isset($optlist['matchclass']) || check_class($uc_id))
						&& (!isset($optlist['filter']) || check_class($row['userclass_visibility']))
					   )
					)
					)
				{
				  $ret[$uc_id] = $just_ids ? '1' : $this->class_tree[$uc_id]['userclass_name'];
				}
			}
		}
		/* Above loop slightly changes the display order of earlier code versions.
			If readonly must be last, delete it from the $text_class_link array, and uncomment the following code

			if (isset($opt_arr['readonly']))
			{
			  $ret[e_UC_READONLY] = $this->class_tree[e_UC_READONLY]['userclass_description'];
			}
		*/
		return $ret;
	}



	/**
	 *	Very similar to self::uc_dropdown, but returns a list of check boxes. Doesn't encapsulate it.
	 *
	 *	@param string $fieldname is the name for the array of checkboxes
	 *	@param string $curval is a comma separated list of class IDs for boxes which are checked.
	 *	@param string $optlist as for uc_dropdown
	 *	@param boolean $showdescription - if TRUE, appends the class description in brackets
	 *	@param boolean $asArray - if TRUE, result returned as array; otherwise result returned as string
	 *
	 *	return string|array according to $asArray
	 */
	public function uc_checkboxes($fieldname, $curval='', $optlist = '', $showdescription = FALSE, $asArray = FALSE)
	{
		$show_classes = $this->uc_required_class_list($optlist);

		$curArray = explode(',', $curval);				// Array of current values
		$ret = array();

		foreach ($show_classes as $k => $v)
		{
			if ($k != e_UC_BLANK)
			{
				$c = (in_array($k,$curArray)) ?  " checked='checked'" : '';
				if ($showdescription) $v .= ' ('.$this->uc_get_classdescription($k).')';
				$ret[] = "<div class='field-spacer'><input type='checkbox' class='checkbox' name='{$fieldname}[{$k}]' id='{$fieldname}-{$k}' value='{$k}'{$c} /><label for='{$fieldname}-{$k}'>".$v."</label></div>\n";
			}
		}
		if ($asArray) return $ret;
		return implode('', $ret);
	}



	/**
	 *	Used by @see{vetted_tree()} to generate lower levels of tree
	 */
	protected function vetted_sub_tree($treename, $callback,$listnum,$nest_level,$current_value, $perms, $opt_options)
	{
		$ret = '';
		$nest_level++;
		if(isset($this->class_tree[$listnum]['class_children']))
		{
			foreach ($this->class_tree[$listnum]['class_children'] as $p)
			{
				// Looks like we don't need to differentiate between function and class calls
				if (isset($perms[$p]))
				{
					$ret .= call_user_func($callback,$treename, $p,$current_value,$nest_level, $opt_options);
				}
				$ret .= $this->vetted_sub_tree($treename, $callback,$p,$nest_level,$current_value, $perms, $opt_options);
			}
		}
		return $ret;
	}


	/**
	 *	create an indented tree - for example within a select box or a list of check boxes.
	 *	For each displayed element, the callback routine is called
	 *	@param string $treename is the name given to the elements where required
	 *	@param	function|object $callback is a routine used to generate each element; there are three implemented within this class:
	 *		select (the default) - generates the option list. Text requires to be encapsulated in a <select......./select> tag set
	 *			- can also be used with multi-select boxes
	 *		checkbox - generates a set of checkboxes
	 *		checkbox_desc - generates a set of checkboxes with the class description in brackets
	 *		Alternative callbacks can be used to achieve different layouts/styles
	 *	@param integer|string $current_value - single class number for single-select dropdown; comma separated array of class numbers for checkbox list or multi-select
	 *	@param string $optlist works the same as for @see uc_dropdown()
	 *	@return string - formatted HTML for tree
	*/
	public function vetted_tree($treename, $callback='', $current_value='', $optlist = '',$opt_options = '')
	{
		$ret = '';
		if (!$callback) $callback=array($this,'select');
		$current_value = str_replace(' ','',$current_value);				// Simplifies parameter passing for the tidy-minded

		$perms = $this->uc_required_class_list($optlist,TRUE);				// List of classes which we can display
		if (isset($perms[e_UC_BLANK]))
		{
			$ret .= call_user_func($callback,$treename, e_UC_BLANK, $current_value,0, $opt_options);
		}
		foreach ($this->class_parents as $p)
		{
			if (isset($perms[$p]))
			{
				$ret .= call_user_func($callback,$treename, $p,$current_value,0, $opt_options);
			}
			$ret .= $this->vetted_sub_tree($treename, $callback,$p,0, $current_value, $perms, $opt_options);
		}
		return $ret;
	}


	/**
	 *	Callback for vetted_tree - Creates the option list for a selection box
	 *	It is the caller's responsibility to enclose this list in a <select...../select> structure
	 *	Can be used as a basis for similar functions
	 *
	 *	@param string $treename	- name of tree elements (not used with select; used with checkboxes, for example)
	 *	@param integer $classnum - user class being displayed.
	 *			- special value e_UC_BLANK adds a blank option in the list.
	 *	@param integer|string $current_value - single class number for single-select dropdown; comma separated array of class numbers for checkbox list or multi-select
	 *	@param integer $nest_level - 'depth' of this item in the tree. Zero is base level. May be used to indent or highlight dependent on level
	 *	@return string - option list
	 */
	public function select($treename, $classnum, $current_value, $nest_level)
	{
		if ($classnum == e_UC_BLANK)  return "<option value=''>&nbsp;</option>\n";
		$tmp = explode(',',$current_value);
		$sel = in_array($classnum,$tmp) ? " selected='selected'" : '';
		if ($nest_level == 0)
		{
			$prefix = '';
			$style = " style='font-weight:bold; font-style: italic;'";
		}
		elseif ($nest_level == 1)
		{
			$prefix = '&nbsp;&nbsp;';
			$style = " style='font-weight:bold'";
		}
		else
		{
			$prefix = '&nbsp;&nbsp;'.str_repeat('--',$nest_level-1).'>';
			$style = '';
		}
		return "<option value='{$classnum}'{$sel}{$style}>".$prefix.$this->class_tree[$classnum]['userclass_name']."</option>\n";
	}


	/**
	 *	Callback for vetted_tree - displays indented checkboxes with class name only
	 *	See @link select for parameter details
	 */
	public function checkbox($treename, $classnum, $current_value, $nest_level)
	{
		if ($classnum == e_UC_BLANK)  return '';
		$tmp = explode(',',$current_value);
		$chk = in_array($classnum, $tmp) ? " checked='checked'" : '';
		if ($nest_level == 0)
		{
			$style = " style='font-weight:bold'";
		}
		else
		{
			$style = " style='text-indent:".(1.2*$nest_level)."em'";
		}
		return "<div {$style}><input type='checkbox' class='checkbox' name='{$treename}[]' id='{$treename}_{$classnum}' value='{$classnum}'{$chk} />".$this->class_tree[$classnum]['userclass_name']."</div>\n";
	}


	/**
	 *	Callback for vetted_tree - displays indented checkboxes with class name, and description in brackets
	 *	See @link select for parameter details
	 */
	public function checkbox_desc($treename, $classnum, $current_value, $nest_level)
	{
		if ($classnum == e_UC_BLANK)  return '';
		$tmp = explode(',',$current_value);
		$chk = in_array($classnum, $tmp) ? " checked='checked'" : '';
		if ($nest_level == 0)
		{
			$style = " style='font-weight:bold'";
		}
		else
		{
			$style = " style='text-indent:".(1.2*$nest_level)."em'";
		}
		return "<div {$style}><input type='checkbox' class='checkbox' name='{$treename}[]' id='{$treename}_{$classnum}' value='{$classnum}'{$chk} />".$this->class_tree[$classnum]['userclass_name'].'  ('.$this->class_tree[$classnum]['userclass_description'].")</div>\n";
	}




	/**
	 *	Return array of all classes, limited according to membership of the userclass_visibility field if $filter is set.
	 *	@param string|integer $filter - user class or class list in format acceptable to check_class()
	 *	@return array of class elements, each itself an array:
	 *		Index field - userclass_id
	 *		Data fields - userclass_name, userclass_description, userclass_editclass
	 */
	public function uc_get_classlist($filter = FALSE)
	{
		$ret = array();
		$this->readTree(FALSE);				// Make sure we have data
		foreach ($this->class_tree as $k => $v)
		{
			if (!$filter || check_class($filter))
			{
				$ret[$k] = array('userclass_name' => $v, 'userclass_description' => $v['userclass_description'], 'userclass_editclass' => $v['userclass_editclass']);
			}
		}
		return $ret;
	}



	/**
	 *	Return class name for given class ID
	 *	Handles 'not a member of...' construct by replacing '--CLASS--' in UC_LAN_INVERT with the class name
	 *	@param integer $id - class number. A negative number indicates 'not a member of...'
	 *	@return string class name
	 */
	public function uc_get_classname($id)
	{
		if (isset($this->class_tree[$id]))
		{
			return $this->class_tree[$id]['userclass_name'];
		}
		if (isset($this->fixed_classes[$id]))
		{
			return $this->fixed_classes[$id];
		}

		if($id < 0)
		{
			$val = abs($id);
			$name = isset($this->class_tree[$val]['userclass_name']) ? $this->class_tree[$val]['userclass_name'] : $this->fixed_classes[$val];
			return str_replace('--CLASS--', $name, UC_LAN_INVERT);
		}
		return '';
	}



	/**
	 *	Return class description for given class ID
	 *	@param integer $id - class number. Must be >= 0
	 *	@return string class description
	 */
	public function uc_get_classdescription($id)
	{
		if (isset($this->class_tree[$id]))
		{
			return $this->class_tree[$id]['userclass_description'];
		}
		if (isset($this->fixed_classes[$id]))
		{
			return $this->fixed_classes[$id];	// Name and description the same for fixed classes
		}
		return '';
	}



	/**
	 *	Return class icon for given class ID
	 *	@param integer $id - class number. Must be >= 0
	 *	@return string class icon if defined, otherwise empty string
	 */
	public function uc_get_classicon($id)
	{
		if (isset($this->class_tree[$id]))
		{
			return $this->class_tree[$id]['userclass_icon'];
		}
		return '';
	}



	/**
	 *	Look up class ID for a given class name
	 *	@param string $name - class name
	 *	@return integer|boolean FALSE if not found, else user class ID
	 */
	public function ucGetClassIDFromName($name)
	{
		$this->readTree();
		// We have all the info - can just search the array
		foreach ($this->class_tree as $uc => $info)
		{
			if ($info['userclass_name'] == $name)
			{
				return $uc;
			}
		}
		return FALSE;		// not found
	}



	/**
	 *	Utility to remove a specified class ID from the default comma-separated list
	 *	Optional conversion to array of classes
	 *	@param integer $classID - class number. Must be >= 0
	 *	@param string $from - comma separated list of class numbers
	 *	@param boolean $asArray - if TRUE, result returned as array; otherwise result returned as string
	 *	@return string class description
	 */
	public function ucRemove($classID, $from, $asArray = FALSE)
	{
		$tmp = array_flip(explode(',',$from));
		if (isset($tmp[$classID]))
		{
			unset($tmp[$classID]);
		}
		$tmp = array_keys($tmp);
		if ($asArray) { return $tmp; }
		if (count($tmp) == 0) { return ''; }
		return implode(',',$tmp);
	}



	/**
	 *	Utility to add a specified class ID to the default comma-separated list
	 *	Optional conversion to array of classes
	 *	@param integer $classID - class number. Must be >= 0
	 *	@param string $to - comma separated list of class numbers
	 *	@param boolean $asArray - if TRUE, result returned as array; otherwise result returned as string
	 *	@return string class description
	 */
	public function ucAdd($classID, $to, $asArray = FALSE)
	{
		$tmp = array_flip(explode(',',$to));
		$tmp[$classID] = 1;
		$tmp = array_keys($tmp);
		if ($asArray) { return $tmp; }
		return implode(',',$tmp);
	}



	/**
	 *	See if a class can be edited (in the sense of the class ID not being fixed)
	 *	@param integer $classID - class number. Must be >= 0
	 *	@return boolean - TRUE if class can be edited
	 */
	public function isEditableClass($classID)
	{
		if (($classID >= e_UC_SPECIAL_BASE) && ($classID <= e_UC_SPECIAL_END)) return FALSE;	// Don't allow deletion of fixed classes
		if (isset($this->fixed_classes[$class_id])) return FALSE;			// This picks up classes such as e_UC_PUBLIC outside the main range which can't be deleted
		return TRUE;
	}


	/**
	 *	Return all users in a particular class or set of classes.
	 *
	 ****** Can be verrrrryyyy slow - has to scan the whole user database at present ******
	 *
	 ********* NOT TESTED **********
	 *
	 ***** NOT SURE WHETHER THIS IS REALLY A USER OR A USER CLASS FUNCTION *****
	 *	@param string $classList - comma separated list of classes
	 *	@param string $fieldList - comma separated list of fields to be returned. `user_id` is always returned as the key of the array entry
	 *	@param boolean $includeAncestors - if TRUE, also looks for classes in the hierarchy; otherwise checks exactly the classes passed
	 *	@param string $orderBy - optional field name to define the order of entries in the results array
	 *	@return array indexed by user_id, each element is an array (database row) containing the requested fields
	 */
	public function get_users_in_class($classList, $fieldList = 'user_name, user_loginname', $includeAncestors = FALSE, $orderBy = 'user_id')
	{
		$ret = array();
		$classList = str_replace(' ','', $classList); // clean up white spaces
		if ($includeAncestors) $classList = $this->get_all_user_classes($classList);
		$class_regex = "(^|,)(".str_replace(",", "|", $classList).")(,|$)";
		$qry = "SELECT user_id,{$fieldList} FROM `#user` WHERE user_class REGEXP '{$class_regex}' ORDER BY '{$orderBy}'";
		if ($this->sql_r->db_Select_gen($qry))
		{
			while ($row = $this->sql_r->db_Fetch(MYSQL_ASSOC))
			{
				$ret[$row['user_id']] = $row;
			}
		}
		return $ret;
	}
}


//========================================================================
//			Functions from previous userclass_class handler
//========================================================================
// Implemented for backwards compatibility/convenience.

// ************** DEPRECATED - use new class-based functions
// Refer to the corresponding class-based functions for full details

/*
With $optlist you can now specify which classes are shown in the dropdown.
All or none can be included, separated by comma (or whatever).
Valid options are:
public
guest
nobody
member
readonly
admin
main - main admin
classes - shows all classes
matchclass - if 'classes' is set, this option will only show the classes that the user is a member of

filter - only show those classes where member is in a class permitted to view them - i.e. as the new 'visible to' field
force  - show all classes (subject to the other options, including matchclass)

$mode = 'off' turns off listing of admin/main admin classes unless enabled in $optlist (can probably be deprecated - barely used)

*/


function r_userclass($fieldname, $curval = 0, $mode = "off", $optlist = "")
{
//  echo "Call r_userclass{$fieldname}, CV: {$curval}  opts: {$optlist}<br />";
  global $e_userclass;
  if ($mode != 'off')
  {	// Handle legacy code
	if ($optlist) $optlist .= ',';
	$optlist .= 'admin,main';
	if ($mode != 'admin') $optlist .= ',readonly';
  }
  if (!is_object($e_userclass)) $e_userclass = new user_class;
  return $e_userclass->uc_dropdown($fieldname,$curval,$optlist);
}


// Very similar to r_userclass, but returns a list of check boxes. (currently only used in newspost.php)
// $curval is a comma separated list of class IDs for boxes which are checked.
function r_userclass_check($fieldname, $curval = '', $optlist = "")
{
//  echo "Call r_userclass_check: {$curval}<br />";
  global $e_userclass;
  if (!is_object($e_userclass)) $e_userclass = new user_class;
  $ret = $e_userclass->uc_checkboxes($fieldname,$curval,$optlist);
  if ($ret) $ret = "<div class='check-block'>".$ret."</div>";
  return $ret;
}



function get_userclass_list()
{
//  echo "Call get_userclass_list<br />";
  global $e_userclass;
  if (!is_object($e_userclass)) $e_userclass = new user_class;
  return $e_userclass->uc_get_classlist();
}



function r_userclass_name($id)
{
//  echo "Call r_userclass_name<br />";
  global $e_userclass;
  if (!is_object($e_userclass)) $e_userclass = new user_class;
  return $e_userclass->uc_get_classname($id);
}





// Deprecated functions to hopefully be removed
function r_userclass_radio($fieldname, $curval = '')
{
  echo "Deprecated function r_userclass_radio not used in core - mutter if you'd like it implemented<br />";
}

//========================================================================
//			Admin Class handler - could go into separate file later
//========================================================================

class user_class_admin extends user_class
{
	protected $field_list = array('userclass_name' => "varchar(100) NOT NULL default ''",
							'userclass_description' => "varchar(250) NOT NULL default ''",
							'userclass_editclass' => "tinyint(3) unsigned NOT NULL default '0'",
							'userclass_parent' => "tinyint(3) unsigned NOT NULL default '0'",
							'userclass_accum' => "varchar(250) NOT NULL default ''",
							'userclass_visibility' => "tinyint(3) unsigned NOT NULL default '0'",
							'userclass_type'		=>"tinyint(1) unsigned NOT NULL default '0'",
							'userclass_icon' => "varchar(250) NOT NULL default ''"
							);		// Note - 'userclass_id' intentionally not in this list


	protected $graph_debug = FALSE;			// Shows extra info on graphical tree when TRUE

	// Icons to use for graphical tree display
	// First index - no children, children
	// Second index - not last item, last item
	// Third index - closed tree, open tree
	protected $tree_icons = array(
						FALSE => array(			// No children
							FALSE => array(			// Not last item
							  FALSE => '',		// Closed tree - don't display
							  TRUE  => 'branch.gif'
							  )
							,
							TRUE => array(			// Last item
							  FALSE => '',		// Closed tree - don't display
							  TRUE  => 'branchbottom.gif'
						    )
						),
						TRUE => array(			// children
							FALSE => array(			// Not last item
							  FALSE => 'plus.gif',		// Closed tree - option to expand
							  TRUE  => 'minus.gif'
							  )
							,
							TRUE => array(			// Last item
							  FALSE => 'plusbottom.gif',		// Closed tree - option to expand
							  TRUE  => 'minusbottom.gif'
							))
						);


	/**
	 *	Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->isAdmin = TRUE;
	}



	/**
	 *	Next three routines are used to update the database after adding/deleting a class
	 *	calc_tree is the public interface
	 *	@return none
	*/
	public function calc_tree()
	{
		$this->readTree(TRUE);			// Make sure we have accurate data
		foreach ($this->class_parents as $cp)
		{
			$rights = array();
			$this->rebuild_tree($cp,$rights);		// increasing rights going down the tree
		}
	}


	/*
	 *	Internal function, called recursively to rebuild the permissions tree where rights increase going down the tree
	 *	If the permissions change, sets the 'change_flag' to force rewrite to DB (by other code)
	 *	@param integer $parent is the class number being processed.
	 *	@param array $rights is the array of rights accumulated so far in the walk down the tree
	 */
	protected function rebuild_tree($parent, $rights)
	{
		if ($this->class_tree[$parent]['userclass_parent'] == e_UC_NOBODY)
		{
			$this->topdown_tree($parent);
			return;
		}
		if ($this->class_tree[$parent]['userclass_type'] == UC_TYPE_GROUP)
		{
			return;			// Probably just stop here for a group class
		}
		$rights[]  = $parent;
		$imp_rights = implode(',',$rights);
		if ($this->class_tree[$parent]['userclass_accum'] != $imp_rights)
		{
			$this->class_tree[$parent]['userclass_accum'] = $imp_rights;
			if (!isset($this->class_tree[$cp]['change_flag'])) $this->class_tree[$parent]['change_flag'] = 'UPDATE';
		}
		foreach ($this->class_tree[$parent]['class_children'] as $cc)
		{
			$this->rebuild_tree($cc,$rights);		// Recursive call
		}
	}


	/*
	 * Internal function, called recursively to rebuild the permissions tree where rights increase going up the tree
	 *	@param integer $our_class - ID of class being processed
	 *	@return array of class permissions
	 */
	protected function topdown_tree($our_class)
	{
		$rights  = array($our_class);				// Accumulator always has rights to its own class

		if ($this->class_tree[$our_class]['userclass_type'] == UC_TYPE_GROUP) return array_merge($rights, explode(',',$this->class_tree[$our_class]['userclass_accum']));					// Stop rights accumulation at a group

		foreach ($this->class_tree[$our_class]['class_children'] as $cc)
		{
			$rights = array_merge($rights,$this->topdown_tree($cc));				// Recursive call
		}
		$rights = array_unique($rights);
		$imp_rights = implode(',',$rights);
		if ($this->class_tree[$our_class]['userclass_accum'] != $imp_rights)
		{
			$this->class_tree[$our_class]['userclass_accum'] = $imp_rights;
			$this->class_tree[$our_class]['change_flag'] = 'UPDATE';
		}
		return $rights;
	}



	/**
	 *	Called after recalculating the tree to save changed records to the database
	 *	@return none
	 */
	public function save_tree()
	{
		foreach ($this->class_tree as $tree)
		{
			if (isset($tree['change_flag']))
			{
				switch ($tree['change_flag'])
				{
				  case 'INSERT' :
					$this->add_new_class($tree);
					break;
				  case 'UPDATE' :
					$this->save_edited_class($tree);
					break;
				  default :
					continue;
				}
			}
		}
	}



	/**
	 *	Next two routines show a text-based tree with markers to indicate the hierarchy.
	 *	Intended primarily for debugging
	 */
	protected function show_sub_tree($listnum,$marker, $add_class = FALSE)
	{
		$ret = '';
		$marker = '--'.$marker;
		foreach ($this->class_tree[$listnum]['class_children'] as $p)
		{
			$ret .= $marker.$this->class_tree[$p]['userclass_id'].':'.$this->class_tree[$p]['userclass_name'];
			if ($add_class) $ret .= " (".$this->class_tree[$p]['userclass_accum'].")";
			$ret .= "  Children: ".count($this->class_tree[$p]['class_children']);
			$ret .= "<br />";
			$ret .= $this->show_sub_tree($p,$marker, $add_class);
		}
		return $ret;
	}


	/**
	 *	Show a text-based sub-tree, optionally with helpful debug info
	 *	@param boolean $add_class - TRUE includes a list of accumulated rights in each line
	 *	@return string text for display
	 */
	public function show_tree($add_class = FALSE)
	{
		$ret = '';
		foreach ($this->class_parents as $p)
		{
		  $ret .= $this->class_tree[$p]['userclass_id'].':'.$this->class_tree[$p]['userclass_name'];
		  if ($add_class) $ret .= " (".$this->class_tree[$p]['userclass_accum'].")";
		  $ret .= "  Children: ".count($this->class_tree[$p]['class_children']);
		  $ret .= "<br />";
		  $ret .= $this->show_sub_tree($p,'>', $add_class);
		}
		return $ret;
	}




	/*
	Next two routines generate a graphical tree, including option to open/close branches
	*/
	protected function show_graphical_subtree($listnum, $indent_images, $is_last = FALSE)
	{
		$num_children = count($this->class_tree[$listnum]['class_children']);
		$is_open = TRUE;
		$tag_name = 'uclass_tree_'.$listnum;

		$ret = "<div class='uclass_tree' style='height: 20px'>\n";

		foreach ($indent_images as $im)
		{
			$ret .= "<img src='".UC_ICON_DIR.$im."' alt='class icon' />";
		}
		// If this link has children, wrap the next image in a link and an expand/hide option
		if ($num_children)
		{
			$ret .= "<span onclick=\"javascript: expandit('{$tag_name}'); expandit('{$tag_name}_p'); expandit('{$tag_name}_m')\"><img src='".UC_ICON_DIR.$this->tree_icons[TRUE][$is_last][TRUE]."' alt='class icon' id='{$tag_name}_m' />";
			$ret .= "<img src='".UC_ICON_DIR.$this->tree_icons[TRUE][$is_last][FALSE]."' style='display:none' id='{$tag_name}_p' alt='class icon' /></span>\n";
		}
		else
		{
			$ret .= "<img src='".UC_ICON_DIR.$this->tree_icons[FALSE][$is_last][$is_open]."' alt='class icon' />\n";
		}
		$name_line = '';
		if ($this->graph_debug) { $name_line = $this->class_tree[$listnum]['userclass_id'].":";  }
	//	if ($this->graph_debug) { $name_line = varset($this->class_tree[$listnum]['userclass_id'], 'XXX').":";  }

		if ($this->class_tree[$listnum]['userclass_type'] == UC_TYPE_GROUP)
		{
			$name_line .= '<b>'.$this->class_tree[$listnum]['userclass_name'].'</b> '.UCSLAN_84;	// Highlight groups
		}
		else
		{
			$name_line .= $this->class_tree[$listnum]['userclass_name'];
		}
		if ($this->graph_debug) $name_line .= "[vis:".$this->class_tree[$listnum]['userclass_visibility'].", edit:".$this->class_tree[$listnum]['userclass_editclass']."] = ".$this->class_tree[$listnum]['userclass_accum']." Children: ".implode(',',$this->class_tree[$listnum]['class_children']);
		// Next (commented out) line gives a 'conventional' link
		//$ret .= "<img src='".UC_ICON_DIR."topicon.png' alt='class icon' /><a style='text-decoration: none' class='userclass_edit' href='".e_ADMIN_ABS."userclass2.php?config.edit.{$this->class_tree[$listnum]['userclass_id']}'>".$name_line."</a></div>";
		if($this->isEditableClass($this->class_tree[$listnum]['userclass_id']))
		{
			$url = e_SELF.'?action=edit&amp;id='.$this->class_tree[$listnum]['userclass_id'];
			$onc = '';
		}
		else
		{
			$url = '#';
			$onc = " onclick=\"alert('".str_replace("'", "\\'", (stripslashes(UCSLAN_90)))."'); return false;\"";
		}

		$ret .= "<img src='".UC_ICON_DIR."topicon.png' alt='class icon' /><a style='text-decoration: none' class='userclass_edit'{$onc} href='{$url}'>".$name_line."</a></div>";
		//$ret .= "<img src='".UC_ICON_DIR."topicon.png' alt='class icon' />
			//<span style='cursor:pointer; vertical-align: bottom' onclick=\"javascript: document.location.href='".e_ADMIN."userclass2.php?config.edit.{$this->class_tree[$listnum]['userclass_id']}'\">".$name_line."</span></div>";
		// vertical-align: middle doesn't work! Nor does text-top

		if ($num_children)
		{
			$ret .= "<div class='uclass_tree' id='{$tag_name}'>\n";
			$image_level = count($indent_images);
			if ($is_last)
			{
				$indent_images[] = 'linebottom.gif';
			}
			else
			{
				$indent_images[] = 'line.gif';
			}
			foreach ($this->class_tree[$listnum]['class_children'] as $p)
			{
				$num_children--;
				if ($num_children)
				{	// Use icon indicating more below
				  $ret .= $this->show_graphical_subtree($p, $indent_images, FALSE);
				}
				else
				{ // else last entry on this tree
				  $ret .= $this->show_graphical_subtree($p, $indent_images, TRUE);
				}
			}
			$ret .= "</div>";
		}
		return $ret;
	}



	/**
	 * Create graphical class tree, including clickable links to expand/contract branches.
	 * @param boolean $show_debug - TRUE to display additional information against each class
	 * @return string - text for display
	 */
	public function show_graphical_tree($show_debug=FALSE)
	{
		$this->graph_debug = $show_debug;
		$indent_images = array();

		$ret = "
		<div class='uclass_tree' style='height:16px'>
			<img src='".UC_ICON_DIR."topicon.png' alt='class icon' style='vertical-align: bottom' />
			<span style='top:3px'></span>
		</div>";		// Just a generic icon here to provide a visual anchor

		$num_parents = count($this->class_parents);
		foreach ($this->class_parents as $p)
		{
			$num_parents--;
			$ret .= $this->show_graphical_subtree($p, $indent_images, ($num_parents == 0));
		}
		return $ret;
	}



	/**
	 *	Creates an array which contains only DB fields (i.e. strips any added status)
	 *	Copies only those valid fields which are found in the source array
	 *	@param array $classrec - array of class-related information
	 *	@param boolean $inc_id - TRUE to include the class id field (if present in the original)
	 *	@return array of class info suitable for writing to DB
	 */
	protected function copy_rec($classrec, $inc_id = FALSE)
	{
		$ret = array();
		if ($inc_id && isset($classrec['userclass_id'])) $ret['userclass_id'] = $classrec['userclass_id'];
		foreach ($this->field_list as $fl => $val)
		{
		  if (isset($classrec[$fl])) $ret[$fl] = $classrec[$fl];
		}
		return $ret;
	}



	/**
	 *	Return an unused class ID. Misses the predefined classes.
	 *	Initially tries to find an unused class ID less than e_UC_SPECIAL_BASE
	 *	Then attempts to find a gap in the lower numbered classes
	 *	Finally allocates a class number above e_UC_SPECIAL_END
	 *	@return integer|boolean - class ID if available; otherwise FALSE
	 */
	public function findNewClassID()
	{
		$i = 1;
		// Start by allocating a new class with a number higher than any previously allocated
		foreach ($this->class_tree as $id => $r)
		{
			if ($id < e_UC_SPECIAL_BASE)
			{
				$i = max($i,$id);
			}
		}
		$i++;
		if ($i < e_UC_SPECIAL_BASE) return $i;

		// Looks like we've had a lot of activity in classes - try and find a gap.
		for ($i = 1; ($i < e_UC_SPECIAL_BASE); $i++)
		{
			if (!isset($this->class_tree[$i])) return $i;
		}
		// Big system!! Assign a class in the 0.8-only block above 255
		for ($i = e_UC_SPECIAL_END+1; ($i < 32767); $i++)
		{
			if (!isset($this->class_tree[$i])) return $i;
		}

		return FALSE;			// Just in case all classes assigned!
	}




	/**
	 *	Add new class. Class ID must be in the passed record.
	 *	@param array $classrec - user class data
	 *	@return boolean TRUE on success, FALSE on failure
	 */
	public function add_new_class($classrec)
	{
		if (!isset($classrec['userclass_id']))
		{
			return FALSE;
		}
		if ($classrec['userclass_type'] == UC_TYPE_GROUP)
		{	// Need to make sure our ID is in the accumulation array
			$temp = explode(',',$classrec['userclass_accum']);
			if (!in_array($classrec['userclass_id'], $temp))
			{
				$temp[] = $classrec['userclass_id'];
				$classrec['userclass_accum'] = implode(',',$temp);
			}
		}
		if ($this->sql_r->db_Insert('userclass_classes',$this->copy_rec($classrec, TRUE)) === FALSE)
		{
			return FALSE;
		}
		$this->clearCache();
		return TRUE;
	}



	/**
	 *	Save class data after editing
	 *	TODO: use new array function
	 *	@param array $classrec - class data
	 *	@return boolean TRUE on success, FALSE on failure
	 */
	public function save_edited_class($classrec)
	{
		if (!$classrec['userclass_id'])
		{
			echo "Programming bungle on save<br />";
			return FALSE;
		}
		$qry = '';
		$spacer = '';
		if ($classrec['userclass_type'] == UC_TYPE_GROUP)
		{	// Need to make sure our ID is in the accumulation array
			$temp = explode(',',$classrec['userclass_accum']);
			if (!in_array($classrec['userclass_id'], $temp))
			{
				$temp[] = $classrec['userclass_id'];
				$classrec['userclass_accum'] = implode(',',$temp);
			}
		}

		foreach ($this->field_list as $fl => $val)
		{
			if (isset($classrec[$fl]))
			{
				$qry .= $spacer."`".$fl."` = '".$classrec[$fl]."'";
				$spacer = ", ";
			}
		}
		if ($this->sql_r->db_Update('userclass_classes', $qry." WHERE `userclass_id`='{$classrec['userclass_id']}'") === FALSE)
		{
			return FALSE;
		}
		$this->clearCache();
		return TRUE;
	}




	/**
	 *	Check if a class may be deleted. (Fixed classes, classes with descendants cannot be deleted)
	 *	@param integer $classID > 0
	 *	@return boolean TRUE if deletion permissible; false otherwise
	 */
	public function queryCanDeleteClass($classID)
	{
		if (($classID >= e_UC_SPECIAL_BASE) && ($classID <= e_UC_SPECIAL_END)) return FALSE;	// Don't allow deletion of fixed classes
		if (isset($this->fixed_classes[$classID])) return FALSE;			// This picks up classes such as e_UC_PUBLIC outside the main range which can't be deleted
		if (!isset($this->class_tree[$classID])) return FALSE;
		if (count($this->class_tree[$classID]['class_children'])) return FALSE;		// Can't delete class with descendants
		foreach ($this->class_tree as $c)
		{
			if ($c['userclass_editclass'] == $classID) return FALSE;
			if ($c['userclass_visibility'] == $classID) return FALSE;
		}
		return TRUE;
	}



	/**
	 *	Delete a class
	 *	@param integer $classID > 0
	 *	@return boolean TRUE on success, FALSE on error
	 */
	public function delete_class($classID)
	{
		if (self::queryCanDeleteClass($classID) === FALSE) return FALSE;

		if ($this->sql_r->db_Delete('userclass_classes', "`userclass_id`='{$classID}'") === FALSE) return FALSE;
		$this->clearCache();
		$this->readTree(TRUE);			// Re-read the class tree
		return TRUE;
	}



	/**
	 *	Delete a class, and all users who are members of that class.
	 *	@param integer $classID > 0
	 *	@return boolean TRUE on success, FALSE on error
	 */
	public function deleteClassAndUsers($classID)
	{
		if (self::delete_class($classID) === TRUE)
		{
			if ($this->sql_r->db_Select('user', 'user_id, user_class', "user_class REGEXP '(^|,){$classID}(,|$)'"))
			{
				$sql2 = new db;
				while ($row = $this->sql_r->db_Fetch())
				{
					$newClass = self::ucRemove($classID,$row['user_class']);
					$sql2->db_Update('user', "user_class = '{$newClass}' WHERE user_id = {$row['user_id']}");
				}
			}
			return TRUE;
		}
		return FALSE;
	}



	/**
	 *	Adds all users in list to a specified user class
	 *	(Moved in from e_userclass class)
	 *	@param integer $cid - user class ID
	 *	@param $uinfoArray is array(uid=>user_class) - list of affected users
	 *	@return none
	 */
	public function class_add($cid, $uinfoArray)
	{
		$e107 = e107::getInstance();
		$uc_sql = new db;
		foreach($uinfoArray as $uid => $curclass)
		{
			if ($curclass)
			{
				$newarray = array_unique(array_merge(explode(',', $curclass), array($cid)));
				$new_userclass = implode(',', $newarray);
			}
			else
			{
				$new_userclass = $cid;
			}
			$uc_sql->db_Update('user', "user_class='".$e107->tp->toDB($new_userclass, true)."' WHERE user_id=".intval($uid));
		}
	}



	/**
	 *	Removes  all users in list from a specified user class
	 *	(Moved in from e_userclass class)
	 *	@param integer $cid - user class ID
	 *	@param $uinfoArray is array(uid=>user_class) - list of affected users
	 *	@return none
	 */
	public function class_remove($cid, $uinfoArray)
	{
		$e107 = e107::getInstance();
		$uc_sql = new db;
		foreach($uinfoArray as $uid => $curclass)
		{
			$newarray = array_diff(explode(',', $curclass), array('', $cid));
			$new_userclass = implode(',', $newarray);
			$uc_sql->db_Update('user', "user_class='".$e107->tp->toDB($new_userclass, true)."' WHERE user_id=".intval($uid));
		}
	}




	/**
	 *	Check class data record for a fixed class - certain fields have constraints on their values.
	 *	updates any values which are unacceptable.
	 *	@param array $data - user class record (passed by reference)
	 *	@param integer $id - class id
	 *	@return boolean Returns TRUE if nothing changed, FALSE if changes made
	 */
	public function checkAdminInfo(&$data, $id)
	{
		$ret = TRUE;
		if (($id < e_UC_SPECIAL_BASE) || ($id > e_UC_SPECIAL_END)) return TRUE;
		if (isset($data['userclass_parent']))
		{
			if (($data['userclass_parent'] < e_UC_SPECIAL_BASE) || ($data['userclass_parent'] > e_UC_SPECIAL_END))
			{
				$data['userclass_parent'] = e_UC_NOBODY;
				$ret = FALSE;
			}
		}
		if (isset($data['userclass_editclass']))
		{
			if ($id == e_UC_MAINADMIN)
			{
				if ($data['userclass_editclass'] < e_UC_MAINADMIN)
				{
					$data['userclass_editclass'] = e_UC_MAINADMIN;
					$ret = FALSE;
				}
			}
			elseif (($data['userclass_editclass'] < e_UC_SPECIAL_BASE) || ($data['userclass_editclass'] > e_UC_SPECIAL_END))
			{
				$data['userclass_editclass'] = e_UC_MAINADMIN;
				$ret = FALSE;
			}
		}
		return $ret;
	}



	/**
	 *	Set a simple default tree structure of classes
	 *	@return none
	 */
	public function set_default_structure()
	{
		// If they don't exist, we need to create class records for the 'standard' user classes
		$init_list = array(
						array('userclass_id' => e_UC_MEMBER, 'userclass_name' => UC_LAN_3,
							'userclass_description' => UCSLAN_75,
							'userclass_editclass' => e_UC_MAINADMIN,
							'userclass_parent' => e_UC_PUBLIC,
							'userclass_visibility' => e_UC_MEMBER
							),
						array('userclass_id' => e_UC_ADMINMOD, 'userclass_name' => UC_LAN_8,
							'userclass_description' => UCSLAN_74,
							'userclass_editclass' => e_UC_MAINADMIN,
							'userclass_parent' => e_UC_MAINADMIN,
							'userclass_visibility' => e_UC_MEMBER
							),
						array('userclass_id' => e_UC_ADMIN, 'userclass_name' => UC_LAN_5,
							'userclass_description' => UCSLAN_76,
							'userclass_editclass' => e_UC_MAINADMIN,
							'userclass_parent' => e_UC_ADMINMOD,
							'userclass_visibility' => e_UC_MEMBER
							),
						array('userclass_id' => e_UC_MAINADMIN, 'userclass_name' => UC_LAN_6,
							'userclass_description' => UCSLAN_77,
							'userclass_editclass' => e_UC_MAINADMIN,
							'userclass_parent' => e_UC_NOBODY,
							'userclass_visibility' => e_UC_MEMBER
							),
						array('userclass_id' => e_UC_MODS, 'userclass_name' => UC_LAN_7,
							'userclass_description' => UCSLAN_78,
							'userclass_editclass' => e_UC_MAINADMIN,
							'userclass_parent' => e_UC_ADMINMOD,
							'userclass_visibility' => e_UC_MEMBER
							),
						array('userclass_id' => e_UC_NEWUSER, 'userclass_name' => UC_LAN_9,
							'userclass_description' => UCSLAN_87,
							'userclass_editclass' => e_UC_MAINADMIN,
							'userclass_parent' => e_UC_MEMBER,
							'userclass_visibility' => e_UC_ADMIN
							),
						array('userclass_id' => e_UC_BOT, 'userclass_name' => UC_LAN_10,
							'userclass_description' => UCSLAN_88,
							'userclass_editclass' => e_UC_MAINADMIN,
							'userclass_parent' => e_UC_PUBLIC,
							'userclass_visibility' => e_UC_ADMIN
							)
						);

		foreach ($init_list as $entry)
		{
			if ($this->sql_r->db_Select('userclass_classes','*',"userclass_id='".$entry['userclass_id']."' "))
			{
				$this->sql_r->db_Update('userclass_classes', "userclass_parent='".$entry['userclass_parent']."', userclass_visibility='".$entry['userclass_visibility']."' WHERE userclass_id='".$entry['userclass_id']."'");
			}
			else
			{
				$this->add_new_class($entry);
			}
		}
	}


	/**
	 *	Clear user class cache
	 *	@return none
	 */
	public function clearCache()
	{
		$e107 = e107::getInstance();
		$e107->ecache->clear_sys(UC_CACHE_TAG);
	}
}





?>