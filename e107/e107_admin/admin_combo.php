<?php
/*
+---------------------------------------------------------------+
|        e107 website system
|        /admin/admin.php
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
require_once("auth.php");


// auto db update ...
if("0" == ADMINPERMS){
        require_once(e_ADMIN."update_routines.php");
        update_check();
}
//        end auto db update

if(e_QUERY == "purge"){
        $sql -> db_Delete("tmp", "tmp_ip='adminlog' ");
}

$tdc=0;
$tdt=0;
function wad($link, $title, $description, $perms, $icon = FALSE, $mode = FALSE){
        global $tdc;
        $permicon = ($mode == TRUE ? e_IMAGE."generic/rname.png" : e_IMAGE."generic/location.png");
        if(getperms($perms)){
                if(!$tdc){$tmp1 = "<tr>";}
                if($tdc == 4){$tmp2 = "</tr>";$tdc=-1;}
                $tdc++;
                $tmp = $tmp1."<td class='td' style='text-align:left; vertical-align:top; width:20%; whitespace:nowrap' onmouseover='tdover(this)' onmouseout='tdnorm(this)'
                onclick=\"document.location.href='$link'\"><img src='$permicon' alt='$description' style='border:0; vertical-align:middle'/> $title</td>\n\n".$tmp2;
        }
        return $tmp;
}

function wad2($link, $title, $description, $perms, $icon = FALSE){
        global $tdt;
        $permicon = ($icon ? e_PLUGIN.$icon : e_IMAGE."generic/e107.gif");
        if(getperms($perms)){
             //  if($tdt == 0){$tmp1 = "<tr>";}
                if($tdt == 4){$tmp2 = "</tr><tr>";$tdt=-1;}
                $tdt++;
                $tmp = $tmp1."<td style='text-align:center; vertical-align:top; width:20%'><a href='".$link."'><img src='$permicon' alt='$description' style='border:0'/></a><br /><a href='".$link."'><b>".$title."</b></a><br />".$description."<br /><br /></td>\n\n".$tmp2;
        }
        return $tmp;
}


$text = "<div style='text-align:center'>
<table style='width:95%'>";

require_once("ad_links.php");

$newarray = asortbyindex ($array_functions, 1);

$text = "<div style='text-align:center'>
<table style='width:95%'>";

while(list($key, $funcinfo) = each($newarray)){
        $text .= wad($funcinfo[0], $funcinfo[1], $funcinfo[2], $funcinfo[3]);
}

if(!$tdc){ $text .= "</tr>"; }
$text3="";


if(getperms("Z")){ // Plugin Manager
        $text3 .= wad2(e_ADMIN."plugin.php", ADLAN_98, ADLAN_99, "P0", e_PLUGIN.e_IMAGE."generic/plugin.png");
}

        if($sql -> db_Select("plugin", "*", "plugin_installflag=1")){
                while($row = $sql -> db_Fetch()){
                        extract($row);
                        include(e_PLUGIN.$plugin_path."/plugin.php");
                        if($eplug_conffile){
                                $text3 .= wad2(e_PLUGIN.$plugin_path."/".$eplug_conffile, $eplug_name, $eplug_caption, "P".$plugin_id, $eplug_icon);
                        }
                }
        }

$text .= "</tr></table></div>";
$ns -> tablerender("<div style='text-align:center'>".ADLAN_47." ".ADMINNAME."</div>", $text);

if($text3){  // Only render if some kind of P access exists.
 $ns -> tablerender("<div style='text-align:center'>Plugins</div>", "<table><tr>".$text3."</tr></table>");
}


// info -------------------------------------------------------

$members = $sql -> db_Count("user");
$unverified = $sql -> db_Count("user", "(*)", "WHERE user_ban=2");
$banned = $sql -> db_Count("user", "(*)", "WHERE user_ban=1");
$chatbox_posts = $sql -> db_Count("chatbox");
$forum_posts = $sql -> db_Count("forum_t");
$comments = $sql -> db_Count("comments");
$permicon = "<img src='".e_IMAGE."generic/location.png' alt='' style='vertical-align:middle' /> ";
$permicon2 = "<img src='".e_IMAGE."generic/rname.png' alt='' style='vertical-align:middle' /> ";
$active_uploads = $sql -> db_Select("upload", "*", "upload_active=0");
$submitted_links = $sql -> db_Select("tmp", "*", "tmp_ip='submitted_link' ");
$submitted_news = $sql -> db_Select("submitnews", "*", "submitnews_auth ='0' ");
$submitted_articles = $sql -> db_Select("content", "*", "content_type ='15' ");
$submitted_reviews = $sql -> db_Select("content", "*", "content_type ='16' ");
$reported_posts = $sql -> db_Select("tmp", "*", "tmp_ip='reported_post' ");

$text = "<div style='text-align:center'>
<table style='width:95%'>
<tr>
<td style='width:50%'>";


$text .= $permicon.ADLAN_110.": ".$members."<br />";
$text .= $permicon.ADLAN_111.": ".$unverified."<br />";
$text .= $permicon.ADLAN_112.": ".$banned."<br />";

$text .= ($submitted_news ? $permicon2."<a href='".e_ADMIN."newspost.php?sn'>".ADLAN_107.": $submitted_news</a>" : $permicon.ADLAN_107.": 0")."<br />";
$text .= ($submitted_articles ? $permicon2."<a href='".e_ADMIN."article.php?sa'>".ADLAN_123.": $submitted_articles</a>" : $permicon.ADLAN_123.": ".$submitted_articles)."<br />";
$text .= ($submitted_reviews ? $permicon2."<a href='".e_ADMIN."review.php?sa'>".ADLAN_124.": $submitted_reviews</a>" : $permicon.ADLAN_124.": ".$submitted_reviews)."<br />";
$text .= ($submitted_links ? $permicon2."<a href='".e_ADMIN."links.php?sn'>".ADLAN_119.": $submitted_links</a>" : $permicon.ADLAN_119.": ".$submitted_links)."<br /><br />";

$text .= ($reported_posts ? $permicon2."<a href='".e_ADMIN."forum.php?sr'>".ADLAN_125.": $reported_posts</a>" : $permicon.ADLAN_125.": ".$reported_posts)."<br /><br />";

$text .= ($active_uploads ? $permicon2."<a href='".e_ADMIN."upload.php'>".ADLAN_108.": $active_uploads</a>" : $permicon.ADLAN_108.": ".$active_uploads)."<br />";

$text .= $permicon.ADLAN_113.": ".$forum_posts."<br />";
$text .= $permicon.ADLAN_114.": ".$comments."<br />";
$text .= $permicon.ADLAN_115.": ".$chatbox_posts;

$text .= "</td>
<td style='width:50%; vertical-align:top'>";

$text .= $permicon." <a style='cursor: pointer; cursor: hand' onclick=\"expandit(this)\">".ADLAN_116."</a>\n";
if(e_QUERY != "logall"){
        $text .= "<div style='display: none;'>";
}
if(e_QUERY == "logall"){
        $sql -> db_Select("tmp", "*", "tmp_ip='adminlog' ORDER BY tmp_time DESC");
}else{
        $sql -> db_Select("tmp", "*", "tmp_ip='adminlog' ORDER BY tmp_time DESC LIMIT 0,10");
}
$text .= "<ul>";
$gen = new convert;
while($row = $sql -> db_Fetch()){
        extract($row);
        $datestamp = $gen->convert_date($tmp_time, "short");
        $text .= "<li>".$datestamp.$tmp_info."</li>";
}
$text .= "</ul>";

$text .= "[ <a href='".e_SELF."?logall'>".ADLAN_117."</a> ][ <a href='".e_SELF."?purge'>".ADLAN_118."</a> ]\n</div>
</td></tr></table></div>

";

$ns -> tablerender(ADLAN_109, $text);
require_once("footer.php");

// Multi indice array sort by sweetland@whoadammit.com
function asortbyindex ($sortarray, $index) {
        $lastindex = count ($sortarray) - 1;
        for ($subindex = 0; $subindex < $lastindex; $subindex++) {
                $lastiteration = $lastindex - $subindex;
                for ($iteration = 0; $iteration < $lastiteration;    $iteration++) {
                        $nextchar = 0;
                        if (comesafter ($sortarray[$iteration][$index], $sortarray[$iteration + 1][$index])) {
                                $temp = $sortarray[$iteration];
                                $sortarray[$iteration] = $sortarray[$iteration + 1];
                                $sortarray[$iteration + 1] = $temp;
                        }
                }
        }
        return ($sortarray);
}

function comesafter ($s1, $s2) {
        $order = 1;
        if (strlen ($s1) > strlen ($s2)) {
                $temp = $s1;
                $s1 = $s2;
                $s2 = $temp;
                $order = 0;
        }
        for ($index = 0; $index < strlen ($s1); $index++) {
                if ($s1[$index] > $s2[$index]) return ($order);
                if ($s1[$index] < $s2[$index]) return (1 - $order);
        }
        return ($order);
}

function headerjs(){
$script = "<script type=\"text/javascript\">
function tdover(object) {
  if (object.className == 'td') object.className = 'forumheader5';
}

function tdnorm(object) {
  if (object.className == 'forumheader5') object.className = 'td';
}

</script>\n";

 return $script;

}


?>
