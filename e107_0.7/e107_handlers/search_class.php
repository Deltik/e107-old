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
|     $Source: /cvs_backup/e107_0.7/e107_handlers/search_class.php,v $
|     $Revision: 1.25 $
|     $Date: 2005-04-19 07:11:44 $
|     $Author: sweetas $
+----------------------------------------------------------------------------+
*/

class e_search {
	
	var $query;
	var $text;
	var $pos;

	function parsesearch($table, $return_fields, $search_fields, $weights, $handler, $no_results, $where, $order) {
		global $sql, $query, $tp, $search_prefs, $pre_title, $search_chars, $search_res, $result_flag;
		$bullet = (defined("BULLET") ? "<img src='".THEME."images/".BULLET."' alt='' style='vertical-align: middle;' />" : "<img src='".THEME."images/bullet2.gif' alt='' style='vertical-align: middle;' />");
		$this -> query = $query;	
		$keywords = explode(' ', $this -> query);
		$field_query = implode(',', $search_fields);
		if($search_prefs['search_sort'] == 'php') {
			foreach ($keywords as $key) {
				foreach ($search_fields as $field) {
					$search_query[] = " ".$field." REGEXP '[[:<:]]".$key."[[:>:]]' ";
				}
				$match_query = implode(' OR ', $search_query);
			}
			$sql_query = "SELECT ".$return_fields." FROM #".$table." WHERE ".$where." (".$match_query.");";
			if (count($keywords) > 1) {
				$php_keywords[] = $query;
				foreach ($keywords as $php_key) {
					$php_keywords[] = $php_key;
				}
				$keywords = $php_keywords;
				$keycount = count($keywords) - 1;
			} else {
				$keycount = count($keywords);
			}
		} else {
			foreach ($search_fields as $field_key => $field) {
				$search_query[] = "(".$weights[$field_key]." * (MATCH(".$field.") AGAINST ('".$this -> query."' IN BOOLEAN MODE)))";
			}
			$match_query = implode(' + ', $search_query);
			$sql_order = '';
			foreach ($order as $sort_key => $sort_value) {
				$sql_order .= ', '.$sort_key.' '.$sort_value;
			}
			$limit = " LIMIT ".$result_flag.",".$search_res;
			$sql_query = "SELECT SQL_CALC_FOUND_ROWS ".$return_fields.", (".$match_query.") AS relevance FROM #".$table." WHERE ".$where." ( MATCH(".$field_query.") AGAINST ('".$this -> query."' IN BOOLEAN MODE) ) HAVING relevance > 0 ORDER BY relevance DESC ".$sql_order.$limit.";";
		}
		if ($ps['results'] = $sql->db_Select_gen($sql_query)) {
			while ($row = $sql->db_Fetch()) {
				$res = call_user_func($handler, $row);
				if (!$res['omit_result']) {
					$matches = array($res['title'], $res['summary']);
					$endcrop = FALSE;
					$output = '';
					$title = TRUE;
					$weight = 0;
					$x = 0;
					foreach ($matches as $this -> text) {
						$exact = TRUE;
						$this -> text = nl2br($this -> text);
						$t_search = $tp -> search;
						$t_replace = $tp -> replace;
						$s_search = array('<br />', '[', ']');
						$s_replace = array(' ', '<', '>');
						$search = array_merge($t_search, $s_search);
						$replace = array_merge($t_replace, $s_replace);
						$this -> text = strip_tags(str_replace($search, $replace, $this -> text));
						foreach ($keywords as $this -> query) {
							if (strpos($this -> query, '-') == FALSE) {
								if (strpos($this -> query, '*') !== FALSE) {
									$regex_prepend = "[[:<:]](";
									$regex_append = ".?)[[:>:]]";
									$this -> query = str_replace('*', '', $this -> query);
								} else {
									$regex_prepend = "[[:<:]](";
									$regex_append = ")[[:>:]]";	
								}
								$this -> query = str_replace(array('"', '+'), array('', ''), $this -> query);
								if (($match_start = stristr($this -> text, $this -> query)) !== FALSE) {
									$this -> pos = strlen($this -> text) - strlen($match_start);
									if($search_prefs['search_sort'] == 'php') {
										if ($exact) {
											$weight += (($weights[$x] * 2) * ($keycount));
											$endweight = TRUE;
										} else if (!$endweight) {
											$weight += $weights[$x];
										}
									}
									if (!$endcrop && !$title) {
										$this -> parsesearch_crop();
										$endcrop = TRUE;
									}
									$key = substr($this -> text, $this -> pos, strlen($this -> query));
									$this -> text = eregi_replace($regex_prepend.$this -> query.$regex_append, "<span class='searchhighlight'>\\1</span>", $this -> text);
								}
							}
							$exact = FALSE;
						}
						if ($title) {
							if ($pre_title == 0) {
								$pre_title_output = "";
							} else if ($pre_title == 1) {
								$pre_title_output = $res['pre_title'];
							} else if ($pre_title == 2) {
								$pre_title_output = $pre_title;
							}
							$this -> text = $bullet." <b><a href='".$res['link']."'>".$pre_title_output.$this -> text."</a></b><br />";
						} else if (!$endcrop && !$title) {
							$this -> parsesearch_crop();
						}
						$output .= $this -> text;
						$title = FALSE;
						$endweight = FALSE;
						$x++;
					}
					if($search_prefs['search_sort'] == 'php') {
						$relevance = $weight;
						$output_array['weight'][] = $weight;
						foreach ($order as $order_key => $order_value) {
							$output_array[$order_key][] = $row[$order_key];
						}
					} else {
						$relevance = $row['relevance'];
					}
					$display_rel = $search_prefs['relevance'] ? " | Relevance: ".$relevance : "";
					$output_array['text'][] = $output."<br /><span class='smalltext'>".$res['detail'].$display_rel."</span><br /><br />";
				} else {
					$ps['results']--;
					$res['omit_result'] = FALSE;
				}
			}
			if ($search_prefs['search_sort'] == 'php') {
				$sort_args[] = $output_array['weight'];
				$sort_args[] = SORT_DESC;
				foreach ($order as $order_key => $order_value) {
					$sort_args[] = $output_array[$order_key];
					$sort_args[] = ($order_value == DESC) ? SORT_DESC : SORT_ASC;
				}
				$sort_args[] = $output_array['text'];
				$sort_args[] = SORT_DESC;
				call_user_func_array('array_multisort', $sort_args);
				foreach ($output_array['weight'] as $arg_id => $arg_value) {
					$ps_limit[] = $output_array['text'][$arg_id];
				}
				for ($i = $result_flag; $i < ($result_flag + $search_res); $i++) {
					$ps['text'] .= $ps_limit[$i];
				}
			} else {
				$ps_limit = $output_array['text'];
				for ($i = 0; $i < $search_res; $i++) {
					$ps['text'] .= $ps_limit[$i];
				}
			}
		} else {
			$ps['text'] = $no_results;
		}
		if ($search_prefs['search_sort'] == 'mysql') {
			$sql->db_Query("SELECT FOUND_ROWS()");
			$frows = $sql->db_Fetch();
			$ps['results'] = $frows[0];
		}
		return $ps;
	}
	
	function parsesearch_crop() {
		global $search_chars;
		if (strlen($this -> text) > $search_chars) {
			if ($this -> pos < ($search_chars - strlen($this -> query))) {
				$this -> text = substr($this -> text, 0, $search_chars)."...";
			} else if ($this -> pos > (strlen($this -> text) - ($search_chars - strlen($this -> query)))) {
				$this -> text = "...".substr($this -> text, (strlen($this -> text) - ($search_chars - strlen($this -> query))));
			} else {
				$this -> text = "...".substr($this -> text, ($this -> pos - round(($search_chars / 3))), $search_chars)."...";
			}
			$match_start = stristr($this -> text, $this -> query);
			$this -> pos = strlen($this -> text) - strlen($match_start);
		}
	}
}

?>