<?php
/*
 * e107 website system
 *
 * Copyright (C) 2001-2009 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 *
 *
 * $Source: /cvs_backup/e107_0.8/e107_languages/English/admin/help/filemanager.php,v $
 * $Revision: 1.2 $
 * $Date: 2009-11-17 11:13:04 $
 * $Author: marj_nl_fr $
 */

if (!defined('e107_INIT')) { exit; }

$text = "You are able to manage the files in your /files directory from this page. If you are getting an error message about permissions when uploading please CHMOD the directory you are attempting to upload into to 777.";
$ns -> tablerender("File Manager Help", $text);
