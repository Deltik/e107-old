<?php
/*
 * e107 website system
 *
 * Copyright (C) 2001-2008 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * Administration - Site Maintenance
 *
 * $Source: /cvs_backup/e107_0.8/e107_admin/ugflag.php,v $
 * $Revision: 1.3 $
 * $Date: 2009-01-07 15:40:06 $
 * $Author: secretr $
 *
*/
require_once("../class2.php");
if (!getperms("9"))
{
	header("location:".e_BASE."index.php");
	exit;
}

require_once(e_HANDLER."form_handler.php");
require_once (e_HANDLER."message_handler.php");
$emessage = &eMessage::getInstance();
$emessage_method = e_AJAX_REQUEST ? 'add' : 'addSession';

$frm = new e_form(true);

if (isset($_POST['updatesettings']))
{
	$changed = FALSE;
	$temp = intval($_POST['maintainance_flag']);
	if ($pref['maintainance_flag'] != $temp)
	{
		$pref['maintainance_flag'] = $temp;
		$changed = TRUE;
	}
	$temp = $tp->toDB($_POST['maintainance_text']);
	if ($pref['maintainance_text'] != $temp)
	{
		$pref['maintainance_text'] = $temp;
		$changed = TRUE;
	}
	if ($changed)
	{
		$admin_log->log_event(($pref['maintainance_flag'] == 0) ? 'MAINT_02' : 'MAINT_01',$pref['maintainance_text'],E_LOG_INFORMATIVE,'');
		save_prefs();
		$emessage->$emessage_method(UGFLAN_1, E_MESSAGE_SUCCESS);
	}
	else $emessage->$emessage_method(UGFLAN_7);

	if(!e_AJAX_REQUEST)
	{
		header("location:".e_SELF);
		exit;
	}
}

require_once("auth.php");

$text = "
	<form method='post' action='".e_SELF."' id='core-ugflag-form'>
		<fieldset id='core-ugflag'>
			<legend class='e-hideme'>".UGFLAN_4."</legend>
			<table cellpadding='0' cellspacing='0' class='adminform'>
				<colgroup span='2'>
					<col class='col-label' />
					<col class='col-control' />
				</colgroup>
				<tbody>
					<tr>
						<td class='label'>".UGFLAN_2.": </td>
						<td class='control'>
							<div class='auto-toggle-area autocheck'>
								".$frm->checkbox('maintainance_flag', '1', $pref['maintainance_flag'])."
							</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".UGFLAN_5."
							<div class='field-help'>".UGFLAN_6."</div>
						</td>
						<td class='control'>
							".$frm->bbarea('maintainance_text', $pref['maintainance_text'], 'maintenance', 'maintenance_bbhelp')."
						</td>
					</tr>
				</tbody>
			</table>
			<div class='buttons-bar center'>
				".$frm->admin_button('updatesettings', UGFLAN_3, 'update')."
			</div>
		</fieldset>
	</form>

";

//Ajax Support
if(!e_AJAX_REQUEST)
{
	echo "<div id='ajax-container'>\n";
	$e107->ns->tablerender(UGFLAN_4, $emessage->render().$text, 'core-ugflag');
	echo "\n</div>";
	require_once(e_ADMIN."footer.php");
	exit;
}

$e107->ns->tablerender(UGFLAN_4, $emessage->render().$text, 'core-ugflag');

/**
 * Handle page DOM within the page header
 *
 * @return string JS source
 */
function headerjs()
{
	$ret = "
		<script type='text/javascript'>
			//Ajax Support
			var CoreUgflagAjaxPage = function(e_event) {
				\$('updatesettings').observe('click', function(event) {
					var form = \$('core-ugflag-form');
					if(form) {
						event.stop();
						form.submitForm('ajax-container', { overlayPage: 'core-ugflag', parameters: { updatesettings: 1 } });
					}
				});
			}
			e107.runOnLoad(CoreUgflagAjaxPage, 'ajax-container', true);

			//Admin JS Init Rules
			var e107Admin = {}
			e107Admin.initRules = {
				'Helper': true,
				'AdminMenu': false
			}

		</script>
		<script type='text/javascript' src='".e_FILE_ABS."jslib/core/admin.js'></script>
	";

	return $ret;
}
?>