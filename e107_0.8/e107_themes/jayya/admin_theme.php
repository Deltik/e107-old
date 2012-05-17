<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     Copyright (C) 2008-2009 e107 Inc (e107.org)
|     http://e107.org
|
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $Source: /cvs_backup/e107_0.8/e107_themes/jayya/admin_theme.php,v $
|     $Revision$
|     $Date$
|     $Author$
+----------------------------------------------------------------------------+
*/

// Protect the file from direct access
if (!defined('e107_INIT')) { exit; }

define('STANDARDS_MODE', TRUE);

// Get language definition files for this theme
include_lan(e_THEME."jayya/languages/".e_LANGUAGE.".php");


// [theme]

$themename = "Jayya";
$themeversion = "1.0";
$themeauthor = "";
$themedate = "";
$themeinfo = "";
$xhtmlcompliant = TRUE;
$csscompliant = TRUE;
define("THEME_DISCLAIMER", "");
define("IMODE", "lite");

// [dont render core style sheet link]
	$no_core_css = TRUE;

//Prototype Scripts
e107::js('core', 'core/decorate.js', 'prototype', 2);
e107::js('core', 'core/tabs.js', 'prototype', 2);

e107::js('inline',"
	 /**
    	* Decorate all tables having e-list class
    	* TODO: add 'adminlist' class to all list core tables, allow theme decorate.
    	*/
        e107.runOnLoad( function(event) {
        	var element = event.memo['element'] ? $(event.memo.element) : $$('body')[0];

            element.select('table.adminlist:not(.no-decorate)').each(function(element) {
            	e107Utils.Decorate.table(element, {tr_td: 'first last'});
            });
			element.select('div.admintabs').each(function(element) {
				//show tab navaigation
				element.select('ul.e-tabs').each( function(el){
					el.show();
					el.removeClassName('e-hideme');//prevent hideme re-register (e.g. ajax load)
				});
				//init tabs
            	new e107Widgets.Tabs(element);
            	//hide legends if any
            	element.select('legend').invoke('hide');
            });

            $$('a.e-dialog-close').invoke('observe', 'click', function(ev) {
					parent.e107Widgets.DialogManagerDefault.getWindow('e-dialog').close();
			});
           //  
            
            
        }, document, true);
"
,'prototype');

//TODO - Move to external files. 

e107::css('inline',"/******** Tabs JS */

.admintabs ul.e-tabs { border-bottom: 1px solid #DDDDDD; height: 31px; }
.admintabs ul.e-tabs li { border: 1px solid #DDDDDD; display: block; float: left; line-height: 30px; padding: 0px 7px; margin-right: 3px; background-color: #F9F9F9 }
.admintabs fieldset { clear: both ; border: 1px solid #DDDDDD; padding: 10px; border-top: 0px none; }
.admintabs fieldset legend { border: 1px solid #DDDDDD; }
.admintabs ul.e-tabs li.active { border-bottom: 1px solid #FFFFFF; background-color: #FFFFFF}
 a.e-tabs {
   text-decoration: none;
 }
",'prototype');


// jQUERY scripts

e107::js('core', 'core/colorbox/jquery.colorbox-min.js', 'jquery', 2);
e107::css('core', 'core/colorbox/colorbox.css', 'jquery');
e107::js('core', 'core/jquery.elastic.source.js', 'jquery', 2);

e107::js('inline','

	$(document).ready(function()
    {
    	// $(".e-hideme").hide();
    	 $(".e-expandit").show();
    			
       	$(".e-expandit").click(function () {
       		var id = $(this).attr("href");
			$(id).toggle("slow");
		}); 
		
		// Date
		$(function() {
			$( ".e-date" ).datepicker();
		});  
		
		// Tabs
		$(function() {
			$( "#tab-container" ).tabs();
		});	
		
		// Decorate		
		$(".adminlist tr:even").addClass("even");
		$(".adminlist tr:odd").addClass("odd");
		$(".adminlist tr:first").addClass("first");
  		$(".adminlist tr:last").addClass("last");
				
		// Character Counter
		$("textarea").before("<p class=\"remainingCharacters\" id=\"" + $("textarea").attr("name")+ "-remainingCharacters\">&nbsp;</p>");
		$("textarea").keyup(function(){
    		
    	//	var max=$(this).attr("maxlength");
			var max = 100;
			var el = "#" + $(this).attr("name") + "-remainingCharacters";
    		var valLen=$(this).val().length;
    		$(el).text( valLen + " characters")
		});
		
		// Text-area AutoGrow
		$("textarea.e-autoheight").elastic();
		
		// Dialog
		$("a.e-dialog").colorbox({
			iframe:true,
			width:"60%",
			height:"60%",
			speed:100
		});
		
		$(".e-dialog-close").click(function () {
			parent.$.colorbox.close()
		}); 
		
		
		// Modal Box - uses inline hidden content 
		$(".e-modal").click(function () {
			var id = $(this).attr("href");
			$(id).dialog({
				 minWidth: 800,
				 maxHeight: 800,
				 modal: true
			 });
		});
		
		
		// Sorting
		var fixHelper = function(e, ui) {
			ui.children().each(function() {
				$(this).width($(this).width());
				$(this).addClass("e-moving");
			});
			return ui;
		};
		
		$("#e-sort").sortable({
			helper: fixHelper,
			cursor: "move",
			opacity: 0.9,
			containment: "parent",
			update: function(event, ui) {
			
			$.ajax({
			  type: "POST",
			  url: "links.php?ajax_used=1",
			  data: { name: "hi", location: "Boston" }
			}).done(function( msg ) {
				
			  alert( "Data Saved: " + msg );
			});

 		}
			
		}).disableSelection();
		
		
		
		// Check-All
		$("input.toggle-all").click(function(evt){
			if($(this).is(":checked")){
				$("input[type=\"checkbox\"].checkbox").attr("checked", "checked");
			}
			else{
				$("input[type=\"checkbox\"].checkbox").removeAttr("checked");
			}
		});
		
		// highlight checked row
		$("input[type=\"checkbox\"].checkbox").click(function(evt){
	
			if(this.checked)
			{
				$(this).closest("tr").switchClass( "odd", "highlight-odd", 0 );
				$(this).closest("tr").switchClass( "even", "highlight-even", 0 );
    		}
			else
			{
				$(this).closest("tr").switchClass( "highlight-even", "even", 300 );
				$(this).closest("tr").switchClass( "highlight-odd", "odd", 300 );
			}	
			
		});
		
			
		
	
		// Basic Delete Confirmation	
		$("input.delete").click(function(){
  			var answer = confirm($(this).attr("title")+ " ?");
  			return answer // answer is a boolean
		});  
		
		
		

		
		
})
		// BC Expandit() function 
		function expandit(e) {
			var id = "#" + e; // TODO detect new "div" when e = ""; 
			$(id).toggle("slow");
			
		};
		
		
		
','jquery');

e107::css('inline',"
	.e-moving { background-color:yellow; }
	tr.highlight-even	{ background-color:silver; }
	tr.highlight-odd	{ background-color:silver; }
	legend { display: none; }
",'jquery');


// [layout]

$layout = "_default";

$HEADER = "<table class='page_container'>
<tr>
<td>

<table class='top_section'>
<tr>
<td class='top_section_left' style='width: 190px; padding-left: 5px; padding-right: 5px'>
{LOGO}
</td>
<td class='top_section_mid'>
{BANNER}
</td>

<td class='top_section_right' style='padding: 0px; white-space: nowrap; width: 170px'>
{CUSTOM=search+default}
</td>
</tr>
</table>

<div>
{SITELINKS_ALT=".THEME_ABS."images/arrow.png+noclick}
</div>

<table class='main_section'>
<colgroup>
<col style='width: 170px' />
<col style='width: auto' />
<col style='width: 170px' />
</colgroup>

<tr>
<td class='left_menu'>
<table class='menus_container'><tr><td>
{SETSTYLE=leftmenu}
{MENU=1}
</td></tr></table>
</td>
<td class='default_menu'>
{SETSTYLE=default}
{WMESSAGE}
";

$FOOTER = "<br />
</td>

<td class='right_menu'>
<table class='menus_container'><tr><td>
{SETSTYLE=rightmenu}
{MENU=2}
</td></tr></table>
</td>
</tr>
</table>
<div style='text-align:center'>
<br />
{SITEDISCLAIMER}
<br /><br />
</div>
</td>
</tr>
</table>
";


// [linkstyle]

define('PRELINK', '');
define('POSTLINK', '');
define('LINKSTART', '');
define('LINKEND', '');
define('LINKDISPLAY', 1);
define('LINKALIGN', 'left');


// [newsstyle]

$sc_style['NEWSIMAGE']['pre'] = "<td style='padding-right: 7px; vertical-align: top'>";
$sc_style['NEWSIMAGE']['post'] = "</td>";

$NEWSSTYLE = "<div class='cap_border'><div class='main_caption'><div class='bevel'>
{STICKY_ICON}{NEWSTITLE}
</div></div></div>
<div class='menu_content'>
<table style='width: 100%'>
<tr>
{NEWSIMAGE}
<td style='width: 100%; vertical-align: top'>
{NEWSBODY}
{EXTENDED}
<br />
</td>
</tr>
</table>
</div>
<div class='menu_content'>
<table class='news_info'>
<tr>
<td style='text-align: center; padding: 3px; padding-bottom: 0px; white-space: nowrap'>
{NEWSICON}
</td>
<td style='width: 100%; padding: 0px; padding-bottom: 0px; padding-left: 2px'>
".LAN_THEME_5."
{NEWSAUTHOR}
 ".LAN_THEME_6."
{NEWSDATE}
</td><td style='text-align: center; padding: 3px; padding-bottom: 0px; white-space: nowrap'>
<img src='".THEME_ABS."images/comments_16.png' style='width: 16px; height: 16px' alt='' />
</td>
<td style='padding: 0px; padding-left: 2px; white-space: nowrap'>
{NEWSCOMMENTS}
</td><td style='padding: 0px; white-space: nowrap'>
{TRACKBACK}
</td><td style='text-align: center; padding: 3px; padding-bottom: 0px; padding-left: 7px; white-space: nowrap'>
{EMAILICON}
{PRINTICON}
{PDFICON}
{ADMINOPTIONS}
</td></tr></table>
<br /></div>";

define('ICONMAIL', 'email_16.png');
define('ICONPRINT', 'print_16.png');
define('ICONSTYLE', 'border: 0px');
define('COMMENTLINK', LAN_THEME_2);
define('COMMENTOFFSTRING', LAN_THEME_1);
define('PRE_EXTENDEDSTRING', '<br /><br />[ ');
define('EXTENDEDSTRING', LAN_THEME_3);
define('POST_EXTENDEDSTRING', ' ]<br />');
define('TRACKBACKSTRING', LAN_THEME_4);
define('TRACKBACKBEFORESTRING', '&nbsp;|&nbsp;');


//	[tablestyle]

function tablestyle($caption, $text, $mode){
	global $style;
	$caption = $caption ? $caption : '&nbsp;';
	if ((isset($mode['style']) && $mode['style'] == 'button_menu') || (isset($mode) && ($mode == 'menus_config'))) {
		$menu = ' buttons';
		$bodybreak = '';
		$but_border = ' button_menu';
	} else {
		$menu = '';
		$bodybreak = '<br />';
		$but_border = '';
	}

	$menu .= ($style && $style != 'default') ? ' non_default' : '';

	echo "<div class='cap_border".$but_border."'>";
	if ($style == 'leftmenu') {
		echo "<div class='left_caption'><div class='bevel'>".$caption."</div></div>";
	}  else if ($style == 'rightmenu') {
		echo "<div class='right_caption'><div class='bevel'>".$caption."</div></div>";
	} else {
		echo "<div class='main_caption'><div class='bevel'>".$caption."</div></div>";
	}
	echo "</div>";
	if ($text != "") {
		echo "<table class='cont'><tr><td class='menu_content ".$menu."'>".$text.$bodybreak."</td></tr></table>";
	}
}


// chatbox post style
$CHATBOXSTYLE = "
<img src='".e_IMAGE_ABS."admin_images/chatbox_16.png' alt='' style='width: 16px; height: 16px; vertical-align: bottom' />
<b>{USERNAME}</b><br />{TIMEDATE}<br />{MESSAGE}<br /><br />";


// comment post style
$sc_style['REPLY']['pre'] = "<tr><td class='forumheader'>";
$sc_style['REPLY']['post'] = "</td>";

$sc_style['SUBJECT']['pre'] = "<td class='forumheader'>";
$sc_style['SUBJECT']['post'] = "</td></tr>";

$sc_style['COMMENTEDIT']['pre'] = "<tr><td class='forumheader' colspan='2' style='text-align: right'>";
$sc_style['COMMENTEDIT']['post'] = "</td></tr>";

$sc_style['JOINED']['post'] = "<br />";

$sc_style['LOCATION']['post'] = "<br />";

$sc_style['RATING']['post'] = "<br /><br />";

$sc_style['COMMENT']['post'] = "<br />";

$COMMENTSTYLE = "<div class='spacer' style='text-align:center'><table class='fborder' style='width: 95%'>
<tr>
<td class='fcaption' colspan='2'>".LAN_THEME_5." {USERNAME} ".LAN_THEME_6." {TIMEDATE}
</td>
</tr>
{REPLY}{SUBJECT}
<tr>
<td style='width: 20%; vertical-align: top' class='forumheader3'>
<div style='text-align: center'>
{AVATAR}
</div>
{LEVEL}<span class='smalltext'>{JOINED}{COMMENTS}{LOCATION}{IPADDRESS}</span>
</td>
<td style='width: 80%; vertical-align: top' class='forumheader3'>
{COMMENT}
{RATING}
{SIGNATURE}
</td>
</tr>
{COMMENTEDIT}
</table>
</div>";


// poll style
$POLLSTYLE = "<img src='".THEME_ABS."images/polls.png' style='width: 10px; height: 14px; vertical-align: bottom' /> {QUESTION}
<br /><br />
{OPTIONS=<img src='".THEME_ABS."images/bullet2.gif' style='width: 10px; height: 10px' /> OPTION<br />BAR<br /><span class='smalltext'>PERCENTAGE VOTES</span><br /><br />}
<div style='text-align:center' class='smalltext'>{AUTHOR}<br />{VOTE_TOTAL} {COMMENTS}
<br />
{OLDPOLLS}
</div>";

?>