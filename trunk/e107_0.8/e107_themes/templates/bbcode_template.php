<?php
/*
 * e107 website system
 *
 * Copyright (C) e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * $URL$
 * $Id$
 */

// How to register your own BBcode button.
// Uncomment the 2 commented lines below to see it in action. (only applies to the user area)

// $register_bb['blank'] = array("", "[blank][/blank]","Blank example helper text",e_IMAGE."bbcode/template.png");
// Simplified default bbcode bar - removed P, H, BR and NOBR bbcodes


// This is used on the front-end. ie. comments etc. 
$BBCODE_TEMPLATE = "
	{BB=link}{BB=b}{BB=i}{BB=u}{BB=img}{BB=justify}{BB=center}{BB=left}{BB=right}
	{BB=bq}{BB=list}{BB=emotes}
	<div class='field-spacer'><!-- --></div>
";

$BBCODE_TEMPLATE_COMMENT = ""; // no buttons on comments by default. 

// $BBCODE_TEMPLATE .= "{BB=blank}";

$BBCODE_TEMPLATE_SIGNATURE = "
	{BB=link}{BB=b}{BB=i}{BB=u}{BB=img}{BB=justify}{BB=center}{BB=left}{BB=right}
	{BB=bq}{BB=list}
	<div class='field-spacer'><!-- --></div>
";




// $sc_style['BB_HELP']['pre'] = "<div style='text-align:center'>";
// $sc_style['BB_HELP']['post'] = "</div>";

$BBCODE_TEMPLATE_SUBMITNEWS = "
	
	{BB_HELP}
	<div class='field-spacer'><!-- --></div>
    {BB=link}{BB=b}{BB=i}{BB=u}{BB=img}{BB=justify}{BB=center}{BB=left}{BB=right}
	{BB=bq}{BB=list}{BB=nobr}{BB=br}{BB=fontcol}{BB=fontsize}{BB=emotes}{BB=flash}{BB=youtube}
	<div class='field-spacer'><!-- --></div>
";


// --------   Admin Templates ----------------------

$BBCODE_TEMPLATE_ADMIN = "
	{BB_HELP=admin}
	<div class='field-spacer'><!-- --></div>
	{BB=link}{BB=b}{BB=i}{BB=u}{BB=img}{BB=format}{BB=justify}{BB=center}{BB=left}
	{BB=right}{BB=bq}{BB=list}{BB=table}{BB=fontcol}{BB=fontsize}{BB=emotes}
	{BB_PREIMAGEDIR}
	{BB=preimage}{BB=prefile}{BB=flash}{BB=youtube}
	<div class='field-spacer'><!-- --></div>
";

$BBCODE_TEMPLATE_MAILOUT = "
	{BB_HELP=admin}
	<div class='field-spacer'><!-- --></div>
	{BB=link}{BB=b}{BB=i}{BB=u}{BB=img}{BB=justify}{BB=center}{BB=left}
	{BB=right}{BB=bq}{BB=list}{BB=fontcol}{BB=fontsize}{BB=emotes}
	{BB_PREIMAGEDIR}
	{BB=preimage}{BB=prefile}{BB=flash}{BB=shortcode}
	<div class='field-spacer'><!-- --></div>
";

// $BBCODE_TEMPLATE_ADMIN .= "{BB=blank}";

$BBCODE_TEMPLATE_NEWSPOST = "
	{BB_HELP=$mode}
	<div class='field-spacer'><!-- --></div>
	{BB=link}{BB=b}{BB=i}{BB=u}{BB=img}{BB=format}{BB=justify}{BB=center}{BB=left}
	{BB=right}{BB=bq}{BB=list}{BB=table}{BB=fontcol}{BB=fontsize}{BB=emotes}
	{BB_PREIMAGEDIR=news}
	{BB=preimage}{BB=prefile}{BB=flash}{BB=youtube}
	<div class='field-spacer'><!-- --></div>
";

$BBCODE_TEMPLATE_CPAGE = "
	{BB_HELP}
	<div class='field-spacer'><!-- --></div>
	{BB=newpage}
	{BB=link}{BB=b}{BB=i}{BB=u}{BB=img}{BB=format}{BB=justify}{BB=center}{BB=left}{BB=right}
	{BB=bq}{BB=list}{BB=table}{BB=fontcol}{BB=fontsize}{BB=emotes}
	{BB_PREIMAGEDIR=page}
	{BB=preimage}{BB=prefile}{BB=flash}{BB=youtube}
	<div class='field-spacer'><!-- --></div>
";
?>