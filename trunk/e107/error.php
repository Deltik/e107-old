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
|     $Source: /cvs_backup/e107/error.php,v $
|     $Revision: 1.6 $
|     $Date: 2005-10-21 00:29:31 $
|     $Author: mcfly_e107 $
+----------------------------------------------------------------------------+
*/



require_once("class2.php");
require_once(HEADERF);

switch(e_QUERY){
        case 401:
                $text = "<div class='installe'>".LAN_1."</div><br /><div class='installh'>".LAN_2."</div><br /><div class='smalltext'>".LAN_3."</div>
                <br /><div class='installh'>".LAN_2."<a href='index.php'>".LAN_20."</a></div>";
        break;
        case 403:
                $text = "<div class='installe'>".LAN_4."</div><br /><div class='installh'>".LAN_5."</div><br /><div class='smalltext'>".LAN_6."</div>
                <br /><div class='installh'>".LAN_2."<a href='index.php'>".LAN_20."</a></div>";
        break;
        case 404:
                $text = "<div class='installe'>".LAN_7."</div><br /><div class='installh'>".LAN_8."</div><br /><div class='smalltext'>".LAN_9."</div>
                <br /><div class='installh'>".LAN_2."<a href='index.php'>".LAN_20."</a></div>";
        break;
        case 500:
                $text = "<div class='installe'>".LAN_10."</div><br /><div class='installh'>".LAN_11."</div><br /><div class='smalltext'>".LAN_12."</div>
                <br /><div class='installh'>".LAN_2."<a href='index.php'>".LAN_20."</a></div>";
        break;
        default:
                $text = "<div class='installe'>".LAN_13." (".$_SERVER['QUERY_STRING'].")</div><br /><div class='installh'>".LAN_14."</div><br /><div class='smalltext'>".LAN_15."</div>
                <br /><div class='installh'>".LAN_2."<a href='index.php'>".LAN_20."</a></div>";
}
$ns -> tablerender(PAGE_NAME." ".e_QUERY, $text);
require_once(FOOTERF);
?>