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
|     $Source: /cvs_backup/e107_0.7/e107_admin/chatbox.php,v $
|     $Revision: 1.1 $
|     $Date: 2004-09-21 19:10:20 $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/
require_once("../class2.php");
if(!getperms("C")){ header("location:".e_BASE."index.php"); exit ;}

require_once("auth.php");

if($action == "u"){
        $message = CHBLAN_1;
}

if(IsSet($_POST['moderate'])){

        extract($_POST);
        if(is_array($cb_blocked)){
                while (list ($key, $id) = each ($cb_blocked)){
                        $sql -> db_Update("chatbox", "cb_blocked='1' WHERE cb_id='$id' ");
                }
        }
        if(is_array($cb_unblocked)){
                while (list ($key, $id) = each ($cb_unblocked)){
                        $sql -> db_Update("chatbox", "cb_blocked='0' WHERE cb_id='$id' ");
                }
        }
        if(is_array($cb_delete)){
                while (list ($key, $id) = each ($cb_delete)) {
                        $sql -> db_Delete("chatbox", "cb_id='$id' ");
                }
        }
        clear_cache("chatbox");
        $message = CHBLAN_2;
}

if(IsSet($_POST['updatesettings'])){

        $pref['chatbox_posts'] = $_POST['chatbox_posts'];
        $aj = new textparse;
        $pref['cb_linkc'] = $aj -> formtpa($_POST['cb_linkc'], "admin");
        $pref['cb_wordwrap'] = $_POST['cb_wordwrap'];
        $pref['cb_linkreplace'] = $_POST['cb_linkreplace'];
        $pref['cb_layer'] = $_POST['cb_layer'];
        $pref['cb_layer_height'] = ($_POST['cb_layer_height'] ? $_POST['cb_layer_height'] : 200);
        $pref['cb_emote'] = $_POST['cb_emote'];
        save_prefs();
        header("location:".e_ADMIN."chatbox.php?u");
        exit;
}

if(IsSet($_POST['prune'])){
        $chatbox_prune = $_POST['chatbox_prune'];
        $prunetime = time() - $chatbox_prune;

        $sql -> db_Delete("chatbox", "cb_datestamp < '$prunetime' ");
        $message = CHBLAN_28;
}



if(IsSet($message)){
        $ns -> tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
}


if(!$sql -> db_Select("chatbox", "*", "ORDER BY cb_datestamp DESC LIMIT 0, 50", $mode="no_where")){
        $text = "<div style='text-align:center'>".CHBLAN_3."</div>";
}else{
        $con = new convert;
        $aj = new textparse();

        $text = "<div style='border : solid 1px #000; padding : 4px; width : auto; height : 200px; overflow : auto; '>
<form method='post' action='".e_SELF."'>
<table style='width:100%' class='fborder'>";

        $sql2 = new db;
        while($row = $sql -> db_Fetch()){
                extract($row);

                $datestamp = $con->convert_date($cb_datestamp, "short");

                $cb_ida = substr($cb_nick, 0, strpos($cb_nick, "."));

                if($cb_ida){
                        $sql2 -> db_Select("user", "*", "user_id='$cb_ida' ");
                        $row = $sql2 -> db_Fetch(); extract($row);
                        $cb_nick = "<a href='".e_BASE."user.php?id.".$user_id."'>".$user_name."</a>";
                        $cb_str = "".CHBLAN_4." ".$user_id;
                }else{
                        $cb_str = CHBLAN_5;
                        $cb_nick = eregi_replace("[0-9]+\.", "", $cb_nick);
                }

                $cb_message = $aj -> tpa($cb_message, "on");

                $text .= "<tr>
<td class='forumheader3' style='width:5%; text-align: center'>".($cb_blocked ? "<img src='".e_IMAGE."generic/blocked.png' />" : "&nbsp;")."</td>
<td class='forumheader3' style='width:15%'>".$datestamp."</td>
<td class='forumheader3' style='width:20%'><b>".$cb_nick."</b><br />".$cb_str."<br />IP: ".$cb_ip."</td>
<td class='forumheader3' style='width:40%'>".$cb_message."</td>
<td class='forumheader3' style='width:20%;text-align:center' >".


($cb_blocked ?  "<input type='checkbox' name='cb_unblocked[]' value='$cb_id' /> ".CHBLAN_6 : "<input type='checkbox' name='cb_blocked[]' value='$cb_id' />".CHBLAN_7)."
 &nbsp;<input type='checkbox' name='cb_delete[]' value='$cb_id' />".CHBLAN_8."
</td>
</tr>";

        }

        $text .= "<tr><td colspan='5' class='forumheader' style='text-align:center'><input class='button' type='submit' name='moderate' value='".CHBLAN_9."' />
        </td>
        </tr>
        </table></form></div>";

        $ns -> tablerender(CHBLAN_10, $text);

        echo "<br />";

}


$chatbox_posts = $pref['chatbox_posts'];
$cb_linkreplace = $pref['cb_linkreplace'];
$cb_linkc = $pref['cb_linkc'];
$cb_wordwrap = $pref['cb_wordwrap'];

$text = "<div style='text-align:center'>
<form method='post' action='".e_SELF."' id='cbform'>
<table style='width:85%' class='fborder'>
<tr>
<td class='forumheader3' style='width:40%'>".CHBLAN_11."?:  <div class='smalltext'>".CHBLAN_12."</div></td>
<td class='forumheader3' style='width:60%'>
<select name='chatbox_posts' class='tbox'>";
if($chatbox_posts == 5){
        $text .= "<option selected='selected'>5</option>\n";
}else{
        $text .= "<option>5</option>\n";
}
if($chatbox_posts == 10){
        $text .= "<option selected='selected'>10</option>\n";
}else{
        $text .= "<option>10</option>\n";
}
if($chatbox_posts == 15){
        $text .= "<option selected='selected'>15</option>\n";
}else{
        $text .= "<option>15</option>\n";
}
if($chatbox_posts == 20){
        $text .= "<option selected='selected'>20</option>\n";
}else{
        $text .= "<option>20</option>\n";
}
if($chatbox_posts == 25){
        $text .= "<option selected='selected'>25</option>\n";
}else{
        $text .= "<option>25</option>\n";
}

$text .= "</select>
</td>
</tr>

<tr><td class='forumheader3' style='width:40%'>".CHBLAN_29."?: </td>
<td class='forumheader3' style='width:60%'>".
($pref['cb_layer'] ? "<input type='checkbox' name='cb_layer' value='1' checked='checked' />" : "<input type='checkbox' name='cb_layer' value='1' />")."&nbsp;&nbsp;".
CHBLAN_30.": <input class='tbox' type='text' name='cb_layer_height' size='8' value='".$pref['cb_layer_height']."' maxlength='3' />
</td>
</tr>

<tr>
<td class='forumheader3' style='width:40%'>".CHBLAN_13."?:  <div class='smalltext'>".CHBLAN_14."</div></td>
<td class='forumheader3' style='width:60%'>".
($cb_linkreplace ? "<input type='checkbox' name='cb_linkreplace' value='1' checked='checked' />" : "<input type='checkbox' name='cb_linkreplace' value='1' />")."
</td>
</tr>


<tr>
<td class='forumheader3' style='width:40%'>".CHBLAN_15.": <div class='smalltext'>".CHBLAN_16."</div></td>
<td class='forumheader3' style='width:60%'>
<input class='tbox' type='text' name='cb_linkc' size='50' value='$cb_linkc' maxlength='200' />
</td>
</tr>

<tr><td class='forumheader3' style='width:40%'>".CHBLAN_31."?: </td>
<td class='forumheader3' style='width:60%'>".
($pref['cb_emote'] ? "<input type='checkbox' name='cb_emote' value='1' checked='checked' />" : "<input type='checkbox' name='cb_emote' value='1' />")."
</td>
</tr>


<tr>
<td class='forumheader3' style='width:40%'>".CHBLAN_17.":  <div class='smalltext'>".CHBLAN_18."</div></td>
<td class='forumheader3' style='width:60%'>
<input class='tbox' type='text' name='cb_wordwrap' size='5' value='$cb_wordwrap' maxlength='3' />
</td>
</tr>

<tr>
<td class='forumheader3' style='width:40%'>".CHBLAN_21.": <div class='smalltext'>".CHBLAN_22."</div></td>
<td class='forumheader3' style='width:60%'>
".CHBLAN_23." <select name='chatbox_prune' class='tbox'>
<option></option>
<option value='86400'>".CHBLAN_24."</option>
<option value='604800'>".CHBLAN_25."</option>
<option value='2592000'>".CHBLAN_26."</option>
<option value='1'>".CHBLAN_27."</option>
</select>
<input class='button' type='submit' name='prune' value='".CHBLAN_21."' />
</td>
</tr>

<tr>
<td  class='forumheader' colspan='3' style='text-align:center'>
<input class='button' type='submit' name='updatesettings' value='".CHBLAN_19."' />
</td>
</tr>
</table>
</form>
</div>";

$ns -> tablerender("<div style='text-align:center'>".CHBLAN_20."</div>", $text);

require_once("footer.php");
?>