<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2009 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 *
 *
 * $Source: /cvs_backup/e107_0.8/e107_admin/includes/tabbed.php,v $
 * $Revision$
 * $Date$
 * $Author$
 */

if (!defined('e107_INIT')) { exit; }

$mes = e107::getMessage();

$text = "<div style='text-align:center'>
	   	<div class='admintabs' id='tab-container'>
			<ul class='e-tabs' id='core-emote-tabs'>";

            foreach ($admin_cat['id'] as $cat_key => $cat_id)
			{
				// $text .= "<li id='tab-main_".$cat_key."' ><span style='white-space:nowrap'><img class='icon S16' src='".$admin_cat['img'][$cat_key]."' alt='' style='margin-right:3px' /><a href='#core-main_".$cat_key."'>".$admin_cat['title'][$cat_key]."</a></span></li>";
				$text .= "<li id='tab-main_".$cat_key."' ><a href='#core-main_".$cat_key."'>".$admin_cat['title'][$cat_key]."</a></li>";
			}
			$text .= "</ul>";



foreach ($admin_cat['id'] as $cat_key => $cat_id)
{
	$text_check = FALSE;

	$text_cat = "<div class='adminedit' id='core-main_".$cat_key."'>
	<div style='border:1px solid silver;margin:10px'>
	<div class='main_caption bevel'><b>".$admin_cat['title'][$cat_key]."</b></div>
	<table style='width:100%'>";


	if ($cat_key != 5) // Note the Plugin category.
	{
		foreach ($newarray as $key => $funcinfo)
		{
			if ($funcinfo[4] == $cat_key)
			{
				$text_rend = render_links($funcinfo[0], $funcinfo[1], $funcinfo[2], $funcinfo[3], $funcinfo[6], 'classis');
				if ($text_rend)
				{
					$text_check = TRUE;
				}
				$text_cat .= $text_rend;
			}
		}
	}
	else // Plugin category.
	{
		$text_rend  = getPluginLinks(E_32_PLUGMANAGER, "classis");

		if ($text_rend)
		{
			$text_check = TRUE;
		}
		$text_cat .= $text_rend;
	}
	$text_cat .= render_clean();
	$text_cat .= "</table></div>
	</div>";

	if ($text_check)
	{
		$text .= $text_cat;
	}
}



$text .= "</div></div>";

$ns->tablerender(ADLAN_47." ".ADMINNAME, $mes->render().$text);


?>