<?php
if (!defined('e107_INIT')) { exit; }

$plugintable = "pcontent";
$reported_content = $sql -> db_Count($plugintable, '(*)', "WHERE content_refer='sa' ");
$text .= "
<div style='padding-bottom: 2px;'>
<img src='".e_PLUGIN."content/images/content_16.png' style='width: 16px; height: 16px; vertical-align: bottom' alt='' />
";
if($reported_content) {
	$text .= " <a href='".e_PLUGIN."content/admin_content_config.php?submitted'>Submitted Content Items: $reported_content</a>";
} else {
	$text .= "Submitted Content Items: ".$reported_content;
}
$text .= "</div>";
?>