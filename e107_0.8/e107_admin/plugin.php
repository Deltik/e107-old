<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2012 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * $URL$
 * $Id$
 * 
 * Plugin administration area
 *
 */

require_once("../class2.php");
if (!getperms("Z"))
{
	header("location:".e_BASE."index.php");
	exit;
}

// Only tested Locally so far. 
if(e_AJAX_REQUEST && isset($_GET['src'])) // Ajax 
{
	$string =  base64_decode($_GET['src']);	
	parse_str($string,$p);
	$remotefile = $p['plugin_url'];
	
	$localfile = md5($remotefile.time()).".zip";
	$status = "Downloading...";
	
	e107::getFile()->getRemoteFile($remotefile,$localfile);
	
	if(!file_exists(e_TEMP.$localfile))
	{
		echo 'There was a problem retrieving the file';
		exit;	
	}
//	chmod(e_PLUGIN,0777);
	chmod(e_TEMP.$localfile,0755);
	
	require_once(e_HANDLER."pclzip.lib.php");
	$archive = new PclZip(e_TEMP.$localfile);
	$unarc = ($fileList = $archive -> extract(PCLZIP_OPT_PATH, e_PLUGIN, PCLZIP_OPT_SET_CHMOD, 0755));
//	chmod(e_PLUGIN,0755);
	$dir 		= basename($unarc[0]['filename']);
//		chmod(e_UPLOAD.$localfile,0666);



	/* Cannot use this yet until 'folder' is included in feed. 
	if($dir != $p['plugin_folder'])
	{
		
		echo "<br />There is a problem with the data submitted by the author of the plugin.";
		echo "dir=".$dir;
		echo "<br />pfolder=".$p['plugin_folder'];
		exit;
	}	
	*/
		
	if($unarc[0]['folder'] ==1 && is_dir($unarc[0]['filename']))
	{
		$status = "Unzipping...";
		$dir 		= basename($unarc[0]['filename']);
		$plugPath	= preg_replace("/[^a-z0-9-\._]/", "-", strtolower($dir));	
		
		e107::getSingleton('e107plugin')->update_plugins_table();
		e107::getDb()->db_Select_gen("SELECT plugin_id FROM #plugin WHERE plugin_path = '".$plugPath."' LIMIT 1");
		$row = e107::getDb()->db_Fetch(MYSQL_ASSOC);
		$status = e107::getSingleton('e107plugin')->install_plugin($row['plugin_id']);
		//unlink(e_UPLOAD.$localfile);
		
	}
	else 
	{
		// print_a($fileList);
		$status = "Error: <br /><a href='".$remotefile."'>Download Manually</a>";
		//echo $archive->errorInfo(true);
		// $status = "There was a problem";	
		//unlink(e_UPLOAD.$localfile);
	}
	
	echo $status;
//	@unlink(e_TEMP.$localfile);

//	echo "file=".$file;
	exit;	
}

e107::coreLan('plugin', true);

$e_sub_cat = 'plug_manage';

define('PLUGIN_SHOW_REFRESH', FALSE);

global $user_pref;


require_once(e_HANDLER.'plugin_class.php');
require_once(e_HANDLER.'file_class.php');
require_once(e_HANDLER."form_handler.php");
require_once (e_HANDLER.'message_handler.php');

if(isset($_POST['uninstall_cancel']))
{
	header("location:".e_SELF);
	exit;		
}


$plugin = new e107plugin;
$pman = new pluginManager;
define("e_PAGETITLE",ADLAN_98." - ".$pman->pagetitle);
require_once("auth.php");
$pman->pluginObserver();




require_once("footer.php");
exit;

// FIXME switch to admin UI
class pluginManager{

	var $plugArray;
	var $action;
	var $id;
	var $frm;
	var $fields;
	var $fieldpref;
	var $titlearray = array();
	var $pagetitle;

	function pluginManager()
	{
        global $user_pref,$admin_log;

        $tmp = explode('.', e_QUERY);
	  	$this -> action = ($tmp[0]) ? $tmp[0] : "installed";
		$this -> id = varset($tmp[1]) ? intval($tmp[1]) : "";
		$this -> titlearray = array('installed'=>EPL_ADLAN_22,'avail'=>EPL_ADLAN_23, 'upload'=>EPL_ADLAN_38);
		
		if(isset($_GET['mode']))
		{
			$this->action = $_GET['mode'];
		}

        $keys = array_keys($this -> titlearray);
		$this->pagetitle = (in_array($this->action,$keys)) ? $this -> titlearray[$this->action] : $this -> titlearray['installed'];


		$this-> fields = array(

		   		"plugin_checkboxes"		=> array("title" => "", "forced"=>TRUE, "width"=>"3%"),
				"plugin_icon"			=> array("title" => EPL_ADLAN_82, "type"=>"icon", "width" => "5%", "thclass" => "middle center",'class'=>'center', "url" => ""),
				"plugin_name"			=> array("title" => EPL_ADLAN_10, "type"=>"text", "width" => "30", "thclass" => "middle", "url" => ""),
 				"plugin_version"		=> array("title" => EPL_ADLAN_11, "type"=>"numeric", "width" => "5%", "thclass" => "middle", "url" => ""),
    			"plugin_date"			=> array("title" => "Release ".LAN_DATE, "type"=>"text", "width" => "auto", "thclass" => "middle"),
    			
    			"plugin_folder"			=> array("title" => EPL_ADLAN_64, "type"=>"text", "width" => "10%", "thclass" => "middle", "url" => ""),
				"plugin_category"		=> array("title" => LAN_CATEGORY, "type"=>"text", "width" => "15%", "thclass" => "middle", "url" => ""),
                "plugin_author"			=> array("title" => EPL_ADLAN_12, "type"=>"text", "width" => "auto", "thclass" => "middle", "url" => ""),
  				"plugin_website"		=> array("title" => EPL_WEBSITE, "type"=>"url", "width" => "5%", "thclass" => "middle center", "url" => ""),
				"plugin_notes"			=> array("title" => EPL_ADLAN_83, "type"=>"url", "width" => "5%", "thclass" => "middle center", "url" => ""),
				"plugin_description"	=> array("title" => EPL_ADLAN_14, "type"=>"text", "width" => "auto", "thclass" => "middle center", "url" => ""),
			   	"plugin_compatible"		=> array("title" => EPL_ADLAN_13, "type"=>"text", "width" => "auto", "thclass" => "middle", "url" => ""),
				"plugin_compliant"		=> array("title" => EPL_ADLAN_81, "type"=>"text", "width" => "5%", "thclass" => "middle center", "url" => ""),
		//		"plugin_release"		=> array("title" => EPL_ADLAN_81, "type"=>"text", "width" => "5%", "thclass" => "middle center", "url" => ""),
				"options"				=> array("title" => LAN_OPTIONS, 'forced'=>TRUE, "width" => "15%", "thclass" => "middle center last", "url" => ""),

		);



/*		if(isset($_POST['uninstall-selected']))
		{
        	foreach($_POST['plugin_checkbox'] as $val)
			{
            	$this -> id = intval($val);
                $this -> pluginUninstall();
			}
      		$this -> action = "installed";
			$this -> pluginRenderList();
			return;

			// Complicated, as each uninstall system is different.
		}*/






    }

    function pluginObserver()
	{

        global $user_pref,$admin_log;
    	if (isset($_POST['upload']))
		{
        	$this -> pluginProcessUpload();
		}

        if(isset($_POST['etrigger_ecolumns']))
		{
			$user_pref['admin_pluginmanager_columns'] = $_POST['e-columns'];
			save_prefs('user');
		}

        $this -> fieldpref = (vartrue($user_pref['admin_pluginmanager_columns'])) ? $user_pref['admin_pluginmanager_columns'] : array("plugin_icon","plugin_name","plugin_version","plugin_description","plugin_category","plugin_author","plugin_website","plugin_notes");



        if($this->action == 'avail' || $this->action == 'installed')   // Plugin Check is done during upgrade_routine.
		{
			$this -> pluginCheck();
		}

		if($this->action == "uninstall")
		{
        	$this -> pluginUninstall();
		}

        if($this->action == "install")
		{
        	$this -> pluginInstall();
    		$this -> action = "installed";
		}



		if($this->action == "upgrade")
		{
        	$this -> pluginUpgrade();
      		$this -> action = "installed";
		}

		if($this->action == "refresh")
		{
        	$this -> pluginRefresh();
		}
		if($this->action == "upload")
		{
        	$this -> pluginUpload();
		}
		
		if($this->action == "online")
		{
        	$this -> pluginOnline();
			return;
		}
		

		if(isset($_POST['install-selected']))
		{
        	foreach($_POST['plugin_checkbox'] as $val)
			{
            	$this -> id = intval($val);
                $this -> pluginInstall();
			}
      		$this -> action = "installed";
		}

        if($this->action != 'avail' && varset($this->fields['plugin_checkboxes']))
		{
		 	$this->fields['plugin_checkboxes'] = FALSE;
		}

		if($this->action !='upload' && $this->action !='uninstall')
		{
			$this -> pluginRenderList();
		}



	}
	
	
	function pluginOnline()
	{
		global $plugin;
		$tp = e107::getParser();
		$frm = e107::getForm();
		
		$caption	= "Search Online";
		
		$e107 = e107::getInstance();
		$xml = e107::getXml();
		$mes = e107::getMessage();
		
		$mes->addWarning("This area is experimental and may produce unpredictable results.");

		$from = intval($_GET['frm']);
	
	//	$file = SITEURLBASE.e_PLUGIN_ABS."release/release.php";  // temporary testing
		$file = "http://e107.org/feed?frm=".$from;
		
		$xml->setOptArrayTags('plugin'); // make sure 'plugin' tag always returns an array
		$xdata = $xml->loadXMLfile($file,'advanced');

		$total = $xdata['@attributes']['total'];

		//TODO use admin_ui including filter capabilities by sending search queries back to the xml script. 

		// XML data array. 
		$c = 1;
		foreach($xdata['plugin'] as $r)
		{
			$row = $r['@attributes'];
			
				$data[] = array(
					'plugin_id'				=> $c,
					'plugin_icon'			=> vartrue($row['icon'],e_IMAGE."admin_images/plugins_32.png"),
					'plugin_name'			=> $row['name'],
					'plugin_folder'			=> $row['folder'],
					'plugin_date'			=> vartrue($row['date']),
					'plugin_category'		=> vartrue($r['category'][0]),
					'plugin_author'			=> vartrue($row['author']),
					'plugin_version'		=> $row['version'],
					'plugin_description'	=> $tp->text_truncate(vartrue($r['description'][0]),200),
				
				
					'plugin_website'		=> vartrue($row['authorUrl']),
					'plugin_url'			=> $row['url'],
					'plugin_notes'			=> ''
					);	
			$c++;
		}
	
//	print_a($data);
		$fieldList = $this->fields;
		unset($fieldList['plugin_checkboxes']);
		
		$text = "
			<form action='".e_SELF."?".e_QUERY."' id='core-plugin-list-form' method='post'>
				<fieldset id='core-plugin-list'>
					<legend class='e-hideme'>".$caption."</legend>
					<table class='adminlist'>
						".$frm->colGroup($fieldList,$this->fieldpref).
						$frm->thead($fieldList,$this->fieldpref)."
						<tbody>
		";	
		
		
	
		
		foreach($data as $key=>$val	)
		{
		//	print_a($val);
			$text .= "<tr>";
						
			foreach($this->fields as $v=>$foo)
			{
				if(!in_array($v,$this->fieldpref) || $v == 'plugin_checkboxes')
				{
					continue;	
				}
				// echo '<br />v='.$v;
				$text .= "<td class='".vartrue($this->fields[$v]['class'],'left')."'>".$frm->renderValue($v, $val[$v], $this->fields[$v])."</td>\n";
			}
			$text .= "<td class='center'>".$this->options($val)."</td>";
			$text .= "</tr>";		
			
		}
		
		
		$text .= "
						</tbody>
					</table>";
		$text .= "
				</fieldset>
			</form>
		";
		
		$amount = 30;
		
		
		if($total > $amount)
		{
			$parms = $total.",".$amount.",".$from.",".e_SELF.'?mode='.$_GET['mode'].'&amp;frm=[FROM]';
			$text .= "<div style='text-align:center;margin-top:10px'>".$tp->parseTemplate("{NEXTPREV=$parms}",TRUE)."</div>";
		}
		
		e107::getRender()->tablerender(ADLAN_98." :: ".$caption, $mes->render(). $text);
	}
	
	
	
	function options($data)
	{		
		$d = http_build_query($data,false,'&');
		$url = e_SELF."?src=".base64_encode($d);
		$id = 'plug_'.$data['plugin_folder'];
		return "<div id='{$id}' style='vertical-align:middle'>
		<button type='button' data-target='{$id}' data-loading='".e_IMAGE."/generic/loading_32.gif' class='e-ajax middle' value='Download and Install' data-src='".$url."' ><span>Download and Install</span></button>
		</div>";				
	}
	
	
	// FIXME - move it to plugin handler, similar to install_plugin() routine
	function pluginUninstall()
	{
			$pref = e107::getPref();
			$admin_log = e107::getAdminLog();
			$plugin = e107::getPlugin();
			$tp = e107::getParser();
			$sql = e107::getDb();
			$eplug_folder = '';
			if(!isset($_POST['uninstall_confirm']))
			{	// $id is already an integer
				$this->pluginConfirmUninstall($this->id);
   				return;
			}

			$plug = $plugin->getinfo($this->id);
			$text = '';
			//Uninstall Plugin
			if ($plug['plugin_installflag'] == TRUE )
			{
				$eplug_folder = $plug['plugin_path'];
				$_path = e_PLUGIN.$plug['plugin_path'].'/';

				if(file_exists($_path.'plugin.xml'))
				{
					unset($_POST['uninstall_confirm']);
					$text .= $plugin->install_plugin_xml($this->id, 'uninstall', $_POST); //$_POST must be used.
				}
				else
				{	// Deprecated - plugin uses plugin.php
					include(e_PLUGIN.$plug['plugin_path'].'/plugin.php');

					$func = $eplug_folder.'_uninstall';
					if (function_exists($func))
					{
						$text .= call_user_func($func);
					}

					if($_POST['delete_tables'])
					{
						if (is_array($eplug_table_names))
						{
							$result = $plugin->manage_tables('remove', $eplug_table_names);
							if ($result !== TRUE)
							{
								$text .= EPL_ADLAN_27.' <b>'.$mySQLprefix.$result.'</b> - '.EPL_ADLAN_30.'<br />';
							}
							else
							{
								$text .= EPL_ADLAN_28."<br />";
							}
						}
					}
					else
					{
						$text .= EPL_ADLAN_49."<br />";
					}

					if (is_array($eplug_prefs))
					{
						$plugin->manage_prefs('remove', $eplug_prefs);
						$text .= EPL_ADLAN_29."<br />";
					}

					if (is_array($eplug_comment_ids))
					{
						$text .= ($plugin->manage_comments('remove', $eplug_comment_ids)) ? EPL_ADLAN_50."<br />" : "";
					}

					if (is_array($eplug_array_pref))
					{
						foreach($eplug_array_pref as $key => $val)
						{
							$plugin->manage_plugin_prefs('remove', $key, $eplug_folder, $val);
						}
					}

					if ($eplug_menu_name)
					{
						$sql->db_Delete('menus', "menu_name='{$eplug_menu_name}' ");
					}

					if ($eplug_link)
					{
						$plugin->manage_link('remove', $eplug_link_url, $eplug_link_name);
					}

					if ($eplug_userclass)
					{
						$plugin->manage_userclass('remove', $eplug_userclass);
					}

					$sql->db_Update('plugin', "plugin_installflag=0, plugin_version='{$eplug_version}' WHERE plugin_id='{$this->id}' ");
					$plugin->manage_search('remove', $eplug_folder);

					$plugin->manage_notify('remove', $eplug_folder);
					
					// it's done inside install_plugin_xml(), required only here
					if (isset($pref['plug_installed'][$plug['plugin_path']]))
					{
						unset($pref['plug_installed'][$plug['plugin_path']]);
					}
					e107::getConfig('core')->setPref($pref);
					$plugin->rebuildUrlConfig();
					e107::getConfig('core')->save();
				}

				$admin_log->log_event('PLUGMAN_03', $plug['plugin_path'], E_LOG_INFORMATIVE, '');
			}

			if($_POST['delete_files'])
			{
				include_once(e_HANDLER.'file_class.php');
				$fi = new e_file;
				$result = $fi->rmtree(e_PLUGIN.$eplug_folder);
				$text .= ($result ? '<br />'.EPL_ADLAN_86.e_PLUGIN.$eplug_folder : '<br />'.EPL_ADLAN_87.'<br />'.EPL_ADLAN_31.' <b>'.e_PLUGIN.$eplug_folder.'</b> '.EPL_ADLAN_32);
			}
			else
			{
				$text .= '<br />'.EPL_ADLAN_31.' <b>'.e_PLUGIN.$eplug_folder.'</b> '.EPL_ADLAN_32;
			}

			$plugin->save_addon_prefs();

			$this->show_message($text, E_MESSAGE_SUCCESS);
		 //	$ns->tablerender(EPL_ADLAN_1.' '.$tp->toHtml($plug['plugin_name'], "", "defs,emotes_off,no_make_clickable"), $text);
			$text = '';
			$this->action = 'installed';
			return;

   }

   function pluginProcessUpload()
   {
			if (!$_POST['ac'] == md5(ADMINPWCHANGE))
			{
				exit;
			}

			extract($_FILES);
			/* check if e_PLUGIN dir is writable ... */
			if(!is_writable(e_PLUGIN))
			{
				/* still not writable - spawn error message */
				e107::getRender()->tablerender(EPL_ADLAN_40, EPL_ADLAN_39);
			}
			else
			{
				/* e_PLUGIN is writable - continue */
				require_once(e_HANDLER."upload_handler.php");
				$fileName = $file_userfile['name'][0];
				$fileSize = $file_userfile['size'][0];
				$fileType = $file_userfile['type'][0];

				if(strstr($file_userfile['type'][0], "gzip"))
				{
					$fileType = "tar";
				}
				else if (strstr($file_userfile['type'][0], "zip"))
				{
					$fileType = "zip";
				}
				else
				{
					/* not zip or tar - spawn error message */
					e107::getRender()->tablerender(EPL_ADLAN_40, EPL_ADLAN_41);
					require_once("footer.php");
					exit;
				}

				if ($fileSize)
				{
					$uploaded = file_upload(e_PLUGIN);
					$archiveName = $uploaded[0]['name'];

					/* attempt to unarchive ... */

					if($fileType == "zip")
					{
						require_once(e_HANDLER."pclzip.lib.php");
						$archive = new PclZip(e_PLUGIN.$archiveName);
						$unarc = ($fileList = $archive -> extract(PCLZIP_OPT_PATH, e_PLUGIN, PCLZIP_OPT_SET_CHMOD, 0666));
					}
					else
					{
						require_once(e_HANDLER."pcltar.lib.php");
						$unarc = ($fileList = PclTarExtract($archiveName, e_PLUGIN));
					}

					if(!$unarc)
					{
						/* unarc failed ... */
						if($fileType == "zip")
						{
							$error = EPL_ADLAN_46." '".$archive -> errorName(TRUE)."'";
						}
						else
						{
							$error = EPL_ADLAN_47.PclErrorString().", ".EPL_ADLAN_48.intval(PclErrorCode());
						}
						e107::getRender()->tablerender(EPL_ADLAN_40, EPL_ADLAN_42." ".$archiveName." ".$error);
						require_once("footer.php");
						exit;
					}

					/* ok it looks like the unarc succeeded - continue */

					/* get folder name ...  */
					$folderName = substr($fileList[0]['stored_filename'], 0, (strpos($fileList[0]['stored_filename'], "/")));

					if(file_exists(e_PLUGIN.$folderName."/plugin.php") || file_exists(e_PLUGIN.$folderName."/plugin.xml"))
					{
						/* upload is a plugin */
						e107::getRender()->tablerender(EPL_ADLAN_40, EPL_ADLAN_43);
					}
					elseif(file_exists(e_PLUGIN.$folderName."/theme.php") || file_exists(e_PLUGIN.$folderName."/theme.xml"))
					{
						/* upload is a menu */
						e107::getRender()->tablerender(EPL_ADLAN_40, EPL_ADLAN_45);
					}
					else
					{
						/* upload is unlocatable */
						e107::getRender()->tablerender(EPL_ADLAN_40, 'Unknown file: '.$fileList[0]['stored_filename']);
					}

					/* attempt to delete uploaded archive */
					@unlink(e_PLUGIN.$archiveName);
				}
			}
   }


// -----------------------------------------------------------------------------

   function pluginInstall()
   {
        global $plugin,$admin_log,$eplug_folder;
			$text = $plugin->install_plugin($this->id);
			if ($text === FALSE)
			{ // Tidy this up
				$this->show_message("Error messages above this line", E_MESSAGE_ERROR);
			}
			else
			{
				 $plugin ->save_addon_prefs();
				$admin_log->log_event('PLUGMAN_01', $this->id.':'.$eplug_folder, E_LOG_INFORMATIVE, '');
				$this->show_message($text, E_MESSAGE_SUCCESS);
			}

   }


// -----------------------------------------------------------------------------

	function pluginUpgrade()
	{
		$pref = e107::getPref();
		$admin_log = e107::getAdminLog();
		$plugin = e107::getPlugin();

	  	$sql = e107::getDb();

   		$emessage = eMessage::getInstance();

		$plug = $plugin->getinfo($this->id);

		$_path = e_PLUGIN.$plug['plugin_path'].'/';
		if(file_exists($_path.'plugin.xml'))
		{
			$plugin->install_plugin_xml($this->id, 'upgrade');
		}
		else
		{
			include(e_PLUGIN.$plug['plugin_path'].'/plugin.php');

			$func = $eplug_folder.'_upgrade';
			if (function_exists($func))
			{
				$text .= call_user_func($func);
			}

			if (is_array($upgrade_alter_tables))
			{
				$result = $plugin->manage_tables('upgrade', $upgrade_alter_tables);
				if (true !== $result)
				{
					//$text .= EPL_ADLAN_9.'<br />';
					$emessage->addWarning(EPL_ADLAN_9)
						->addDebug($result);
				}
				else
				{
					$text .= EPL_ADLAN_7."<br />";
				}
			}

			if (is_array($upgrade_add_prefs))
			{
				$plugin->manage_prefs('add', $upgrade_add_prefs);
				$text .= EPL_ADLAN_8.'<br />';
			}

			if (is_array($upgrade_remove_prefs))
			{
				$plugin->manage_prefs('remove', $upgrade_remove_prefs);
			}

			if (is_array($upgrade_add_array_pref))
			{
				foreach($upgrade_add_array_pref as $key => $val)
				{
					$plugin->manage_plugin_prefs('add', $key, $eplug_folder, $val);
				}
			}

			if (is_array($upgrade_remove_array_pref))
			{
				foreach($upgrade_remove_array_pref as $key => $val)
				{
					$plugin->manage_plugin_prefs('remove', $key, $eplug_folder, $val);
				}
			}

			$plugin->manage_search('upgrade', $eplug_folder);
			$plugin->manage_notify('upgrade', $eplug_folder);

			$eplug_addons = $plugin -> getAddons($eplug_folder);

			$admin_log->log_event('PLUGMAN_02', $eplug_folder, E_LOG_INFORMATIVE, '');
			$text .= (isset($eplug_upgrade_done)) ? '<br />'.$eplug_upgrade_done : "<br />".LAN_UPGRADE_SUCCESSFUL;
			$sql->db_Update('plugin', "plugin_version ='{$eplug_version}', plugin_addons='{$eplug_addons}' WHERE plugin_id='$this->id' ");
			$pref['plug_installed'][$plug['plugin_path']] = $eplug_version; 			// Update the version
			
			e107::getConfig('core')->setPref($pref);
			$plugin->rebuildUrlConfig();
			e107::getConfig('core')->save();
		}


		$emessage->add($text, E_MESSAGE_SUCCESS);
		$plugin->save_addon_prefs();

   }


// -----------------------------------------------------------------------------

   function pluginRefresh()
   {
       global $plug;

			$plug = $plugin->getinfo($this->id);

			$_path = e_PLUGIN.$plug['plugin_path'].'/';
			if(file_exists($_path.'plugin.xml'))
			{
				$text .= $plugin->install_plugin_xml($this->id, 'refresh');
				$admin_log->log_event('PLUGMAN_04', $this->id.':'.$plug['plugin_path'], E_LOG_INFORMATIVE, '');
			}

    }

// -----------------------------------------------------------------------------

		// Check for new plugins, create entry in plugin table ...
    function pluginCheck()
	{
		global $plugin;
		$plugin->update_plugins_table();
    }
		// ----------------------------------------------------------
		//        render plugin information ...


// -----------------------------------------------------------------------------


    function pluginUpload()
	{
         global $plugin;
		 $frm = e107::getForm();

		//TODO 'install' checkbox in plugin upload form. (as it is for theme upload)

		/* plugin upload form */

			if(!is_writable(e_PLUGIN))
			{
			   	e107::getRender()->tablerender(EPL_ADLAN_40, EPL_ADLAN_44);
			}
			else
			{
			  // Get largest allowable file upload
			  require_once(e_HANDLER.'upload_handler.php');
			  $max_file_size = get_user_max_upload();

			  $text = "
				<form enctype='multipart/form-data' method='post' action='".e_SELF."'>
                <table class='adminform'>
                	<colgroup>
                		<col class='col-label' />
                		<col class='col-control' />
                	</colgroup>
				<tr>
				<td>".EPL_ADLAN_37."</td>
				<td>
				<input type='hidden' name='MAX_FILE_SIZE' value='{$max_file_size}' />
				<input type='hidden' name='ac' value='".md5(ADMINPWCHANGE)."' />
				<input class='tbox' type='file' name='file_userfile[]' size='50' />
				</td>
                </tr>
				</table>

				<div class='center buttons-bar'>";
                $text .= $frm->admin_button('upload', EPL_ADLAN_38, 'submit', EPL_ADLAN_38);

				$text .= "
				</div>

				</form>\n";
			}

         e107::getRender()->tablerender(ADLAN_98." :: ".EPL_ADLAN_38, $text);
	}

// -----------------------------------------------------------------------------

	function pluginRenderList() // Uninstall and Install sorting should be fixed once and for all now !
	{

		global $plugin;
		$frm = e107::getForm();
		$e107 = e107::getInstance();

		if($this->action == "" || $this->action == "installed")
		{
			$installed = $plugin->getall(1);
			$caption = EPL_ADLAN_22;
			$pluginRenderPlugin = $this->pluginRenderPlugin($installed);
			$button_mode = "uninstall-selected";
			$button_caption = EPL_ADLAN_85;
			$button_action = "delete";
		}
		if($this->action == "avail")
		{
			$uninstalled = $plugin->getall(0);
			$caption = EPL_ADLAN_23;
			$pluginRenderPlugin = $this->pluginRenderPlugin($uninstalled);
			$button_mode = "install-selected";
			$button_caption = EPL_ADLAN_84;
			$button_action = "update";
		}

		$text = "
			<form action='".e_SELF."?".e_QUERY."' id='core-plugin-list-form' method='post'>
				<fieldset id='core-plugin-list'>
					<legend class='e-hideme'>".$caption."</legend>
					<table class='adminlist'>
						".$frm->colGroup($this->fields,$this->fieldpref).
						$frm->thead($this->fields,$this->fieldpref)."
						<tbody>
		";

		if($pluginRenderPlugin)
		{
			$text .= $pluginRenderPlugin;
		}
		else
		{
			//TODO LANs
			$text .= "<tr><td class='center' colspan='".count($this->fields)."'>No plugins installed - <a href='".e_ADMIN."plugin.php?avail'>click here to install some</a>.</td></tr>";
		}

		$text .= "
						</tbody>
					</table>";

		if($this->action == "avail")
		{
			$text .= "
					<div class='buttons-bar left'>".$frm->admin_button($button_mode, $button_caption, $button_action)."</div>";
		}
		$text .= "
				</fieldset>
			</form>
		";

		$emessage = &eMessage::getInstance();
		e107::getRender()->tablerender(ADLAN_98." :: ".$caption, $emessage->render(). $text);
	}


// -----------------------------------------------------------------------------

	function pluginRenderPlugin($pluginList)
	{
			global $plugin; 
			
			if (empty($pluginList)) return '';

			$tp = e107::getParser();
			$frm = e107::getForm();

			$text = "";

			foreach($pluginList as $plug)
			{
				e107::loadLanFiles($plug['plugin_path'],'admin');

				$_path = e_PLUGIN.$plug['plugin_path'].'/';

				$plug_vars = false;
				$plugin_config_icon = "";

				if($plugin->parse_plugin($plug['plugin_path']))
				{
					$plug_vars = $plugin->plug_vars;
				}

				if(varset($plug['plugin_category']) == "menu") // Hide "Menu Only" plugins.
				{
					continue;
				}

				if($plug_vars)
				{

					$icon_src = (isset($plug_vars['plugin_php']) ? e_PLUGIN : $_path).$plug_vars['administration']['icon'];
					$plugin_icon = $plug_vars['administration']['icon'] ? "<img src='{$icon_src}' alt='' class='icon S32' />" : E_32_CAT_PLUG;
                    $conf_file = "#";
					$conf_title = "";

					if ($plug_vars['administration']['configFile'] && $plug['plugin_installflag'] == true)
					{
						$conf_file = e_PLUGIN.$plug['plugin_path'].'/'.$plug_vars['administration']['configFile'];
						$conf_title = LAN_CONFIGURE.' '.$tp->toHtml($plug_vars['@attributes']['name'], "", "defs,emotes_off, no_make_clickable");
						$plugin_icon = "<a title='{$conf_title}' href='{$conf_file}' >".$plugin_icon."</a>";
						$plugin_config_icon = "<a title='{$conf_title}' href='{$conf_file}' >".ADMIN_CONFIGURE_ICON."</a>";
					}

					$plugEmail = varset($plug_vars['author']['@attributes']['email'],'');
					$plugAuthor = varset($plug_vars['author']['@attributes']['name'],'');
					$plugURL = varset($plug_vars['author']['@attributes']['url'],'');
					$plugDate	= varset($plug_vars['@attributes']['date'],'');
					
					$description = varset($plug_vars['description']['@attributes']['lang']) ? $tp->toHTML($plug_vars['description']['@attributes']['lang'], false, "defs,emotes_off, no_make_clickable") : $tp->toHTML($plug_vars['description']['@value'], false, "emotes_off, no_make_clickable") ;
					
                    $plugReadme = "";
					if(varset($plug['plugin_installflag']))
					{
						$plugName = "<a title='{$conf_title}' href='{$conf_file}' >".$tp->toHTML($plug['plugin_name'], false, "defs,emotes_off, no_make_clickable")."</a>";
                    }
                    else
					{
                    	$plugName = $tp->toHTML($plug['plugin_name'], false, "defs,emotes_off, no_make_clickable");
					}
					if(varset($plug_vars['readme']))   // 0.7 plugin.php
					{
                    	$plugReadme = $plug_vars['readme'];
					}
					if(varset($plug_vars['readMe'])) // 0.8 plugin.xml
					{
                    	$plugReadme = $plug_vars['readMe'];
					}

					$text .= "<tr>";

					if(varset($this-> fields['plugin_checkboxes']))
					{
                 		$rowid = "plugin_checkbox[".$plug['plugin_id']."]";
                		$text .= "<td class='center middle'>".$frm->checkbox($rowid, $plug['plugin_id'])."</td>\n";
					}

				//	$text .= (in_array("plugin_status",$this->fieldpref)) ? "<td class='center'>".$img."</td>" : "";
                    $text .= (in_array("plugin_icon",$this->fieldpref)) ? "<td class='center middle'>".$plugin_icon."</td>" : "";
                    $text .= (in_array("plugin_name",$this->fieldpref)) ? "<td class='middle'>".$plugName."</td>" : "";
                    $text .= (in_array("plugin_version",$this->fieldpref)) ? "<td class='middle'>".$plug['plugin_version']."</td>" : "";
					$text .= (in_array("plugin_date",$this->fieldpref)) ? "<td class='middle'>".$plugDate."</td>" : "";
					
					$text .= (in_array("plugin_folder",$this->fieldpref)) ? "<td class='middle'>".$plug['plugin_path']."</td>" : "";
					$text .= (in_array("plugin_category",$this->fieldpref)) ? "<td class='middle'>".$plug['plugin_category']."</td>" : "";
                    $text .= (in_array("plugin_author",$this->fieldpref)) ? "<td class='middle'><a href='mailto:".$plugEmail."' title='".$plugEmail."'>".$plugAuthor."</a>&nbsp;</td>" : "";
                    $text .= (in_array("plugin_website",$this->fieldpref)) ? "<td class='center middle'>".($plugURL ? "<a href='{$plugURL}' title='{$plugURL}' >".ADMIN_URL_ICON."</a>" : "")."</td>" : "";
                    $text .= (in_array("plugin_notes",$this->fieldpref)) ? "<td class='center middle'>".($plugReadme ? "<a href='".e_PLUGIN.$plug['plugin_path']."/".$plugReadme."' title='".$plugReadme."'>".ADMIN_INFO_ICON."</a>" : "&nbsp;")."</td>" : "";
					$text .= (in_array("plugin_description",$this->fieldpref)) ? "<td class='middle'>".$description."</td>" : "";
                    $text .= (in_array("plugin_compatible",$this->fieldpref)) ? "<td class='center middle'>".varset($plug_vars['@attributes']['compatibility'],'')."</td>" : "";
					$text .= (in_array("plugin_compliant",$this->fieldpref)) ? "<td class='center middle'>".((varset($plug_vars['compliant']) || varsettrue($plug_vars['@attributes']['xhtmlcompliant'])) ? ADMIN_TRUE_ICON : "&nbsp;")."</td>" : "";


                	// Plugin options Column --------------

   					$text .= "<td class='center middle'>".$plugin_config_icon;


						if ($plug_vars['@attributes']['installRequired'])
						{
							if ($plug['plugin_installflag'])
							{
						  		$text .= ($plug['plugin_installflag'] ? "<a href=\"".e_SELF."?uninstall.{$plug['plugin_id']}\" title='".EPL_ADLAN_1."'  >".ADMIN_UNINSTALLPLUGIN_ICON."</a>" : "<a href=\"".e_SELF."?install.{$plug['plugin_id']}\" title='".EPL_ADLAN_0."' >".ADMIN_INSTALLPLUGIN_ICON."</a>");

                             //   $text .= ($plug['plugin_installflag'] ? "<button type='button' class='delete' value='no-value' onclick=\"location.href='".e_SELF."?uninstall.{$plug['plugin_id']}'\"><span>".EPL_ADLAN_1."</span></button>" : "<button type='button' class='update' value='no-value' onclick=\"location.href='".e_SELF."?install.{$plug['plugin_id']}'\"><span>".EPL_ADLAN_0."</span></button>");
								if (PLUGIN_SHOW_REFRESH && !varsettrue($plug_vars['plugin_php']))
								{
									$text .= "<br /><br /><input type='button' class='button' onclick=\"location.href='".e_SELF."?refresh.{$plug['plugin_id']}'\" title='".'Refresh plugin settings'."' value='".'Refresh plugin settings'."' /> ";
								}
							}
							else
							{
							  //	$text .=  "<input type='button' class='button' onclick=\"location.href='".e_SELF."?install.{$plug['plugin_id']}'\" title='".EPL_ADLAN_0."' value='".EPL_ADLAN_0."' />";
							  //	$text .= "<button type='button' class='update' value='no-value' onclick=\"location.href='".e_SELF."?install.{$plug['plugin_id']}'\"><span>".EPL_ADLAN_0."</span></button>";
                            	$text .= "<a href=\"".e_SELF."?install.{$plug['plugin_id']}\" title='".EPL_ADLAN_0."' >".ADMIN_INSTALLPLUGIN_ICON."</a>";
							}
						}
						else
						{
							if ($plug_vars['menuName'])
							{
								$text .= EPL_NOINSTALL.str_replace("..", "", e_PLUGIN.$plug['plugin_path'])."/ ".EPL_DIRECTORY;
							}
							else
							{
								$text .= EPL_NOINSTALL_1.str_replace("..", "", e_PLUGIN.$plug['plugin_path'])."/ ".EPL_DIRECTORY;
								if($plug['plugin_installflag'] == false)
								{					
									e107::getDb()->db_Delete('plugin', "plugin_installflag=0 AND (plugin_path='{$plug['plugin_path']}' OR plugin_path='{$plug['plugin_path']}/' )  ");
								}
							}
						}

						if ($plug['plugin_version'] != $plug_vars['@attributes']['version'] && $plug['plugin_installflag'])
						{
						  //	$text .= "<br /><input type='button' class='button' onclick=\"location.href='".e_SELF."?upgrade.{$plug['plugin_id']}'\" title='".EPL_UPGRADE." to v".$plug_vars['@attributes']['version']."' value='".EPL_UPGRADE."' />";
							$text .= "<a href='".e_SELF."?upgrade.{$plug['plugin_id']}' title=\"".EPL_UPGRADE." to v".$plug_vars['@attributes']['version']."\" >".ADMIN_UPGRADEPLUGIN_ICON."</a>";
						}

					$text .="</td>";
                    $text .= "</tr>";

				}
			}
			return $text;
	}


// -----------------------------------------------------------------------------



		function pluginConfirmUninstall()
		{
			global $plugin;

			$frm 	= e107::getForm();
			$tp 	= e107::getParser();
			$mes 	= e107::getMessage();

			$plug = $plugin->getinfo($this->id);

			if ($plug['plugin_installflag'] == true )
			{
				if($plugin->parse_plugin($plug['plugin_path']))
				{
					$plug_vars = $plugin->plug_vars;
				}
				else
				{
					return FALSE;
				}
			}
			else
			{
				return FALSE;
			}
			$userclasses = '';
			$eufields = '';
			if (isset($plug_vars['userClasses']))
			{
				if (isset($plug_vars['userclass']['@attributes']))
				{
					$plug_vars['userclass'][0]['@attributes'] = $plug_vars['userclass']['@attributes'];
					unset($plug_vars['userclass']['@attributes']);
				}
				$spacer = '';
				foreach ($plug_vars['userClasses']['class'] as $uc)
				{
					$userclasses .= $spacer.$uc['@attributes']['name'].' - '.$uc['@attributes']['description'];
					$spacer = '<br />';
				}
			}
			if (isset($plug_vars['extendedFields']))
			{
				if (isset($plug_vars['extendedFields']['@attributes']))
				{
					$plug_vars['extendedField'][0]['@attributes'] = $plug_vars['extendedField']['@attributes'];
					unset($plug_vars['extendedField']['@attributes']);
				}
				$spacer = '';
				foreach ($plug_vars['extendedFields']['field'] as $eu)
				{
					$eufields .= $spacer.'plugin_'.$plug_vars['folder'].'_'.$eu['@attributes']['name'];
					$spacer = '<br />';
				}
			}

			if(is_writable(e_PLUGIN.$plug['plugin_path']))
			{
				$del_text = $frm->selectbox('delete_files','yesno',0);
			}
			else
			{
				$del_text = "
				".EPL_ADLAN_53."
				<input type='hidden' name='delete_files' value='0' />
				";
			}

			$text = "
			<form action='".e_SELF."?".e_QUERY."' method='post'>
			<fieldset id='core-plugin-confirmUninstall'>
			<legend>".EPL_ADLAN_54." ".$tp->toHtml($plug_vars['@attributes']['name'], "", "defs,emotes_off, no_make_clickable")."</legend>
            <table class='adminform'>
            	<colgroup>
            		<col class='col-label' />
            		<col class='col-control' />
            	</colgroup>
 			<tr>
				<td>".EPL_ADLAN_55."</td>
				<td>".LAN_YES."</td>
			</tr>";

			$opts = array();

			$opts['delete_tables'] = array(
					'label'			=> EPL_ADLAN_57,
					'helpText'		=> EPL_ADLAN_58,
					'itemList'		=> array(1=>LAN_YES,0=>LAN_NO),
					'itemDefault' 	=> 1
			);

			if ($userclasses)
			{
				$opts['delete_userclasses'] = array(
					'label'			=> EPL_ADLAN_78,
					'preview'		=> $userclasses,
					'helpText'		=> EPL_ADLAN_79,
					'itemList'		=> array(1=>LAN_YES,0=>LAN_NO),
					'itemDefault' 	=> 1
				);
			}

			if ($eufields)
			{
				$opts['delete_xfields'] = array(
					'label'			=> EPL_ADLAN_80,
					'preview'		=> $eufields,
					'helpText'		=> EPL_ADLAN_79,
					'itemList'		=> array(1=>LAN_YES,0=>LAN_NO),
					'itemDefault' 	=> 0
				);
			}

			$med = e107::getMedia();
			$icons = $med->listIcons(e_PLUGIN.$plug['plugin_path']);

			if(count($icons)>0)
			{
				foreach($icons as $key=>$val)
				{
					$iconText .= "<img src='".$tp->replaceConstants($val)."' alt='' />";
				}

				$opts['delete_ipool'] = array(
					'label'			=>'Remove icons from Media-Manager',
					'preview'		=> $iconText,
					'helpText'		=> EPL_ADLAN_79,
					'itemList'		=> array(1=>LAN_YES,0=>LAN_NO),
					'itemDefault' 	=> 1
				);
			}

			if(is_readable(e_PLUGIN.$plug['plugin_path']."/".$plug['plugin_path']."_setup.php"))
			{
				include_once(e_PLUGIN.$plug['plugin_path']."/".$plug['plugin_path']."_setup.php");


				$mes->add("Loading ".e_PLUGIN.$plug['plugin_path']."/".$plug['plugin_path']."_setup.php", E_MESSAGE_DEBUG);

				$class_name = $plug['plugin_path']."_setup";

				if(class_exists($class_name))
				{
					$obj = new $class_name;
					if(method_exists($obj,'uninstall_options'))
					{
						$arr = call_user_func(array($obj,'uninstall_options'), $this);
						foreach($arr as $key=>$val)
						{
							$newkey = $plug['plugin_path']."_".$key;
							$opts[$newkey] = $val;
						}
					}
				}
			}

			foreach($opts as $key=>$val)
			{
				$text .= "<tr>\n<td class='top'>".$tp->toHTML($val['label'],FALSE,'TITLE');
				$text .= varset($val['preview']) ? "<div class='indent'>".$val['preview']."</div>" : "";
				$text .= "</td>\n<td>".$frm->selectbox($key,$val['itemList'],$val['itemDefault']);
				$text .= varset($val['helpText']) ? "<div class='field-help'>".$val['helpText']."</div>" : "";
				$text .= "</td>\n</tr>\n";
			}


			$text .="<tr>
			<td>".EPL_ADLAN_59."</td>
			<td>{$del_text}
			<div class='field-help'>".EPL_ADLAN_60."</div>
			</td>
			</tr>
			</table>
			<div class='buttons-bar center'>";
			
			$text .= $frm->admin_button('uninstall_confirm',EPL_ADLAN_3,'submit');
			$text .= $frm->admin_button('uninstall_cancel',EPL_ADLAN_62,'cancel');

			/*
			$text .= "<input class='button' type='submit' name='uninstall_confirm' value=\"".EPL_ADLAN_3."\" />&nbsp;&nbsp;
			<input class='button' type='submit' name='uninstall_cancel' value='".EPL_ADLAN_62."' onclick=\"location.href='".e_SELF."'; return false;\"/>";
			*/
             //   $frm->admin_button($name, $value, $action = 'submit', $label = '', $options = array());

			$text .= "</div>
			</fieldset>
			</form>
			";
			e107::getRender()->tablerender(EPL_ADLAN_63." ".$tp->toHtml($plug_vars['@attributes']['name'], "", "defs,emotes_off, no_make_clickable"),$mes->render(). $text);

		}

        function show_message($message, $type = E_MESSAGE_INFO, $session = false)
		{
		// ##### Display comfort ---------
			$emessage = &eMessage::getInstance();
			$emessage->add($message, $type, $session);
		}

        function pluginMenuOptions()
		{
		   //	$e107 = &e107::getInstance();

				$var['installed']['text'] = EPL_ADLAN_22;
				$var['installed']['link'] = e_SELF;

				$var['avail']['text'] = EPL_ADLAN_23;
				$var['avail']['link'] = e_SELF."?avail";

			//	$var['upload']['text'] = EPL_ADLAN_38;
			//	$var['upload']['link'] = e_SELF."?upload";
				
				$var['online']['text'] = "Search";
				$var['online']['link'] = e_SELF."?mode=online";

				$keys = array_keys($var);

				$action = (in_array($this->action,$keys)) ? $this->action : "installed";

				e_admin_menu(ADLAN_98, $action, $var);
		}



} // end of Class.



function plugin_adminmenu()
{
	global $pman;
	$pman -> pluginMenuOptions();
}

?>