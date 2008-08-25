<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     �Steve Dunstan 2001-2002
|     http://e107.org
|     jalist@e107.org
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $Source: /cvs_backup/e107_0.8/e107_handlers/xml_class.php,v $
|     $Revision: 1.8 $
|     $Date: 2008-08-25 10:46:33 $
|     $Author: e107steved $
+----------------------------------------------------------------------------+
*/

class xmlClass
{
	var $xmlFileContents;
	var $filter;					// Optional filter for loaded XML
									// Set to FALSE if not enabled (default position)
									// Otherwise mirrors the required subset of the loaded XML - set a field FALSE to accept all 
									// ...elements lower down the tree. e.g.:
									// $filter = array(
									//		'name' => FALSE,
									//		'administration' => FALSE,
									//		'management' => array('install' => FALSE)
									//		);


	// Constructor - set defaults
	function xmlClass()
	{
		$this->xmlFileContents = '';
		$this->filter = FALSE;
	}

	function getRemoteFile($address, $timeout=10)
	{
	  // Could do something like: if ($timeout <= 0) $timeout = $pref['get_remote_timeout'];  here
	  $timeout = min($timeout,120);
	  $timeout = max($timeout,3);

	  $address = str_replace(array("\r","\n", "\t"),'',$address);			// May be paranoia, but streaky thought it might be a good idea
																			// ... and there shouldn't be unprintable characters in the URL anyway
	  
		if(function_exists('file_get_contents'))
		{
		  $old_timeout = e107_ini_set('default_socket_timeout', $timeout);
		  $data = file_get_contents(urlencode($address));
		  e107_ini_set('default_socket_timeout', $old_timeout);
		  if ($data)
		  {
			return $data;
		  }
		}

		if(function_exists("curl_init"))
		{
			$cu = curl_init ();
			curl_setopt($cu, CURLOPT_URL, $address);
			curl_setopt($cu, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($cu, CURLOPT_HEADER, 0);
			curl_setopt ($cu, CURLOPT_TIMEOUT, $timeout);
			$this->xmlFileContents = curl_exec($cu);
			if (curl_error($cu))
			{
				$this -> error =  "Error: ".curl_errno($cu).", ".curl_error($cu);
				return FALSE;
			}
			curl_close ($cu);
			return $this->xmlFileContents;
		}

		if(ini_get("allow_url_fopen"))
		{
		  $old_timeout = e107_ini_set('default_socket_timeout', $timeout);
		  $remote = @fopen ($address, "r");
		  e107_ini_set('default_socket_timeout', $old_timeout);
		  if(!$remote)
		  {
			$this -> error = "Unable to open remote XML file.";
			return FALSE;
		  }
		}
		else
		{
		  $tmp = parse_url($address);
		  if(!$remote = fsockopen ($tmp['host'], 80 ,$errno, $errstr, $timeout))
		  {
			$this -> error = "Unable to open remote XML file.";
			return FALSE;
		  }
		  else
		  {
			socket_set_timeout($remote, $timeout);
			fputs($remote, "GET ".urlencode($address)." HTTP/1.0\r\n\r\n");
		  }
		}

		$this -> xmlFileContents = "";
		while (!feof($remote))
		{
			$this->xmlFileContents .= fgets ($remote, 4096);
		}
		fclose ($remote);
		return $this->xmlFileContents;
	}



	function parseXml($xml='')
	{
		if($xml == '' && $this->xmlFileContents)
		{
			$xml = $this->xmlFileContents;
		}
		if(!$xml)
		{
			return false;
		}

		$xml = simplexml_load_string($xml);
		if(is_object($xml))
		{
			$xml = (array)$xml;
		}
		$xml = $this->xml_convert_to_array($xml, $this->filter);
		return $xml;
	}

	function xml_convert_to_array($xml, $localFilter = FALSE)
	{
		if(is_array($xml))
		{
			foreach($xml as $k => $v)
			{
				$enabled = FALSE;
				if ($localFilter === FALSE)
				{
					$enabled = TRUE;
					$onFilter = FALSE;
				}
				elseif (isset($localFilter[$k]))
				{
					$enabled = TRUE;
					$onFilter = $localFilter[$k];
				}
				if ($enabled)
				{
					if(is_object($v))
					{
						$v = (array)$v;
					}
					$xml[$k] = $this->xml_convert_to_array($v, $onFilter);
				}
				else
				{
					unset($xml[$k]);
				}
			}
			if(count($xml) == 1 && isset($xml[0]))
			{
				$xml = $xml[0];
			}
		}
		return $xml;
	}



	function loadXMLfile($fname='', $parse = false, $replace_constants = false)
	{
		if($fname == '')
		{
			return false;
		}
		$xml = false;

		if(strpos($filename, '://') !== false)
		{
			$this->getRemoteFile($fname);
		}
		else
		{
			if($xml = file_get_contents($fname))
			{
				$this->xmlFileContents = $xml;
			}
		}
		if($this->xmlFileContents)
		{
			if($replace_constants == true)
			{
				global $tp;
				if(!is_object($tp))
				{
					require_once('e_parse_class.php');
					$tp = new e_parse;
				}
				$this->xmlFileContents = $tp->replaceConstants($this->xmlFileContents, '', true);
			}
			if($parse == true)
			{
				return $this->parseXML('');
			}
			else
			{
				return $this->xmlFileContents;
			}
		}
		return false;
	}


}
?>