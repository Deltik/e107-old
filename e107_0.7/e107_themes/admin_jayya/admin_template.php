<?php
// [register use of admin_alt_nav]

$admin_alt_nav = TRUE;

// [prerenders]

ob_start();
$style = "leftmenu";
$tp -> parseTemplate('{ADMIN_HELP}');
$tp -> parseTemplate('{ADMIN_PWORD}');
$tp -> parseTemplate('{ADMIN_MSG}');
$tp -> parseTemplate('{ADMIN_PLUGINS}');
$style = "default";
$preleft = ob_get_contents();
ob_end_clean();

ob_start();
$style = "rightmenu";
echo $tp -> parseTemplate('{ADMIN_MENU=pre}');
$style = "default";
$preright = ob_get_contents();
ob_end_clean();


// [admin button style]

if (!function_exists('show_admin_menu')) {
	function show_admin_menu($title,$page,$e107_vars){
		global $ns;
		$text = "<div style='text-align:center; width:100%'><table class='fborder' style='width: 100%'>";
		foreach (array_keys($e107_vars) as $act) {
			$t=str_replace(" ","&nbsp;",$e107_vars[$act]['text']);
			if (!$e107_vars[$act]['perm'] || getperms($e107_vars[$act]['perm'])) {
				$arrow_icon = $page == $act ? E_16_NAV_ARROW_OVER : E_16_NAV_ARROW;
				$menu_link = $page == $act ? "" : "onclick=\"document.location='".$e107_vars[$act]['link']."'; disabled=true;\"";
				$text .= "<tr><td style='border-bottom: 1px solid #000'><div class='emenuBar' style='width:100%;'>
				<div class='menuButton' onmouseover='adbutover(this)' onmouseout='adbutnorm(this)' ".$menu_link." 
				style='background-image: url(".$arrow_icon."); background-repeat: no-repeat; background-position: 3px 1px; width: 100%; display: block;'>
				".$t."</div>
				</div>
				</td></tr>";
			}
		}
		$text .= "</table></div>";
		if ($title=="") {
			return $text;
		}
		$ns -> tablerender($title,$text, array('id' => $title, 'style' => 'button_menu'));
	}
}
/*
function show_admin_menu($title,$page,$e107_vars){
	global $ns;
	$text = "<div style='text-align:center; width:100%'><table class='fborder' style='width:98%;'>";
	foreach(array_keys($e107_vars) as $act) {
		$pre = "";
		$post = "";
		if ($page == $act) {
			$pre = "<b>&laquo;&nbsp;";
			$post = "&nbsp;&raquo;</b>";
		}
		$t=str_replace(" ","&nbsp;",$e107_vars[$act]['text']);
		if (!$e107_vars[$act]['perm'] || getperms($e107_vars[$act]['perm'])) {
			$text .= "<tr><td><div style='width:100%; text-align:center'><a style='cursor:hand; cursor:pointer; text-decoration:none;' href='{$e107_vars[$act]['link']}'>{$pre}{$t}{$post}</a></div></td></tr>";
		}
	}
	$text .= "</table></div>";
	if ($title=="") {
		return $text;
	}
	$ns -> tablerender($title, $text, array('id' => 'unique_id', 'style' => 'button_menu'));
}
*/

// [layout]

$ADMIN_HEADER = "<script type=\"text/javascript\">
<!--
function adbutover(object) {
	if (object.className == 'menuButton') object.className = 'menuButton_over';
}

function adbutnorm(object) {
	if (object.className == 'menuButton_over') object.className = 'menuButton';
}
// -->
</script>

<table cellpadding='0' cellspacing='0' border='0' class='top_section'>
<tr>
<td style='vertical-align: top; padding: 0px 0px 0px 0px'>
<img src='".THEME."images/adminlogo_2.png' style='width: 170px; height: 68px; display: block;' alt='' />
</td>
<td style='vertical-align: bottom; text-align: right; padding: 3px 5px 3px 0px; background-color: #efefef; border-left: 1px solid #3D4251; width: 100%; background-image: url(".THEME."images/computer.jpg); background-repeat: no-repeat'>
<div style='height: 23px'>
{CUSTOM=search}
</div>
<div style='margin-bottom: 3px;'>
{ADMIN_LOGGED}
{ADMIN_SEL_LAN}
{ADMIN_USERLAN}
</div>
{SITELINKS=flat}
</td>
<td style='width: 68px; padding: 0px 18px 0px 18px; text-align: center; vertical-align: middle; border-left: 1px solid #3D4251; background-color: #f3f3f3; background-image: url(".THEME."images/screen.png); background-repeat: no-repeat'>
<div style='height: 32px;'>
{ADMIN_ICON}
</div>
</td>
</tr>
</table>

{ADMIN_ALT_NAV}

<table cellpadding='0' cellspacing='0' border='0' class='main_section'>
<tr>
<td class='left_menu'>
<table cellpadding='0' cellspacing='0' border='0' style='width:100%;'>
<tr>
<td>
{SETSTYLE=leftmenu}
{ADMIN_LANG}";

if ($preleft!='') {
	$ADMIN_HEADER .= $preleft;
} else {
	$ADMIN_HEADER .= "{ADMIN_SITEINFO}";
}

$ADMIN_HEADER .= "<br />
</td></tr></table>
</td>
<td class='default_menu'>
{SETSTYLE=default}
";

$ADMIN_FOOTER = "<br />
</td>";

if ($preright=='pre') {
	$ADMIN_FOOTER .= "<td class='right_menu'>
	<table cellpadding='0' cellspacing='0' border='0' style='width:100%;'>
	<tr>
	<td>
	{SETSTYLE=rightmenu}
	{ADMIN_MENU}
	<br />
	</td></tr></table>
	</td>";
}

$ADMIN_FOOTER .= "</tr>
</table>
<div style='text-align:center'>
<br />
{SITEDISCLAIMER}
</div>
";
?>
