<?php
/*
+---------------------------------------------------------------+
|        e107 website system
|        /admin/links.php
|
|        �Steve Dunstan 2001-2002
|        http://e107.org
|        jalist@e107.org
|
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
|
|        16/02/2003
+---------------------------------------------------------------+
*/
require_once("../class2.php");
if(!getperms("I")){ header("location:".e_BASE."index.php"); exit; }
require_once("auth.php");
require_once(e_HANDLER."ren_help.php");
    if($pref['htmlarea']){
        require_once(e_HANDLER."htmlarea/htmlarea.inc.php");
        htmlarea("menu_text");
    }
// New texteparse used
require_once(e_HANDLER."textparse/basic.php");
$etp = new e107_basicparse;
// End of new textparse

$aj = new textparse;
$cm = array();
$cm2 = array();
unset($message);

// Create link
if(IsSet($_POST['mainmenu_link']) && $_POST['type_content']==2){
  //global $PLUGINS_DIRECTORY;
  if(!$sql -> db_Select("links","link_id","link_name='".$_POST['menu_name']."' AND link_category='1' ")){
    $sql -> db_Insert("links", "0, '".$_POST['menu_name']."', '".$PLUGINS_DIRECTORY."custompages/".$_POST['menu_name'].".php', '', '', '1', '0', '0', '0', '0'");
    $message = CUSLAN_28."<br /><br />";
  }else{
    $message = CUSLAN_27."<br /><br />";
  }
}

if((IsSet($_POST['add_menu']) || IsSet($_POST['update_menu'])) && $_POST['type_content']==1){

        if(!$_POST['menu_name'] || !$_POST['menu_caption'] || !$_POST['menu_text']){
                $message .= CUSLAN_1;
        }else{


                
                $_POST['menu_caption'] = $etp -> e107in_basic($_POST['menu_caption']);
                $_POST['menu_text'] = $etp -> e107in_basic($_POST['menu_text']);
                /*
                $search = array("\"", "'", "\\");
                $replace = array("&quot;", "&#39;", "&#92;");
                $_POST['menu_caption']  = str_replace($search, $replace, stripslashes($_POST['menu_caption']));
                $_POST['menu_text']  = str_replace($search, $replace, stripslashes($_POST['menu_text']));
                */
                $data = chr(60)."?php\n".
chr(47)."*\n+---------------------------------------------------------------+\n|        e107 website system\n|        ".e_PLUGIN.$_POST['menu_name']."/custom_".$_POST['menu_name'].".php\n|\n|        �Steve Dunstan 2001-2002\n|        http://e107.org\n|        jalist@e107.org\n|\n|        Released under the terms and conditions of the\n|        GNU General Public License (http://gnu.org).\n+---------------------------------------------------------------+\n\nThis file has been generated by ".e_ADMIN."custommenu.php.\n\n*".
chr(47)."\n\n".
chr(36)."caption = ".chr(34).$_POST['menu_caption'].chr(34).";\n".
chr(36)."text = ".chr(34).$_POST['menu_text'].chr(34).";\n".


chr(36)."aj = new textparse;\n".
chr(36)."caption = ".chr(36)."aj -> tpa(".chr(36)."caption, \"on\");\n".
chr(36)."text = ".chr(36)."aj -> tpa(".chr(36)."text, \"on\",\"admin\");\n".
chr(36)."ns -> tablerender(".chr(36)."caption, ".chr(36)."text);\n?".chr(62);

$_POST['menu_caption'] = $etp -> e107out_basic($_POST['menu_caption']);
$_POST['menu_text'] = $etp -> e107out_basic($_POST['menu_text']);

                $fp = @fopen(e_PLUGIN."custom/".$_POST['menu_name'].".php","w");
                if(!@fwrite($fp, $data)){
                        $message .= CUSLAN_2.e_PLUGIN.CUSLAN_3;
                }else{
                        fclose($fp);
                        $message .= (IsSet($_POST['update_menu']) ? CUSLAN_4 : CUSLAN_5);
                        unset($_POST['menu_name'], $_POST['menu_caption'], $_POST['menu_text'], $_POST['edit'], $_POST['type_content']);
                }
        }
}

if((IsSet($_POST['add_menu']) || IsSet($_POST['update_menu'])) && $_POST['type_content']==2){

        if(!$_POST['menu_name'] || !$_POST['menu_caption'] || !$_POST['menu_text']){
                $message .= CUSLAN_1;
        }else{


                $_POST['menu_caption'] = $etp -> e107in_basic($_POST['menu_caption']);
                $_POST['menu_text'] = $etp -> e107in_basic($_POST['menu_text']);
                /*
                $search = array("\"", "'", "\\");
                $replace = array("&quot;", "&#39;", "&#92;");
                $_POST['menu_caption']  = str_replace($search, $replace, stripslashes($_POST['menu_caption']));
                $_POST['menu_text']  = str_replace($search, $replace, stripslashes($_POST['menu_text']));
                */
                $data = chr(60)."?php\n".
chr(47)."*\n+---------------------------------------------------------------+\n|        e107 website system\n|        ".e_PLUGIN."/custompages/".$_POST['menu_name'].".php\n|\n|        �e107 Dev Team (Lolo Irie) 2004\n|        http://e107.org\n|        \n|\n|        Released under the terms and conditions of the\n|        GNU General Public License (http://gnu.org).\n+---------------------------------------------------------------+\n\nThis file has been generated by ".e_ADMIN."custommenu.php.\n\n*".
chr(47)."\n
require_once(\"../../class2.php\");\n
require_once(HEADERF);\n\n".
chr(36)."caption = ".chr(34).$_POST['menu_caption'].chr(34).";\n".
chr(36)."text = ".chr(34).$_POST['menu_text'].chr(34).";\n".


chr(36)."aj = new textparse;\n".
chr(36)."caption = ".chr(36)."aj -> tpa(".chr(36)."caption, \"on\");\n".
chr(36)."text = ".chr(36)."aj -> tpa(".chr(36)."text, \"on\",\"admin\");\n".
chr(36)."ns -> tablerender(".chr(36)."caption, ".chr(36)."text);\n\n
require_once(FOOTERF);\n
?".chr(62);

$_POST['menu_caption'] = $etp -> e107out_basic($_POST['menu_caption']);
$_POST['menu_text'] = $etp -> e107out_basic($_POST['menu_text']);

                $fp = @fopen(e_PLUGIN."custompages/".$_POST['menu_name'].".php","w");
                if(!@fwrite($fp, $data)){
                        $message .= CUSLAN_20.e_PLUGIN.CUSLAN_21;
                }else{
                        fclose($fp);
                        $message .= (IsSet($_POST['update_menu']) ? CUSLAN_4 : CUSLAN_24);
                        unset($_POST['menu_name'], $_POST['menu_caption'], $_POST['menu_text'], $_POST['edit'], $_POST['type_content']);
                }
        }
}

if(IsSet($_POST['preview'])){
        $menu_caption = $aj -> tpa($_POST['menu_caption']);
        $menu_text = $aj -> tpa($_POST['menu_text'],"on","admin");
        echo "<div style='text-align:center'>
        <table style='width:200px'>
        <tr>
        <td>";
        $ns -> tablerender($menu_caption, $menu_text);
        echo "</td></tr></table></div><br /><br />";
        $_POST['menu_caption'] = $aj -> tpa($_POST['menu_caption']);
        $_POST['menu_text'] = $aj -> tpa($_POST['menu_text']);
        $_POST['menu_text'] = str_replace("<br />", "", $_POST['menu_text']);
}else if(IsSet($_POST['edit'])){
        $menu = e_PLUGIN."custom/".$_POST['existing'];
        if($fp = @fopen($menu,"r")){
                $buffer = str_replace("\n", "", fread($fp, filesize($menu)));
                fclose($fp);
                preg_match_all("/\"(.*?)\"/", $buffer, $result);
                $_POST['menu_caption'] = $etp -> e107out_basic($result[1][0]);
                $_POST['menu_text'] = $etp -> e107out_basic($result[1][1]);
                $_POST['menu_text'] = str_replace("<br />", "", $_POST['menu_text']);
                $_POST['menu_name'] = eregi_replace(e_PLUGIN."custom/|.php", "", $menu);
				$_POST['type_content'] = 1;
        }else{
                $message .= CUSLAN_6." '".$_POST['existing']."' ".CUSLAN_7;
        }
}else if(IsSet($_POST['edit2'])){
        $menu = e_PLUGIN."custompages/".$_POST['existingpages'];
        if($fp = @fopen($menu,"r")){
                $buffer = str_replace("\n", "", fread($fp, filesize($menu)));
                fclose($fp);
                preg_match_all("/\"(.*?)\"/", $buffer, $result);
                $_POST['menu_caption'] = $etp -> e107out_basic($result[1][1]);
                $_POST['menu_text'] = $etp -> e107out_basic($result[1][2]);
                $_POST['menu_text'] = str_replace("<br />", "", $_POST['menu_text']);
                $_POST['menu_name'] = eregi_replace(e_PLUGIN."custompages/|.php", "", $menu);
				$_POST['type_content'] = 2;
        }else{
                $message .= CUSLAN_6." '".$_POST['existing']."' ".CUSLAN_7;
        }
}

if(IsSet($message)){
        $ns -> tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
}

$text = "<div style='text-align:center'>
<form method='post' action='".e_SELF."' id='dataform'>
<table style='width:85%' class='fborder'>
<tr>

<td style='text-align:center' colspan='2' class='forumheader'><span class='defaulttext'>".CUSLAN_8.":</span> ";

$handle=opendir(e_PLUGIN."custom/");
while(false !== ($file = readdir($handle))){
        if($file != "." && $file != ".."){
                $cm[] = $file;
                $found = TRUE;
        }
}
closedir($handle);

$c = 0;
if($found){
        $text .= "<select name='existing' class='tbox'>";
        while($cm[$c]){
                $text .= "<option>".$cm[$c]."</option>";
                $c++;
        }
        $text .= "</select>\n<input class='button' type='submit' name='edit' value='".CUSLAN_9."' /> ";
}else{
        $text .= "<span class='defaulttext'>".CUSLAN_10."</span>";
}

$text .= "
</td>
</tr>";

$text .= "<tr>

<td style='text-align:center' colspan='2' class='forumheader'><span class='defaulttext'>".CUSLAN_19.":</span> ";

$handle=opendir(e_PLUGIN."custompages/");
while(false !== ($file = readdir($handle))){
        if($file != "." && $file != ".."){
                $cm2[] = $file;
                $found2 = TRUE;
        }
}
closedir($handle);

$c = 0;
if($found2){
        $text .= "<select name='existingpages' class='tbox'>";
        while($cm2[$c]){
                $text .= "<option>".$cm2[$c]."</option>";
                $c++;
        }
        $text .= "</select>\n<input class='button' type='submit' name='edit2' value='".CUSLAN_9."' /> ";
}else{
        $text .= "<span class='defaulttext'>".CUSLAN_10."</span>";
}

$_POST['menu_text'] = str_replace("&nbsp;","&#38;nbsp;",$_POST['menu_text']);

$text .= "
</td>
</tr>";

$text .= "<tr>
<td style='width:30%' class='forumheader3'>".CUSLAN_11.": </td>
<td style='width:70%' class='forumheader3'>
<input class='tbox' type='text' size='25' maxlength='25' name='menu_name' value='".$_POST['menu_name']."' />
</td>
</tr>

<tr>
<td style='width:30%' class='forumheader3'>".CUSLAN_12.": </td>
<td style='width:70%' class='forumheader3'>
<input class='tbox' type='text' size='60' maxlength='250' name='menu_caption' value='".$_POST['menu_caption']."' />
</td>
</tr>

<tr>
<td style='width:30%' class='forumheader3'>".CUSLAN_13.": </td>
<td style='width:70%' class='forumheader3'>
<textarea class='tbox' id='menu_text' name='menu_text' cols='59' rows='15' onselect='storeCaret(this);' onclick='storeCaret(this);' onkeyup='storeCaret(this);'>".$_POST['menu_text']."</textarea>
</td>
</tr>";

if(!$pref['htmlarea']){
     $text .="
     <tr>
     <td class='forumheader3'>&nbsp;</td>
     <td class='forumheader3'>
     <input class='helpbox' type='text' name='helpb' size='100' />
     <br />". ren_help()."</td>
     </tr>";
}

$text .= "<tr>
<td style='width:30%' class='forumheader3'>".CUSLAN_22.": </td>
<td style='width:70%' class='forumheader3'>
<input type='radio' name='type_content' value='1' ".( $_POST['type_content']==1 ? "checked" : "" )." />
</td>
</tr>";

$text .= "<tr>
<td style='width:30%' class='forumheader3'>".CUSLAN_23.": </td>
<td style='width:70%' class='forumheader3'>
<input type='radio' name='type_content' value='2' ".( $_POST['type_content']==2 ? "checked" : "" )." />
<br />".CUSLAN_25." <input type='checkbox' name='mainmenu_link' value='1' />
<br />
<b class=\"smalltext\" >".CUSLAN_26."</b></td>
</tr>";



$text .="
<tr style='vertical-align:top'>
<td colspan='2'  style='text-align:center' class='forumheader'>";
if(IsSet($_POST['preview'])){
        $text .= "<input class='button' type='submit' name='preview' value='".CUSLAN_14."' /> ";
        if(IsSet($_POST['edit'])){
                $text .= "<input type='hidden' name='edit' value='".$_POST['edit']."'>";
        }
}else{
        $text .= "<input class='button' type='submit' name='preview' value='".CUSLAN_15."' /> ";
        if(IsSet($_POST['edit'])){
                $text .= "<input type='hidden' name='edit' value='".$_POST['edit']."'>";
        }
}

if(IsSet($_POST['edit'])){
        $text .= "<input class='button' type='submit' name='update_menu' value='".CUSLAN_16."' />";
}else{
        $text .= "<input class='button' type='submit' name='add_menu' value='".CUSLAN_17."' />";
}
$text .= "</td>
</tr>
</table>
</form>
</div>";

$ns -> tablerender("<div style='text-align:center'>".CUSLAN_18."</div>", $text);

require_once("footer.php");
?>