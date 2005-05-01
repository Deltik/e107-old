<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_0.7/e107_plugins/content/languages/English/lan_content_help.php,v $
|     $Revision: 1.6 $
|     $Date: 2005-05-01 23:14:26 $
|     $Author: lisa_ $
+----------------------------------------------------------------------------+
*/

//require_once(e_PLUGIN."content/handlers/content_class.php");

define("CONTENT_ADMIN_HELP_LAN_0", "<i>if you have not yet added main parent categories, please do so at the Create New Category page.</i><br /><br /><b>main parent buttons</b><br />select a main parent category by clicking the button.");

define("CONTENT_ADMIN_HELP_LAN_1", "<i>this page shows all content items from the main parent category you have selected at the content main page.</i><br />");

define("CONTENT_ADMIN_HELP_LAN_2", "<br /><i>you have selected a specific category to show content items.<br />the list will now contain only those content items from the selected category.</i><br />");

define("CONTENT_ADMIN_HELP_LAN_3", "<br /><b>first letters</b><br />if multiple content item starting letters of the content_heading are present, you will see buttons to sleect only those content items starting with that letter.<br /><br /><b>detailed list</b><br />You see a list of all content items with their id, icon, author, heading [subheading] and options.<br /><br /><b>options</b><br />you can edit or delete a content item using the buttons shown.");

define("CONTENT_ADMIN_HELP_LAN_4", "<i>the main parent categories are shown as buttons.</i><br /><br /><b>main parent buttons</b><br />please select a main parent category to create your content item for.<br />");

define("CONTENT_ADMIN_HELP_LAN_5", "<i>this page shows the content item creation form</i><br /><br /><b>create form</b><br />you can now supply all information for the new content item.<br /><br /><b>bbcode</b><br />with the bbcode tags you can specify certain style elements to parts of text, insert links and more.<br /><br /><b>[newpage=name]</b><br />with the [newpage] tag you can split your content item into multiple pages.<br />usage of the [newpage] tag: if you want to specify multiple pages, insert a [newpage] tag before each page (and don't forget to insert one at the very beginning of the content item !).<br />The new [newpage=name] method allows you to give a name to each page, which will be shown in the content item index when you view the content item.<br />");

define("CONTENT_ADMIN_HELP_LAN_6", "<i>this page shows the content item edit form</i><br /><br /><b>edit form</b><br />you can now edit all information for this content item and submit your changes.");

define("CONTENT_ADMIN_HELP_LAN_7", "<i>you see the main parent category buttons and the parent creation form</i><br /><br /><b>default main parent</b><br />by default this form allows you to create a new main parent. (the category pulldown is empty)<br /><br /><b>main parent buttons</b><br />if you want to create a subcategory within one of the present main parent categories, please select a main parent category by clicking the button.");

define("CONTENT_ADMIN_HELP_LAN_8", "<i>you have selected a main parent category to create a new subcategory in.</i><br /><br /><b>category pulldown</b><br />you see that the category pulldown now contains all present subcategories from the main parent category.<br /><br /><b>category creation form</b><br />you can now supply all information for the new subcategory.");

define("CONTENT_ADMIN_HELP_LAN_9", "<i>you see the main parent category buttons.</i><br /><br /><b>main parent buttons</b><br />first you have to select a main parent category by clicking the button.");

define("CONTENT_ADMIN_HELP_LAN_10", "
<i>this page shows all categories from the main parent category you have selected at the previous page.</i><br /><br />
<b>detailed list</b><br />You see a list of all subcategories with their id, icon, author, category [subheading] and options.<br />
<br />
<b>explanation of icons</b><br />
".CONTENT_ICON_EDIT." : for all categories you can click this button to edit the category.<br />
".CONTENT_ICON_DELETE." : for all categories you can click this button to delete the category.<br />
".CONTENT_ICON_OPTIONS." : for only the main category (at the top of the list) you can click this button to set and control all options.<br />
".CONTENT_ICON_CONTENTMANAGER_SMALL." : (site admin only) for each subcategory you can click this button to manage the personalmanager for other admins.<br />
");

define("CONTENT_ADMIN_HELP_LAN_11", "<i>this page shows the category edit form.</i><br /><br /><b>category edit form</b><br />you can now edit all information for this (sub)category and submit your changes.");

define("CONTENT_ADMIN_HELP_LAN_12", "
<i>this page shows the options you can set for this main parent. Each main parent has their own specific set of options, so be sure to set them all correctly.</i><br /><br />
<b>default values</b><br />By default all values are present and already updated in the preferences when you view this page, but change any setting to your own standards.<br /><br />
<b>division into eight sections</b><br />the options are divided into eight main sections. You see the different section in the right menu. you can click on them to go to the specific set of options for that section.<br /><br />
<b>create</b><br />in this section you can specify options for the creation of content items on the admin pages on the admins end.<br /><br />
<b>submit</b><br />in this section you can specify options for the submit form of content items.<br /><br />
<b>path and theme</b><br />in this section you can set a theme for this main parent, and provide path locations to where you have stored your images for this main parent.<br /><br /><b>general</b><br />in this section you can specify general options to use throughout all the content pages.<br /><br />
<b>list pages</b><br />in this section you can specify options pages, where content items are listed.<br /><br />
<b>category pages</b><br />in this section you can specify options how to show the category pages.<br /><br />
<b>content pages</b><br />in this section you can specify options how to show the content item page.<br /><br />
<b>menu</b><br />in this section you can specify options for the menu of this main parent.<br /><br />
");

define("CONTENT_ADMIN_HELP_LAN_13", "<i>On this page you see a list of all content items that were submitted by users.</i><br /><br /><b>detailed list</b><br />You see a list of these content items with their id, icon, main parent, heading [subheading], author and options.<br /><br /><b>options</b><br />you can post or delete a content item using the buttons shown.");

define("CONTENT_ADMIN_HELP_LAN_14", "Content Management Help Area");

define("CONTENT_ADMIN_HELP_LAN_15", "<br /><b>personal manager</b><br />you can assign admins to certain categories. In doing so, these admins can manage their personal content items within these categories from outside of the admin page (content_manager.php).");

define("CONTENT_ADMIN_HELP_LAN_16", "<i>on this page you can assign admins to the selected category you have clicked</i><br /><br />Assign admin from the left colomn by clicking their name. you will see these names move to the right colomn. After clicking the assign button the admins in the right colomn are assigned to this category.");

define("CONTENT_ADMIN_HELP_LAN_17", "<i>if you have not yet added main parent categories, please do so at the Create New Category page.</i><br /><br /><b>main parent buttons</b><br />select a main parent category by clicking the button.");

define("CONTENT_ADMIN_HELP_LAN_18", "
<i>this page shows all categories from the main parent category you have selected at the content order main page.</i><br /><br />
<b>detailed list</b><br />you see the category id and the category name. also you see several options to manage the order of the categories.<br />
<br />
<b>explanation of icons</b><br />
".CONTENT_ICON_ORDERALL." manage the global order of content item regardless of category.<br />
".CONTENT_ICON_ORDERCAT." manage the order of content items in the specific category.<br />
<img src='".e_IMAGE."admin_images/up.png' alt='' /> the up button allow you to move a content item one up in order.<br />
<img src='".e_IMAGE."admin_images/down.png' alt='' /> the down button allow you to move a content item one down in order.<br />
<br />
<b>order</b><br />here you can manually set the order of all the categories in this main parent. You need to change the values in the select boxes to the order of your kind and then press the update button below to save the new order.<br />
");

define("CONTENT_ADMIN_HELP_LAN_19", "
<i>this page shows all content items from the category you have selected.</i><br /><br />
<b>detailed list</b><br />you see the content id, the content author and the content heading. also you see several options to manage the order of the content items.<br />
<br />
<b>explanation of icons</b><br />
<img src='".e_IMAGE."admin_images/up.png' alt='' /> the up button allow you to move a content item one up in order.<br />
<img src='".e_IMAGE."admin_images/down.png' alt='' /> the down button allow you to move a content item one down in order.<br />
<br />
<b>order</b><br />here you can manually set the order of all the categories in this main parent. You need to change the values in the select boxes to the order of your kind and then press the update button below to save the new order.<br />
");

define("CONTENT_ADMIN_HELP_LAN_20", "
<i>if you have not yet added main parent categories, please do so at the Create New Category page.</i><br /><br />
<b>main parent buttons</b><br />select a main parent category by clicking the button.<br /><br />
<b>three types of ordering</b><br />there are three different ordering methods available, which will not interfere with each other.<br />
<b>order category</b><br />first you can order the subcategories of this main parent (this will be used in the overview of the categories)<br />
<b>order items in each category</b><br />secondly you can order the content items in each seperate subcategory (this will be used in the category overview pages of content items)<br />
<b>order all items in one list</b><br />thirdly you can order all the content items of the main parent. (this will be used in the recent list of content items).


");

?>