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
|     $Source: /cvs_backup/e107_0.7/e107_themes/templates/footer_default.php,v $
|     $Revision: 1.9 $
|     $Date: 2005-01-26 02:56:46 $
|     $Author: mcfly_e107 $
+----------------------------------------------------------------------------+
*/
if(!is_object($sql)){
        // reinstigate db connection if another connection from third-party script closed it ...
        global $sql, $sDBdbg, $mySQLserver, $mySQLuser, $mySQLpassword, $mySQLdefaultdb, $CUSTOMFOOTER, $FOOTER;
        $sql = new db;
        $sql -> db_Connect($mySQLserver, $mySQLuser, $mySQLpassword, $mySQLdefaultdb);
}

unset($fh);
if($e107_popup!=1){
        parseheader(($ph ? $cust_footer : $FOOTER));

        $eTimingStop = explode(' ', microtime());
        global $eTimingStart;
        $rendertime = number_format((($eTimingStop[0]+$eTimingStop[1])-($eTimingStart[0]+$eTimingStart[1])), 4);
        $db_time    = number_format($db_time,4);
        $rinfo = '';
        if($pref['displayrendertime']){ $rinfo .= "Render time: ".$rendertime." second(s); ".$db_time." of that for queries. "; }
        if($pref['displaysql']){ $rinfo .= "DB queries: ".$sql -> mySQLquerycount.". "; }
        if($pref['displaycacheinfo']){ $rinfo .= $cachestring."."; }
        echo ($rinfo ? "\n<div style='text-align:center' class='smalltext'>$rinfo</div>\n" : "");
        if ($e107_debug_level) {
                global $db_debug;
                $dbg_summary = "
                \n<!-- DEBUG -->\n
                <div style='text-align:left' class='smalltext'>
                ".
                $db_debug->Show_Performance().
                "</div>";
                $dbg_details = "
                <div style='text-align:left' class='smalltext'>
                ".
                $db_debug->sDBdbg.
                "</div>";
                $ns->tablerender('DB Debug Summary',$dbg_summary);
                $ns->tablerender('DB Debug Details',$dbg_details);
        }
}

$sql -> db_Close();

/*
changes by jalist 24/01/2005:
show sql queries
usage: add ?showsql to query string, must be admin
*/
if(is_array($sql->mySQLinfo) && ADMIN) {
	$mySQLInfo = $sql->mySQLinfo;
	echo "<table class='fborder' style='width: 100%;'>
	<tr>
	<td class='fcaption' style='width: 5%;'>ID</td><td class='fcaption' style='width: 95%;'>SQL Queries</td>\n</tr>\n";
	for($c = 1; $c<=($sql -> mySQLquerycount); $c++) {
		if($mySQLInfo[$c]['data']) {
			echo "<tr>\n<td class='forumheader3' style='width: 5%;'>$c</td><td class='forumheader3' style='width: 95%;'>".$mySQLInfo[$c]['data']."</td>\n</tr>\n";
		}
	}
	echo "</table>";
}



// Provide a way to sync user and server time -- see e107.js and class2.php
// This should be done as late as possible in page processing.
$_serverTime=time();
$lastSet = isset($_COOKIE['e107_tdSetTime']) ? $_COOKIE['e107_tdSetTime'] : 0;
if (abs($_serverTime - $lastSet) > 120) {
        /* update time delay every couple of minutes.
         * Benefit: account for user time corrections and changes in internet delays
         * Drawback: each update may cause all server times to display a bit different
         */
       // echo "<script type='text/javascript'>\n";
      //  echo "SyncWithServerTime('{$_serverTime}');
      //  </script>\n";
      // commented out until error is fixed. missing the function ?
}
if (ob_get_level() != $start_ob_level ) {
        $oblev=ob_get_level();
        $obdbg = "<div style='text-align:center' class='smalltext'>";
        $obdbg .= "Software defect detected; ob_*() level $oblev at end.</div>";
        if ($oblev > $start_ob_level) {
                while (ob_get_level() > $start_ob_level) {
                        ob_end_flush();  /* clear extras */
                }
        } else {
                ob_start(); // create one buffer to be completely clean...
        }
        echo $obdbg;
}
ob_end_flush(); // flush primary output -- buffer was opened in class2.php
echo "</body></html>";
?>