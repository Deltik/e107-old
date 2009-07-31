<?php
/*
 * e107 website system
 *
 * Copyright (C) 2001-2008 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * e107 Preference Handler
 *
 * $Source: /cvs_backup/e107_0.8/e107_handlers/pref_class.php,v $
 * $Revision: 1.4 $
 * $Date: 2009-07-31 16:12:19 $
 * $Author: secretr $
*/

if (!defined('e107_INIT')) { exit; }

/**
 * Base preference object - shouldn't be used direct,
 * used internal by {@link e_plugin_pref} and {@link e_core_pref classes}
 *
 * @package e107
 * @category e107_handlers
 * @version 1.0
 * @author SecretR
 * @copyright Copyright (c) 2009, e107 Inc.
 */
class e_pref extends e_model 
{
	/**
	 * Preference ID - DB row value
	 *
	 * @var string
	 */
	protected $prefid;
	
	/**
	 * Preference ID alias e.g. 'core' is an alias of prefid 'SitePrefs'
	 * 
	 * @var string
	 */
	protected $alias;
	
	/**
	 * Set on first data load
	 *
	 * @var string
	 */
	protected $pref_cache = '';
	
	/**
	 * Backward compatibility - serialized preferences
	 * Note: serialized preference storage is deprecated
	 *
	 * @var boolean
	 */
	protected $serial_bc = false;
	
	/**
	 * If true, $prefid.'_Backup' row will be created/updated
	 * on every {@link save()} call
	 *
	 * @var boolean
	 */
	protected $set_backup = false;
	
	/**
	 * Constructor
	 *
	 * @param string $prefid
	 * @param string $alias
	 * @param array $data
	 */
	function __construct($prefid, $alias = '', $data = array())
	{
		$this->prefid = $prefid;
		if(empty($alias))
		{
			$alias = $prefid;
		}
		$this->alias = $alias;
		parent::__construct($data);
		if($data) $this->setStructure();
	}
	
	/**
	 * Advanced getter - $pref_name is parsed (multidimensional arrays support), alias of {@link e_model::getData()}
	 *
	 * @param string $pref_name
	 * @param mixed $default
	 * @param integer $index
	 * @return mixed
	 */
	public function getPref($pref_name, $default = null, $index = null)
	{
		return $this->getData($pref_name, $default, $index);
	}
	
	/**
	 * Simple getter - $pref_name is not parsed (no multidimensional arrays support), alias of {@link e_model::get()}
	 *
	 * @param string $pref_name
	 * @param mixed $default
	 * @return mixed
	 */
	public function get($pref_name, $default = null)
	{
		return parent::get($pref_name, $default);
	}
	
	/**
	 * Advanced setter - $pref_name is parsed (multidimensional arrays support), alias of {@link setData()}
	 * Object data reseting is not allowed, adding new pref is not allowed
	 * @param string|array $key
	 * @param mixed $value
	 * @return e_pref
	 */
	public function setPref($pref_name, $value)
	{
		return $this->setData($pref_name, $value, true);
	}
	
	/**
	 * Simple setter - $pref_name is  not parsed (no multidimensional arrays support)
	 * Adding new pref is not allowed
	 * 
	 * @param string|array $key
	 * @param mixed $value
	 * @return e_pref
	 */
	public function set($pref_name, $value)
	{
		if(empty($pref_name) || false !== strpos($pref_name, '/') || !$this->isData($pref_name))
		{
			return $this;
		}
		return $this->setData($pref_name, $value, true);
	}
	
	/**
	 * Add new (single) preference (ONLY if doesn't exist)
	 * 
	 * @see addData()
	 * @param string $pref_name
	 * @param mixed $value
	 * @return e_pref
	 */
	public function add($pref_name, $value = null)
	{	
		if(!is_string($pref_name) || strpos($pref_name, '/'))
		{
			return $this;
		}
		
		$this->addData($pref_name, $value, false);
		return $this;
	}
	
	/**
	 * Add new preference or preference array (ONLY if it/they doesn't exist)
	 * 
	 * @see addData()
	 * @param string|array $pref_name
	 * @param mixed $value
	 * @return e_pref
	 */
	public function addPref($pref_name, $value = null)
	{	
		return $this->addData($pref_name, $value, false);
	}
	
	/**
	 * Remove single preference
	 * 
	 * @see e_model::remove()
	 * @param string $pref_name
	 * @return e_pref
	 */
	public function remove($pref_name)
	{
		parent::remove($pref_name);
		
		$struct = $this->getStructure();
		unset($struct[$pref_name]);
		$this->setStructure($struct);
		
		return $this;
	}
	
	/**
	 * Remove single preference (parse $pref_name)
	 * 
	 * @see removeData()
	 * @param string $pref_name
	 * @return e_pref
	 */
	public function removePref($pref_name)
	{
		$this->removeData($pref_name);		
		return $this;
	}
	
	/**
	 * Disallow public use of addData()
	 * Disallow preference override
	 *
	 * @param string|array $pref_name
	 * @param boolean $strict
	 */
	final public function addData($pref_name, $value = null, $strict = false)
	{
		//XXX - Move most of this to the base class?
		if(is_array($pref_name))
		{
			parent::addData($pref_name, $strict, false);
			$this->setStructure($this->getStructure() + array_keys($pref_name));
			return $this;
		}
		
		if(!$this->isData($pref_name))
		{
			$pref_name = trim($pref_name, '/');
			parent::setData($pref_name, $value, $strict);
			if(!strpos($pref_name, '/'))
			{
				$this->setStructure($this->getStructure() + array($pref_name));
			}
		}
		
		return $this;
	}
	
	/**
	 * Disallow public use of setData()
	 * Object data reseting is not allowed
	 * Adding new preference key is not allowed
	 * 
	 * @see e_model::setData()
	 * @param string|array $key
	 * @param mixed $value
	 * @return e_pref
	 */
	final public function setData($pref_name, $value, $strict)
	{
		//object reset not allowed, adding new pref is not allowed
		if(empty($pref_name) || !$this->isData($pref_name))
		{
			return $this; 
		}
		parent::setData($pref_name, $value, $strict);
		
		return $this;
	}
	
	/**
	 * Disallow public use of removeData()
	 * Object data reseting is not allowed
	 *
	 * @return e_pref
	 */
	final public function removeData($pref_name)
	{
		if(is_null($pref_name))
		{
			return $this; //object reseting not allowed
		}
		parent::removeData($pref_name);
		
		$pref_name = trim($pref_name, '/');
		if(!strpos($pref_name, '/'))
		{
			$struct = $this->getStructure();
			unset($struct[$pref_name]);
			$this->setStructure($struct);
		}
		return $this;
	}
	
	/**
	 * Load object data - public
	 *
	 * @see _load()
	 * @param boolean $force
	 * @return e_pref
	 */
	public function load($force = false)
	{
		if($force || !$this->hasData())
		{
			$this->data_has_changed = false;
			$this->_load($force);
		}
		
		return $this;
	}
	
	/**
	 * Load object data
	 *
	 * @param boolean $force
	 * @return e_pref
	 */
	protected function _load($force = false)
	{
		$id = e107::getParser()->toDB($this->prefid);
		$data = $force ? false : $this->getPrefCache(true); 
		
		if(false !== $data)
		{
			$this->pref_cache = e107::getArrayStorage()->WriteArray($data, false); //runtime cache
			return $this->setData($data);
		}
		
		if ($this->getDb()->db_Select('core', 'e107_value', "e107_name='{$id}'"))
		{
			$row = $this->getDb()->db_Fetch();

			if($this->serial_bc)
			{
				$data = unserialize($row['e107_value']);
				$row['e107_value'] = e107::getArrayStorage()->WriteArray($row['e107_value'], false);
			}
			else 
			{
				$data = e107::getArrayStorage()->ReadArray($row['e107_value']);
			}
			
			$this->pref_cache = $row['e107_value']; //runtime cache
			$this->setPrefCache($row['e107_value']);
		}

		if(empty($data)) $data = array();
		
		return $this->setData($data);
	}
	
	/**
	 * Save object data to DB
	 *
	 * @param boolean $from_post merge post data
	 * @param boolean $session_messages use session messages
	 * @return unknown
	 */
	public function save($from_post = true, $session_messages = false)
	{
		if(!$this->prefid)
		{
			return $this;
		}
		
		if($from_post)
		{
			$this->setStructure(); //set structure from current data
			$this->mergePostedData(); //all posted data is sanitized and filtered vs structure array
		}
		
		//TODO - LAN
		require_once(e_HANDLER.'message_handler.php');
		$emessage = eMessage::getInstance();
		
		//Save to DB
		if($this->data_has_changed && !$this->isError())
		{
			if(true === $this->set_backup)
			{
				if(e107::getDb()->db_Select_gen("REPLACE INTO `#core` (e107_name,e107_value) values ('{$this->prefid}_Backup', '".addslashes($this->pref_cache)."') "))
				{
					$emessage->add('Backup successfully created.', E_MESSAGE_SUCCESS, $session_messages);
				}
			}
			if(e107::getDb()->db_Select_gen("REPLACE INTO `#core` (e107_name,e107_value) values ('{$this->prefid}', '".$this->toString(true)."') "))
			{
				$this->data_has_changed = false; //reset status
				$this->pref_cache = $this->toString(false); //reset pref cache
				$this->setPrefCache($this->pref_cache); //reset pref cache file
				$emessage->add('Settings successfully saved.', E_MESSAGE_SUCCESS, $session_messages);
			}
		}
		
		if($this->isError())
		{
			$this->setErrors(true, $session_messages); //add errors to the eMessage stack
			$emessage->add('Settings not saved.', E_MESSAGE_ERROR, $session_messages);
		}
		else 
		{
			$emessage->add('Settings not saved as no changes were made.', E_MESSAGE_INFO, $session_messages);
		}
		
		return $this;
	}
	
	/**
	 * Get cached data from server cache file
	 *
	 * @param boolean $toArray convert to array
	 * @return string|array|false
	 */
	protected function getPrefCache($toArray = true)
	{
		if(!$this->pref_cache)
		{
			$this->pref_cache = ecache::retrieve_sys('Config_'.$this->alias, 24 * 60, true);
		}
		return ($toArray && $this->pref_cache ? e107::getArrayStorage()->ReadArray($this->pref_cache) : $this->pref_cache);
	}
	
	/**
	 * Store data to a server cache file
	 * If $cache_string is an array, it'll be converted to a string
	 *
	 * @param string|array $cache_string
	 * @return e_pref
	 */
	protected function setPrefCache($cache_string)
	{
		if(is_array($cache_string))
		{
			$cache_string = e107::getArrayStorage()->WriteArray($cache_string, false);
		}
		ecache::set_sys('Config_'.$this->alias, $cache_string, true);
		return $this;
	}
	
	/**
	 * Set $set_backup option
	 *
	 * @param boolean $optval
	 * @return e_pref
	 * 
	 */
	public function setOptionBackup($optval)
	{
		$this->set_backup = $optval;
		return $this;
	}
	
	/**
	 * Set $serial_bc option
	 *
	 * @param boolean $optval
	 * @return e_pref
	 * 
	 */
	public function setOptionSerialize($optval)
	{
		$this->serial_bc = $optval;
		return $this;
	}
	
}

/**
 * Handle core preferences
 * 
 * @package e107
 * @category e107_handlers
 * @version 1.0
 * @author SecretR
 * @copyright Copyright (c) 2009, e107 Inc.
 */
final class e_core_pref extends e_pref 
{	
	/**
	 * Allowed core id array
	 *
	 * @var array
	 */
	protected $alliases = array(
		'core' 			=> 'SitePrefs', 
		'core_backup' 	=> 'SitePrefs_Backup', 
		'emote' 		=> 'emote', 
		'menu' 			=> 'menu_pref', 
		'search' 		=> 'search_prefs', 
		'notify' 		=> 'notify_prefs'
	);
	
	/**
	 * Backward compatibility - list of prefid's which operate wit serialized data
	 *
	 * @var array
	 */
	protected $serial_bc_array = array('emote', 'menu', 'search');

	/**
	 * Constructor
	 *
	 * @param string $alias
	 * @param boolean $load load DB data on startup
	 */
	function __construct($alias, $load = true)
	{
		if($this->getAlias($alias))
		{
			$pref_alias = $this->getAlias($alias);
			$pref_id = $alias;
		}
		elseif($this->getConfigId($alias))
		{
			$pref_id = $this->getConfigId($alias);
			$pref_alias = $alias;
		}
		else 
		{
			$pref_id = $pref_alias = '';
			trigger_error('Core config ID '.$alias.' not found!', E_USER_WARNING);
			return;
		}
		
		if(in_array($pref_alias, $this->serial_bc_array))
		{
			$this->setOptionSerialize(true);
		}
		
		if('core' === $pref_alias)
		{
			$this->setOptionBackup(true);
		}
		
		parent::__construct($pref_id, $pref_alias);
		if($load && $pref_id)
		{
			$this->load();
		}
	}
	
	/**
	 * Get config ID
	 * Allowed values: key or value from $alias array
	 * If id not found this method returns false
	 *
	 * @param string $alias
	 * @return string
	 */
	public function getConfigId($alias)
	{
		$alias = trim($alias);
		if(isset($this->alliases[$alias]))
		{
			return $this->allias[$alias];
		}
		return array_search($alias, $this->alliases);
	}
	
	/**
	 * Get config ID
	 * Allowed values: key or value from $alias array
	 * If id not found this method returns false
	 *
	 * @param string $alias
	 * @return string
	 */
	public function getAlias($prefid)
	{
		$prefid = trim($prefid);
		return array_search($prefid, $this->alliases);
	}
}

/**
 * Handle plugin preferences
 * 
 * @package e107
 * @category e107_handlers
 * @version 1.0
 * @author SecretR
 * @copyright Copyright (c) 2009, e107 Inc.
 */
class e_plugin_pref extends e_pref 
{
	/**
	 * Unique plugin name
	 *
	 * @var string
	 */
	protected $plugin_id;
	
	/**
	 * Constructor
	 * Note: object data will be loaded only if the plugin is installed (no matter of the passed
	 * $load value)
	 *
	 * @param string $plugin_id unique plugin name
	 * @param string $multi_row additional field identifier appended to the $prefid
	 * @param boolean $load load on startup
	 */
	function __construct($plugin_id, $multi_row = '', $load = true)
	{
		$this->plugin_id = $plugin_id;
		if($multi_row)
		{
			$plugin_id = $plugin_id.'_'.$multi_row;
		}
		parent::__construct($plugin_id, 'plugin_'.$plugin_id);
		if($load && e107::findPref('plug_installed/'.$this->plugin_id))
		{
			$this->load();
		}
	}
	
	/**
	 * Retrive unique plugin name
	 *
	 * @return string
	 */
	public function getPluginId()
	{
		return $this->plugin_id;
	}
}

/**
 * Base e107 Model class
 *
 * @package e107
 * @category e107_handlers
 * @version 1.0
 * @author SecretR
 * @copyright Copyright (c) 2009, e107 Inc.
 */
class e_model
{
    /**
     * Object data
     *
     * @var array
     */
    protected $_data = array();
    
    /**
     * Data structure array
     * Needs implementation by child classes
     *
     * @var array
     */
    protected $_data_structure = array();
    
    /**
     * Avoid DB calls if data is not changed
     *
     * @see mergePostedData()
     * @var boolean
     */
    protected $data_has_changed = false;
    
    /**
     * Validation structure in format 
     * 'field_name' => rule (to be used with core validator handler)
     * Needs implementation by child classes
     *
     * @var array
     */
    protected $_validation_rules = array();
    
    /**
     * @var validatorClass
     */
    protected $_validator = null;
    
    /**
     * Validation error stack 
     * See also {@link validate()}, {@link setErrors()}, {@link getErrors()}
     * 
     * @var array
     */
    protected $_validation_errors = array();
    
    /**
    * Posted data
    *
    * @var array
    */
    protected $_posted_data = array();
    
    /**
    * Name of object id field
    *
    * @var string
    */
    protected $_field_id;
    

    /**
     * Constructor - set data on initialization
     *
     * @param array $data
     */
	function __construct($data = array())
	{
		$this->setData($data);
	}
    
    /**
     * Set name of object's field id
     *
     * @see getId()
     * 
     * @param   string $name
     * @return  e_model
     */
    public function setFieldIdName($name)
    {
        $this->_idFieldName = $name;
        return $this;
    }

    /**
     * Retrieve name of object's field id
     *
     * @see getId()
     * 
     * @param   string $name
     * @return  string
     */
    public function getFieldIdName()
    {
        return $this->_idFieldName;
    }
    
    /**
     * Get object data structure
     * Used as a filter when copy/merge posted data to the object
     *
     * @return array
     */
    public function getStructure()
    {
    	return  $this->_data_structure;
    }
    
    /**
     * Set object data structure if $_data_structure is empty
     *
     * @param array $struct
     * @return e_model
     */
    public function setStructure(array $struct = array())
    {
    	if(empty($this->_data_structure) || $struct)
    	{
    		$this->_data_structure = !empty($struct) ? array_unique($struct) : array_keys($this->getData());
    	}
    	return $this;
    }
    
    /**
     * @return array
     */
    public function getValidationRules()
    {
    	return $this->_validation_rules;
    }
    
    /**
     * Set object validation rules if $_validation_rules is empty
     * 
     * @param array $vrules
     * @return e_model
     */
    public function setValidationRules(array $vrules)
    {
    	if(empty($this->_validation_rules))
    	{
    		$this->_validation_rules = $vrules;
    	}
    	return $this;
    }
    
    /**
     * Retrieve object field id value
     *
     * @return mixed
     */
    public function getId()
    {
        if ($this->getIdFieldName()) 
        {
            return $this->getData($this->getIdFieldName(), 0, null);
        }
        return $this->getData('id', 0, null);
    }
    
    /**
     * Retrieves data from the object ($_data) without
     * key parsing (performance wise, prefered when possible)
     *
     * @see _getDataSimple()
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
	public function get($key = '', $default = null)
    {
    	return $this->_getDataSimple($key, $default);
    }
    
    /**
     * Retrieves data from the object ($_data)
     *
     * @see _getData()
     * @param string $key
     * @param mixed $default
     * @param integer $index
     * @return mixed
     */
	public function getData($key = '', $default = null, $index = null)
    {
    	return $this->_getData($key, $default, $index);
    }
    
    /**
     * Retrieves data from the object ($_posted_data) without
     * key parsing (performance wise, prefered when possible)
     *
     * @see _getDataSimple()
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
	public function getPosted($key = '', $default = null)
    {
    	return $this->_getDataSimple($key, $default, '_posted_data');
    }
    
    /**
     * Retrieves data from the object ($_posted_data)
     *
     * @see _getData()
     * @param string $key
     * @param mixed $default
     * @param integer $index
     * @return mixed
     */
	public function getPostedData($key = '', $default = null, $index = null)
    {
    	return $this->_getData($key, $default, $index, '_posted_data');
    }
    
    /**
     * Search for requested data from available sources in this order:
     * - posted data
     * - default object data
     * - empty string
     * 
     * Useful when operating with form GUI
     *
     * @param string $key
     * @param string $default
     * @param integer $index
     * @return string
     */
    public function getIfPosted($key, $default = '', $index = null)
    {
		if(null !== $this->getPostedData($key))
		{
			return e107::getParser()->post_toForm($this->getPostedData($key, null, $index));
		}
		return $this->getData($key, $default, $index);
    }
    
    /**
     * Overwrite data in the object for a single field. Key is not parsed.
     * Public proxy of {@link _setDataSimple()}
     * Data isn't sanitized so use this method only when data comes from trustable sources (e.g. DB)
     * 
     *
     * @see _setData()
     * @param string $key
     * @param mixed $value
     * @param boolean $strict
     * @return e_model
     */
	public function set($key, $value = null, $strict = false)
    {
    	$this->data_has_changed = true;
    	return $this->_setDataSimple($key, $value, $strict);
    }
    
    /**
     * Overwrite data in the object. Public proxy of {@link _setData()}
     * Data isn't sanitized so use this method only when data comes from trustable sources (e.g. DB)
     *
     * @see _setData()
     * @param string|array $key
     * @param mixed $value
     * @param boolean $strict
     * @return e_model
     */
	public function setData($key, $value = null, $strict = false)
    {
    	$this->data_has_changed = true;
    	return $this->_setData($key, $value, $strict);
    }
    
    /**
     * Overwrite posted data in the object for a single field. Key is not parsed.
     * Public proxy of {@link _setDataSimple()}
     * Use this method to store data from non-trustable sources (e.g. _POST) - it doesn't overwrite 
     * the original object data
     * 
     * @param string $key
     * @param mixed $data
     * @param boolean $strict
     * @return e_model
     */
    public function setPosted($key, $data = null, $strict = false)
    {
        return $this->_setDataSimple($key, $data, $strict, '_posted_data');
    }
    
    /**
     * Overwrite posted data in the object for a single field
     * Public proxy of {@link _setData()}
     * Use this method to store data from non-trustable sources (e.g. _POST) - it doesn't overwrite 
     * the original object data
     *
     * @param string $key
     * @param mixed $data
     * @param boolean $strict
     * @return e_model
     */
    public function setPostedData($key = null, $data = null, $strict = false)
    {
        return $this->setData($key, $data, $strict, '_posted_data');
    }
    
    /**
     * Add data to the object.
     * Retains existing data in the object.
     * Public proxy of {@link _addData()}
     * 
     * If $strict is true, data will be filtered by the 
     * object structure. NOTE - Multidimensional arrays are not filtered.
     * 
     * @param array $arr
     * @param boolean $strict
     * @return e_model
     */
    public function addData(array $arr, $strict = false, $override = true)
    {
    	if($arr) $this->data_has_changed = true; //TODO - better status change check
    	return $this->_addData($arr, $strict, $override);
    }
    
    /**
     * Add data to the object.
     * Retains existing data in the object.
     * Public proxy of {@link _addData()}
     * 
     * If $strict is true, data will be filtered by the 
     * object structure. NOTE - Multidimensional arrays are not filtered.
     * 
     * @param array $arr
     * @param boolean $strict
     * @return e_model
     */
    public function addPostedData(array $arr, $strict = false, $override = true)
    {
    	return $this->_addData($arr, $strict, $override, '_posted_data');
    }
    
	/**
     * Unset single field from the object.
     * Public proxy of {@link _unsetDataSimple()}
     *
     * @param string $key
     * @return e_model
     */
    public function remove($key)
    {
    	$this->data_has_changed = true;
    	return $this->_unsetDataSimple($key);
    }
    
	/**
     * Unset data from the object.
     * $key can be a string only. Array will be ignored.
     * '/' inside the key will be treated as array path
     * if $key is null entire object will be reset
     * 
     * Public proxy of {@link _unsetData()}
     *
     * @param string|null $key
     * @return e_model
     */
    public function removeData($key = null)
    {
    	$this->data_has_changed = true;
    	return $this->_unsetData($key);
    }
    
	/**
     * Unset single posted data field from the object.
     * Public proxy of {@link _unsetDataSimple()}
     *
     * @param string $key
     * @return e_model
     */
    public function removePosted($key)
    {
    	return $this->_unsetDataSimple($key, '_posted_data');
    }
    
	/**
     * Unset posted data from the object.
     * $key can be a string only. Array will be ignored.
     * '/' inside the key will be treated as array path
     * if $key is null entire object will be reset
     * 
     * Public proxy of {@link _unsetData()}
     *
     * @param string|null $key
     * @return e_model
     */
    public function removePostedData($key = null)
    {
    	return $this->_unsetData($key, '_posted_data');
    }
    
    /**
     * @param string $key
     * @return boolean
     */
    public function has($key)
    {
    	return $this->_hasData($key);
    }
    
    /**
     * @return boolean
     */
    public function hasData()
    {
    	return $this->_hasData();
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function hasPosted($key)
    {
    	return $this->_hasData($key, '_posted_data');
    }
    
    public function hasPostedData()
    {
    	return $this->_hasData('', '_posted_data');
    }
    
    /**
     * @param string $key
     * @return boolean
     */
    public function isData($key)
    {
    	return $this->_isData($key);
    }
    
    /**
     * @param string $key
     * @return boolean
     */
    public function isPostedData($key)
    {
    	return $this->_isData($key, '_posted_data');
    }
    
    /**
     * Compares posted data vs object data
     *
     * @param string $field
     * @param boolean $strict compare variable type as well
     * @return boolean
     */
    public function dataHasChangedFor($field, $strict = false)
    {
        $newData = $this->getData($field);
        $postedData = $this->getPostedData($field);
        return ($strict ? $newData !== $postedData : $newData != $postedData);
    }
    
    /**
     * Merge posted data with the object data
     * Should be used on edit/update record
     * Copied posted data will be removed
     *
     * @param boolean $sanitize
     * @return e_model
     */
    public function mergePostedData($sanitize = true)
    {
    	if(!$this->getPostedData() || !$this->getStructure() || !$this->validate())
    	{
    		return $this;
    	}
    	
    	$tp = e107::getParser();
    	foreach ($this->getStructure() as $field) 
    	{
    		if(null === $this->getPostedData($field) || !$this->dataHasChangedFor($field))
    		{
    			$this->removePostedData($field);
    			continue;
    		}
    		
    		$this->data_has_changed = true;
    		$data = $sanitize ? $tp->toDB($this->getPostedData($field)) : $this->getPostedData($field);
    		$this->setData($field, $data)
    			->removePostedData($field);
    	}
    	return $this;
    }
    
    /**
     * Copy posted data with the object data
     * Should be used on create/insert records
     * Copied posted data will be removed
     *
     * @param boolean $validate
     * @param boolean $sanitize
     * @param boolean $strict filter by the object structure
     * @return e_model
     */
    public function copyPostedData($validate = true, $sanitize = true, $strict = true)
    {
    	if($validate && !$this->validate())
    	{
    		return $this;
    	}
    	
    	$tp = e107::getParser();
    	if(!$strict || !$this->getStructure())
    	{
    		$data = $sanitize ? $tp->toDB($this->getPostedData()) : $this->getPostedData();
    		if(!empty($data))
    		{
    			$this->data_has_changed = true;
	    		$this->setPostedData(null)
	    			->setData($data);
    		}
    		return $this;
    	}
    	
    	foreach ($this->getStructure() as $field) 
    	{
    		if(null === $this->getPostedData($field) || !$this->dataHasChangedFor($field))
    		{
    			continue;
    		}

    		$this->data_has_changed = true;
    		$data = $sanitize ? $tp->toDB($this->getPostedData($field)) : $this->getPostedData($field);
    		$this->setData($field, $data)
    			->removePostedData($field);
    	}
    	return $this;
    }
    
    /**
     * Retrieves data from the object
     *
     * If $key is empty will return all the data as an array
     * Otherwise it will return value of the attribute specified by $key
     * '/' inside the key will be treated as array path (x/y/z equals to [x][y][z]
     *
     * If $index is specified it will assume that attribute data is an array
     * and retrieve corresponding member.
     *
     * @param string $key
     * @param mixed $default
     * @param integer $index
     * @param boolean $posted data source
     * @return mixed
     */
    protected function _getData($key = '', $default = null, $index = null, $data_src = '_data')
    {
    	$key = trim($key, '/');
        if ('' === $key) 
        {
            return $this->$data_src;
        }

        if (strpos($key, '/'))
        {
            $keyArr = explode('/', $key);
            $data = $this->$data_src;
            foreach ($keyArr as $k) 
            {
                if ('' === $k) 
                {
                    return $default;
                }
                if (is_array($data)) 
                {
                    if (!isset($data[$k])) 
                    {
                        return $default;
                    }
                    $data = $data[$k];
                }
                else 
                {
                    return $default;
                }
            }
            return $data;
        }

        //get $index
        if (isset($this->$data_src[$key])) 
        {
            if (null === $index) 
            {
                return $this->$data_src[$key];
            }

            $value = $this->$data_src[$key];
            if (is_array($value)) 
            {
                if (isset($value[$index])) 
                {
                    return $value[$index];
                }
                return $default;
            } 
            elseif (is_string($value)) 
            {
                $arr = explode("\n", $value);
                return (isset($arr[$index]) ? $arr[$index] : $default);
            }
            return $default;
        }
        return $default;
    }
    
    /**
     * Get value from _data array without parsing the key
     *
     * @param string $key
     * @param mixed $default
     * @param string $posted data source
     * @return mixed
     */
    protected function _getDataSimple($key, $default = null, $data_src = '_data')
    {
        return isset($this->$data_src[$key]) ? $this->$data_src[$key] : $default;
    }
    
    /**
     * Overwrite data in the object.
     *
     * $key can be string or array.
     * If $key is string, the attribute value will be overwritten by $value
     * '/' inside the key will be treated as array path
     *
     * If $key is an array, it will overwrite all the data in the object.
     * 
     * If $strict is true and $key is an array, data will be filtered by the 
     * object structure
     *
     * @param string|array $key
     * @param mixed $value
     * @param boolean $strict
     * @param string $data_src
     * @return e_model
     */
    protected function _setData($key, $value = null, $strict = false, $data_src = '_data')
    {
        if(is_array($key)) 
        {
            if($strict && $this->getStructure())
	    	{
	    		$struct = $this->getStructure();
				foreach($struct as $k) 
		        {
		        	if(isset($key[$k]))
		        	{
		            	$this->_setData($k, $key[$k], $strict, $data_src);
		        	}
		        }
		        return $this;
	    	}
            $this->$data_src = $key;
            return $this;
        } 
        
        $key = trim($key, '/');
        if(strpos($key,'/')) 
        {
        	$keyArr = explode('/', $key);
        	$data = &$this->$data_src;
            for ($i = 0, $l = count($keyArr); $i < $l; $i++) 
            {
	            $k = $keyArr[$i];
	            if (!isset($data[$k])) 
	            {
	                $data[$k] = array();
	            }
	            $data = &$data[$k];
	        }
	        $data = $value;
        }
        else 
        {
            $this->$data_src[$key] = $value;
        }

        return $this;
    }
    
    /**
     * Set data for the given source. More simple (and performance wise) version
     * of {@link _setData()}
     *
     * @param string $key
     * @param mixed $value
     * @param boolean $strict
     * @param string $data_src
     * @return unknown
     */
	protected function _setDataSimple($key, $value = null, $strict = false, $data_src = '_data')
    {
    	if(!$strict || in_array($key, $this->getStructure()))
    	{
    		$this->$data_src[$key] = $value;
    	}
    	return $this;
    }
    
    /**
     * Add data to the object.
     * Retains existing data in the object.
     * 
     * If $strict is true, data will be filtered by the 
     * object structure. NOTE - Multidimensional arrays are not filtered.
     * 
     * @param array $arr
     * @param boolean $strict
     * @param boolean $override allow override of existing data
     * @param string $data_src data source
     * @return e_model
     */
    protected function _addData(array $arr, $strict = true, $override = true, $data_src = '_data')
    {
    	if($strict && $this->getStructure())
    	{
    		$struct = $this->getStructure();
			foreach($struct as $key => $value) 
	        {
	        	if(!$override && $this->isData($key))
	        	{
	        		continue;
	        	}
	        	if(isset($arr[$key]))
	        	{
	            	$this->_setData($key, $value, $strict, $data_src);
	        	}
	        }
	        return $this;
    	}
    	
		foreach($arr as $key => $value) 
		{
		    $this->_setData($key, $value, $strict, $data_src);
		}
        return $this;
    }
    
    /**
     * Unset data from the object from the given source.
     * $key can be a string only. Array will be ignored.
     * '/' inside the key will be treated as array path
     * if $key is null entire object will be reset
     *
     * @param string|null $key
     * @param string $data_src data source
     * @return e_model
     */
    protected function _unsetData($key = null, $data_src = '_data')
    {
        if (null === $key) 
        {
        	$this->$data_src = array();
            return $this;
        } 
		
        $key = trim($key, '/');
        if(strpos($key,'/')) 
        {
        	$keyArr = explode('/', $key);
        	$data = &$this->$data_src;
        	
        	$unskey = array_pop($data);
        	
            for ($i = 0, $l = count($keyArr); $i < $l; $i++) 
            {
	            $k = $keyArr[$i];
	            if (!isset($data[$k])) 
	            {
	                return $this; //not found
	            }
	            $data = &$data[$k];
	        }
	        if(is_array($data))
	        {
	        	unset($data[$unskey]);
	        }
        }
        else 
        {
            unset($this->$data_src[$key]);
        }
        return $this;
    }
    
	/**
     * Unset single field from the object from the given source. Key is not parsed
     *
     * @param string $key
     * @param string $data_src data source
     * @return e_model
     */
    protected function _unsetDataSimple($key, $data_src = '_data')
    {
    	unset($this->$data_src[$key]);
    	return $this;
    }

    /**
     * If $key is empty, checks whether there's any data in the object
     * Otherwise checks if the specified key is empty/set.
     *
     * @param string $key
     * @param string $data_src data source
     * @return boolean
     */
    protected function _hasData($key = '', $data_src = '_data')
    {
        if (empty($key)) 
        {
            return !empty($this->$data_src);
        }
        $value = $this->_getData($key, null, null, $data_src);
        return !empty($value);
    }
    
    /**
     * Checks if the specified key is set
     *
     * @param string $key
     * @param string $data_src data source
     * @return boolean
     */
    protected function _isData($key, $data_src = '_data')
    {
        return (null !== $this->_getData($key, null, null, $data_src));
    }
    
    /**
     * Validate posted data:
     * 1. validate posted data against object validation rules
     * 2. add validation errors to the object if any
     * 3. return true for valid and false for non-valid data
     *
     * @return boolean
     */
    public function validate()
    {
    	$this->_validation_errors = array();
    	
    	if(!$this->getValidationRules())
    	{
    		return true;
    	}
    	
    	$result = $this->getValidator()->validateFields($this->getPostedData(), $this->getValidationRules());
    	if(!empty($result['errors']))
    	{
    		$this->_validation_errors = $result['errors'];
    		return false;
    	}
    	
    	return true;
    }
    
    /**
     * @return boolean
     */
    public function isError()
    {
    	return !empty($this->_validation_errors);
    }
    
    /**
     * Under construction
     * Add human readable errors to eMessage stack
     * 
     * @param boolean $reset reset errors
     * @param boolean $session store messages to session
     * @return e_model
     */
    public function setErrors($reset = true, $session = false)
    {
    	//$emessage = eMessage::getInstance();
    	return $this;
    }
    
    /**
     * Load data from DB
     * Awaiting for child class implementation
     *
     */
    public function load()
    {
    }
    
    /**
     * Save data to DB
     * Awaiting for child class implementation
     *
     */
    public function save()
    {
    }
    
    /**
     * Insert data to DB
     * Awaiting for child class implementation
     *
     */
    public function dbInsert()
    {
    }
    
    /**
     * Update DB data
     * Awaiting for child class implementation
     *
     */
    public function dbUpdate()
    {
    }
    
	/**
	 * @return validatorClass
	 */
	public function getValidator()
	{
		if(null === $this->_validator)
		{
			$this->_validator = e107::getObject('validatorClass', null, e_HANDLER.'validator_class.php');
		}
		return $this->_validator;
	}
	
	/**
	 * Wrapper of {@link __toString()}
	 *
	 * @param boolean $AddSlashes
	 * @return string
	 */
	public function toString($AddSlashes = true)
	{
		return $this->__toString($AddSlashes);
	}
	
	/**
	 * Magic method - convert object data to string
	 * NOTE: before PHP 5.2.0 the __toString method was only 
	 * called when it was directly combined with echo() or print()
	 *
	 * @param boolean $AddSlashes
	 * @return string
	 */
	public function __toString($AddSlashes = true)
	{
		return e107::getArrayStorage()->WriteArray($this->getData(), $AddSlashes);
	}
}

/**
 * DEPRECATED - see e107::getConfig(), e_core_pref and e_plugin_pref
 *
 */
//
// Simple functionality:
// Grab all prefs once, in one DB query. Reuse them throughout the session.
//
// get/set methods serve/consume strings (with slashes taken care of)
// getArray/setArray methods serve/consume entire arrays (since most prefs are such!)
//
// NOTE: Use of this class is VALUABLE (efficient) yet not NECESSARY (i.e. the system
//       will not break if it is ignored)... AS LONG AS there is no path consisting of:
//             - modify pref value(s) IGNORING this class
//  - retrieve pref value(s) USING this class
//       (while processing a single web page)
//  Just to be safe I have changed a number of menu_pref edits to use setArray().
//

class prefs 
{
	var $prefVals;
	var $prefArrays;

	// Default prefs to load
	var $DefaultRows = "e107_name='e107' OR e107_name='menu_pref' OR e107_name='notify_prefs'";

	// Read prefs from DB - get as many rows as are required with a single query.
	// $RowList is an array of pref entries to retrieve.
	// If $use_default is TRUE, $RowList entries are added to the default array. Otherwise only $RowList is used.
	// Returns TRUE on success (measured as getting at least one row of data); false on error.
	// Any data read is buffered (in serialised form) here - retrieve using get()
	function ExtractPrefs($RowList = "", $use_default = FALSE) 
	{
		global $sql;
		$Args = '';
		if($use_default)
		{
			$Args = $this->DefaultRows;
		}
		if(is_array($RowList))
		{
			foreach($RowList as $v)
			{
				$Args .= ($Args ? " OR e107_name='{$v}'" : "e107_name='{$v}'");
			}
		}
		if (!$sql->db_Select('core', '*', $Args, 'default'))
		{
			return FALSE;
		}
		while ($row = $sql->db_Fetch())
		{
			$this->prefVals['core'][$row['e107_name']] = $row['e107_value'];
		}
		return TRUE;
	}


	/**
	* Return current pref string $name from $table (only core for now)
	*
	* - @param  string $name -- name of pref row
	* - @param  string $table -- "core"
	* - @return  string pref value, slashes already stripped. FALSE on error
	* - @access  public
	*/
	function get($Name) 
	{
		if(isset($this->prefVals['core'][$Name]))
		{
			if($this->prefVals['core'][$Name] != '### ROW CACHE FALSE ###')
			{
				return $this->prefVals['core'][$Name];		// Dava from cache
			} 
			else 
			{
				return false;
			}
		}

		// Data not in cache - retrieve from DB
		$get_sql = new db; // required so sql loops don't break using $tp->toHTML(). 
		if($get_sql->db_Select('core', '*', "`e107_name` = '{$Name}'", 'default')) 
		{
			$row = $get_sql->db_Fetch();
			$this->prefVals['core'][$Name] = $row['e107_value'];
			return $this->prefVals['core'][$Name];
		} 
		else 
		{	// Data not in DB - put a 'doesn't exist' entry in cache to save another DB access
			$this->prefVals['core'][$Name] = '### ROW CACHE FALSE ###';
			return false;
		}
	}

	/**
	* Return current array from pref string $name in $table (core only for now)
	*
	* - @param:  string $name -- name of pref row
	* - @param  string $table -- "core" only now
	* - @return  array pref values
	* - @access     public
	*/
	// retrieve prefs as an array of values
	function getArray($name) {
		return unserialize($this->get($name));
	}


	/**
	* Update pref set and cache
	*
	* @param  string val -- pre-serialized string
	* @param  string $name -- name of pref row
	* @param  string $table -- "core" or "user"
	* @global  $$name
	* @access  public
	*
	* set("val")    == 'core', 'pref'
	* set("val","rowname")   == 'core', rowname
	* set("val","","user")   == 'user', 'user_pref' for current user
	* set("val","","user",uid)   == 'user', 'user_pref' for user uid
	* set("val","fieldname","user")  == 'user', fieldname
	*
	*/
	function set($val, $name = "", $table = "core", $uid = USERID) {
		global $sql;
		if (!strlen($name)) {
			switch ($table) {
				case 'core':
				$name = "pref";
				break;
				case 'user':
				$name = "user_pref";
				break;
			}
		}
		$val = addslashes($val);

		switch ($table ) {
			case 'core':
			if(!$sql->db_Update($table, "e107_value='$val' WHERE e107_name='$name'"))
			{
				$sql->db_Insert($table, "'{$name}', '{$val}'");
			}
			$this->prefVals[$table][$name] = $val;
			unset($this->prefArrays[$table][$name]);
			break;
			case 'user':
			$sql->db_Update($table, "user_prefs='$val' WHERE user_id=$uid");
			break;
		}
	}


	/**
	* Update pref set and cache
	*
	* - @param  string $name -- name of pref row
	* - @param  string $table -- "core" or "user"
	* - @global  $$name
	* - @access  public
	*
	* set()    == core, pref
	* set("rowname")   == core, rowname
	* set("","user")   == user, user_pref for current user
	* set("","user",uid)   == user, user_pref for user uid
	* set("fieldname","user")  == user, fieldname
	*
	* all pref sets other than menu_pref get toDB()
	*/
	function setArray($name = "", $table = "core", $uid = USERID) {
		global $tp;

		if (!strlen($name)) {
			switch ($table) {
				case 'core':
				$name = "pref";
				break;
				case 'user':
				$name = "user_pref";
				break;
			}
		}

		global $$name;
		if ($name != "menu_pref") {
			foreach($$name as $key => $prefvalue) {
				$$name[$key] = $tp->toDB($prefvalue);
			}
		}

		$tmp = serialize($$name);
		$this->set($tmp, $name, $table, $uid);
	}
}
?>