<?php

require_once("../class2.php");
require_once("auth.php");
require_once("update_routines.php");

if($_POST){
        foreach($dbupdate as $func => $rmks){
                $installed = call_user_func("update_".$func);
                if((LAN_UPDATE_4 == $_POST[$func] || $_POST['updateall']) && !$installed){
                        if(function_exists("update_".$func)){
                                $message .=LAN_UPDATE_7." {$rmks}<br />";
                                call_user_func("update_".$func,"do");
                        }
                }
        }
}

if($message){
        $ns -> tablerender("&nbsp;",$message);
}

$text = "
<form method='POST' action='".e_SELF."'>
<div style='width:100%'>
<table class='fborder' style='width:100%'>
<tr>
        <td class='fcaption'>".LAN_UPDATE_1."</td>
        <td class='fcaption'>".LAN_UPDATE_2."</td>
</tr>
";

$updates=0;
foreach($dbupdate as $func => $rmks){
        if(function_exists("update_".$func)){
                $text .= "<tr><td class='forumheader3'>{$rmks}</td>";
                if(call_user_func("update_".$func)){
                        $text .= "<td class='forumheader3' style='text-align:center'>".LAN_UPDATE_3."</td>";
                } else {
                        $updates++;
                        $text .= "<td class='forumheader3' style='text-align:center'><input class='button' type='submit' name='{$func}' value='".LAN_UPDATE_4."' /></td>";
                }
                $text .= "</tr>";
        }
}
if($updates > 1){
        $text .= "
        <tr><td class='forumheader3'></td><td class='forumheader3'></td></tr>
        <tr><td class='forumheader3'>{$updates} ".LAN_UPDATE_5."</td><td class='forumheader3'><input class='button' type='submit' name='updateall' value='".LAN_UPDATE_6."' /></td></tr>";
}

$text .= "</table></div></form>";
$ns -> tablerender(LAN_UPDATE_10,$text);
require_once("footer.php");