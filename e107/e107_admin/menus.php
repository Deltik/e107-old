<?php
/*
+---------------------------------------------------------------+
|        e107 website system
|        /admin/menus2.php
|
|        �Steve Dunstan 2001-2002
|        http://e107.org
|        jalist@e107.org
|
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/
require_once("../class2.php");
if(!getperms("2")){ header("location:".e_BASE."index.php"); exit;}
require_once("auth.php");
function headerjs(){
$headerjs =  "<script type=\"text/javascript\">
<!--
image1 = new Image(); image1.src = \"".e_IMAGE."generic/off.png\";
image2 = new Image(); image2.src = \"".e_IMAGE."generic/move.png\";
image3 = new Image(); image3.src = \"".e_IMAGE."generic/up.png\";
image4 = new Image(); image4.src = \"".e_IMAGE."generic/up.png\";
// -->
</script>";
return $headerjs;
}
$sql -> db_Select("core", "*", "e107_name='menu_pref' ");
$row = $sql -> db_Fetch();
$tmp = stripslashes($row['e107_value']);
$menu_pref=unserialize($tmp);


$tmp = explode(".", e_QUERY);
$action = $tmp[0];
$id = $tmp[1];
$position = $tmp[2];
$location = $tmp[3];


if($action == "adv"){
        $sql -> db_Select("menus", "*", "menu_id='$id' ");
        $row = $sql -> db_Fetch(); extract($row);
        $text = "<div style=\"text-align:center\">
<form  method=\"post\" action=\"".e_SELF."?sv.".$menu_id."\">\n
<table style=\"width:50%\">
<tr>
<td>

        <input name=\"menu_class\" type=\"radio\" value=\"0\" ";
        if(!$menu_class){ $text .= "checked='checked'"; }
        $text .= " />".MENLAN_1."<br />

        <input name=\"menu_class\" type=\"radio\" value=\"252\" ";
        if($menu_class == 252){ $text .= "checked='checked'"; }
        $text .= " />".MENLAN_21."<br />

        <input name=\"menu_class\" type=\"radio\" value=\"253\" ";
        if($menu_class == 253){ $text .= "checked='checked'"; }
        $text .= " />".MENLAN_2."<br />

        <input name=\"menu_class\" type=\"radio\" value=\"254\" ";
        if($menu_class == 254){ $text .= "checked='checked'"; }
        $text .= " />".MENLAN_3."<br />";


        $sql -> db_Select("userclass_classes");
        while($row = $sql -> db_Fetch()){
                extract($row);
                $text .= "<input name=\"menu_class\" type=\"radio\" value=\"".$userclass_id."\"";
                if($menu_class == $userclass_id){ $text .= "checked='checked'"; }
                $text .= ">".MENLAN_4." ".$userclass_name." ".MENLAN_5."<br />";
        }

        $text .= "</td>
</tr>
<tr>
<td style=\"text-align:center\"><br />
<input class=\"button\" type=\"submit\" name=\"class_submit\" value=\"".MENLAN_6."\" />
</td>
</tr>
</table>
</form>
</div>";
        $caption = MENLAN_7." ".$menu_name;
        $ns -> tablerender($caption, $text);
}

if($action == "sv"){
        $sql -> db_Update("menus", "menu_class='".$_POST['menu_class']."' WHERE menu_id='$id' ");
        $message = "<br />".MENLAN_8."<br />";
}

if($action == "move"){
        $menu_count = $sql -> db_Count("menus", "(*)", " WHERE menu_location='$position' ");
        $sql -> db_Update("menus", "menu_location='$position', menu_order='".($menu_count+1)."' WHERE menu_id='$id' ");
        header("location: ".e_SELF);
        exit;
}

if($action == "activate"){
        $menu_count = $sql -> db_Count("menus", "(*)", " WHERE menu_location='$position' ");
        $sql -> db_Update("menus", "menu_location='$position', menu_order='".($menu_count+1)."' WHERE menu_id='$id' ");
        header("location: ".e_SELF);
        exit;
}

if($action == "deac"){
        $sql -> db_Update("menus", "menu_location='0', menu_order='0' WHERE menu_id='$id' ");
        header("location: ".e_SELF);
        exit;
}

if($action == "dec"){
        $sql -> db_Update("menus", "menu_order=menu_order-1 WHERE menu_order='".($position+1)."' AND menu_location='$location' ");
        $sql -> db_Update("menus", "menu_order=menu_order+1 WHERE menu_id='$id' AND menu_location='$location' ");
        header("location: ".e_SELF);
        exit;
}

if($action == "inc"){
        $sql -> db_Update("menus", "menu_order=menu_order+1 WHERE menu_order='".($position-1)."' AND menu_location='$location' ");
        $sql -> db_Update("menus", "menu_order=menu_order-1 WHERE menu_id='$id' AND menu_location='$location' ");
        header("location: ".e_SELF);
        exit;
}
unset($message);
$handle=opendir(e_PLUGIN);
$c=0;
while(false !== ($file = readdir($handle))){
        if($file != "." && $file != ".." && $file != "index.html" && (strstr($file, "menu") || strstr($file, "custom"))){


                if($file == "custom"){
                        $handle2=opendir(e_PLUGIN."custom/");
                        $d=0;
                        while(false !== ($file2 = readdir($handle2))){
                                if($file2 != "." && $file2 != ".." && $file2 != "/" && $file2 != "Readme.txt"){
                                        $file2 = "custom_".str_replace(".php", "", $file2);
                                        if(!$sql -> db_Select("menus", "*", "menu_name='$file2'")){
                                                $sql -> db_Insert("menus", " 0, '$file2', 0, 0, 0 ");
                                                $message .= "<b>".MENLAN_9." - ".$file2."</b><br />";
                                        }
                                        $menustr .= "&".$file2;
                                        $d++;
                                }
                        }
                        closedir($handle2);


                }else if(!$sql -> db_Select("menus", "*", "menu_name='$file'")){
                        if(file_exists(e_PLUGIN.$file."/plugin.php")){
                                @include(e_PLUGIN.$file."/plugin.php");
                                if($sql -> db_Select("plugin", "*", "plugin_name='$eplug_name' AND plugin_installflag='1' ")){
                                        $sql -> db_Insert("menus", " 0, '$file', 0, 0, 0 ");
                                        $message .= "<b>".MENLAN_10." - ".$file."</b><br />";
                                }
                        }else{
                                $sql -> db_Insert("menus", " 0, '$file', 0, 0, 0 ");
                                $message .= "<b>".MENLAN_10." - ".$file."</b><br />";
                        }
                }
                $menustr .= "&".str_replace(".php", "", $file);
                $c++;
        }
}
closedir($handle);

$areas = substr_count(($NEWSHEADER ? $NEWSHEADER : $HEADER).$FOOTER, "MENU");


$sql2 = new db;
for($a=1; $a<=$areas; $a++){
        if($sql -> db_Select("menus", "*",  "menu_location='$a' ORDER BY menu_order ASC")){
                $c=1;
                while($row = $sql -> db_Fetch()){
                        extract($row);
                        $sql2 -> db_Update("menus", "menu_order='$c' WHERE menu_id='$menu_id' ");
                        $c++;
                }
        }
}


$sql -> db_Select("menus");
while(list($menu_id, $menu_name, $menu_location, $menu_order) = $sql-> db_Fetch()){
        if(!eregi($menu_name, $menustr)){
                $sql2 -> db_Delete("menus", "menu_name='$menu_name'");
                $message .= "<b>".MENLAN_11." - ".$menu_name."</b><br />";
        }
}

$menus_used = (substr_count(($NEWSHEADER ? $NEWSHEADER : $HEADER).$FOOTER, "MENU"));
$sql -> db_Update("menus", "menu_location='0', menu_order='0' WHERE menu_location>'$menus_used' ");

if($message != ""){
        echo "<div style=\"text-align:center\"><b>".$message."</b></div>";
}

parseheader(($NEWSHEADER ? $NEWSHEADER : $HEADER), $menus_used);

echo "<div style=\"text-align:center\">
<div style=\"font-size:14px\" class=\"fborder\"><div class=\"forumheader\"><b>".MENLAN_22."</b></div></div><br />
<table style=\"width:96%\" class=\"fborder\">";

$sql -> db_Select("menus", "*", "menu_location='0' ");
while(list($menu_id, $menu_name, $menu_location, $menu_order) = $sql-> db_Fetch()){
        $menu_name = eregi_replace("_menu", "", $menu_name);

        echo "<tr>
<td class=\"fcaption\" style=\"text-align:center\">
<b>".$menu_name."</b>
</td>
</tr>
<td class=\"forumheader3\" style=\"text-align:center\">";
        echo "<select name=\"activate\" onchange=\"urljump(this.options[selectedIndex].value)\" class=\"tbox\">
        <option selected='selected'  value=\"0\">".MENLAN_12." ...</option>";
        for($a=1; $a<=$menus_used; $a++){
                echo "<option value=\"".e_SELF."?activate.$menu_id.$a\">".MENLAN_13." $a</option>";
        }
        echo "</select>";
        if($menu <> $c){

}

echo "</td></tr>
<tr><td><br /></td></tr>
        ";
}
echo "</table></div>";


parseheader($FOOTER, $menus_used);

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
function parseheader($LAYOUT){
        $tmp = explode("\n", $LAYOUT);
        for($c=0; $c < count($tmp); $c++){
                if(preg_match("/[\{|\}]/", $tmp[$c])){
                        $str = checklayout($tmp[$c]);
                }else{
                        echo $tmp[$c];
                }
        }
}
function checklayout($str){
        global $pref, $menus_used, $menu_pref, $areas;
        if(strstr($str, "LOGO")){
                echo "[Logo]";
        }else if(strstr($str, "SITENAME")){
                echo "[SiteName]";
        }else if(strstr($str, "SITETAG")){
                echo "[SiteTag]";
        }else if(strstr($str, "SITELINKS")){
                echo "[SiteLinks]";
        }else if(strstr($str, "MENU")){
                $ns = new e107table;
                $menu = preg_replace("/\{MENU=(.*?)\}/si", "\\1", $str);
                echo "<div style=\"text-align:center; font-size:14px\" class=\"fborder\"><div class=\"forumheader\"><b>".MENLAN_14."  ".$menu."</b></div></div><br />";
                unset($text);

                $sql9 = new db;
                $sql9 -> db_Select("menus", "*",  "menu_location='$menu' ORDER BY menu_order");
                $menu_count = $sql9 -> db_Rows();
                while(list($menu_id, $menu_name, $menu_location, $menu_order) = $sql9-> db_Fetch()){
                        $menu_name = eregi_replace("_menu", "", $menu_name);
                        $caption = "<div style=\"text-align:center\">".$menu_name."</div>";
                        $text = "<a href=\"".e_SELF."?deac.".$menu_id."\"><img style=\"border:0\" src=\"".e_IMAGE."generic/off.png\" alt=\"\" /> ".MENLAN_15."</a><br />";

                        $config_path = e_PLUGIN.$menu_name."_menu/config.php";
                        if(file_exists($config_path)){
                                $text .= "<a href=\"$config_path\"><img style=\"border:0\" src=\"".e_IMAGE."generic/move.png\" alt=\"\" /> ".MENLAN_16."</a><br />";
                        }
                        if($menu_order != 1){
                                $text .= "<a href=\"".e_SELF."?inc.".$menu_id.".".$menu_order.".".$menu."\"><img style=\"border:0\" src=\"".e_IMAGE."generic/up.gif\" alt=\"\" /> ".MENLAN_17."</a><br />";
                        }
                        if($menu_count != $menu_order){
                                $text .= "<a href=\"".e_SELF."?dec.".$menu_id.".".$menu_order.".".$menu."\"><img style=\"border:0\" src=\"".e_IMAGE."generic/down.gif\" alt=\"\" /> ".MENLAN_18."</a><br />";
                        }
                        for($c=1; $c<=$areas; $c++){
                                if($menu <> $c){
                                        $text .= "<a href=\"".e_SELF."?move.".$menu_id.".$c\"><img style=\"border:0\" src=\"".e_IMAGE."generic/move.png\" alt=\"\" /> ".MENLAN_19." ".$c."</a><br />";

                                }
                        }

                        $text .= "<a href=\"".e_SELF."?adv.".$menu_id."\"><img style=\"border:0\" src=\"".e_IMAGE."generic/move.png\" alt=\"\" /> ".MENLAN_20."</a>";


                        $ns -> tablerender($caption, $text);
                        echo "<br />";
                }


        }else if(strstr($str, "SETSTYLE")){
                $tmp = explode("=", $str);
                $style = preg_replace("/\{SETSTYLE=(.*?)\}/si", "\\1", $str);
        }else if(strstr($str, "SITEDISCLAIMER")){
                echo "[Sitedisclaimer]";
        }
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
require_once("footer.php");
?>