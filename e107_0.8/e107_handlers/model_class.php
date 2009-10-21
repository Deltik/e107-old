<?php
/*
 * e107 website system
 *
 * Copyright (C) 2001-2008 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * e107 Base Model
 *
 * $Source: /cvs_backup/e107_0.8/e107_handlers/model_class.php,v $
 * $Revision: 1.15 $
 * $Date: 2009-10-21 19:34:18 $
 * $Author: e107steved $
*/

if (!defined('e107_INIT')) { exit; }

/**
 * Base e107 Model class
 * 
 * @package e107
 * @category e107_handlers
 * @version 1.0
 * @author SecretR
 * @copyright Copyright (C) 2009, e107 Inc.
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
     * Runtime cache of parsed from {@link _getData()} keys
     *
     * @var array
     */
    protected $_parsed_keys = array();
	
    /**
     * Avoid DB calls if data is not changed
     *
     * @see _setData()
     * @var boolean
     */
    protected $data_has_changed = false;
    
    /**
    * Name of object id field
    * Required for {@link getId()()} method
    *
    * @var string
    */
    protected $_field_id;
	
	/**
	 * Namespace to be used for model related system messages in {@link eMessage} handler
	 * 
	 * @var string 
	 */
	protected $_message_stack = 'default';
    

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
        $this->_field_id = $name;
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
        return $this->_field_id;
    }

    /**
     * Retrieve object field id value
     *
     * @return mixed
     */
    public function getId()
    {
        if ($this->getFieldIdName()) 
        {
            return $this->get($this->getFieldIdName(), 0);
        }
        return $this->get('id', 0);
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
	public function get($key, $default = null)
    {
    	return $this->_getDataSimple((string) $key, $default);
    }
    
    /**
     * Retrieves data from the object ($_data)
     * If $key is empty, return all object data
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
     * Overwrite data in the object for a single field. Key is not parsed.
     * Public proxy of {@link _setDataSimple()}
     * Data isn't sanitized so use this method only when data comes from trustable sources (e.g. DB)
     * 
     *
     * @see _setData()
     * @param string $key
     * @param mixed $value
     * @param boolean $strict update only
     * @return e_model
     */
	public function set($key, $value = null, $strict = false)
    {
    	return $this->_setDataSimple($key, $value, $strict);
    }
    
    /**
     * Overwrite data in the object. Public proxy of {@link _setData()}
     * Data isn't sanitized so use this method only when data comes from trustable sources (e.g. DB)
     *
     * @see _setData()
     * @param string|array $key
     * @param mixed $value
     * @param boolean $strict update only
     * @return e_model
     */
	public function setData($key, $value = null, $strict = false)
    {
    	return $this->_setData($key, $value, $strict);
    }
    
    /**
     * Add data to the object.
     * Retains existing data in the object.
     * Public proxy of {@link _addData()}
     * 
     * If $override is false, data will be updated only (check against existing data)
     * 
     * @param string|array $key
     * @param mixed $value
     * @param boolean $override override existing data
     * @return e_model
     */
    public function addData($key, $value = null, $override = true)
    {
    	return $this->_addData($key, $value, $override);
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
    	return $this->_unsetData($key);
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
     * @param string $key
     * @return boolean
     */
    public function hasData($key = '')
    {
    	return $this->_hasData($key);
    }
	
    /**
     * @param string $key
     * @return boolean
     */
    public function is($key)
    {
    	return (isset($this->_data[$key]));
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
        	if(isset($this->_parsed_keys[$data_src.'/'.$key]))
        	{
        		return $this->_parsed_keys[$data_src.'/'.$key];
        	}
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
            $this->_parsed_keys[$data_src.'/'.$key] = $data;
            return $data;
        }

        //get $index
        if (isset($this->{$data_src}[$key])) 
        {
            if (null === $index) 
            {
                return $this->{$data_src}[$key];
            }

            $value = $this->{$data_src}[$key];
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
        return isset($this->{$data_src}[$key]) ? $this->{$data_src}[$key] : $default;
    }
    
    /**
     * Overwrite data in the object.
     *
     * $key can be string or array.
     * If $key is string, the attribute value will be overwritten by $value
     * '/' inside the key will be treated as array path
     *
     * If $key is an array and $strict is false, it will overwrite all the data in the object.
     * 
     * If $strict is true and $data_src is '_data', data will be updated only (no new data will be added)
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
            if($strict)
	    	{
				foreach(array_keys($key) as $k) 
		        {
		        	$this->_setData($k, $key[$k], true, $data_src);
		        }
		        return $this;
	    	}

            $this->$data_src = $key;
            return $this;
        } 
        
        //multidimensional array support - strict _setData for values of type array
    	if($strict && !empty($value) && is_array($value))
       	{
			foreach($value as $k => $v)
			{
			    $this->_setData($key.'/'.$k, $v, true, $data_src);
			}
			return $this;
       	}
        
        //multidimensional array support - parse key
        $key = trim($key, '/');
        if(strpos($key,'/')) 
        {
        	//if strict - update only
	        if($strict && !$this->isData($key))
	        {
	        	return $this;
	        }
	        
        	$keyArr = explode('/', $key);
        	$data = &$this->{$data_src};
            for ($i = 0, $l = count($keyArr); $i < $l; $i++) 
            {
	            $k = $keyArr[$i];
		        
	            if (!isset($data[$k])) 
	            {
	                $data[$k] = array();
	            }
	            $data = &$data[$k];
	        }
            
	        //data has changed - optimized
	        if('_data' === $data_src && !$this->data_has_changed)
	        {
	        	$this->data_has_changed = (!isset($this->_data[$key]) || $this->_data[$key] != $value);
	        }
	        $this->_parsed_keys[$data_src.'/'.$key] = $value;
	        $data = $value;
        }
        else 
        {
			//if strict - update only
	        if($strict && !isset($this->_data[$key]))
	        {
	        	return $this;
	        }
        	if('_data' === $data_src && !$this->data_has_changed)
	        {
	        	$this->data_has_changed = (!isset($this->_data[$key]) || $this->{$data_src}[$key] != $value);
	        }
            $this->{$data_src}[$key] = $value;
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
     * @return e_model
     */
	protected function _setDataSimple($key, $value = null, $strict = false, $data_src = '_data')
    {
    	$key = $key.'';//smart toString
    	if(!$strict)
    	{
			//data has changed
	        if('_data' === $data_src && !$this->data_has_changed)
	        {
	        	$this->data_has_changed = (!isset($this->_data[$key]) || $this->_data[$key] != $value);
	        }
	        $this->{$data_src}[$key] = $value;
    		return $this;
    	}
    	
    	if($this->isData($key))
    	{
			if('_data' === $data_src && !$this->data_has_changed)
	        {
	        	$this->data_has_changed = (!isset($this->_data[$key]) || $this->_data[$key] != $value);
	        }
	        $this->{$data_src}[$key] = $value;
    	}

    	return $this;
    }
    
    /**
     * Add data to the object.
     * Retains existing data in the object.
     * 
     * If $override is false, only new (non-existent) data will be added
     * 
     * @param string|array $key
     * @param mixed $value
     * @param boolean $override allow override of existing data
     * @param string $data_src data source
     * @return e_model
     */
    protected function _addData($key, $value = null, $override = true, $data_src = '_data')
    {
    	if(is_array($key))
    	{
			foreach($key as $k => $v)
			{
			    $this->_addData($k, $v, $override, $data_src);
			}
			return $this;
    	}
    	
		if($override || !$this->_isData($key, $data_src))
       	{
       		if(is_array($value))
       		{
				if(is_array($key))
				{
					foreach($key as $k => $v)
					{
					    $this->_addData($key.'/'.$k, $v, $override, $data_src);
					}
				}
				return $this;
       		}
       		$this->_setData($key, $value, false, $data_src);
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
        	if('_data' === $data_src && !empty($this->_data))
        	{
        		$this->data_has_changed = true;
        	}
        	$this->{$data_src} = array();
            return $this;
        } 
		
        $key = trim($key, '/');
        if(strpos($key,'/')) 
        {
        	$keyArr = explode('/', $key);
        	$data = &$this->{$data_src};
        	
        	$unskey = array_pop($keyArr);
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
				if('_data' === $data_src && isset($data[$unskey]))
	        	{
	        		$this->data_has_changed = true;
	        	}
	        	unset($data[$unskey], $this->_parsed_keys[$data_src.'/'.$key]);
	        }
        }
        else 
        {
       		if('_data' === $data_src && isset($this->{$data_src}[$key]))
        	{
        		$this->data_has_changed = true;
        	}
            unset($this->{$data_src}[$key]);
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
		if('_data' === $data_src && isset($this->{$data_src}[$key]))
       	{
       		$this->data_has_changed = true;
       	}
    	unset($this->{$data_src}[$key]);
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
	 * Add system message of type Information
	 * 
	 * @param string $message
	 * @param boolean $session [optional]
	 * @return e_validator
	 */
	public function addMessageInfo($message, $session = false)
	{
		e107::getMessage()->addStack($message, $this->_message_stack, E_MESSAGE_INFO, $session);
		return $this;
	}
	
	/**
	 * Add system message of type Warning
	 * 
	 * @param string $message
	 * @param boolean $session [optional]
	 * @return e_validator
	 */
	public function addMessageWarning($message, $session = false)
	{
		e107::getMessage()->addStack($message, $this->_message_stack, E_MESSAGE_WARNING, $session);
		return $this;
	}
	
	/**
	 * Add system message of type Error
	 * 
	 * @param string $message
	 * @param boolean $session [optional]
	 * @return e_validator
	 */
	public function addMessageError($message, $session = false)
	{
		e107::getMessage()->addStack($message, $this->_message_stack, E_MESSAGE_ERROR, $session);
		return $this;
	}
	
	/**
	 * Add system message of type Information
	 * 
	 * @param string $message
	 * @param boolean $session [optional]
	 * @return e_validator
	 */
	public function addMessageDebug($message, $session = false)
	{
		e107::getMessage()->addStack($message, $this->_message_stack, E_MESSAGE_DEBUG, $session);
		return $this;
	}
	
    /**
     * Render System messages (if any)
     * 
     * @param boolean $session store messages to session
     * @param boolean $reset reset errors
     * @return string
     */
    public function renderMessages($session = false, $reset = true)
    {
    	return e107::getMessage()->render($this->_message_stack, $session, $reset);
    }
	
    /**
     * User defined model validation
     * Awaiting for child class implementation
     *
     */
    public function verify()
    {
    }
	
    /**
     * Model validation 
     * @see e_model_admin
     */
    public function validate()
    {
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
     * @see e_model_admin
     */
    public function save()
    {
    }
    
    /**
     * Insert data to DB
     * Awaiting for child class implementation
     * @see e_model_admin
     */
    public function dbInsert()
    {
    }
    
    /**
     * Update DB data
     * Awaiting for child class implementation
     * @see e_model_admin
     */
    public function dbUpdate()
    {
    }
	
	/**
	 * Try to convert string to a number
	 * Shoud fix locale related troubles
	 * 
	 * @param string $value
	 * @return 
	 */
	public function toNumber($value)
	{
		if(!is_numeric($value))
		{
			$larr = localeconv(); 
			$search = array($larr['decimal_point'], $larr['mon_decimal_point'], $larr['thousands_sep'], $larr['mon_thousands_sep'], $larr['currency_symbol'], $larr['int_curr_symbol']);
			$replace = array('.', '.', '', '', '', '');
			$value = str_replace($search, $replace, $value);
		}
		return (0 + $value);
	}
	
	/**
	 * Convert model object to array
	 * @return array object data
	 */
	public function toArray()
	{
		return $this->getData();
	}
	
	/**
	 * Convert object data to a string
	 *
	 * @param boolean $AddSlashes
	 * @param string $key optional, if set method will return corresponding value as a string
	 * @return string
	 */
	public function toString($AddSlashes = true, $key = null)
	{
		if (null !== $key)
		{
			$value = $this->getData($key);
			if(is_array($value))
			{
				return e107::getArrayStorage()->WriteArray($value, $AddSlashes);
			}
			return (string) $value;
		}
		return (string) e107::getArrayStorage()->WriteArray($this->getData(), $AddSlashes);
	}
	
	/**
	 * Magic method - convert object data to a string
	 * NOTE: before PHP 5.2.0 the __toString method was only 
	 * called when it was directly combined with echo() or print()
	 * 
	 * NOTE: PHP 5.3+ is throwing parse error if __toString has optional arguments.
	 *
	 * @param boolean $AddSlashes
	 * @return string
	 */
	public function __toString()
	{
		return $this->toString((@func_get_arg(0) === true));
	}
}

//FIXME - move e_model_admin to e_model_admin.php

/**
 * Base e107 Admin Model class
 * 
 * Some important points:
 * - model data should be always in toDB() format:
 * 		- retrieved direct from DB
 * 		- set & sanitized via setPostedData()->mergePostedData()
 * 		- manually sanitized before passed to model setter (set(), setData(), add(), addData(), etc.) methods 
 * - $_db_fields property is important, it tells to sanitize() method how to sanitize posted data
 * - if $_db_fields is missing, sanitize() will call internally e107::getParser()->toDB() on the data
 * - sanitize() is triggered by default on mergePostedData() and mergeData() methods
 * - mergePostedData() and mergeData() methods will filter posted/passed data against (in this order):
 * 		- getValidator()->getValidData() if true is passed as validate parameter (currently disabled, gather feedback)
 * 		- $_db_fields if true is passed as sanitize parameter
 * - toSqlQuery() needs $_db_fields and $_field_id to work proper, $_FIELD_TYPES is optional but recommended (faster SQL queries)
 * - result array from toSqlQuery() call will be filtered against $_db_fields
 * - in almost every case $_FIELD_TYPES shouldn't contain 'escape' and 'todb' - dont't forget you are going to pass already sanitized data (see above)
 * - most probably $_FIELD_TYPES will go in the future, $_db_fields alone could do the job
 * - default db related methods (save(), dbUpdate(), etc.) need $_db_table
 * 
 * @package e107
 * @category e107_handlers
 * @version 1.0
 * @author SecretR
 * @copyright Copyright (C) 2009, e107 Inc.
 */
class e_model_admin extends e_model
{
	/**
	 * Current model DB table, used in all db calls
	 * 
	 * This can/should be overwritten by extending the class
	 * 
	 * @var string
	 */
	protected $_db_table;
	
    /**
    * Posted data
    * Back-end related
    *
    * @var array
    */
    protected $_posted_data = array();
	
    /**
     * DB structure array, required for {@link sanitize()} method, 
     * it also serves as a map (find data) for building DB queries,
     * copy/sanitize posted data to object data, etc.
     * 
     * This can/should be overwritten by extending the class
     *
     * @var array
     */
    protected $_db_fields = array();
	
    /**
     * DB format array - see db::_getTypes() and db::_getFieldValue() (mysql_class.php)
     * for example
     * 
     * This can/should be overwritten by extending the class
     *
     * @var array
     */
    protected $_FIELD_TYPES = array();
    
    /**
     * Validation structure - see {@link e_validator::$_required_rules} for
     * more information about the array format.
     * Used in {@link validate()} method.
     * 
     * This can/should be overwritten by extending the class.
     *
     * @var array
     */
    protected $_validation_rules = array();
	
	/**
	 * @var integer Last SQL error number
	 */
	protected $_db_errno = 0;
    
    /**
     * Validator object
     * 
     * @var e_validator 
     */
    protected $_validator = null;
	
	public function setModelTable($table)
	{
		$this->_db_table = $table;
		return $this;
	}
	
	public function getModelTable()
	{
		return $this->_db_table;
	}
	
    /**
     * @return array
     */
    public function getValidationRules()
    {
    	return $this->_validation_rules;
    }
    
    /**
     * Set object validation rules if $_validation_rules array is empty
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
     * @return array
     */
    public function getDbFields()
    {
    	return $this->_db_fields;
    }
	
    /**
     * @return array
     */
    public function getFieldTypes()
    {
    	return $this->_FIELD_TYPES;
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
	public function getPosted($key, $default = null)
    {
    	return $this->_getDataSimple((string) $key, $default, '_posted_data');
    }
    
    /**
     * Retrieves data from the object ($_posted_data)
     * If $key is empty, return all object posted data
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
     * - passed default value
     * 
     * Use this method inside forms
     *
     * @param string $key
     * @param string $default
     * @param integer $index
     * @return string
     */
    public function getIfPosted($key, $default = '', $index = null)
    {
		if(null !== $this->getPostedData((string) $key))
		{
			return e107::getParser()->post_toForm($this->getPostedData((string) $key, null, $index));
		}
		return e107::getParser()->toForm($this->getData((string) $key, $default, $index));
    }
    
    /**
     * Overwrite posted data in the object for a single field. Key is not parsed.
     * Public proxy of {@link _setDataSimple()}
     * Use this method to store data from non-trustable sources (e.g. _POST) - it doesn't overwrite 
     * the original object data
     * 
     * @param string $key
     * @param mixed $value
     * @param boolean $strict update only
     * @param boolean $toForm use post_toForm() on both key and data arguments
     * @return e_model
     */
    public function setPosted($key, $value, $strict = false, $toForm = true)
    {
    	if($toForm)
		{
			$tp = e107::getParser();
			$key = $tp->post_toForm($key);
			$value = $tp->post_toForm($value);
		}
        return $this->_setDataSimple($key, $value, $strict, '_posted_data');
    }
    
    /**
     * Overwrite posted data in the object. Key is parsed (multidmensional array support).
     * Public proxy of {@link _setData()}
     * Use this method to store data from non-trustable sources (e.g. _POST) - it doesn't overwrite 
     * the original object data
     *
     * @param string|array $key
     * @param mixed $value
     * @param boolean $strict update only
     * @param boolean $toForm use post_toForm() on both key and data arguments
     * @return e_model
     */
    public function setPostedData($key, $value = null, $strict = false, $toForm = true)
    {
    	if($toForm)
		{
			$tp = e107::getParser();
			$key = $tp->post_toForm($key);
			$value = $tp->post_toForm($value);
		}
        return $this->_setData($key, $value, $strict, '_posted_data');
    }
    
    /**
     * Add data to the object.
     * Retains existing data in the object.
     * Public proxy of {@link _addData()}
     * 
     * If $override is false, data will be updated only (check against existing data)
     * 
     * @param string|array $key
     * @param mixed $value
     * @param boolean $override override existing data
     * @param boolean $toForm use post_toForm() on both key and data arguments
     * @return e_model
     */
    public function addPostedData($key, $value = null, $override = true, $toForm = true)
    {
    	if($toForm)
		{
			$tp = e107::getParser();
			$key = $tp->post_toForm($key);
			$value = $tp->post_toForm($value);
		}
    	return $this->_addData($key, $value, $override, '_posted_data');
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
     * Check if given key exists and non-empty in the posted data array
     * @param string $key
     * @return boolean
     */
    public function hasPosted($key)
    {
    	return $this->_hasData($key, '_posted_data');
    }
    
	/**
	 * Check if posted data is empty
	 * @return boolean
	 */
    public function hasPostedData()
    {
    	return $this->_hasData('', '_posted_data');
    }
	
    /**
     * Check if given key exists in the posted data array
     * 
     * @param string $key
     * @return boolean
     */
    public function isPosted($key)
    {
    	return (isset($this->_posted_data[$key]));
    }
    
    /**
     * Check if given key exists in the posted data array ($key us parsed)
     * 
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
     * @return boolean
     */
    public function dataHasChanged()
    {
        return $this->data_has_changed;
    }
	
    /**
     * Merge posted data with the object data
     * Should be used on edit/update/create record (back-end)
     * Retrieved for copy Posted data will be removed (no matter if copy is successfull or not)
     * 
     * If $strict is true, only existing object data will be copied (update)
     * If $validate is true, data will be copied only after successful validation
     * TODO - move to admin e_model extension?
     *
     * @param boolean $strict 
     * @param boolean $sanitize sanitize posted data before move it to the object data
     * @param boolean $validate perform validation check
     * @return e_model
     */
    public function mergePostedData($strict = true, $sanitize = true, $validate = true)
    {
    	if(!$this->hasPostedData() || ($validate && !$this->validate()))
    	{
    		return $this; 
    	}
    	
		/* XXX - Wrong? Should validator keep track on validated data at all?
		// retrieve only valid data
		if($validate) 
		{
			$data = $this->getValidator()->getValidData();
		}
		else // retrieve all posted data
		{
			$data = $this->getPostedData();
		}*/
		
		$data = $this->getPostedData();
		if($sanitize)
		{
			// search for db_field types
			if($this->getDbFields())
			{
				$data = $this->sanitize($data);
			}
			else //no db field types, use toDB()
			{
				$tp = e107::getParser();
				$data = $tp->toDB($data);
			}
		}
		
    	foreach ($data as $field => $dt) 
    	{
    		$this->setData($field, $dt, $strict)
    			->removePostedData($field);
    	}
    	return $this;
    }
    
    /**
     * Merge passed data array with the object data
     * Should be used on edit/update/create record (back-end)
     * 
     * If $strict is true, only existing object data will be copied (update)
     * If $validate is true, data will be copied only after successful validation
     * TODO - move to admin e_model extension?
     *
     * @param array $src_data
     * @param boolean $sanitize
     * @param boolean $validate perform validation check
     * @return e_model
     */
    public function mergeData(array $src_data, $strict = true, $sanitize = true, $validate = true)
    {
    	//FIXME
    	if(!$src_data || ($validate && !$this->validate($src_data)))
    	{
    		return $this;
    	}
		
		/* Wrong?
		// retrieve only valid data
		if($validate) 
		{
			$src_data = $this->getValidator()->getValidData();
		}*/
    	
		if($sanitize)
		{
			// search for db_field types
			if($this->getDbFields())
			{
				$src_data = $this->sanitize($src_data);
			}
			else //no db field types, use toDB()
			{
				$src_data = $tp->toDB($src_data);
			}
		}
   		
		foreach ($src_data as $key => $value)
		{
			$this->setData($key, $value, $strict);
		}
   		
    	return $this;
    }
    
    /**
     * Validate posted data:
     * 1. validate posted data against object validation rules
     * 2. add validation errors to the object if any
     * 3. return true for valid and false for non-valid data
     *
     * @param array $data optional - data for validation, defaults to posted data
     * @return boolean
     */
    public function validate(array $data = array())
    {
    	if(!$this->getValidationRules())
    	{
    		return true;
    	}
		if(!$data) 
		{
			$data = $this->getPostedData();
		}
		return $this->getValidator()->validate($data);
    }
	
    /**
     * User defined model validation
     * Awaiting for child class implementation
     *
     */
    public function verify()
    {
    }
    
	/**
	 * @return e_validator
	 */
	public function getValidator()
	{
		if(null === $this->_validator)
		{
			$this->_validator = e107::getObject('e_validator');
			$this->_validator->setRules($this->getValidationRules())->setMessageStack($this->_message_stack.'_validator');
			//TODO - optional check rules
		}
		return $this->_validator;
	}

	/**
	 * Add custom validation message.
	 * $field_type and $error_code will be inserted via sprintf()
	 * in the $message string
	 * Example: 
	 * <code>
	 * $model->addValidationError('Custom error message [#%d] for %s', 'My Field', 1000); 
	 * //produces 'Custom error message [#1000] for My Field'
	 * </code>
	 * 
	 * @param string $message
	 * @param string $field_title [optional]
	 * @param integer $error_code [optional]
	 * @return 
	 */
	public function addValidationError($message, $field_title = '', $error_code = '')
	{
		$this->getValidator()->addValidateMessage($field_title, $error_code, $message);
		return $this;
	}
    
    /**
     * Render validation errors (if any)
     * 
     * @param boolean $session store messages to session
     * @param boolean $reset reset errors
     * @return string
     */
    public function renderValidationErrors($session = false, $reset = true)
    {
		return $this->getValidator()->renderValidateMessages($session, $reset);
    }
	
    /**
     * Render System messages (if any)
     * 
     * @param boolean $validation render validation messages as well
     * @param boolean $session store messages to session
     * @param boolean $reset reset errors
     * @return string
     */
    public function renderMessages($validation = true, $session = false, $reset = true)
    {
    	if($validation)
		{
			e107::getMessage()->moveStack($this->_message_stack.'_validator', $this->_message_stack, false, $session);
		}
		return parent::renderMessages($session, $reset);
    }

    /**
     * @return boolean
     */
    public function hasValidationError()
    {
    	return $this->getValidator()->isValid();
    }
	
    /**
     * @return boolean
     */
    public function hasSqlError()
    {
    	return !empty($this->_db_errno);
    }
	
    /**
     * @return boolean
     */
    public function hasError()
    {
    	return ($this->hasValidationError() || $this->hasSqlError());
    }
	
    /**
     * Save data to DB
     * 
     * @param boolen $from_post
     */
    public function save($from_post = true)
    {
    	if(!$this->getFieldIdName())
		{
			return false;
		}
		
		if($from_post)
		{
			//no strict copy, validate & sanitize 
			$this->mergePostedData(false, true, true);
		}
		
		if($this->getId())
		{
			return $this->dbUpdate();
		}
		
		return $this->dbInsert();
    }
    
    /**
     * Insert data to DB
     * 
     * @param boolean $force force query even if $data_has_changed is false
     * @param boolean $session_messages to use or not session to store system messages
     */
    public function dbInsert($force = false, $session_messages = false)
    {
    	$this->_db_errno = 0;
		if(!$this->data_has_changed && !$force)
		{
			return 0;
		}
		
		$res = e107::getDb()->db_Insert($this->getModelTable(), $this->toSqlQuery('create'));
		if(!$res)
		{
			$this->_db_errno = e107::getDb()->getLastErrorNumber();
			$this->addMessageError('SQL Insert Error', $session_messages); //TODO - Lan
			$this->addMessageDebug('SQL Error #'.$this->_db_errno.': '.e107::getDb()->getLastErrorText());
		}
		
		return $res;
    }
	
    /**
     * Replace data in DB
     * 
     * @param boolean $force force query even if $data_has_changed is false
     * @param boolean $session_messages to use or not session to store system messages
     */
    public function dbReplace($force = false, $session_messages = false)
    {
    	$this->_db_errno = 0;
		if(!$this->data_has_changed && !$force)
		{
			return 0;
		}
		
		$res = e107::getDb()->db_Insert($this->getModelTable(), $this->toSqlQuery('replace'));
		if(!$res)
		{
			$this->_db_errno = e107::getDb()->getLastErrorNumber();
			if($this->_db_errno)
			{
				$this->addMessageError('SQL Replace Error', $session_messages); //TODO - Lan
				$this->addMessageDebug('SQL Error #'.$this->_db_errno.': '.e107::getDb()->getLastErrorText());
			}
		}
		
		return $res;
    }
    
    /**
     * Update DB data
     * 
     * @param boolean $force force query even if $data_has_changed is false
     * @param boolean $session_messages to use or not session to store system messages
     */
    public function dbUpdate($force = false, $session_messages = false)
    {
    	$this->_db_errno = 0;
		$res = e107::getDb()->db_Update($this->getModelTable(), $this->toSqlQuery('update'));
		if(!$res)
		{
			$this->_db_errno = e107::getDb()->getLastErrorNumber();
			if($this->_db_errno)
			{
				$this->addMessageError('SQL Update Error', $session_messages); //TODO - Lan
				$this->addMessageDebug('SQL Error #'.$this->_db_errno.': '.e107::getDb()->getLastErrorText());
			}
		}
    }
	
	/**
	 * Build query array to be used with db methods (db_Update, db_Insert, db_Replace)
	 * 
	 * @param string $force [optional] force action - possible values are create|update|replace
	 * @return array db query
	 */
	public function toSqlQuery($force = '')
	{
		$fields = array_keys($this->_db_fields);
		$qry = array();
		
		$action = $this->getId() ? 'update' : 'create';
		if($force)
		{
			$action = $force;
		}
		
		foreach ($fields as $key => $value)
		{
			$qry['data'][$key] = $this->getData($key);
		}
		$qry['_FIELD_TYPES'] = $this->_FIELD_TYPES;
		
		switch($action)
		{
			case 'create':
				$qry['data'][$this->getFieldIdName()] = 0;
			break;
			case 'replace':
				$qry['_REPLACE'] = true;
			break;
		
			case 'update':
				unset($qry['data'][$this->getFieldIdName()]);
				$qry['WHERE'] = $this->getFieldIdName().'='.intval($this->getId()); //intval just in case...
			break;
		}
		
		return $qry;
	}
	
	/**
	 * Sanitize value based on its db field type ($_db_fields),
	 * method will return null only if db field rule is not found.
	 * If $value is null, it'll be retrieved from object posted data
	 * If $key is an array, $value is omitted.
	 * 
	 * NOTE: If $key is not found in object's _db_fields array, null is returned
	 * 
	 * @param mixed $key string key name or array data to be sanitized
	 * @param mixed $value 
	 * @return mixed sanitized $value or null on failure
	 */
	public function sanitize($key, $value = null)
	{
		$tp = e107::getParser();
		if(is_array($key))
		{
			$ret = array(); 
			foreach ($key as $k=>$v)
			{
				if(isset($this->_db_fields[$k]))
				{
					$ret[$k] = $this->toDb($k, $v);
				}
			}
			return $ret;
		}
		
		if(!isset($this->_db_fields[$key]))
		{
			return null;
		}
		$type =  $this->_db_fields[$key];
		if(null === $value)
		{
			$value = $this->getPostedData($key);
		}
		
		switch ($type)
		{
			case 'int':
			case 'integer':
				return intval($this->toNumber($value));
			break;

			case 'str':
			case 'string':
				return $tp->toDB($value);
			break;

			case 'float': 
				return $this->toNumber($value);
			break;

			case 'bool':
			case 'boolean':
				return ($value ? true : false);
			break;

			case 'model':
				return $value->mergePostedData()->toArray(); //XXX - ???
			break;
			
			case 'null':
				return ($value ? $tp->toDB($value) : null);
			break;
	  	}
		
		return null;
	}
}
