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
|     $Source: /cvs_backup/e107_0.8/e107_files/thumb.php,v $
|     $Revision: 1.1.1.1 $
|     $Date: 2006-12-02 04:33:37 $
|     $Author: mcfly_e107 $
+----------------------------------------------------------------------------+
*/
/*
 Usage: simply replace your <img src='filename.jpg'
 with
 <img src='".e_IMAGE."thumb.php?filename.jpg+size"' />
 or
 <img src='".e_IMAGE."thumb.php?<full path to file>/filename.jpg+size"' />
 eg <img src='".e_IMAGE."thumb.php?home/images/myfilename.jpg+100)"' />

*/

require_once("../class2.php");
require_once(e_HANDLER."resize_handler.php");

    if (e_QUERY){
        $tmp = explode("+",rawurldecode(e_QUERY));
        if(strpos($tmp[0], "/") === 0 || strpos($tmp[0], ":") >= 1){
            $source = $tmp[0];
        }else{
            $source = "../".str_replace("../","",$tmp[0]);
        }

        $newsize = $tmp[1];
        if(!resize_image($source, "stdout", $newsize)){
            echo "Couldn't find: ".$source;
        }
    }
?>