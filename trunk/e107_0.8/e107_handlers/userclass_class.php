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
|     $Source: /cvs_backup/e107_0.8/e107_handlers/userclass_class.php,v $
|     $Revision: 1.7 $
|     $Date: 2007-12-29 22:32:58 $
|     $Author: e107steved $
+----------------------------------------------------------------------------+
*/


/*
This class handles everything a user needs. Admin functions inherit from it.
*/

if (!defined('e107_INIT')) { exit; }

include_lan(e_LANGUAGEDIR.e_LANGUAGE."/lan_userclass.php");


/*
For info:
define("e_UC_PUBLIC", 0);
define("e_UC_MAINADMIN", 250);
define("e_UC_READONLY", 251);
define("e_UC_GUEST", 252);
define("e_UC_MEMBER", 253);
define("e_UC_ADMIN", 254);
define("e_UC_NOBODY", 255);
*/
// Move these definitions to class2.php later if they're adopted
define("e_UC_ADMINMOD",249);
define("e_UC_MODS",248);
//define("e_UC_USERS",247);

define('UC_CLASS_ICON_DIR','userclasses/');		// Directory for userclass icons
define('UC_ICON_DIR',e_IMAGE.'generic/');		// Directory for the icons used in the admin tree displays


class user_class
{
	var $class_tree;				// Simple array, filled with current tree. Additional field class_children is an array of child user classes (by ID)
	var $class_parents;				// Array of class IDs of 'parent' (i.e. top level) classes
  
	var  $fixed_classes = array();	// The 'predefined' classes of 0.7
	// List of 'core' user classes and the related constants
	var  $text_class_link = array();
	
	var $sql_r;						// We'll use our own DB to avoid interactions
	
	// Constructor
	function user_class()
	{
	  global $imode;
	  
	  $this->sql_r = new db;

	  $this->fixed_classes = array(e_UC_PUBLIC => UC_LAN_0,
							e_UC_GUEST => UC_LAN_1,
							e_UC_NOBODY => UC_LAN_2,
							e_UC_MEMBER => UC_LAN_3,
							e_UC_ADMIN => UC_LAN_5,
							e_UC_MAINADMIN => UC_LAN_6,
							e_UC_READONLY => UC_LAN_4
							);
							
	 $this->text_class_link = array('public' => e_UC_PUBLIC, 'guest' => e_UC_GUEST, 'nobody' => e_UC_NOBODY, 'member' => e_UC_MEMBER, 
									'admin' => e_UC_ADMIN, 'main' => e_UC_MAINADMIN, 'readonly' => e_UC_READONLY);

      $this->read_tree(TRUE);			// Initialise the classes on entry

	}


	/*
	  Ensure the tree of userclass data is stored in our object. 
	  Only read if its either not present, or the $force flag is set
	*/
  function read_tree($force = FALSE)
  {
    if ($this->class_tree && !$force) return $this->class_tree;
	$this->class_tree = array();
	$this->class_parents = array();

	$this->sql_r->db_Select("userclass_classes", '*', "ORDER BY userclass_parent", 'nowhere');		// The order statement should give a consistent return

	while ($row = $this->sql_r->db_Fetch()) 
	{
	  $this->class_tree[$row['userclass_id']] = $row;
	  $this->class_tree[$row['userclass_id']]['class_children'] = array();		// Create the child array in case needed
	}

	// Now build the tree
	foreach ($this->class_tree as $uc)
	{
	  if ($uc['userclass_parent'] == 0)
	  {	// Note parent (top level) classes
	    $this->class_parents[$uc['userclass_id']] = $uc['userclass_id'];
	  }
	  else
	  {
	    if (!array_key_exists($uc['userclass_parent'],$this->class_tree))
		{
		  echo "Orphaned class record: ID=".$uc['userclass_id']." Name=".$uc['userclass_name']."  Parent=".$uc['userclass_parent']."<br />";
		}
		else
		{	// Add to array
		  $this->class_tree[$uc['userclass_parent']]['class_children'][] = $uc['userclass_id'];
		}
	  }
	} 
	
	// Finally, add in any fixed classes that aren't already defined
	foreach ($this->fixed_classes as $c => $d)
	{
	  if (!isset($this->class_tree[$c]))
	  {
		$this->class_tree[$c]['userclass_parent'] = 0;
		$this->class_tree[$c]['userclass_id'] = $c;
		$this->class_tree[$c]['userclass_name'] = $d;
		$this->class_tree[$c]['userclass_description'] = 'Fixed class';
		$this->class_tree[$c]['userclass_visibility'] = 0;
		$this->class_tree[$c]['userclass_editclass'] = 0;
		$this->class_tree[$c]['userclass_accum'] = $c;
		$this->class_parents[] = $c;
	  }
	}
  }



	// Given the list of 'base' classes a user belongs to, returns a comma separated list including ancestors. Duplicates stripped
  function get_all_user_classes($start_list)
  {
    $is = array();
    $start_array = explode(',', $start_list);
	foreach ($start_array as $sa)
	{	// Merge in latest values - should eliminate duplicates as it goes
	  if (isset($this->class_tree[$sa]))
	  {
		$is = array_merge($is,explode(',',$this->class_tree[$sa]['userclass_accum']));
	  }
	}
	return implode(',',array_unique($is));
  }



  // Given a comma separated list, returns the minimum number of class memberships required to achieve this (i.e. strips classes 'above' another in the tree)
  // Requires the class tree to have been initialised
  function normalise_classes($class_list)
  {
    $drop_classes = array();
	$old_classes = explode(',',$class_list);
	foreach ($old_classes as $c)
	{  // Look at our parents (which are in 'userclass_accum') - if any of them are contained in old_classes, we can drop them.
	  $tc = array_flip(explode(',',$this->class_tree[$c]['userclass_accum']));
	  unset($tc[$c]);		// Current class should be in $tc anyway
	  foreach ($tc as $tc_c => $v)
	  {
	    if (in_array($tc_c,$old_classes))
		{
		  $drop_classes[] = $tc_c;
		}
	  }
	}
	$new_classes = array_diff($old_classes,$drop_classes);
	return implode(',',$new_classes);
  }

  
  

	/* Generate a dropdown list of user classes from which to select - virtually as r_userclass()
		$optlist allows selection of the classes to be shown in the dropdown. All or none can be included, separated by comma. Valid options are:
			public
			guest
			nobody
			member
			readonly
			admin
			main - main admin
			classes - shows all classes
			matchclass - if 'classes' is set, this option will only show the classes that the user is a member of
			language - list of languages.

			filter - only show those classes where member is in a class permitted to view them - i.e. as the new 'visible to' field - added for 0.8
			force  - show all classes (subject to the other options, including matchclass) - added for 0.8

		$extra_js - can add JS handlers (e.g. 'onclick', 'onchange') if required
		
		[ $mode parameter of r_userclass() removed - $optlist is more flexible) ]
*/
	function uc_dropdown($fieldname, $curval = 0, $optlist = "", $extra_js = '') 
	{
	  global $pref;

	  $show_classes = $this->uc_required_class_list($optlist);

	  $text = '';
	  foreach ($show_classes as $k => $v)
	  {
		$s = ($curval == $k) ?  "selected='selected'" : "";
	    $text .= "<option  value='".$k."' ".$s.">".$v."</option>\n";
	  }

	  if (isset($opt_arr['language']) && $pref['multilanguage']) 
	  {
		$text .= "<optgroup label=' ------ ' />\n";
		$tmpl = explode(",",e_LANLIST);
        foreach($tmpl as $lang)
		{
		  $s = ($curval == $lang) ?  " selected='selected'" : "";
          $text .= "<option  value='$lang' ".$s.">".$lang."</option>\n";
		}
	  }

	  // Only return the select box if we've ended up with some options
	  if ($text) $text = "<select class='tbox' name='{$fieldname}' {$extra_js}>\n".$text."</select>\n";
	  return $text;
	}




	/*
	  Generate an ordered array  classid=>classname - used for dropdown and check box lists
	  If $just_ids is TRUE, array value is just '1'
	*/
	function uc_required_class_list($optlist = '', $just_ids = FALSE)
	{
	  $ret = array();
	  if (!$optlist) $optlist = 'public,guest,nobody,member,classes';		// Set defaults to simplify ongoing processing

	  $opt_arr = explode(',',$optlist);
	  foreach ($opt_arr as $k => $v) 
	  { 
	    $opt_arr[$k] = trim($v);
	  }

	  $opt_arr = array_flip($opt_arr);		// This also eliminates duplicates which could arise from applying the other options, although shouldn't matter

	  if (isset($opt_arr['force'])) unset($opt_arr['filter']);

	  // Do the 'fixed' classes next
	  foreach ($this->text_class_link as $k => $v)
	  {
		if (isset($opt_arr[$k]) || isset($opt_arr['force']))
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
			&& (   getperms("0")
				|| (
					(!isset($optlist["matchclass"]) || check_class($uc_id))
					&& (!isset($optlist["filter"]) || check_class($row['userclass_visibility']))
				   )
				)
				)
			{
			  $ret[$uc_id] = $this->class_tree[$uc_id]['userclass_name'];
			}
		}
	  }
/* Above loop slightly changes the display order of earlier code versions. 
	If readonly must be last (after language), delete it from the $text_class_link array, and uncomment the following code

	if (isset($opt_arr['readonly']))
	{
	  $ret[e_UC_READONLY] = $this->class_tree[e_UC_READONLY]['userclass_description'];
	}
*/
	  return $ret;
	}
	
	

	/*
	Very similar to r_userclass, but returns a list of check boxes. Doesn't encapsulate it.
	$fieldname is the name for the array of checkboxes
	$curval is a comma separated list of class IDs for boxes which are checked.
	$optlist as for uc_dropdown
	if $showdescription is TRUE, appends the class description in brackets
	*/
	function uc_checkboxes($fieldname, $curval='', $optlist = '', $showdescription = FALSE)
	{
	  global $pref;
	  $show_classes = $this->uc_required_class_list($optlist);

	  $curArray = explode(",", $curval);				// Array of current values
	  $ret = "";

	  foreach ($show_classes as $k => $v)
	  {
		  $c = (in_array($k,$curArray)) ?  " checked='checked'" : "";
		  if ($showdescription) $v .= " (".$this->uc_get_classdescription($k).")";
		  $ret .= "<label><input type='checkbox' name='{$fieldname}[{$k}]' value='{$k}' {$c} /> ".$v."</label><br />\n";
	  }

	  if (strpos($optlist, "language") !== FALSE && $pref['multilanguage']) 
	  {
		$ret .= "<hr />\n";
		$tmpl = explode(",",e_LANLIST);
        foreach($tmpl as $lang)
		{
		  $c = (in_array($lang, $curArray)) ? " checked='checked' " : "";
          $ret .= "<label><input type='checkbox' name='{$fieldname}[{$lang}]'  value='1' {$c} /> {$lang}</label><br />";
		}
	  }
	  return $ret; 
	}




	/*
	Next two routines create an indented tree - for example within a select box or a list of check boxes.
	
	For each displayed element, the callback routine is called
	$treename is the name given to the elements where required
	$callback is a routine used to generate each element; there are two implemented within this class:
		select (the default) - generates the option list. Text requires to be encapsulated in a <select......./select> tag set
				- can also be used with multi-select boxes
		checkbox - generates a set of checkboxes
		Alternative callbacks can be used to achieve different layouts/styles
	$current_value is a single class number for single-select dropdown; comma separated array of class numbers for checkbox list or multi-select
	$optlist works the same as for other class displays
	*/
  function vetted_sub_tree($treename, $callback,$listnum,$nest_level,$current_value, $perms)
  {
    $ret = '';
	$nest_level++;
	foreach ($this->class_tree[$listnum]['class_children'] as $p)
	{
	// Looks like we don't need to differentiate between function and class calls
	  if (isset($perms[$p]))
	  {
		$ret .= call_user_func($callback,$treename, $p,$current_value,$nest_level);
	  }
	  $ret .= $this->vetted_sub_tree($treename, $callback,$p,$nest_level,$current_value, $perms);
	}
	return $ret;
  }


  function vetted_tree($treename, $callback='', $current_value='', $optlist = '')
  {
	$ret = '';
	if (!$callback) $callback=array($this,'select');
	$current_value = str_replace(' ','',$current_value);				// Simplifies parameter passing for the tidy-minded

	$perms = $this->uc_required_class_list($optlist,TRUE);				// List of classes which we can display
	
/*	// Start with the fixed classes
	foreach ($this->fixed_classes as $c => $j)
	{
	  if (isset($perms[$c]))
	  {
		$ret .= call_user_func($callback,$treename, $c,$current_value,0);
	  }
	}
*/
    foreach ($this->class_parents as $p)
	{
	// Looks like we don't need to differentiate between function and class calls
	  if (isset($perms[$p]))
	  {
		$ret .= call_user_func($callback,$treename, $p,$current_value,0);
	  }
	  $ret .= $this->vetted_sub_tree($treename, $callback,$p,0, $current_value, $perms);
	}
	return $ret;
  }


  function select($treename, $classnum, $current_value, $nest_level)
  {
	$tmp = explode(',',$current_value);
    $sel = in_array($classnum,$tmp) ? " selected='selected'" : '';
    if ($nest_level == 0)
	{
	  $prefix = '';
	  $style = " style='font-weight:bold; font_style: italic;'";
	}
	elseif ($nest_level == 1)
	{
	  $prefix = '&nbsp;&nbsp;';
	  $style = " style='font-weight:bold'";
	}
	else
	{
	  $prefix = '&nbsp;&nbsp;'.str_repeat('--',$nest_level).'>';
	  $style = '';
	}
    return "<option value='{$classnum}'{$sel}{$style}>".$prefix.$this->class_tree[$classnum]['userclass_name']."</option>\n";
  }


	// Callback for vetted_tree - displays indented checkboxes with class name only
  function checkbox($treename, $classnum, $current_value, $nest_level)
  {
	$tmp = explode(',',$current_value);
	$chk = in_array($classnum, $tmp) ? " checked='checked'" : '';
    if ($nest_level == 0)
	{
	  $style = " style='font-weight:bold'";
	}
	else
	{
//	  $style = " style='text-indent:".(12*$nest_level)."px'";
	  $style = " style='text-indent:".(1.2*$nest_level)."em'";
	}
    return "<div {$style}><input type='checkbox' name='{$treename}[]' value='{$classnum}'{$chk} />".$this->class_tree[$classnum]['userclass_name']."</div>\n";
  }


	// Callback for vetted_tree - displays indented checkboxes with class name, and description in brackets
  function checkbox_desc($treename, $classnum, $current_value, $nest_level)
  {
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
    return "<div {$style}><input type='checkbox' name='{$treename}[]' value='{$classnum}'{$chk} />".$this->class_tree[$classnum]['userclass_name'].'  ('.$this->class_tree[$classnum]['userclass_description'].")</div>\n";
  }



	
	/*
	Return array of all classes, limited according to membership of the userclass_visibility field if $filter is set.
		Index field - userclass_id
		Data fields - userclass_name, userclass_description, userclass_editclass
	*/
	function uc_get_classlist($filter = FALSE)
	{
	  $ret = array();
	  $this->read_tree(FALSE);				// Make sure we have data
	  foreach ($this->class_tree as $k => $v)
	  {
	    if (!$filter || check_class($filter))
		{
		  $ret[$k] = array('userclass_name' => $v, 'userclass_description' => $v['userclass_description'], 'userclass_editclass' => $v['userclass_editclass']);
		}
	  }
	  return $ret;
	}
	
	
	function uc_get_classname($id)
	{
	  if (isset($this->class_tree[$id]))
	  {
	    return $this->class_tree[$id]['userclass_name'];
	  }
	  if (isset($this->fixed_classes[$id]))
	  {
	    return $this->fixed_classes[$id];
	  }
	  return '';
	}
	
	
	function uc_get_classdescription($id)
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

	function uc_get_classicon($id)
	{
	  if (isset($this->class_tree[$id]))
	  {
	    return $this->class_tree[$id]['userclass_icon'];
	  }
	  return '';
	}
	
	
	/*
	Return all users in a particular class or set of classes.
	$classlist is a comma separated list of classes - if the 'predefined' classes are required, they must be included. No spaces allowed
	$field_list is used to select the returned fields ($user_id is the primary index)
	
	****** Can be verrrrryyyy slow - has to scan the whole user database at present ******
	
	********* NOT TESTED **********
	
	***** NOT SURE WHETHER THIS IS REALLY A USER OR A USER CLASS FUNCTION *****
	*/
	function get_users_in_class($classlist, $field_list = 'user_name, user_loginname', $include_ancestors = FALSE, $order_by = 'user_id')
	{
	  $ret = array();
	  if ($include_ancestors) $classlist = $this->get_all_user_classes($classlist);
	  $class_regex = "(^|,)(".str_replace(' ','',str_replace(",", "|", $classlist)).")(,|$)";
	  $qry = "SELECT 'user_id,{$field_list}' FROM `user` WHERE user_class REGEXP '{$class_regex}' ORDER BY '{$order_by}'";
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
// Implemented for backwards compatibility/convenience

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
language - list of languages.

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
  if ($ret) $ret = "<div class='tbox' style='margin-left:0px;margin-right:auto;width:60%;height:58px;overflow:auto'>".$ret."</div>";
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
  var $field_list = array('userclass_name' => "varchar(100) NOT NULL default ''", 
						'userclass_description' => "varchar(250) NOT NULL default ''", 
						'userclass_editclass' => "tinyint(3) unsigned NOT NULL default '0'",
						'userclass_parent' => "tinyint(3) unsigned NOT NULL default '0'",
						'userclass_accum' => "varchar(250) NOT NULL default ''", 
						'userclass_visibility' => "tinyint(3) unsigned NOT NULL default '0'",
						'userclass_icon' => "varchar(250) NOT NULL default ''"
						);		// Note - 'userclass_id' intentionally not in this list

  var $fixed_list = array(e_UC_MAINADMIN, e_UC_MEMBER, e_UC_ADMIN, e_UC_ADMINMOD, e_UC_MODS, e_UC_READONLY);	// Classes which can't be deleted
//  var $fixed_list = array(e_UC_MAINADMIN, e_UC_MEMBER, e_UC_ADMIN, e_UC_ADMINMOD, e_UC_MODS, e_UC_USERS, e_UC_READONLY);	// Classes which can't be deleted

  // Icons to use for graphical tree display
  // First index - no children, children
  // Second index - not last item, last item
  // Third index - closed tree, open tree
  var $tree_icons  = array(	);
  var $graph_debug = FALSE;			// Shows extra info on graphical tree when TRUE


  function user_class_admin()
  {
	$this->user_class();			// Call constructor from ancestor class
	
	// Have to initialise the images this way - PHP4 won't take a nested array assignment in the variable list
	$this->tree_icons  = array(
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
  }

 

	/*
	Next three routines are used to update the database after adding/deleting a class
	*/
  function calc_tree()
  {
//    echo "Calc Tree<br />";
    $this->read_tree(TRUE);			// Make sure we have accurate data
	foreach ($this->class_parents as $cp)
	{
	  $rights = array();
	  $this->rebuild_tree($cp,$rights);
	}
  }
  

	// Internal function, called recursively to rebuild the permissions tree
  function rebuild_tree($parent, $rights) 
  {
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


  function save_tree()
  {
//    echo "Save Tree<br />";
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
  


	/*
	Next two routines show a text-based tree with markers to indicate the hierarchy.
	*/
  function show_sub_tree($listnum,$marker, $add_class = FALSE)
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
  
  function show_tree($add_class = FALSE)
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
  function show_graphical_subtree($listnum, $indent_images, $is_last = FALSE)
  {
    $num_children = count($this->class_tree[$listnum]['class_children']);
	$is_open = TRUE;
	$tag_name = 'uclass_tree_'.$listnum;

	$ret = "<div class='uclass_tree' style='height:20px'>";
	
	foreach ($indent_images as $im)
	{
	  $ret .= "<img src='".UC_ICON_DIR.$im."' alt='class icon' />";
	}
	// If this link has children, wrap the next image in a link and an expand/hide option
	if ($num_children) 
	{
	  $ret .= "<span onclick=\"javascript: expandit('{$tag_name}'); expandit('{$tag_name}_p'); expandit('{$tag_name}_m')\"><img src='".UC_ICON_DIR.$this->tree_icons[TRUE][$is_last][TRUE]."' alt='class icon' id='{$tag_name}_m' />";
	  $ret .= "<img src='".UC_ICON_DIR.$this->tree_icons[TRUE][$is_last][FALSE]."' style='display:none' id='{$tag_name}_p' alt='class icon' /></span>";
	}
	else
	{
	  $ret .= "<img src='".UC_ICON_DIR.$this->tree_icons[FALSE][$is_last][$is_open]."' alt='class icon' />";
	}
	$name_line = '';
	if ($this->graph_debug) $name_line = $this->class_tree[$listnum]['userclass_id'].":";
	$name_line .= $this->class_tree[$listnum]['userclass_name'];
	if ($this->graph_debug) $name_line .= "[vis:".$this->class_tree[$listnum]['userclass_visibility']."] = ".$this->class_tree[$listnum]['userclass_accum'];
	// Next (commented out) line gives a 'conventional' link
//	$ret .= "<img src='images/topicon.png' alt='class icon' /><a href='".e_ADMIN."userclass2.php?config.edit.{$this->class_tree[$listnum]['userclass_id']}'>".$this->class_tree[$listnum]['userclass_name']."</a></div>";
	$ret .= "<img src='".UC_ICON_DIR."topicon.png' alt='class icon' />
		<span style='cursor:pointer; vertical-align: bottom' onclick=\"javascript: document.location.href='".e_ADMIN."userclass2.php?config.edit.{$this->class_tree[$listnum]['userclass_id']}'\">".$name_line."</span></div>";
// vertical-align: middle doesn't work! Nor does text-top

	if ($num_children)
	{
	  $ret .= "<div class='uclass_tree' id='{$tag_name}'>";
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
  

  function show_graphical_tree($show_debug=FALSE)
  {
    $this->graph_debug = $show_debug;
    $indent_images = array();
    $ret = "<div class='uclass_tree' style='height:16px'><img src='".UC_ICON_DIR."topicon.png' alt='class icon' style='vertical-align: bottom' /><span style='top:3px'>".UC_LAN_0."</span></div>";		// 'Everyone' link
	$num_parents = count($this->class_parents);
    foreach ($this->class_parents as $p)
	{
	  $num_parents--;
	  $ret .= $this->show_graphical_subtree($p, $indent_images, ($num_parents == 0));  
	}
	return $ret;
  }



  // Creates an array which contains only DB fields (i.e. strips the added status)
  function copy_rec($classrec, $inc_id = FALSE)
  {
	$ret = array();
	if ($inc_id && isset($classrec['userclass_id'])) $ret['userclass_id'] = $classrec['userclass_id'];
	foreach ($this->field_list as $fl => $val)
	{
	  if (isset($classrec[$fl])) $ret[$fl] = $classrec[$fl];
	}
	return $ret;
  }
  
  
  function add_new_class($classrec)
  {
//    echo "Add new class<br />";
    $this->sql_r->db_Insert('userclass_classes',$this->copy_rec($classrec, TRUE));
  }
  
  
  function save_edited_class($classrec)
  {
//    echo "Save edited class: ".implode(',', $classrec)."<br />";
    if (!$classrec['userclass_id']) 
	{
	  echo "Programming bungle on save<br />";
	  return;
	}
    $qry = '';
	$spacer = '';
	foreach ($this->field_list as $fl => $val)
	{
	  if (isset($classrec[$fl]))
	  {
	    $qry .= $spacer."`".$fl."` = '".$classrec[$fl]."'";
		$spacer = ", ";
	  }
	}
	$this->sql_r->db_Update('userclass_classes', $qry." WHERE `userclass_id`='{$classrec['userclass_id']}'");
  }
  
 
  function delete_class($class_id)
  {
    if (in_array($class_id, $this->fixed_list)) return FALSE;			// Some classes can't be deleted
	if (isset($this->class_list[$class_id]) && count($this->class_list[$class_id]['class_children'])) return FALSE;		// Can't delete class with descendants
    return $this->sql_r->db_Delete('userclass_classes', "`userclass_id`='{$class_id}'");
  }



  
  // Update the userclass table with the extra fields for 0.8
  // Return TRUE if all fields present. Return FALSE if update needed
  function update_db($check_only = FALSE)
  {	// Just run through all the fields that should be there, and add if not

    $fn = 1;		// Count fields
	$prev_field = 'userclass_id';
	foreach ($this->field_list as $fl => $parms)
	{
	  $field_name = $this->sql_r->db_Field("userclass_classes",$fn);
//	  echo "Compare: {$field_name} : {$fl}<br />";
	  if ($field_name != $fl)
	  {
	    if ($check_only) return FALSE;
		$this->sql_r->db_Select_gen("ALTER TABLE #userclass_classes ADD `{$fl}` {$parms} AFTER `{$prev_field}`;");
	  }
	  $fn++;
	  $prev_field = $fl;
	}
	return TRUE;
  }
  


  // Set default tree structure
  function set_default_structure()
  {
    // If they don't exist, we need to create class records for the 'standard' user classes
    $init_list = array(
					array('userclass_id' => e_UC_MEMBER, 'userclass_name' => "Member", 
						'userclass_description' => "Registered and logged in users", 
						'userclass_editclass' => e_UC_MAINADMIN,
						'userclass_parent' => e_UC_PUBLIC,
						'userclass_visibility' => e_UC_MEMBER
						),
					array('userclass_id' => e_UC_ADMINMOD, 'userclass_name' => "Admins and Mods", 
						'userclass_description' => "Administrators and Moderators", 
						'userclass_editclass' => e_UC_MAINADMIN,
						'userclass_parent' => e_UC_MEMBER,
						'userclass_visibility' => e_UC_MEMBER
						),
					array('userclass_id' => e_UC_ADMIN, 'userclass_name' => "Admin", 
						'userclass_description' => "Administrators", 
						'userclass_editclass' => e_UC_MAINADMIN,
						'userclass_parent' => e_UC_ADMINMOD,
						'userclass_visibility' => e_UC_MEMBER
						),
					array('userclass_id' => e_UC_MAINADMIN, 'userclass_name' => "Main Admins", 
						'userclass_description' => "Main Administrators", 
						'userclass_editclass' => e_UC_MAINADMIN,
						'userclass_parent' => e_UC_ADMIN,
						'userclass_visibility' => e_UC_MEMBER
						),
					array('userclass_id' => e_UC_MODS, 'userclass_name' => "Moderators", 
						'userclass_description' => "Forum Moderators", 
						'userclass_editclass' => e_UC_MAINADMIN,
						'userclass_parent' => e_UC_ADMINMOD,
						'userclass_visibility' => e_UC_MEMBER
						)  /*,
					array('userclass_id' => e_UC_USERS, 'userclass_name' => "Users", 
						'userclass_description' => "Non-admin users", 
						'userclass_editclass' => e_UC_MAINADMIN,
						'userclass_parent' => e_UC_MEMBER,
						'userclass_visibility' => e_UC_MEMBER  
						)  */
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
}





//========================================================================
//			Legacy Admin Class handler - maybe add to admin class
//========================================================================

// class_add() - called only from userclass2.php
// class_remove() - called only from userclass2.php
// class_create() - called only from forum update routines - could probably go


class e_userclass 
{
	function class_add($cid, $uinfoArray)
	{
		global $tp;
		$sql2 = new db;
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
			$sql2->db_Update('user', "user_class='".$tp -> toDB($new_userclass, true)."' WHERE user_id=".intval($uid));
		}
	}

	function class_remove($cid, $uinfoArray)
	{
		global $tp;
		$sql2 = new db;
		foreach($uinfoArray as $uid => $curclass)
		{
			$newarray = array_diff(explode(',', $curclass), array('', $cid));
			if (count($newarray) > 1)
			{
				$new_userclass = implode(',', $newarray);
			}
			else
			{
				$new_userclass = $newarray[0];
			}
			$sql2->db_Update('user', "user_class='".$tp -> toDB($new_userclass, true)."' WHERE user_id=".intval($uid));
		}
	}


// Mostly for upgrades?
// Create a new user class, with a specified prefix to the name
// $ulist - comma separated list of user names to be added
	function class_create($ulist, $class_prefix = "NEW_CLASS_", $num = 0)
	{
		global $sql;
		$varname = "uc_".$ulist;
		if($ret = getcachedvars($varname))
		{
			return $ret;
		}
		$ul = explode(",", $ulist);
		array_walk($ul, array($this, 'munge'));
		$qry = "
		SELECT user_id, user_class from #user AS u
		WHERE user_name = ".implode(" OR user_name = ", $ul);
		if($sql->db_Select_gen($qry))
		{
			while($row = $sql->db_Fetch())
			{
				$idList[$row['user_id']] = $row['user_class'];

			}
			while($sql->db_Count("userclass_classes","(*)","WHERE userclass_name = '".strtoupper($class_prefix.$num)."'"))
			{
				$num++;
			}
			$newname = strtoupper($class_prefix.$num);
			$i = 1;
			while ($sql->db_Select('userclass_classes', '*', "userclass_id='".intval($i)."' ") && $i < 240)
			{
				$i++;
			}
			if ($i < 240)		// Give a bit of headroom - we're allocating 'system' classes downwards from 255
			{
				$sql->db_Insert("userclass_classes", "{$i}, '{$newname}', 'Auto_created_class', 254");
				$this->class_add($i, $idList);		// Add users
				cachevars($varname, $i);
				return $i;
			}
		}

	}

	function munge(&$value, &$key)
	{
		$value = "'".trim($value)."'";
	}
}



?>