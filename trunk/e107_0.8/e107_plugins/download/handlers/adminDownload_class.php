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
|     $Source: /cvs_backup/e107_0.8/e107_plugins/download/handlers/adminDownload_class.php,v $
|     $Revision: 1.3 $
|     $Date: 2009-06-24 22:19:27 $
|     $Author: e107coders $
|
+----------------------------------------------------------------------------+
*/
require_once("../../class2.php");
if (!plugInstalled('download')) { exit(); }
require_once(e_PLUGIN.'download/handlers/download_class.php');

class adminDownload extends download
{
   var $filterCookieName;
   var $filterFields;
   var $userclassOptions;

   function adminDownload()
   {
      global $pref;
      parent::download();
      $this->userclassOptions = 'blank,nobody,guest,public,main,admin,member,classes';
      $this->filterCookieName = $pref['cookie_name']."_download_filter_admin";
      if (isset($_COOKIE[$this->filterCookieName])) {
         $this->filterFields = unserialize($_COOKIE[$this->filterCookieName]);
      }

      // Delete filter selection
      if (isset($_POST['download_filter_delete']))
      {
         $this->filterFields = array();
      }

      // Save filter selection
      if (isset($_POST['download_filter_submit']))
      {
         // Store filter fields in a cookie
         $this->filterFields = $_POST['download_filter'];
         // Convert date if input
         //TODO need a core way of doing this - e_form validation? convert class?
         $tmp = split("/", $this->filterFields['date']);
         $this->filterFields['date'] = mktime(0,0,0,$tmp[1],$tmp[0],$tmp[2]);
         cookie($this->filterCookieName, serialize($this->filterFields), 0); // Use session cookie

         // Columns to display saved in prefs
         $pref['admin_download_disp'] = implode("|",$_POST['filter_columns']);
         save_prefs();
      }
   }
   function show_filter_form($action, $subAction, $id, $from, $amount)
   {
      global $mySQLdefaultdb, $pref;
      $eform = new e_form();

	  /* ************************************************************

      This whole form needs a rework. We need to incorporate ajax usage extensively.
	  Displaying all filter fields at once should be avoided.

	  ******************************************************************* */



      $filterColumns = ($pref['admin_download_disp'] ? explode("|",$pref['admin_download_disp']) : array("download_name","download_class"));

      // Filter fields
      $text .= '<fieldset id="download_filter"><legend>'.DOWLAN_183.'</legend>';
      $text .= "<form method='post' action='".e_SELF."'>
         <table style='width:100%'>
            <colgroup>
               <col style='width:8%;'/>
               <col style='width:20%;'/>
               <col style='width:8%;'/>
               <col style='width:36%;'/>
               <col style='width:8%;'/>
               <col style='width:20%;'/>
            </colgroup>
            <tr>
               <td>Name</td><td><input class='tbox' type='text' name='download_filter[name]' size='20' value='{$this->filterFields['name']}' maxlength='50'/></td>
               <td>Category</td><td>
         ";
        $text .= $this->getCategorySelectList($this->filterFields['category'], true, false, '&nbsp;', 'download_filter[category]');
      $text .= "</td>
               <td>Filesize</td>
               <td>
         ";
      $text .= $this->_getConditionList('download_filter[filesize_condition]', $this->filterFields['filesize_condition']);
      $text .= "
                  <input class='tbox' type='text' name='download_filter[filesize]' size='10' value='{$this->filterFields['filesize']}'/>
                  <select name='download_filter[filesize_units]' class='tbox'>
                     <option value='1' ".($this->filterFields['filesize_units'] == '' ? " selected='selected' " : "")." >b</option>
                     <option value='1024' ".($this->filterFields['filesize_units'] == '1024' ? " selected='selected' " : "")." >Kb</option>
                     <option value='1048576' ".($this->filterFields['filesize_units'] == '1048576' ? " selected='selected' " : "")." >Mb</option>
                  </select>
                  </td>
            </tr>
            <tr>
               <td>Date</td>
               <td>
         ";
      $text .= $this->_getConditionList('download_filter[date_condition]', $this->filterFields['date_condition']);
 //     $text .= $eform->datepicker('download_filter[date]', $this->filterFields['date']);
      $text .= "
               </td>
               <td>Status</td>
               <td>
                  <select name='download_filter[status]' class='tbox'>";
      $text .= $this->_getStatusList('download_filter[status]', $this->filterFields['status']);
      $text .= "  </select>
               </td>
               <td>Requested</td>
               <td>
         ";
      $text .= $this->_getConditionList('download_filter[requested_condition]', $this->filterFields['requested_condition']);
      $text .= "  <input class='tbox' type='text' name='download_filter[requested]' size='6' value='{$this->filterFields['requested']}' maxlength='6'/> times
               </td>
            </tr>
            <tr>
               <td>Visibility</td>
               <td>
                  ";
      $text .= $eform->uc_select('download_filter[visible]', $this->filterFields['visible'], $this->userclassOptions);
      $text .= "
               </td>
               <td>URL</td><td><input class='tbox' type='text' name='download_filter[url]' size='50' value='{$this->filterFields['url']}' maxlength='50'/></td>
               <td>Author</td><td><input class='tbox' type='text' name='download_filter[author]' size='20' value='{$this->filterFields['author']}' maxlength='50'/></td>
            </tr>
            <tr>
               <td>Class</td>
               <td>
                  ";
      $text .= $eform->uc_select('download_filter[class]', $this->filterFields['class'], $this->userclassOptions);
      $text .= "
               </td>
               <td>Description</td><td><input class='tbox' type='text' name='download_filter[description]' size='50' value='{$this->filterFields['description']}' maxlength='50'/></td>
               <td>&nbsp;</td>
               <td>&nbsp;</td>
            </tr>
         </table>
         <p>Fields to show</p>
         ";

      // Fields to display list - these are just columns form the download table
      $fields = mysql_list_fields($mySQLdefaultdb, MPREFIX."download");
      $columns = mysql_num_fields($fields);
      for ($i = 0; $i < $columns; $i++) {
         $fname[] = mysql_field_name($fields, $i);
      }
      $replacechar = array("download_","_");
      $text .= "<div>";
      foreach($fname as $fcol)
      {
         $checked = (in_array($fcol,$filterColumns)) ? "checked='checked'" : "";
         $text .= "<div style='float:left;width:20%;'>";
         $text .= "<label for='filter_".$fcol."'><input type='checkbox' name='filter_columns[]' value='".$fcol."' id='filter_".$fcol."' $checked/>".str_replace($replacechar," ",$fcol)."</label>";
         $text .= "</div>\n";
      }

      $text .= "
            <div class='buttons-bar center' style='clear:both;'>
               <button type='submit' class='update' name='download_filter_submit' value='".DOWLAN_51."'><span>".DOWLAN_51."</span></button>
               <button type='submit' class='delete' name='download_filter_delete' value='".DOWLAN_57."'><span>".DOWLAN_57."</span></button>
            </div>
         </div></form></fieldset>";
      return $text;
   }

   function show_existing_items($action, $subAction, $id, $from, $amount)
   {
      global $sql, $rs, $ns, $tp, $mySQLdefaultdb, $pref;
      $sortorder = ($pref['download_order']) ? $pref['download_order'] : "download_datestamp";
      $sortdirection = ($pref['download_sort']) ? strtolower($pref['download_sort']) : "desc";
      if ($sortdirection != 'desc') $sortdirection = 'asc';

      if (!$pref['admin_download_disp'])
      {
         $filterColumns = array("download_name","download_class");
      }
      else
      {
         $filterColumns = explode("|",$pref['admin_download_disp']);
      }
      $query = "SELECT d.*, dc.* FROM `#download` AS d LEFT JOIN `#download_category` AS dc ON dc. download_category_id=d.download_category";
      if ($this->filterFields) {
         $where = array();
         if (strlen($this->filterFields['name']) > 0) {
            array_push($where, "download_name REGEXP('".$this->filterFields['name']."')");
         }
         if (strlen($this->filterFields['url']) > 0) {
            array_push($where, "download_url REGEXP('".$this->filterFields['url']."')");
         }
         if (strlen($this->filterFields['author']) > 0) {
            array_push($where, "download_author REGEXP('".$this->filterFields['author']."')");
         }
         if (strlen($this->filterFields['description']) > 0) {
            array_push($where, "download_description REGEXP('".$this->filterFields['description']."')");
         }
         if (strlen($this->filterFields['category']) != 0) {
            array_push($where, "download_category=".$this->filterFields['category']);
         }
         if (strlen($this->filterFields['filesize']) > 0) {
            array_push($where, "download_filesize".$this->filterFields['filesize_condition'].($this->filterFields['filesize']*$this->filterFields['filesize_units']));
         }
         if ($this->filterFields['status'] != 99) {
            array_push($where, "download_active=".$this->filterFields['status']);
         }
         if (strlen($this->filterFields['date']) > 0) {
            switch ($this->filterFields['date_condition']) {
               case "<=" :
               {
                  array_push($where, "download_datestamp".$this->filterFields['date_condition'].($this->filterFields['date']+86400));
                  break;
               }
               case "=" :
               {
                  array_push($where, "(download_datestamp>=".$this->filterFields['date']." AND download_datestamp<=".($this->filterFields['date']+86399).")");
                  break;
               }
               case ">=" :
               {
                  array_push($where, "download_datestamp".$this->filterFields['date_condition'].$this->filterFields['date']);
                  break;
               }
            }
         }
         if (strlen($this->filterFields['requested']) > 0) {
            array_push($where, "download_requested".$this->filterFields['requested_condition'].$this->filterFields['requested']);
         }
         if ($this->filterFields['visible']) {
            array_push($where, "download_visible=".$this->filterFields['visible']);
         }
         if ($this->filterFields['class']) {
            array_push($where, "download_class=".$this->filterFields['class']);
         }
         $where = (count($where) > 0 ? " WHERE ".implode(" AND ", $where) : "");

         $query .= "$where ORDER BY {$sortorder} {$sortdirection}";
      }
      else
      {
         $query .= " ORDER BY ".($subAction ? $subAction : $sortorder)." ".($id ? $id : $sortdirection)."  LIMIT $from, $amount";
      }

      $text .= '<fieldset id="downloads-list">
         <legend>'.DOWLAN_7.'</legend>';
      $text .= "<div style='text-align:center'><div style='padding : 1px; ".ADMIN_WIDTH."; margin-left: auto; margin-right: auto;'>";
        if ($dl_count = $sql->db_Select_gen($query))
      {
         $text .= $rs->form_open("post", e_SELF."?".e_QUERY, "myform")."
            <table class='adminlist' style='width:100%'>
            <thead>

            <tr class='first last'>
            <th>".DOWLAN_67."</th>
            ";

         // Search Display Column header.----------
         foreach($filterColumns as $disp)
         {
             if ($disp == "download_name")
             {  // Toggle direction
               $text .= "<th><a href='".e_SELF."?main.download_name.".($id == "desc" ? "asc" : "desc").".$from'>".DOWLAN_12."</a></th>";
             }
             else
             {
               $repl = array("download_","_");
               $text .= "<th><a href='".e_SELF."?main.{$disp}.".($id == "desc" ? "asc" : "desc").".$from'>".ucwords(str_replace($repl," ",$disp))."</a></th>";
             }
         }

         $text .="<th class='center'>".LAN_OPTIONS."</th></tr>
		 </thead>
		 <tbody>";

         $rowStyle = "even";

         while ($row = $sql->db_Fetch())
         {
		 	$rowStyle = ($rowStyle == "odd") ? "even" : "odd";
            $text .= "<tr class='{$rowStyle}'><td>".$row['download_id']."</td>";

            // Display Chosen options
            foreach($filterColumns as $disp)
            {
               $text .= "<td>";
               switch ($disp)
               {
                  case "download_name" :
                       $text .= "<a href='".e_PLUGIN."download/download.php?view.".$row['download_id']."'>".$tp->toHTML($row['download_name'])."</a>";
                     break;
                  case "download_category" :
                     $text .= $tp->toHTML($row['download_category_name']);
                     break;
                  case "download_datestamp" :
                  global $gen;
                     $text .= ($row[$disp]) ? $gen->convert_date($row[$disp],'short') : "";
                     break;
                  case "download_class" :
                  case "download_visible" :
                     $text .= r_userclass_name($row[$disp])."&nbsp;";
                     break;
                  case "download_filesize" :
                     $text .= ($row[$disp]) ? $this->e107->parseMemorySize(($row[$disp])) : "";
                     break;
                  case "download_thumb" :
                     $text .= ($row[$disp]) ? "<img src='".e_FILE."downloadthumbs/".$row[$disp]."' alt=''/>" : "";
                     break;
                  case "download_image" :
                     $text .= "<a rel='external' href='".e_FILE."downloadimages/".$row[$disp]."' >".$row[$disp]."</a>";
                     break;
                  case "download_description" :
                     $text .= $tp->toHTML($row[$disp],TRUE);
                     break;
                  case "download_active" :
                     if ($row[$disp]== 1)
                     {
                        $text .= "<img src='".ADMIN_TRUE_ICON_PATH."' title='".DOWLAN_123."' alt='' style='cursor:help'/>";
                     }
                     elseif ($row[$disp]== 2)
                     {
                        $text .= "<img src='".ADMIN_TRUE_ICON_PATH."' title='".DOWLAN_124."' alt='' style='cursor:help'/><img src='".ADMIN_TRUE_ICON_PATH."' title='".DOWLAN_124."' alt='' style='cursor:help'/>";
                     }
                     else
                     {
                        $text .= "<img src='".ADMIN_FALSE_ICON_PATH."' title='".DOWLAN_122."' alt='' style='cursor:help'/>";
                     }
                     break;
                  case "download_comment" :
                     $text .= ($row[$disp]) ? ADMIN_TRUE_ICON : "";
                     break;
                  default :
                     $text .= $tp->toHTML($row[$disp]);
               }
               $text .= "</td>";
            }

            $text .= "
                  <td class='center'>
                     <a href='".e_SELF."?create.edit.".$row['download_id']."'>".ADMIN_EDIT_ICON."</a>
                     <input type='image' title='".LAN_DELETE."' name='delete[main_".$row['download_id']."]' src='".ADMIN_DELETE_ICON_PATH."' onclick=\"return jsconfirm('".$tp->toJS(DOWLAN_33." [ID: ".$row['download_id']." ]")."') \"/>
                  </td>
                  </tr>";
         }
         $text .= "</tbody></table></form>";
      }
      else
      {   // 'No downloads yet'
        $text .= "<div style='text-align:center'>".DOWLAN_6."</div>";
      }
      $text .= "</div>";

      // Next-Previous.
      $downloads = $sql->db_Count("download");
      if ($downloads > $amount && !$_POST['searchquery'])
      {
         $parms = "{$downloads},{$amount},{$from},".e_SELF."?".(e_QUERY ? "$action.$subAction.$id." : "main.{$sortorder}.{$sortdirection}.")."[FROM]";
         $text .= "<br/>".$tp->parseTemplate("{NEXTPREV={$parms}}");
      }

      $text .= "</div>
      </fieldset>";
      return $text;
   }

// ---------------------------------------------------------------------------


   // Given the string which is stored in the DB, turns it into an array of mirror entries
   // If $byID is true, the array index is the mirror ID. Otherwise its a simple array
   function makeMirrorArray($source, $byID = FALSE)
   {
      $ret = array();
      if ($source)
      {
         $mirrorTArray = explode(chr(1), $source);

         $count = 0;
         foreach($mirrorTArray as $mirror)
         {
            if ($mirror)
            {
               list($mid, $murl, $mreq, $msize) = explode(",", $mirror);
               $ret[$byID ? $mid : $count] = array('id' => $mid, 'url' => $murl, 'requests' => $mreq, 'filesize' => $msize);
               $count++;
            }
         }
      }
      return $ret;
   }


   // Turn the array into a string which can be stored in the DB
   function compressMirrorArray($source)
   {
      if (!is_array($source) || !count($source)) return '';
      $inter = array();
      foreach ($source as $s)
      {
         $inter[] = $s['id'].','.$s['url'].','.$s['requests'].','.$s['filesize'];
      }
      return implode(chr(1),$inter);
   }



   function create_download($subAction, $id)
   {
      global $download, $e107, $cal, $tp, $sql, $fl, $rs, $ns, $file_array, $image_array, $thumb_array, $pst;
      require_once(e_PLUGIN.'download/download_shortcodes.php');
      require_once(e_HANDLER."form_handler.php");

      if ($file_array = $fl->get_files(e_DOWNLOAD, "","standard",5))
      {
            sort($file_array);
      }
      if ($public_array = $fl->get_files(e_FILE."public/"))
      {
         foreach($public_array as $key=>$val)
         {
             $file_array[] = str_replace(e_FILE."public/","",$val);
         }
      }
      if ($sql->db_Select("rbinary"))
      {
         while ($row = $sql->db_Fetch())
         {
            extract($row);
            $file_array[] = "Binary ".$binary_id."/".$binary_name;
         }
      }
      if ($image_array = $fl->get_files(e_FILE.'downloadimages/', '\.gif$|\.jpg$|\.png$|\.GIF$|\.JPG$|\.PNG$','standard',2))
      {
         sort($image_array);
      }
      if ($thumb_array = $fl->get_files(e_FILE.'downloadthumbs/', '\.gif$|\.jpg$|\.png$|\.GIF$|\.JPG$|\.PNG$','standard',2))
      {
         sort($thumb_array);
      }

      $eform = new e_form();
      $mirrorArray = array();

      $download_status[0] = DOWLAN_122;
      $download_status[1] = DOWLAN_123;
      $download_status[2] = DOWLAN_124;
      $preset = $pst->read_preset("admin_downloads");  // read preset values into array
      extract($preset);

      if (!$sql->db_Select("download_category"))
      {
         $ns->tablerender(ADLAN_24, "<div style='text-align:center'>".DOWLAN_5."</div>");
         return;
      }
      $download_active = 1;
      if ($subAction == "edit" && !$_POST['submit'])
      {
         if ($sql->db_Select("download", "*", "download_id=".$id))
         {
            $row = $sql->db_Fetch();
            extract($row);

            $mirrorArray = $this->makeMirrorArray($row['download_mirror']);
         }
      }

      if ($subAction == "dlm" && !$_POST['submit'])
      {
         require_once(e_PLUGIN.'download/download_shortcodes.php');
         if ($sql->db_Select("upload", "*", "upload_id=".$id))
         {
            $row = $sql->db_Fetch();

            $download_category = $row['upload_category'];
            $download_name = $row['upload_name'].($row['upload_version'] ? " v" . $row['upload_version'] : "");
            $download_url = $row['upload_file'];
            $download_author_email = $row['upload_email'];
            $download_author_website = $row['upload_website'];
            $download_description = $row['upload_description'];
            $download_image = $row['upload_ss'];
            $download_filesize = $row['upload_filesize'];
            $image_array[] = array("path" => "", "fname" => $row['upload_ss']);
            $download_author = substr($row['upload_poster'], (strpos($row['upload_poster'], ".")+1));
         }
      }


      $text = "
      <div class='admintabs' id='tab-container'>
         <ul class='e-tabs e-hideme' id='core-download-tabs'>
            <li id='tab-general'><a href='#download-create'>".DOWLAN_175."</a></li>
            <li id='tab-external'><a href='#download-edit-external'>".DOWLAN_176."</a></li>
            <li id='tab-mirror'><a href='#download-edit-mirror'>".DOWLAN_128."</a></li>
         </ul>
         <div style='text-align:center'>
            <form method='post' action='".e_SELF."?".e_QUERY."' id='myform'>
               <fieldset id='download-create'>
                  <table style='".ADMIN_WIDTH."' class='fborder'>
                     <tr>
                        <td style='width:20%;'>".DOWLAN_13."</td>
                        <td style='width:80%'>
                           <div>".DOWLAN_131."&nbsp;&nbsp;
                              <select name='download_url' class='tbox'>
                                 <option value=''>&nbsp;</option>
         ";

      $counter = 0;
      while (isset($file_array[$counter]))
      {
         $fpath = str_replace(e_DOWNLOAD,"",$file_array[$counter]['path']).$file_array[$counter]['fname'];
         $selected = '';
         if (stristr($fpath, $download_url) !== FALSE)
         {
            $selected = " selected='selected'";
            $found = 1;
         }

         $text .= "<option value='".$fpath."' $selected>".$fpath."</option>\n";
         $counter++;
      }

      if (preg_match("/http:|ftp:/", $download_url))
      {
         $download_url_external = $download_url;
         $download_url = '';
      }

      $etext = " - (".DOWLAN_68.")";
      if (file_exists(e_FILE."public/".$download_url))
      {
         $etext = "";
      }

      if (!$found && $download_url)
      {
         $text .= "<option value='".$download_url."' selected='selected'>".$download_url.$etext."</option>\n";
      }

      $text .= "             </select>
                        </div>
                     </td>
                  </tr>
               </table>
            </fieldset>
            <fieldset id='download-edit-external'>
               <table style='".ADMIN_WIDTH."' class='fborder'>
                  <tr>
                       <td style='width:20%;'>".DOWLAN_149."</td>
                       <td style='width:80%;'>
                          <input class='tbox' type='text' name='download_url_external' size='70' value='{$download_url_external}' maxlength='255'/>
                       </td>
                    </tr>
                    <tr>
                       <td>".DOWLAN_66."</td>
                       <td>
                          <input class='tbox' type='text' name='download_filesize_external' size='8' value='{$download_filesize}' maxlength='10'/>
                       </td>
                  </tr>
               </table>
            </fieldset>
            <fieldset id='download-edit-mirror'>
               <table style='".ADMIN_WIDTH."' class='fborder'>
                  <tr>
                     <td style='width:20%'><span title='".DOWLAN_129."' style='cursor:help'>".DOWLAN_128."</span></td>
                     <td style='width:80%'>";

      // See if any mirrors to display
      if (!$sql -> db_Select("download_mirror"))
      {   // No mirrors defined here
         $text .= DOWLAN_144."</tr>";
      }
      else
      {
         $text .= DOWLAN_132."<div id='mirrorsection'>";
         $mirrorList = $sql -> db_getList();         // Get the list of possible mirrors
         $m_count = (count($mirrorArray) ? count($mirrorArray) : 1);      // Count of mirrors actually in use (or count of 1 if none defined yet)
         for($count = 1; $count <= $m_count; $count++)
         {
            $opt = ($count==1) ? "id='mirror'" : "";
            $text .="
                        <div {$opt}>
                           <select name='download_mirror_name[]' class='tbox'>
                              <option value=''>&nbsp;</option>";

            foreach ($mirrorList as $mirror)
            {
               extract($mirror);
               $text .= "<option value='{$mirror_id}'".($mirror_id == $mirrorArray[($count-1)]['id'] ? " selected='selected'" : "").">{$mirror_name}</option>\n";
            }

            $text .= "</select>
                           <input  class='tbox' type='text' name='download_mirror[]' style='width: 60%;' value=\"".$mirrorArray[($count-1)]['url']."\" maxlength='200'/>
                           <input  class='tbox' type='text' name='download_mirror_size[]' style='width: 15%;' value=\"".$mirrorArray[($count-1)]['filesize']."\" maxlength='10'/>";
            if (DOWNLOAD_DEBUG)
            {
               if ($id)
               {
                  $text .= '('.$mirrorArray[($count-1)]['requests'].')';
               }
               else
               {
               $text .= "<input  class='tbox' type='text' name='download_mirror_requests[]' style='width: 10%;' value=\"".$mirrorArray[($count-1)]['requests']."\" maxlength='10'/>";
               }
            }
            $text .= "  </div>";
         }
         $text .="      </div>
                        <input class='button' type='button' name='addoption' value='".DOWLAN_130."' onclick=\"duplicateHTML('mirror','mirrorsection')\"/>
                     </td>
                  </tr>
                  <tr>
                     <td style='width:20%' ><span style='cursor:help' title='".DOWLAN_154."'>".DOWLAN_155."</span></td>
                     <td style='width:80%'>
                        <input type='radio' name='download_mirror_type' value='1'".($download_mirror_type ? " checked='checked'" : "")."/> ".DOWLAN_156."<br/>
                        <input type='radio' name='download_mirror_type' value='0'".(!$download_mirror_type ? " checked='checked'" : "")."/> ".DOWLAN_157."
                     </td>
                  </tr>";
      }      // End of mirror-related stuff

      $download_author = $subAction != "edit" && $download_author == "" ? USERNAME : $download_author;//TODO what if editing an no author specified
      $download_author_email = $subAction != "edit" && $download_author_email == "" ? USEREMAIL : $download_author_email;
      $text .= "
               </table>
            </fieldset>
            <fieldset id='download-edit-therest'>
               <table style='".ADMIN_WIDTH."' class='fborder'>
                  <tr>
                     <td style='width:20%'>".DOWLAN_11."</td>
                     <td style='width:80%'>";
      $text .= $this->getCategorySelectList($download_category);
      $text .= "     </td>
                  </tr>
                  <tr>
                     <td style='width:20%;'>".DOWLAN_12."</td>
                     <td style='width:80%'>
                        <input class='tbox' type='text' name='download_name' size='60' value=\"".$tp->toForm($download_name)."\" maxlength='200'/>
                     </td>
                  </tr>
                  <tr>
                     <td style='width:20%'>".DOWLAN_15."</td>
                     <td style='width:80%'>
                        <input class='tbox' type='text' name='download_author' size='60' value='$download_author' maxlength='100'/>
                     </td>
                  </tr>
                  <tr>
                     <td style='width:20%'>".DOWLAN_16."</td>
                     <td style='width:80%'>
                        <input class='tbox' type='text' name='download_author_email' size='60' value='$download_author_email' maxlength='100'/>
                     </td>
                  </tr>
                  <tr>
                     <td style='width:20%'>".DOWLAN_17."</td>
                     <td style='width:80%'>
                        <input class='tbox' type='text' name='download_author_website' size='60' value='$download_author_website' maxlength='100'/>
                     </td>
                  </tr>
                  <tr>
                     <td style='width:20%'>".DOWLAN_18."</td>
                     <td style='width:80%'>
      ";
      $text .= $eform->bbarea('download_description',$download_description);
      $text .= "     </td>
                  </tr>
                  <tr>
                     <td style='width:20%'>".DOWLAN_19."</td>
                     <td style='width:80%'>
                        <select name='download_image' class='tbox'>
                           <option value=''>&nbsp;</option>";
      foreach($image_array as $img)
      {
         $fpath = str_replace(e_FILE."downloadimages/","",$img['path'].$img['fname']);
           $sel = ($download_image == $fpath) ? " selected='selected'" : "";
           $text .= "<option value='".$fpath."' $sel>".$fpath."</option>\n";
      }

      $text .= "     </select>";
      if ($subAction == "dlm" && $download_image)
      {
         $text .= "
         <input type='hidden' name='move_image' value='1'/>\n";
      }
      $text .= "     </td>
                  </tr>
                  <tr>
                     <td style='width:20%'>".DOWLAN_20."</td>
                     <td style='width:80%'>
                        <select name='download_thumb' class='tbox'>
                           <option value=''>&nbsp;</option>";
      foreach($thumb_array as $thm){
         $tpath = str_replace(e_FILE."downloadthumbs/","",$thm['path'].$thm['fname']);
         $sel = ($download_thumb == $tpath) ? " selected='selected'" : "";
         $text .= "<option value='".$tpath."' $sel>".$tpath."</option>\n";
      }

      $text .= "        </select>
                     </td>
                  </tr>
                  <tr>
                     <td style='width:20%'>".LAN_DATESTAMP."</td>
                     <td style='width:80%'>";
      if (!$download_datestamp){
           $download_datestamp = time();
      }
      $cal_options['showsTime'] = false;
      $cal_options['showOthers'] = false;
      $cal_options['weekNumbers'] = false;
      $cal_options['ifFormat'] = "%d/%m/%Y %H:%M:%S";
      $cal_options['timeFormat'] = "24";
      $cal_attrib['class'] = "tbox";
      $cal_attrib['size'] = "22";
      $cal_attrib['name'] = "download_datestamp";
      $cal_attrib['value'] = date("d/m/Y H:i:s", $download_datestamp);
      $text .= $cal->make_input_field($cal_options, $cal_attrib);
      $update_checked = ($_POST['update_datestamp']) ? "checked='checked'" : "";
      $text .= "        &nbsp;&nbsp;<span><input type='checkbox' value='1' name='update_datestamp' $update_checked/>".DOWLAN_148."</span>
                     </td>
                  </tr>
                  <tr>
                     <td style='width:20%'>".DOWLAN_21."</td>
                     <td style='width:80%'>
                        <select name='download_active' class='tbox'>";
      foreach($download_status as $key => $val){
         $sel = ($download_active == $key) ? " selected = 'selected' " : "";
           $text .= "<option value='{$key}' {$sel}>{$val}</option>\n";
      }
      $text .= "        </select>
                     </td>
                  </tr>
                  <tr>
                     <td style='width:20%'>".DOWLAN_102."</td>
                     <td style='width:80%'>";
      if ($download_comment == "0") {
         $text .= LAN_YES.": <input type='radio' name='download_comment' value='1'/>
            ".LAN_NO.": <input type='radio' name='download_comment' value='0' checked='checked'/>";
      } else {
         $text .= LAN_YES.": <input type='radio' name='download_comment' value='1' checked='checked'/>
            ".LAN_NO.": <input type='radio' name='download_comment' value='0'/>";
      }
      $text .= "     </td>
                  </tr>
                  <tr>
                     <td style='width:20%'>".DOWLAN_145."</td>
                     <td style='width:80%'>".r_userclass('download_visible', $download_visible, 'off', 'public, nobody, member, admin, classes, language')."</td>
                  </tr>
                  <tr>
                     <td style='width:20%'>".DOWLAN_106."</td>
                     <td style='width:80%'>".r_userclass('download_class', $download_class, 'off', 'public, nobody, member, admin, classes, language')."</td>
                  </tr>";
      if ($subAction == "dlm") {
         $text .= "
                  <tr>
                     <td style='width:30%'>".DOWLAN_153."</td>
                     <td style='width:70%'>
                        <select name='move_file' class='tbox'>
                           <option value=''>".LAN_NO."</option>";
           $dl_dirlist = $fl->get_dirs(e_DOWNLOAD);
           if ($dl_dirlist){
            sort($dl_dirlist);
            $text .= "<option value='".e_DOWNLOAD."'>/</option>\n";
            foreach($dl_dirlist as $dirs)
            {
                 $text .= "<option value='". e_DOWNLOAD.$dirs."/'>".$dirs."/</option>\n";
            }
         }
         else
         {
              $text .= "<option value='".e_DOWNLOAD."'>".LAN_YES."</option>\n";
         }
         $text .= "     </select>
                     </td>
                  </tr>
                  <tr>
                     <td style='width:30%'>".DOWLAN_103."</td>
                     <td style='width:70%'>
                        <input type='checkbox' name='remove_upload' value='1'/>
                        <input type='hidden' name='remove_id' value='$id'/>
                     </td>
                  </tr>";
      }

      //triggerHook
      $data = array('method'=>'form', 'table'=>'download', 'id'=>$id, 'plugin'=>'download', 'function'=>'create_download');
      $hooks = $e107->e_event->triggerHook($data);
      if(!empty($hooks))
      {
         $text .= "<tr>
                     <td colspan='2' >".LAN_HOOKS." </td>
                   </tr>
         ";
         foreach($hooks as $hook)
         {
            if(!empty($hook))
            {
               $text .= "<tr>
                            <td class='label'>".$hook['caption']."</td>
                            <td class='control'>".$hook['text']."</td>
                         </tr>";
            }
         }
      }

      $text .= "  <tr style=''>
                     <td colspan='2' style='text-align:center'>";
      if ($id && $subAction == "edit") {
         $text .= "<input class='button' type='submit' name='submit_download' value='".DOWLAN_24."'/> ";
      } else {
         $text .= "<input class='button' type='submit' name='submit_download' value='".DOWLAN_25."'/>";
      }

      $text .= "
                     </td>
                  </tr>
               </table>
            </fieldset>
         </form>
         </div>
         </div>";
      $ns->tablerender(ADLAN_24, $text);
   }


// -----------------------------------------------------------------------------

   function show_message($message) {
      global $ns;
      $ns->tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
   }

// -----------------------------------------------------------------------------


   // Actually save a new or edited download to the DB
   function submit_download($subAction, $id)
   {
      global $e107, $tp, $sql, $DOWNLOADS_DIRECTORY, $e_event;

      $dlInfo = array();
      $dlMirrors = array();

      if ($subAction == 'edit')
      {
         if ($_POST['download_url_external'] == '')
         {
            $_POST['download_filesize_external'] = FALSE;
         }
      }

      if ($_POST['download_url_external'] && $_POST['download_url'] == '')
      {
         $dlInfo['download_url'] = $tp->toDB($_POST['download_url_external']);
         $filesize = intval($_POST['download_filesize_external']);
      }
      else
      {
         $dlInfo['download_url'] = $tp->toDB($_POST['download_url']);
         if ($_POST['download_filesize_external'])
         {
            $filesize = intval($_POST['download_filesize_external']);
         }
         else
         {
            if (strpos($DOWNLOADS_DIRECTORY, "/") === 0 || strpos($DOWNLOADS_DIRECTORY, ":") >= 1)
            {
               $filesize = filesize($DOWNLOADS_DIRECTORY.$dlInfo['download_url']);
            }
            else
            {
               $filesize = filesize(e_BASE.$DOWNLOADS_DIRECTORY.$dlInfo['download_url']);
            }
         }
      }

      if (!$filesize)
      {
         if ($sql->db_Select("upload", "upload_filesize", "upload_file='{$dlInfo['download_url']}'"))
         {
            $row = $sql->db_Fetch();
            $filesize = $row['upload_filesize'];
         }
      }
      $dlInfo['download_filesize'] = $filesize;


      //  ----   Move Images and Files ------------
      if ($_POST['move_image'])
      {
         if ($_POST['download_thumb'])
         {
            $oldname = e_FILE."public/".$_POST['download_thumb'];
            $newname = e_FILE."downloadthumbs/".$_POST['download_thumb'];
            if (!$this -> move_file($oldname,$newname))
            {
                  return;
            }
         }
         if ($_POST['download_image'])
         {
            $oldname = e_FILE."public/".$_POST['download_image'];
            $newname = e_FILE."downloadimages/".$_POST['download_image'];
            if (!$this -> move_file($oldname,$newname))
            {
                  return;
            }
         }
      }

        if ($_POST['move_file'] && $_POST['download_url'])
      {
           $oldname = e_FILE."public/".$_POST['download_url'];
         $newname = $_POST['move_file'].$_POST['download_url'];
         if (!$this -> move_file($oldname,$newname))
         {
               return;
         }
            $dlInfo['download_url'] = str_replace(e_DOWNLOAD,"",$newname);
      }


       // ------------------------------------------


      $dlInfo['download_description'] = $tp->toDB($_POST['download_description']);
      $dlInfo['download_name'] = $tp->toDB($_POST['download_name']);
      $dlInfo['download_author'] = $tp->toDB($_POST['download_author']);
      $dlInfo['download_author_email'] = $tp->toDB($_POST['download_author_email']);
      $dlInfo['download_author_website'] = $tp->toDB($_POST['download_author_website']);
      $dlInfo['download_category'] = intval($_POST['download_category']);
      $dlInfo['download_active']  = intval($_POST['download_active']);
      $dlInfo['download_thumb'] = $tp->toDB($_POST['download_thumb']);
      $dlInfo['download_image'] = $tp->toDB($_POST['download_image']);
      $dlInfo['download_comment'] = $tp->toDB($_POST['download_comment']);
      $dlInfo['download_class'] = intval($_POST['download_class']);
      $dlInfo['download_visible'] =intval($_POST['download_visible']);

      if (preg_match("#(.*?)/(.*?)/(.*?) (.*?):(.*?):(.*?)$#", $_POST['download_datestamp'], $matches))
      {
         $dlInfo['download_datestamp'] = mktime($matches[4], $matches[5], $matches[6], $matches[2], $matches[1], $matches[3]);
      }
      else
      {
           $dlInfo['download_datestamp'] = time();
      }

      if ($_POST['update_datestamp'])
      {
         $dlInfo['download_datestamp'] = time();
      }

      $mirrorStr = "";
      $mirrorFlag = FALSE;

      // See if any mirrors defined
      // Need to check all the possible mirror names - might have deleted the first one if we're in edit mode
      foreach ($_POST['download_mirror_name'] as $mn)
      {
         if ($mn)
         {
            $mirrorFlag = TRUE;
            break;
         }
      }
      if ($mirrorFlag)
      {
         $mirrors = count($_POST['download_mirror_name']);
         $mirrorArray = array();
         $newMirrorArray = array();
         if ($id && $sql->db_Select('download','download_mirror', 'download_id = '.$id))      // Get existing download stats
         {
            if ($row = $sql->db_Fetch())
            {
               $mirrorArray = $this->makeMirrorArray($row['download_mirror'], TRUE);
            }
         }
         for($a=0; $a<$mirrors; $a++)
         {
            $mid = trim($_POST['download_mirror_name'][$a]);
            $murl = trim($_POST['download_mirror'][$a]);
            $msize = trim($_POST['download_mirror_size'][$a]);
            if ($mid && $murl)
            {
               $newMirrorArray[$mid] = array('id' => $mid, 'url' => $murl, 'requests' => 0, 'filesize' => $msize);
               if (DOWNLOAD_DEBUG && !$id)
               {
                  $newMirrorArray[$mid]['requests'] = intval($_POST['download_mirror_requests'][$a]);
               }
            }
         }
         // Now copy across any existing usage figures
         foreach ($newMirrorArray as $k => $m)
         {
            if (isset($mirrorArray[$k]))
            {
               $newMirrorArray[$k]['requests'] = $mirrorArray[$k]['requests'];
            }
         }
         $mirrorStr = $this->compressMirrorArray($newMirrorArray);
      }

      $dlMirrors['download_mirror']=$mirrorStr;
      $dlMirrors['download_mirror_type']=intval($_POST['download_mirror_type']);

      if ($id)
      {  // Its an edit
         // Process triggers before calling admin_update so trigger messages can be shown
         $data = array('method'=>'update', 'table'=>'download', 'id'=>$id, 'plugin'=>'download', 'function'=>'update_download');
         $hooks = $e107->e_event->triggerHook($data);
         require_once(e_HANDLER."message_handler.php");
         $emessage = &eMessage::getInstance();
         $emessage->add($hooks, E_MESSAGE_SUCCESS);

         admin_update($sql->db_UpdateArray('download',array_merge($dlInfo,$dlMirrors),'WHERE download_id='.intval($id)), 'update', DOWLAN_2." (<a href='".e_PLUGIN."download/download.php?view.".$id."'>".$_POST['download_name']."</a>)");
         $dlInfo['download_id'] = $id;
         $this->downloadLog('DOWNL_06',$dlInfo,$dlMirrors);
         $dlInfo['download_datestamp'] = $time;      // This is what 0.7 did, regardless of settings
         unset($dlInfo['download_class']);         // Also replicating 0.7
         $e_event->trigger('dlupdate', $dlInfo);
      }
      else
      {
         if ($download_id = $sql->db_Insert('download',array_merge($dlInfo,$dlMirrors)))
         {
            // Process triggers before calling admin_update so trigger messages can be shown
            $data = array('method'=>'create', 'table'=>'download', 'id'=>$download_id, 'plugin'=>'download', 'function'=>'create_download');
            $hooks = $e107->e_event->triggerHook($data);
            require_once(e_HANDLER."message_handler.php");
            $emessage = &eMessage::getInstance();
            $emessage->add($hooks, E_MESSAGE_SUCCESS);

            admin_update($download_id, 'insert', DOWLAN_1." (<a href='".e_PLUGIN."download/download.php?view.".$download_id."'>".$_POST['download_name']."</a>)");

            $dlInfo['download_id'] = $download_id;
            $this->downloadLog('DOWNL_05',$dlInfo,$dlMirrors);
            $dlInfo['download_datestamp'] = $time;      // This is what 0.7 did, regardless of settings
            unset($dlInfo['download_class']);         // Also replicating 0.7
            $e_event->trigger("dlpost", $dlInfo);

            if ($_POST['remove_upload'])
            {
               $sql->db_Update("upload", "upload_active='1' WHERE upload_id='".$_POST['remove_id']."'");
               $mes = "<br/>".$_POST['download_name']." ".DOWLAN_104;
               $mes .= "<br/><br/><a href='".e_ADMIN."upload.php'>".DOWLAN_105."</a>";
               $this->show_message($mes);
            }
         }
      }
   }


   function downloadLog($aText, &$dlInfo, &$mirrorInfo=NULL)
   {
      global $admin_log;
      $logString = DOWLAN_9;
      foreach ($dlInfo as $k => $v)
      {
         $logString .= '[!br!]'.$k.'=>'.$v;
      }
      if ($mirrorInfo != NULL)
      {
         foreach ($mirrorInfo as $k => $v)
         {
            $logString .= '[!br!]'.$k.'=>'.$v;
         }
      }
      $admin_log->log_event($aText,$logString,E_LOG_INFORMATIVE,'');
   }

// -----------------------------------------------------------------------------

   function show_categories($subAction, $id)
   {
      global $download, $sql, $sql2, $rs, $ns, $tp, $pst;

      require_once(e_HANDLER."form_handler.php");
      $eform = new e_form();

      $text = $rs->form_open("post", e_SELF."?".e_QUERY, "myform");
      $text .= "<div style='padding : 1px; ".ADMIN_WIDTH."; height : 200px; overflow : auto; margin-left: auto; margin-right: auto;'>";

      $qry = "
      SELECT dc.*, COUNT(d.download_id) AS filecount FROM #download_category AS dc
      LEFT JOIN #download AS d ON d.download_category = dc.download_category_id
      GROUP BY dc.download_category_id
      ORDER BY dc.download_category_order
      ";
      if ($sql->db_Select_gen($qry))
      {
         $categories = $sql->db_getList();
         foreach($categories as $cat)
         {
            $cat_array[$cat['download_category_parent']][] = $cat;
         }
         $text .= "
         <table class='fborder' style='width:100%'>
            <colgroup>
               <col style='width:5%;'/>
               <col style='width:55%;'/>
               <col style='width:10%;'/>
               <col style='width:10%;'/>
               <col style='width:20%;'/>
            </colgroup>
            <tr>
               <td class='fcaption' colspan='2'>".DOWLAN_11."</td>
               <td class='fcaption'>".DOWLAN_52."</td>
               <td class='fcaption'>".LAN_ORDER."</td>
               <td class='fcaption'>".LAN_OPTIONS."</td>
            </tr>";


         //Start displaying parent categories
         foreach($cat_array[0] as $parent)
         {
            if (strstr($parent['download_category_icon'], chr(1)))
            {
               list($parent['download_category_icon'], $parent['download_category_icon_empty']) = explode(chr(1), $parent['download_category_icon']);
            }

            $text .= "<tr>
               <td style='text-align:center'>".($parent['download_category_icon'] ? "<img src='".e_IMAGE."icons/{$parent['download_category_icon']}' style='vertical-align:middle; border:0' alt=''/>" : "&nbsp;")."</td>
               <td>
                  <a href='".e_PLUGIN."download/download.php?list.{$parent['download_category_id']}'>";
                  $text .= $tp->toHTML($parent['download_category_name']);
                  $text .= "</a><br/>
                  <span class='smalltext'>";
            $text .= $tp->toHTML($parent['download_category_description']);
            $text .= "</span>
               </td>
               <td>
               </td>
               <td>
                  <input class='tbox' type='text' name='catorder[{$parent['download_category_id']}]' value='{$parent['download_category_order']}' size='3'/>
               </td>
               <td style='text-align:left;padding-left:12px'>
                  <a href='".e_SELF."?cat.edit.{$parent['download_category_id']}'>".ADMIN_EDIT_ICON."</a>
               ";
               if (!is_array($cat_array[$parent['download_category_id']]))
               {
                  $text .= "<input type='image' title='".LAN_DELETE."' name='delete[category_{$parent['download_category_id']}]' src='".ADMIN_DELETE_ICON_PATH."' onclick=\"return jsconfirm('".$tp->toJS(DOWLAN_34." [ID: {$parent['download_category_id']} ]")."') \"/>";
               }
            $text .= "
                  </td>
               </tr>
               ";

            //Show sub categories
            if (is_array($cat_array[$parent['download_category_id']]))
            {
               foreach($cat_array[$parent['download_category_id']] as $subcat)
               {

                  if (strstr($subcat['download_category_icon'], chr(1)))
                  {
                     list($subcat['download_category_icon'], $subcat['download_category_icon_empty']) = explode(chr(1), $subcat['download_category_icon']);
                  }
                  $text .= "
                  <tr>
                     <td style='text-align:center'>".($subcat['download_category_icon'] ? "<img src='".e_IMAGE."icons/{$subcat['download_category_icon']}' style='vertical-align:middle; border:0' alt=''/>" : "&nbsp;")."</td>
                     <td>
                        <a href='".e_PLUGIN."download/download.php?list.{$subcat['download_category_id']}'>";
                  $text .= $tp->toHTML($subcat['download_category_name']);
                  $text .= "</a>
                        <br/>
                        <span class='smalltext'>";
                  $text .= $tp->toHTML($subcat['download_category_description']);
                  $text .= "</span>
                     </td>
                     <td>{$subcat['filecount']}</td>
                     <td>
                        <input class='tbox' type='text' name='catorder[{$subcat['download_category_id']}]' value='{$subcat['download_category_order']}' size='3'/>
                     </td>
                     <td style='text-align:left;padding-left:12px'>
                        <a href='".e_SELF."?cat.edit.{$subcat['download_category_id']}'>".ADMIN_EDIT_ICON."</a>";
                  if (!is_array($cat_array[$subcat['download_category_id']]) && !$subcat['filecount'])
                  {
                     $text .= "<input type='image' title='".LAN_DELETE."' name='delete[category_{$subcat['download_category_id']}]' src='".ADMIN_DELETE_ICON_PATH."' onclick=\"return jsconfirm('".$tp->toJS(DOWLAN_34." [ID: {$subcat['download_category_id']} ]")."') \"/>";
                  }
                  $text .= "
                     </td>
                  </tr>";

                  //Show sub-sub categories
                  if (is_array($cat_array[$subcat['download_category_id']]))
                  {
                     foreach($cat_array[$subcat['download_category_id']] as $subsubcat)
                     {

                        if (strstr($subsubcat['download_category_icon'], chr(1)))
                        {
                           list($subsubcat['download_category_icon'], $subsubcat['download_category_icon_empty']) = explode(chr(1), $subsubcat['download_category_icon']);
                        }
                        $text .= "<tr>
                           <td style='text-align:center'>".($subsubcat['download_category_icon'] ? "<img src='".e_IMAGE."icons/{$subsubcat['download_category_icon']}' style='vertical-align:middle; border:0' alt=''/>" : "&nbsp;")."</td>
                           <td>
                              &nbsp;&nbsp;&nbsp;&nbsp;<a href='".e_PLUGIN."download/download.php?list.{$subsubcat['download_category_id']}'>";
                        $text .= $tp->toHTML($subsubcat['download_category_name']);
                        $text .= "</a>
                              <br/>
                              &nbsp;&nbsp;&nbsp;&nbsp;<span class='smalltext'>";
                        $text .= $tp->toHTML($subsubcat['download_category_description']);
                        $text .= "</span>
                           </td>
                           <td>{$subsubcat['filecount']}</td>
                           <td>
                              <input class='tbox' type='text' name='catorder[{$subsubcat['download_category_id']}]' value='{$subsubcat['download_category_order']}' size='3'/>
                           </td>
                           <td style='text-align:left;padding-left:12px'>
                           <a href='".e_SELF."?cat.edit.{$subsubcat['download_category_id']}'>".ADMIN_EDIT_ICON."</a>
                           ";
                        if (!$subsubcat['filecount'])
                        {
                           $text .= "<input type='image' title='".LAN_DELETE."' name='delete[category_{$subsubcat['download_category_id']}]' src='".ADMIN_DELETE_ICON_PATH."' onclick=\"return jsconfirm('".$tp->toJS(DOWLAN_34." [ID: {$subsubcat['download_category_id']} ]")."') \"/>";
                        }
                        $text .= "
                           </td>
                           </tr>";
                     }
                  }
               }
            }

         }

         $text .= "</table></div>";
         $text .= "<div style='text-align:center'>
            <input class='button' type='submit' name='update_catorder' value='".LAN_UPDATE."'/>
            </div>";
      }
      else
      {
         $text .= "<div style='text-align:center'>".DOWLAN_38."</div>";
      }
      $text .= "</form>";
      $ns->tablerender(DOWLAN_37, $text);

      unset($download_category_id, $download_category_name, $download_category_description, $download_category_parent, $download_category_icon, $download_category_class);

      $handle = opendir(e_IMAGE."icons");
      while ($file = readdir($handle)) {
         if ($file != "." && $file != ".." && $file != "/" && $file != "CVS") {
            $iconlist[] = $file;
         }
      }
      closedir($handle);

      if ($subAction == "edit" && !$_POST['add_category']) {
         if ($sql->db_Select("download_category", "*", "download_category_id=$id")) {
            $row = $sql->db_Fetch();
             extract($row);
            $main_category_parent = $download_category_parent;
            if (strstr($download_category_icon, chr(1)))
            {
               list($download_category_icon, $download_category_icon_empty) = explode(chr(1), $download_category_icon);
            }
            else
            {
               $download_category_icon_empty = "";
            }
         }
      }

      $preset = $pst->read_preset("admin_dl_cat");  // read preset values into array
      extract($preset);

      $frm_action = (isset($_POST['add_category'])) ? e_SELF."?cat" : e_SELF."?".e_QUERY;
      $text = "<div style='text-align:center'>
         <form method='post' action='{$frm_action}' id='dlform'>
         <table style='".ADMIN_WIDTH."' class='fborder'>
         <colgroup>
            <col style='width:30%'/>
            <col style='width:70%'/>
         </colgroup>
         <tbody>
         <tr>
         <td>".DOWLAN_37.": </td>
         <td>";

      $text .= $this->getCategorySelectList($main_category_parent, false, false, DOWLAN_40);
      $text .= "</td></tr><tr>
         <td>".DOWLAN_12.": </td>
         <td>
         <input class='tbox' type='text' name='download_category_name' size='40' value='$download_category_name' maxlength='100'/>
         </td>
         </tr>

         <tr>
         <td>".DOWLAN_18.": </td>
         <td>";
         $text .= $eform->bbarea('download_category_description',$download_category_description);
         $text .= "</td>
         </tr>

         <tr>
         <td>".DOWLAN_41.": </td>
         <td>
         <input class='tbox' type='text' id='download_category_icon' name='download_category_icon' size='60' value='$download_category_icon' maxlength='100'/>
         <input class='button' type ='button' style='cursor:pointer' size='30' value='".DOWLAN_42."' onclick='expandit(this)'/>
         <div id='cat_icn' style='display:none;{head}' >";

      while (list($key, $icon) = each($iconlist)) {
         $text .= "<a href=\"javascript:insertext('$icon','download_category_icon','cat_icn')\"><img src='".e_IMAGE."icons/".$icon."' style='border:0' alt=''/></a> ";
      }

      reset($iconlist);
      $text .= "
         </div></td>
         </tr>

         <tr>
         <td>".DOWLAN_147.": </td>
         <td>
         <input class='tbox' type='text' id='download_category_icon_empty' name='download_category_icon_empty' size='60' value='$download_category_icon_empty' maxlength='100'/>
         <input class='button' type ='button' style='cursor:pointer' size='30' value='".DOWLAN_42."' onclick='expandit(this)'/>
         <div id='cat_icn_empty' style='display:none;{head}' >";

      while (list($key, $icon) = each($iconlist)) {
         $text .= "<a href=\"javascript:insertext('$icon','download_category_icon_empty','cat_icn_empty')\"><img src='".e_IMAGE."icons/".$icon."' style='border:0' alt=''/></a> ";
      }

      $text .= "
         </div></td>
         </tr>
         <tr>
         <td>".DOWLAN_43.":<br/><span class='smalltext'>(".DOWLAN_44.")</span></td>
         <td>".r_userclass("download_category_class", $download_category_class, 'off', 'public, nobody, member, admin, classes, language')."
         </td></tr>";

      $text .= "
         <tr style=''>
         <td colspan='2' style='text-align:center' class='forumheader'>";
      if ($id && $subAction == "edit" && !isset($_POST['add_category'])) {
         $text .= "<input class='button' type='submit' name='add_category' value='".DOWLAN_46."'/> ";
      } else {
         $text .= "<input class='button' type='submit' name='add_category' value='".DOWLAN_45."'/>";
      }
      $text .= "</td>
         </tr>
         </tbody>
         </table>
         </form>
         </div>";
      $ns->tablerender(DOWLAN_39, $text);
   }

   function create_category($subAction, $id)
   {
      global $sql, $tp, $admin_log;
      $download_category_name = $tp->toDB($_POST['download_category_name']);
      $download_category_description = $tp->toDB($_POST['download_category_description']);
        $download_category_icon = $tp->toDB($_POST['download_category_icon']);
      $download_category_class = $tp->toDB($_POST['download_category_class']);
      $download_categoory_parent = intval($_POST['download_category_parent']);

      if (isset($_POST['download_category_icon_empty']) && $_POST['download_category_icon_empty'] != "")
      {
        $download_category_icon .= trim(chr(1).$tp->toDB($_POST['download_category_icon_empty']));
      }

      if ($id)
      {
         admin_update($sql->db_Update("download_category", "download_category_name='{$download_category_name}', download_category_description='{$download_category_description}', download_category_icon ='{$download_category_icon}', download_category_parent= '{$download_categoory_parent}', download_category_class='{$download_category_class}' WHERE download_category_id='{$id}'"), 'update', DOWLAN_48);
         $admin_log->log_event('DOWNL_03',$download_category_name.'[!br!]'.$download_category_description,E_LOG_INFORMATIVE,'');
      }
      else
      {
         admin_update($sql->db_Insert("download_category", "0, '{$download_category_name}', '{$download_category_description}', '{$download_category_icon}', '{$download_categoory_parent}', '{$download_category_class}', 0 "), 'insert', DOWLAN_47);
         $admin_log->log_event('DOWNL_02',$download_category_name.'[!br!]'.$download_category_description,E_LOG_INFORMATIVE,'');
      }
      if ($subAction == "sn")
      {
         $sql->db_Delete("tmp", "tmp_time='{$id}' ");
      }
   }

   function show_existing_mirrors()
   {
      global $sql, $ns, $tp, $subAction, $id, $delete, $del_id, $admin_log;

      require_once(e_HANDLER."form_handler.php");
      $eform = new e_form();
      if ($delete == "mirror")
      {
         admin_update($sql -> db_Delete("download_mirror", "mirror_id=".$del_id), delete, DOWLAN_135);
         $admin_log->log_event('DOWNL_14','ID: '.$del_id,E_LOG_INFORMATIVE,'');
      }


      if (!$sql -> db_Select("download_mirror"))
      {
         $text = "<div style='text-align:center;'>".DOWLAN_144."</div>"; // No mirrors defined yet
      }
      else
      {

         $text = "<div style='text-align:center'>
         <form method='post' action='".e_SELF."?".e_QUERY."'>
         <table style='".ADMIN_WIDTH."' class='fborder'>
         <tr>
         <td style='width: 10%; text-align: center;' class='forumheader'>ID</td>
         <td style='width: 30%;' class='forumheader'>".DOWLAN_12."</td>
         <td style='width: 30%;' class='forumheader'>".DOWLAN_136."</td>
         <td style='width: 30%; text-align: center;' class='forumheader'>".LAN_OPTIONS."</td>
         </tr>
         ";

         $mirrorList = $sql -> db_getList();

         foreach($mirrorList as $mirror)
         {
            extract($mirror);
            $text .= "

            <tr>
            <td style='width: 10%; text-align: center;'>$mirror_id</td>
            <td style='width: 30%;'>".$tp -> toHTML($mirror_name)."</td>
            <td style='width: 30%;'>".($mirror_image ? "<img src='".e_FILE."downloadimages/".$mirror_image."' alt=''/>" : DOWLAN_28)."</td>
            <td style='width: 30%; text-align: center;'>
            <a href='".e_SELF."?mirror.edit.{$mirror_id}'>".ADMIN_EDIT_ICON."</a>
            <input type='image' title='".LAN_DELETE."' name='delete[mirror_{$mirror_id}]' src='".ADMIN_DELETE_ICON_PATH."' onclick=\"return jsconfirm('".DOWLAN_137." [ID: $mirror_id ]')\"/>
            </td>
            </tr>
            ";
         }
         $text .= "</table></form></div>";

      }

      $ns -> tablerender(DOWLAN_138, $text);

      require_once(e_HANDLER."file_class.php");
      $fl = new e_file;
      $imagelist = $fl->get_files(e_FILE.'downloadimages/');

      if ($subAction == "edit" && !defined("SUBMITTED"))
      {
         $sql -> db_Select("download_mirror", "*", "mirror_id='".intval($id)."' ");
         $mirror = $sql -> db_Fetch();
         extract($mirror);
         $edit = TRUE;
      }
      else
      {
         unset($mirror_name, $mirror_url, $mirror_image, $mirror_location, $mirror_description);
         $edit = FALSE;
      }

      $text = "<div style='text-align:center'>
      <form method='post' action='".e_SELF."?".e_QUERY."' id='dataform'>\n
      <table style='".ADMIN_WIDTH."' class='fborder'>

      <tr>
      <td style='width: 30%;'>".DOWLAN_12."</td>
      <td style='width: 70%;'>
      <input class='tbox' type='text' name='mirror_name' size='60' value='{$mirror_name}' maxlength='200'/>
      </td>
      </tr>

      <tr>
      <td style='width: 30%;'>".DOWLAN_139."</td>
      <td style='width: 70%;'>
      <input class='tbox' type='text' name='mirror_url' size='70' value='{$mirror_url}' maxlength='255'/>
      </td>
      </tr>

      <tr>
      <td style='width: 30%;'>".DOWLAN_136."</td>
      <td style='width: 70%;'>
      <input class='tbox' type='text' id='mirror_image' name='mirror_image' size='60' value='{$mirror_image}' maxlength='200'/>


      <br/><input class='button' type ='button' style='cursor:pointer' size='30' value='".DOWLAN_42."' onclick='expandit(this)'/>
      <div id='imagefile' style='display:none;{head}'>";

      $text .= DOWLAN_140."<br/>";
      foreach($imagelist as $file)
      {
         $text .= "<a href=\"javascript:insertext('".$file['fname']."','mirror_image','imagefile')\"><img src='".e_FILE."downloadimages/".$file['fname']."' alt=''/></a> ";
      }

      $text .= "</div>
      </td>
      </tr>

      <tr>
      <td style='width: 30%;'>".DOWLAN_141."</td>
      <td style='width: 70%;'>
      <input class='tbox' type='text' name='mirror_location' size='60' value='$mirror_location' maxlength='200'/>
      </td>
      </tr>

      <tr>
      <td style='width: 30%;'>".DOWLAN_18."</td>
      <td style='width: 70%;'>";
      $text .= $eform->bbarea('mirror_description',$mirror_description);
      $text .= "</td>
      </tr>

      <tr>
      <td colspan='2' class='forumheader' style='text-align:center;'>
      ".($edit ? "<input class='button' type='submit' name='submit_mirror' value='".DOWLAN_142."'/><input type='hidden' name='id' value='{$mirror_id}'/>" : "<input class='button' type='submit' name='submit_mirror' value='".DOWLAN_143."'/>")."
      </td>
      </tr>

      </table>
      </form>
      </div>";

      $caption = ($edit ? DOWLAN_142 : DOWLAN_143);

      $ns -> tablerender($caption, $text);
   }

   function submit_mirror()
   {
      global $tp, $sql, $admin_log;
      define("SUBMITTED", TRUE);
      if (isset($_POST['mirror_name']) && isset($_POST['mirror_url']))
      {
         $name = $tp -> toDB($_POST['mirror_name']);
         $url = $tp -> toDB($_POST['mirror_url']);
         $location = $tp -> toDB($_POST['mirror_location']);
         $description = $tp -> toDB($_POST['mirror_description']);

         $logString = $name.'[!br!]'.$url.'[!br!]'.$location.'[!br!]'.$description;

         if (isset($_POST['id']))
         {
            admin_update($sql -> db_Update("download_mirror", "mirror_name='{$name}', mirror_url='{$url}', mirror_image='".$tp->toDB($_POST['mirror_image'])."', mirror_location='{$location}', mirror_description='{$description}' WHERE mirror_id=".intval($_POST['id'])), 'update', DOWLAN_133);
            $admin_log->log_event('DOWNL_13','ID: '.intval($_POST['id']).'[!br!]'.$logString,E_LOG_INFORMATIVE,'');
         }
         else
         {
            admin_update($sql -> db_Insert("download_mirror", "0, '{$name}', '{$url}', '".$tp->toDB($_POST['mirror_image'])."', '{$location}', '{$description}', 0"), 'insert', DOWLAN_134);
            $admin_log->log_event('DOWNL_12',$logString,E_LOG_INFORMATIVE,'');
         }
      }
   }

 // ---------------------------------------------------------------------------

    function move_file($oldname,$newname)
   {
      global $ns;
      if (file_exists($newname))
      {
           return TRUE;
      }

      if (!file_exists($oldname) || is_dir($oldname))
      {
         $ns -> tablerender(LAN_ERROR,DOWLAN_68 . " : ".$oldname);
           return FALSE;
      }

      $directory = dirname($newname);
      if (is_writable($directory))
      {
         if (!rename($oldname,$newname))
         {
            $ns -> tablerender(LAN_ERROR,DOWLAN_152." ".$oldname ." -> ".$newname);
            return FALSE;
         }
         else
         {
            return TRUE;
         }
      }
      else
      {
            $ns -> tablerender(LAN_ERROR,$directory ." ".LAN_NOTWRITABLE);
         return FALSE;
      }
   }
   /**
    *
    * @private
    */
   function _getConditionList($name, $value) {
      $text .= "
         <select name='{$name}' class='tbox'>
            <option value='>=' ".($value == '>=' ? " selected='selected' " : "")." >&gt;=</option>
            <option value='=' ".($value == '=' ? " selected='selected' " : "")." >==</option>
            <option value='<=' ".($value == '<=' ? " selected='selected' " : "")." >&lt;=</option>
         </select>
         ";
      return $text;
   }
   /**
    *
    * @private
    */
   function _getStatusList($name, $value) {
      $download_status[99]= '&nbsp;';
      $download_status[0] = DOWLAN_122;
      $download_status[1] = DOWLAN_123;
      $download_status[2] = DOWLAN_124;
      $text = "";
      foreach($download_status as $key=>$val){
         $sel = ($value == $key && $value != null) ? " selected='selected'" : "";
           $text .= "<option value='{$key}'{$sel}>{$val}</option>\n";
      }
      return $text;
   }
}
?>