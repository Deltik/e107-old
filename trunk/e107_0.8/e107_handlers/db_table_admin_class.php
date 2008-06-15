<?php
/*
+----------------------------------------------------------------------------+
|     e107 website system
|
|     �Steve Dunstan 2001-2002
|     http://e107.org
|     jalist@e107.org
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $Source: /cvs_backup/e107_0.8/e107_handlers/db_table_admin_class.php,v $
|     $Revision: 1.3 $
|     $Date: 2008-06-15 09:52:09 $
|     $Author: e107steved $
+----------------------------------------------------------------------------+
*/

/*
Database utilities for admin tasks:
	Get structure of a table from a database
	Get structure of a table from a file
	First level parse of table structure
	Parse of field definitions part of table structure
	Comparison of two structures, including generation of MySQL to make them the same
	Some crude printing utilities
	
Note: there are some uncommented 'echo' statements which are intentional to highlight that something's gone wrong! (not that it should, of course)
*/

class db_table_admin
{
  var $file_buffer = '';		// Contents of a file
  var $last_file = '';
  
// Get list of fields and keys for a table - return FALSE if unsuccessful
// Return as for get_table_def
  function get_current_table($table_name, $prefix = "") 
  {
	global $sql;

    if (!$prefix) $prefix = MPREFIX;
//	echo "Get table structure for: {$table_name}, prefix: {$prefix}<br />";
	$sql->db_Select_gen('SET SQL_QUOTE_SHOW_CREATE = 1');
	$qry = 'SHOW CREATE TABLE `'.$prefix.$table_name."`";
	if (!($z = $sql->db_Select_gen($qry))) return FALSE;
	$row = $sql->db_Fetch();
	$tmp = str_replace("`", "", stripslashes($row[1])).';';		// Add semicolon to work with our parser
	$count = preg_match_all("#CREATE\s+?TABLE\s+?`{0,1}({$prefix}{$table_name})`{0,1}\s+?\((.*?)\)\s+?(?:TYPE|ENGINE)\s*\=\s*(.*?);#is",$tmp,$matches,PREG_SET_ORDER);
	if ($count === FALSE) return "Error occurred";
	if (!$count) return "No matches";
	return $matches;
  }


// Routine to do first-level parse of table structure
//---------------------------------------------------
// Given the name of a file, returns an array, with each element being a table creation definition. 
// Tracks the last file read - only reads it once
// If the file name is an empty string, uses a previously read/set buffer
// If a table name is given, returns only that table's info; otherwise returns a list of all tables
// The table name must include a prefix where appropriate (although not required with standard E107 table definition files)
// Each element is itself an array:
//		[0] - The complete string which creates a table (unless a prefix needs adding to the table name), including terminating ';'
//		[1] - The table name. Any backticks are stripped
//		[2] - Field definitions, with the surrounding (...) stripped
//		[3] - The 'TYPE' field ('TYPE=' is stripped) and any AUTO-INCREMENT definition or other text.
//  function get_table_def($table_name='',$file_name = e_ADMIN."sql/core_sql.php")
  function get_table_def($table_name='',$file_name ="")
  {
    if ($file_name != '')
	{	// Read in and buffer a new file (if we've not already got one)
	  if ($this->last_file != $file_name)
	  {
		if (!is_readable($file_name)) return "No file";
		$temp = file_get_contents($file_name);
		// Strip any php header
		$this->file_buffer = preg_replace("#\<\?php.*?\?\>#mis",'',$temp);
		$this->last_file = $file_name;
	  }
	}
	if (!$table_name) $table_name = '\w+?';
	// Regex should be identical to that in get_current_table (apart from the source text variable name)
	$count = preg_match_all("#CREATE\s+?TABLE\s+?`{0,1}({$table_name})`{0,1}\s+?\((.*?)\)\s+?(?:TYPE|ENGINE)\s*\=\s*(.*?);#is",$this->file_buffer,$matches,PREG_SET_ORDER);
	if ($count === FALSE) return "Error occurred";
	if (!$count) return "No matches";
	return $matches;
  }

  
// Parses the block of lines which make up the field and index definitions
// Returns an array where each entry is the definitions of a field or index
  function parse_field_defs($text)
  {
    $ans = array();
	$text = str_replace("\r","\n",$text);
    $field_lines = explode("\n",$text);
	foreach ($field_lines as $fv)
	{
	  unset($defs);
	  $fv = trim(str_replace('  ',' ',$fv));
	  if (substr($fv,-1) == ',') $fv = trim(substr($fv,0,-1));
//	  echo "Line: ".$fv."<br />";
	  if ($fv)
	  {
	    $fd = explode(' ',$fv);
		switch (strtoupper($fd[0]))
		{
		  case 'PRIMARY' :
		    if (strtoupper($fd[1]) == 'KEY')
		    $defs['type'] = 'pkey';
			$defs['name'] = $fd[2];
			break;
		  case 'UNIQUE' :
		    if (count($fd) < 3)
			{
			  echo "Truncated definition after UNIQUE {$i}: ".$fd[1]."<br />";
			}
		    elseif (strtoupper($fd[1]) == 'KEY')
			{
		      $defs['type'] = 'ukey';
			  $defs['name'] = $fd[2];
			if (isset($fd[3])) $defs['keyfield'] = $fd[3]; else $defs['keyfield'] = '['.$fd[2].']';
			}
			else
			{
			  echo "Unrecognised word after UNIQUE in definition {$i}: ".$fd[1]."<br />";
			}
		    break;
		  case 'KEY' :
		    $defs['type'] = 'key';
			$defs['name'] = $fd[1];
			if (isset($fd[2])) $defs['keyfield'] = $fd[2]; else $defs['keyfield'] = '['.$fd[1].']';
			break;
		  default :		// Must be a DB field name
		    $defs['type'] = 'field';
			$defs['name'] = $fd[0];
			$defs['fieldtype'] = $fd[1];
			$i = 2;		// First unused field
			if ((strpos($fd[1],'int') === 0) || (strpos($fd[1],'tinyint') === 0) || (strpos($fd[1],'smallint') === 0) || (strpos($fd[1],'bigint') === 0))
			{
			  if (isset($fd[2]) && (strtoupper($fd[2]) == 'UNSIGNED'))
			  {
			    $defs['vartype'] = $fd[2];
				$i++;
			  }
			}
			while ($i < count($fd))
			{
			  switch(strtoupper($fd[$i]))
			  {
			    case 'NOT' :
				  if (isset($fd[$i+1]) && strtoupper($fd[$i+1]) == 'NULL')
				  {
					$i++;
					$defs['nulltype'] = 'NOT NULL';
				  }
				  else
				  {  // Syntax error
				    echo "Unrecognised word in definition {$i} after 'NOT': ".$fd[$i+1]."<br />";
				  }
				  break;
				case 'DEFAULT' :
				  if (isset($fd[$i+1]))
				  {
					$i++;
					$defs['default'] = $fd[$i];
				  }
				  break;
				case 'COLLATE' :
				  $i++;		// Just skip over - we ignore collation
				  break;
				case 'AUTO_INCREMENT' :
				  $defs['autoinc'] = TRUE;
				  break;
				default :
				  echo "Unknown definition {$i}: ".$fd[$i]."<br />";
			  }
			  $i++;
			}
		}
		if (count($defs) > 1) $ans[] = $defs; else echo "Partial definition<br />";
	  }
	}
	if (!count($ans)) return FALSE;
	return $ans;
  }



  // Utility routine - given our array-based definition, create a string MySQL field definition
  function make_def($list)
  {
    switch ($list['type'])
	{
	  case 'key' :
	    return 'KEY '.$list['name'].' ('.str_replace(array('(',')'),'',$list['keyfield']).')';
	  case 'ukey' :
	    return 'UNIQUE KEY '.$list['name'].' ('.$list['name'].')';
	  case 'pkey' :
		return 'PRIMARY KEY ('.$list['name'].')';
	  case 'field' :	// Require a field - got a key. so add a field at the end
	    $def = $list['name'];
		if (isset($list['fieldtype'])) $def .= ' '.$list['fieldtype'];
		if (isset($list['vartype']))   $def .= ' '.$list['vartype'];
		if (isset($list['nulltype']))  $def .= ' '.$list['nulltype'];
		if (isset($list['default']))   $def .= ' default '.$list['default'];
		if (varsettrue($list['autoinc'])) $def .= ' auto_increment';
		return $def;
	}
	return "Cannot generate definition for: ".$list['type'].' '.$list['name'];
  }
  

  // Compare two field/index lists as generated by parse_field_defs
  // If $stop_on_error is TRUE, returns TRUE if the same, false if different
  // Return a text list of differences, plus an array of MySQL queries to fix
  // List1 is the reference, List 2 is the actual
  // This version looks ahead on a failed match, and moves a field up in the table if already defined - should retain as much as possible
  function compare_field_lists($list1, $list2, $stop_on_error=FALSE)
  {
    $i = 0;								// Counts records in list1 (required format)
	$j = 0;								// Counts records in $created_list (our 'table so far' list)
	$error_list = array();				// Free text list of differences
	$change_list = array();				// MySQL statements to implement changes
	$created_list = array();			// List of field defs that we build up (just names)
	while ($i < count($list1))
	{
	  if (count($list2) == 0)
	  {	// Missing field at end
		if ($stop_on_error) return FALSE;
		$error_list[] = 'Missing field at end: '.$list1[$i]['name'];
		$change_list[] = 'ADD '.$this->make_def($list1[$i]);
	    $created_list[$j] = $list1[$i]['name'];
		$j++;
	  }
	  elseif ($list1[$i]['type'] == $list2[0]['type'])
	  {  // Worth doing a compare - fields are same type
		if (strcasecmp($list1[$i]['name'],$list2[0]['name']) != 0)
		{		// Names differ, so need to add or subtract a field.
//		  echo $i.': names differ - '.$list1[$i]['name'].', '.$list2[0]['name'].'<br />';
		  if ($stop_on_error) return FALSE;
		  $found = FALSE;
		  for ($k = 0; $k < count($list2); $k++)
		  {
//		    echo "Compare ".$list1[$i]['name'].' with '.$list2[$k]['name'];
			if (strcasecmp($list1[$i]['name'],$list2[$k]['name']) == 0)
			{  // Field found; we need to move it up
//			  echo " - match<br />";
			  $found = TRUE;
			  break;
			}
//			echo " - no match<br />";
		  }
		  if ($found)
		  {
			$error_list[] = 'Field out of position: '.$list2[$k]['name'];
			$change_list[] = 'MODIFY '.$this->make_def($list1[$i]).(count($created_list) ? ' AFTER '.$created_list[count($created_list)-1] : ' FIRST');
			array_splice($list2,$k,1);			// Finished with this element - delete it, and renumber the keys
			$created_list[$j] = $list1[$i]['name'];
			$j++;
			  // The above also amends any parameters as necessary
		  }
		  else
		  {  // Need to insert a field
			$error_list[] = 'Missing field: '.$list1[$i]['name'].' (found: '.$list2[0]['type'].' '.$list2[0]['name'].')';
			switch ($list1[$i]['type'])
			{
			  case 'key' :
			  case 'ukey' :
			  case 'pkey' :		// Require a key 
				$change_list[] = 'ADD '.$this->make_def($list1[$i]);
				$error_list[] = 'Missing index: '.$list1[$i]['name'];
				$created_list[$j] = $list1[$i]['name'];
				$j++;
				break;

			  case 'field' :
				$change_list[] = 'ADD '.$this->make_def($list1[$i]).(count($created_list) ? ' AFTER '.$created_list[count($created_list)-1] : ' FIRST');
				$error_list[] = 'Missing field: '.$list1[$i]['name'].' (found: '.$list2[0]['type'].' '.$list2[0]['name'].')';
				$created_list[$j] = $list1[$i]['name'];
				$j++;
				break;
			}
		  }
		}
		else
		{  // Field/index is present as required; may be changes though
		   // Any difference and we need to update the table
//		  echo $i.': name match - '.$list1[$i]['name'].'<br />';
		  foreach ($list1[$i] as $fi => $v)
		  {
			$t = $list2[0][$fi];
			if (stripos($v,'varchar') !== FALSE) $v = substr($v,3);		// Treat char, varchar the same
			if (stripos($t,'varchar') !== FALSE) $t = substr($t,3);		// Treat char, varchar the same
			if (strcasecmp($t , $v) !== 0)
			{
			  if ($stop_on_error) return FALSE;
			  $error_list[] = 'Incorrect definition: '.$fi.' = '.$v;
			  $change_list[] = 'MODIFY '.$this->make_def($list1[$i]);
			  break;
			}
		  }
		  array_shift($list2);
		  $created_list[$j] = $list1[$i]['name'];
		  $j++;
		}
	  }
	  else
	  {  // Field type has changed. We know fields come before indexes. So something's missing
//		echo $i.': types differ - '.$list1[$i]['type'].' '.$list1[$i]['name'].', '.$list2[$k]['type'].' '.$list2[$k]['name'].'<br />';
		if ($stop_on_error) return FALSE;
	    switch ($list1[$i]['type'])
		{
		  case 'key' :
		  case 'ukey' :
		  case 'pkey' :		// Require a key - got a field
			while ((count($list2)>0) && ($list2[0]['type'] == 'field'))
			{
			  $error_list[] = 'Extra field: '.$list2[0]['name'];
			  $change_list[] = 'DROP '.$list2[0]['name'];
			  array_shift($list2);
			}
		    break;

		  case 'field' :	// Require a field - got a key. so add a field at the end
			  $error_list[] = 'Missing field: '.$list1[$i]['name'].' (found: '.$list2[0]['type'].' '.$list2[0]['name'].')';
			  $change_list[] = 'ADD '.$this->make_def($list1[$i]);
		    break;
			
		  default :
		    $error_list[] = 'Unknown field type: '.$list1[$i]['type'];
			$change_list[] = '';   // Null entry to keep them in step
		} 
	  }		// End - missing or extra field
	  
	  $i++;			// On to next field
	}
	if (count($list2))
	{  // Surplus fields in actual table
//	  Echo count($list2)." fields at end to delete<br />";
	  foreach ($list2 as $f)
	  {
		switch ($f['type'])
		{
		  case 'key' :
		  case 'ukey' :
		  case 'pkey' :		// Require a key - got a field
			$error_list[] = 'Extra index: '.$list2[0]['name'];
			$change_list[] = 'DROP INDEX '.$list2[0]['name'];
			break;
		  case 'field' :
			$error_list[] = 'Extra field: '.$list2[0]['name'];
			$change_list[] = 'DROP '.$list2[0]['name'];
			break;
		}
	  }
	}
	if ($stop_on_error) return TRUE;			// If doing a simple comparison and we get to here, all matches
	return array($error_list, $change_list);
  }
  
  
  // Return a table of info from the output of get_table_def
  function make_table_list($result)
  {
    if (!is_array($result)) return "Not an array<br />";
	$text = "<table>";
	for ($i = 0; $i < count($result); $i++)
	{
	  $text .= "<tr><td>{$result[$i][0]}</td>";
	  $text .= "<td>{$result[$i][1]}</td>";
	  $text .= "<td>{$result[$i][2]}</td>";
	  $text .= "<td>{$result[$i][3]}</td></tr>\n";
	}
	$text .= "</table><br /><br />";
	return $text;
  }
  

  // Return a table of info from the output of parse_field_defs()
  function make_field_list($fields)
  {
	$text = "<table>";
	foreach ($fields as $f)
	{
	  switch($f['type'])
	  {
		case 'pkey' :
		  $text .= "<tr><td>PRIMARY KEY</td><td>{$f['name']}</td><td>&nbsp;</td></tr>";
		  break;
		case 'ukey' :
		  $text .= "<tr><td>UNIQUE KEY</td><td>{$f['name']}</td><td>{$f['keyfield']}</td></tr>";
		  break;
		case 'key' :
		  $text .= "<tr><td>KEY</td><td>{$f['name']}</td><td>{$f['keyfield']}</td></tr>";
		  break;
		case 'field' :
		  $text .= "<tr><td>FIELD</td><td>{$f['name']}</td><td>{$f['fieldtype']}";
		  if (isset($f['vartype'])) $text .= " ".$f['vartype'];
		  $text .= "</td>";
		  if (isset($f['nulltype'])) $text .= "<td>{$f['nulltype']}</td>"; else $text .= "<td>&nbsp;</td>";
		  if (isset($f['default'])) $text .= "<td>default {$f['default']}</td>"; elseif
			(isset($f['autoinc'])) $text .= "<td>AUTO_INCREMENT</td>"; else $text .= "<td>&nbsp;</td>";
		  $text .= "</tr>";
		  break;
		default :
		  $text .= "<tr><td>!!Unknown!!</td><td>{$f['type']}</td><td>&nbsp;</td></tr>";
	  }
	}
	$text .= "</table><br /><br />--Ends--<br />";
	return $text;
  }
}



?>