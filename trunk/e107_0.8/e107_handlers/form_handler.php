<?php
/*
 * e107 website system
 *
 * Copyright (C) 2001-2008 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * Form Handler
 *
 * $Source: /cvs_backup/e107_0.8/e107_handlers/form_handler.php,v $
 * $Revision: 1.69 $
 * $Date: 2009-11-05 17:32:18 $
 * $Author: secretr $
 *
*/

if (!defined('e107_INIT')) { exit; }
//FIXME hardcoded text
/**
 * Automate Form fields creation. Produced markup is following e107 CSS/XHTML standards
 * If options argument is omitted, default values will be used (which OK most of the time)
 * Options are intended to handle some very special cases.
 *
 * Overall field options format (array or GET string like this one: var1=val1&var2=val2...):
 *
 *  - id => (mixed) custom id attribute value
 *  if numeric value is passed it'll be just appended to the name e.g. {filed-name}-{value}
 *  if false is passed id will be not created
 *  if empty string is passed (or no 'id' option is found)
 *  in all other cases the value will be used as field id
 * 	default: empty string
 *
 *  - class => (string) field class(es)
 * 	Example: 'tbox select class1 class2 class3'
 * 	NOTE: this will override core classes, so you have to explicit include them!
 * 	default: empty string
 *
 *  - size => (int) size attribute value (used when needed)
 *	default: 40
 *
 *  - title (string) title attribute
 *  default: empty string (omitted)
 *
 *  - readonly => (bool) readonly attribute
 * 	default: false
 *
 *  - selected => (bool) selected attribute (used when needed)
 * 	default: false
 *
 *  checked => (bool) checked attribute (used when needed)
 *  default: false
 *  - disabled => (bool) disabled attribute
 *  default: false
 *
 *  - tabindex => (int) tabindex attribute value
 *	default: inner tabindex counter
 *
 *  - other => (string) additional data
 *  Example: 'attribute1="value1" attribute2="value2"'
 *  default: empty string
 */
class e_form
{
	protected $_tabindex_counter = 0;
	protected $_tabindex_enabled = true;
	protected $_cached_attributes = array();

	/**
	 * @var user_class
	 */
	protected $_uc;

	function __construct($enable_tabindex = false)
	{
		
		$this->_tabindex_enabled = $enable_tabindex;
		$this->_uc = e107::getUserClass();		
	}

	function text($name, $value, $maxlength = 200, $options = array())
	{
		$options = $this->format_options('text', $name, $options);
		//never allow id in format name-value for text fields
		return "<input type='text' name='{$name}' value='{$value}' maxlength='{$maxlength}'".$this->get_attributes($options, $name)." />";
	}
	
	function iconpreview($id, $default, $width='', $height='') // FIXME
	{		
		$parms = $name."|".$width."|".$height."|".$id;
		$sc_parameters .= 'mode=preview&default='.$default.'&id='.$id;
		return e107::getParser()->parseTemplate("{ICONPICKER=".$sc_parameters."}");
	}

	function iconpicker($name, $default, $label, $sc_parameters = '', $ajax = true)
	{
    	// TODO - Hide the <input type='text'> element, and display the icon itself after it has been chosen.
		// eg. <img id='iconview' src='".$img."' style='border:0; ".$blank_display."' alt='' />
		// The button itself could be replaced with an icon just for this purpose.


		$e107 = e107::getInstance();
		$id = $this->name2id($name);
		$sc_parameters .= '&id='.$id;
		$jsfunc = $ajax ? "e107Ajax.toggleUpdate('{$id}-iconpicker', '{$id}-iconpicker-cn', 'sc:iconpicker=".urlencode($sc_parameters)."', '{$id}-iconpicker-ajax', { overlayElement: '{$id}-iconpicker-button' })" : "e107Helper.toggle('{$id}-iconpicker')";
		$ret = $this->text($name, $default);
	//	$ret .= $this->iconpreview($id,$default); //FIXME
		$ret .= $this->admin_button($name.'-iconpicker-button', $label, 'action', '', array('other' => "onclick=\"{$jsfunc}\""));
		$ret .= "
			<div id='{$id}-iconpicker' class='e-hideme'>
				<div class='expand-container' id='{$id}-iconpicker-cn'>
					".(!$ajax ? $e107->tp->parseTemplate('{ICONPICKER='.$sc_parameters.'}') : '')."
				</div>
			</div>
		";

		return $ret;
	}
	
	// FIXME - better GUI, {IMAGESELECTOR} rewrite, flexibility, thumbnails, tooltip image preivew, etc.
	function imagepicker($name, $default, $label = '', $sc_parameters = '')
	{
		if(is_string($sc_parameters)) parse_str($sc_parameters, $sc_parameters);
		if(!$label) $label = LAN_SELECT;
		$parms = "name={$name}";
		$parms .= "&path=".urlencode(e107::getParser()->replaceConstants(vartue($sc_parameters['path'], '{e_FILE}images/')));
		$parms .= "&filter=0";
		$parms .= "&fullpath=1";
		$parms .= "&default=".$default;
		$parms .= "&multiple=FALSE";
		$parms .= "&label=-- ".$label." --";
		$parms .= "&subdirs=0";
		//$parms .= "&tabindex=".$this->getNext();
		//$parms .= "&click_target=data";
		//$parms .= "&click_prefix=[img][[e_IMAGE]]newspost_images/";
		//$parms .= "&click_postfix=[/img]";
		$tp = e107::getParser();
		$ret = "<div class='field-section'>".$tp->parseTemplate("{IMAGESELECTOR={$parms}&scaction=select}")."</div>";
		$ret .= "<div class='field-spacer'>".$tp->parseTemplate("{IMAGESELECTOR={$parms}&scaction=preview}")."</div>";
		return $ret;
	}
	
	/**
	 * Date field with popup calendar
	 * $options allowed keys:
	 * - time: show time, default is true
	 * - others: ???, default is false
	 * - weeks: show weeks, default is false
	 * - size: input field size attribute, default 25
	 * 
	 * @param string $name the name of the field
	 * @param integer $datestamp UNIX timestamp - default value of the field
	 * @param array $options calendar options
	 */
	function datepicker($name, $datestamp = false, $options = array())
	{
		$cal = new DHTML_Calendar(true);
		$cal_options['showsTime'] = varset($options['time'], true);
		$cal_options['showOthers'] = varset($options['others'], false);
		$cal_options['weekNumbers'] = varset($options['weeks'], false);
		$cal_options['ifFormat'] = e107::getPref('inputdate', '%d/%m/%Y %H:%M:%S');
		$cal_options['timeFormat'] = "24";
		$cal_attrib['class'] = "tbox date";
		$cal_attrib['size'] = varset($options['size'], 25);
		$cal_attrib['name'] = $name;
		if ($datestamp)
		{
		   $cal_attrib['value'] = e107::getDateConvert()->convert_date($datestamp, 'input'); //date("d/m/Y H:i:s", $datestamp);
		}
		//JS manager to send JS/CSS to header if possible, if not - footer
		e107::getJs()// FIXME - no CSS support yet!!! ->tryHeaderFile($cal->calendar_theme_file)
			->tryHeaderFile($cal->calendar_file)
			->tryHeaderFile($cal->calendar_setup_file)
			->tryHeaderFile($cal->calendar_lang_file);

		return $cal->make_input_field($cal_options, $cal_attrib);
	}
	
	function user($name, $default_id, $options = array())
	{
		return 'User auto-complete search - under development';
	}

	function file($name, $options = array())
	{
		$options = $this->format_options('file', $name, $options);
		//never allow id in format name-value for text fields
		return "<input type='file' name='{$name}'".$this->get_attributes($options, $name)." />";
	}


	function password($name, $maxlength = 50, $options = array())
	{
		$options = $this->format_options('text', $name, $options);
		//never allow id in format name-value for text fields
		return "<input type='password' name='{$name}' value='' maxlength='{$maxlength}'".$this->get_attributes($options, $name)." />";
	}

	function textarea($name, $value, $rows = 15, $cols = 40, $options = array())
	{
		$options = $this->format_options('textarea', $name, $options);
		//never allow id in format name-value for text fields
		return "<textarea name='{$name}' rows='{$rows}' cols='{$cols}'".$this->get_attributes($options, $name).">{$value}</textarea>";
	}

	function bbarea($name, $value, $help_mod = '', $help_tagid='', $size = 'large')
	{
		//size - large|medium|small
		//width should be explicit set by current admin theme
		switch($size)
		{			
			case 'medium':
				$rows = '10';
			break;
			
			case 'small':
				$rows = '7';
			break;
			
			case 'large':
			default:
				$rows = '15';
				$size = 'large';
			break;
		}
	   	$options = array('class' => 'tbox'.($size ? ' '.$size : '').' e-wysiwyg');
		$bbbar = '';
		// FIXME - see ren_help.php
		if(!deftrue('e_WYSIWYG'))
		{
			require_once(e_HANDLER."ren_help.php");
			$options['other'] = "onselect='storeCaret(this);' onclick='storeCaret(this);' onkeyup='storeCaret(this);'";
			$bbbar = display_help($help_tagid, $help_mod, 'addtext', 'help', $size);
		}

		$ret = "
		<div class='bbarea {$size}'>
			".$this->textarea($name, $value, $rows, 50, $options)."
			<div class='field-spacer'><!-- --></div>
			{$bbbar}
		</div>
		";

		return $ret;
	}

	function checkbox($name, $value, $checked = false, $options = array())
	{
		$options = $this->format_options('checkbox', $name, $options);
		$options['checked'] = $checked; //comes as separate argument just for convenience
		return "<input type='checkbox' name='{$name}' value='{$value}'".$this->get_attributes($options, $name, $value)." />";

	}
	
	function checkbox_label($label_title, $name, $value, $checked = false, $options = array())
	{
		return $this->checkbox($name, $value, $checked, $options).$this->label($label_title, $name, $value);
	}

	function checkbox_switch($name, $value, $checked = false, $label = '')
	{
		return $this->checkbox($name, $value, $checked).$this->label($label ? $label : LAN_ENABLED, $name, $value);
	}

	function checkbox_toggle($name, $selector = 'multitoggle')
	{
		$selector = 'jstarget:'.$selector;
		return $this->checkbox($name, $selector, false, array('id'=>false,'class'=>'checkbox toggle-all'));
	}

	function uc_checkbox($name, $current_value, $uc_options, $field_options = array())
	{
		if(!is_array($field_options)) parse_str($field_options, $field_options);
		return '
			<div class="check-block">
				'.$this->_uc->vetted_tree($name, array($this, '_uc_checkbox_cb'), $current_value, $uc_options, $field_options).'
			</div>
		';
	}

	function _uc_checkbox_cb($treename, $classnum, $current_value, $nest_level, $field_options)
	{
		if($classnum == e_UC_BLANK)
			return '';

		$tmp = explode(',', $current_value); //TODO add support for when $current_value is an array.
		
		$class = $style = '';
		if($nest_level == 0)
		{
			$class = " strong";
		}
		else
		{
			$style = " style='text-indent:" . (1.2 * $nest_level) . "em'";
		}
		$descr = varset($field_options['description']) ? ' <span class="smalltext">('.$this->_uc->uc_get_classdescription($classnum).')</span>' : '';

		return "<div class='field-spacer{$class}'{$style}>".$this->checkbox($treename.'[]', $classnum, in_array($classnum, $tmp), $field_options).$this->label($this->_uc->uc_get_classname($classnum).$descr, $treename.'[]', $classnum)."</div>\n";
	}
	
	
	function uc_label($classnum)
	{
		return $this->_uc->uc_get_classname($classnum);	
	}

	function radio($name, $value, $checked = false, $options = array())
	{
		$options = $this->format_options('radio', $name, $options);
		$options['checked'] = $checked; //comes as separate argument just for convenience
		return "<input type='radio' name='{$name}' value='".$value."'".$this->get_attributes($options, $name, $value)." />";

	}

	function radio_switch($name, $checked_enabled = false, $label_enabled = '', $label_disabled = '')
	{
		return $this->radio($name, 1, $checked_enabled)."".$this->label($label_enabled ? $label_enabled : LAN_ENABLED, $name, 1)."&nbsp;&nbsp;
			".$this->radio($name, 0, !$checked_enabled)."".$this->label($label_disabled ? $label_disabled : LAN_DISABLED, $name, 0);

	}

	function radio_multi($name, $elements, $checked, $multi_line = false)
	{
		$text = array();
		if(is_string($elements)) parse_str($elements, $elements);

		foreach ($elements as $value => $label)
		{
			$text[] = $this->radio($name, $value, $checked == $value)."".$this->label($label, $name, $value);
		}
		if(!$multi_line)
			return implode("&nbsp;&nbsp;", $text);

		return "<div class='field-spacer'>".implode("</div><div class='field-spacer'>", $text)."</div>";

	}

	function label($text, $name = '', $value = '')
	{
		$for_id = $this->_format_id('', $name, $value, 'for');
		return "<label$for_id>{$text}</label>";
	}

	function select_open($name, $options = array())
	{
		$options = $this->format_options('select', $name, $options);
		return "<select name='{$name}'".$this->get_attributes($options, $name).">";
	}

	function selectbox($name, $option_array, $selected = false, $options = array())
	{
		if($option_array == 'yesno')
		{
			$option_array = array(1=>LAN_YES,0=>LAN_NO);
		} 
		return $this->select_open($name, $options)."\n".$this->option_multi($option_array, $selected)."\n".$this->select_close();
	}

	function uc_select($name, $current_value, $uc_options, $select_options = array(), $opt_options = array())
	{
		return $this->select_open($name, $select_options)."\n".$this->_uc->vetted_tree($name, array($this, '_uc_select_cb'), $current_value, $uc_options, $opt_options)."\n".$this->select_close();
	}

	// Callback for vetted_tree - Creates the option list for a selection box
	function _uc_select_cb($treename, $classnum, $current_value, $nest_level)
	{
		if($classnum == e_UC_BLANK)
			return $this->option('&nbsp;', '');

		$tmp = explode(',', $current_value);
		if($nest_level == 0)
		{
			$prefix = '';
			$style = "font-weight:bold; font-style: italic;";
		}
		elseif($nest_level == 1)
		{
			$prefix = '&nbsp;&nbsp;';
			$style = "font-weight:bold";
		}
		else
		{
			$prefix = '&nbsp;&nbsp;'.str_repeat('--', $nest_level - 1).'&gt;';
			$style = '';
		}
		return $this->option($prefix.$this->_uc->uc_get_classname($classnum), $classnum, in_array($classnum, $tmp), array("style"=>"{$style}"))."\n";
	}

	function optgroup_open($label, $disabled)
	{
		return "<optgroup class='optgroup' label='{$label}'".($disabled ? " disabled='disabled'" : '').">";
	}

	function option($option_title, $value, $selected = false, $options = array())
	{
		if(false === $value) $value = '';
		$options = $this->format_options('option', '', $options);
		$options['selected'] = $selected; //comes as separate argument just for convenience
		return "<option value='{$value}'".$this->get_attributes($options).">{$option_title}</option>";
	}

	function option_multi($option_array, $selected = false, $options = array())
	{
		if(is_string($option_array)) parse_str($option_array, $option_array);

		$text = '';
		foreach ($option_array as $value => $label)
		{
			$text .= $this->option($label, $value, $selected == $value, $options)."\n";
		}

		return $text;
	}

	function optgroup_close()
	{
		return "</optgroup>";
	}

	function select_close()
	{
		return "</select>";
	}

	function hidden($name, $value, $options = array())
	{
		$options = $this->format_options('hidden', $name, $options);
		return "<input type='hidden' name='{$name}' value='{$value}'".$this->get_attributes($options, $name, $value)." />";
	}

	function submit($name, $value, $options = array())
	{
		$options = $this->format_options('submit', $name, $options);
		return "<input type='submit' name='{$name}' value='{$value}'".$this->get_attributes($options, $name, $value)." />";
	}

	function submit_image($name, $value, $image, $title='', $options = array())
	{
		$options = $this->format_options('submit_image', $name, $options);
		switch ($image) {
			case 'edit':
				$image = ADMIN_EDIT_ICON_PATH;
				$options['class'] = 'action edit';
			break;

			case 'delete':
				$image = ADMIN_DELETE_ICON_PATH;
				$options['class'] = 'action delete';
			break;
		}
		$options['title'] = $title;//shorthand

		return "<input type='image' src='{$image}' name='{$name}' value='{$value}'".$this->get_attributes($options, $name, $value)." />";
	}

	/**
	 * 
	 * @param object $name
	 * @param object $value
	 * @param object $action [optional] default is submit
	 * @param object $label [optional]
	 * @param object $options [optional]
	 * @return 
	 */
	function admin_button($name, $value, $action = 'submit', $label = '', $options = array())
	{
		$btype = 'submit';
		if(strpos($action, 'action') === 0) $btype = 'button';
		$options = $this->format_options('admin_button', $name, $options);
		$options['class'] = $action;//shorthand
		if(empty($label)) $label = $value;

		return "
			<button type='{$btype}' name='{$name}' value='{$value}'".$this->get_attributes($options, $name)."><span>{$label}</span></button>
		";
	}

	function getNext()
	{
		if(!$this->_tabindex_enabled) return 0;
		$this->_tabindex_counter += 1;
		return $this->_tabindex_counter;
	}

	function getCurrent()
	{
		if(!$this->_tabindex_enabled) return 0;
		return $this->_tabindex_counter;
	}

	function resetTabindex($reset = 0)
	{
		$this->_tabindex_counter = $reset;
	}

	function get_attributes($options, $name = '', $value = '')
	{
		$ret = '';
		//
		foreach ($options as $option => $optval)
		{
			switch ($option) {

				case 'id':
					$ret .= $this->_format_id($optval, $name, $value);
					break;

				case 'class':
					if(!empty($optval)) $ret .= " class='{$optval}'";
					break;

				case 'size':
					if($optval) $ret .= " size='{$optval}'";
					break;

				case 'title':
					if($optval) $ret .= " title='{$optval}'";
					break;
					
				case 'label':
					if($optval) $ret .= " label='{$optval}'";
					break;

				case 'tabindex':
					if($optval) $ret .= " tabindex='{$optval}'";
					elseif(false === $optval || !$this->_tabindex_enabled) break;
					else
					{
						$this->_tabindex_counter += 1;
						$ret .= " tabindex='".$this->_tabindex_counter."'";
					}
					break;

				case 'readonly':
					if($optval) $ret .= " readonly='readonly'";
					break;

				case 'selected':
					if($optval) $ret .= " selected='selected'";
					break;

				case 'checked':
					if($optval) $ret .= " checked='checked'";
					break;

				case 'disabled':
					if($optval) $ret .= " disabled='disabled'";
					break;

				case 'other':
					if($optval) $ret .= " $optval";
					break;
			}
		}

		return $ret;
	}

	/**
	 * Auto-build field attribute id
	 *
	 * @param string $id_value value for attribute id passed with the option array
	 * @param string $name the name attribute passed to that field
	 * @param unknown_type $value the value attribute passed to that field
	 * @return string formatted id attribute
	 */
	function _format_id($id_value, $name, $value = '', $return_attribute = 'id')
	{
		if($id_value === false) return '';

		//format data first
		$name = trim($this->name2id($name), '-');
		$value = trim(preg_replace('#[^a-z0-9\-]/i#','-', $value), '-');
		$value = trim(str_replace("/","-",$value), '-');
		if(!$id_value && is_numeric($value)) $id_value = $value;
		
		// clean - do it better, this could lead to dups
		$id_value = trim($id_value, '-');

		if(empty($id_value) ) return " {$return_attribute}='{$name}".($value ? "-{$value}" : '')."'";// also useful when name is e.g. name='my_name[some_id]'
		elseif(is_numeric($id_value) && $name) return " {$return_attribute}='{$name}-{$id_value}'";// also useful when name is e.g. name='my_name[]'
		else return " {$return_attribute}='{$id_value}'";
	}

	function name2id($name)
	{
		return rtrim(str_replace(array('[]', '[', ']', '_', '/'), array('-', '-', '', '-', '-'), $name), '-');
	}


	/**
	 * Format options based on the field type,
	 * merge with default
	 *
	 * @param string $type
	 * @param string $name form name attribute value
	 * @param array|string $user_options
	 * @return array merged options
	 */
	function format_options($type, $name, $user_options)
	{
		if(is_string($user_options)) parse_str($user_options, $user_options);

		$def_options = $this->_default_options($type);

		foreach (array_keys($user_options) as $key)
		{
			if(!isset($def_options[$key])) unset($user_options[$key]);//remove it?
		}

		$user_options['name'] = $name; //required for some of the automated tasks
		return array_merge($def_options, $user_options);
	}

	/**
	 * Get default options array based on the field type
	 *
	 * @param string $type
	 * @return array default options
	 */
	function _default_options($type)
	{
		if(isset($this->_cached_attributes[$type])) return $this->_cached_attributes[$type];

		$def_options = array(
			'id' => '',
			'class' => '',
			'title' => '',
			'size' => '',
			'readonly' => false,
			'selected' => false,
			'checked' => false,
			'disabled' => false,
			'tabindex' => 0,
			'label' => '',
			'other' => ''
		);

		switch ($type) {
			case 'hidden':
				$def_options = array('id' => false, 'disabled' => false, 'other' => '');
				break;

			case 'text':
				$def_options['class'] = 'tbox input-text';
				unset($def_options['selected'], $def_options['checked']);
				break;

			case 'file':
				$def_options['class'] = 'tbox file';
				unset($def_options['selected'], $def_options['checked']);
				break;

			case 'textarea':
				$def_options['class'] = 'tbox textarea';
				unset($def_options['selected'], $def_options['checked'], $def_options['size']);
				break;

			case 'select':
				$def_options['class'] = 'tbox select';
				unset($def_options['checked'],  $def_options['checked']);
				break;

			case 'option':
				$def_options = array('class' => '', 'selected' => false, 'other' => '', 'disabled' => false, 'label' => '');
				break;

			case 'radio':
				$def_options['class'] = 'radio';
				unset($def_options['size'], $def_options['selected']);
				break;

			case 'checkbox':
				$def_options['class'] = 'checkbox';
				unset($def_options['size'],  $def_options['selected']);
				break;

			case 'submit':
				$def_options['class'] = 'button';
				unset($def_options['checked'], $def_options['selected'], $def_options['readonly']);
				break;

			case 'submit_image':
				$def_options['class'] = 'action';
				unset($def_options['checked'], $def_options['selected'], $def_options['readonly']);
				break;

			case 'admin_button':
				unset($def_options['checked'],  $def_options['selected'], $def_options['readonly']);
				break;

		}

		$this->_cached_attributes[$type] = $def_options;
		return $def_options;
	}





	function columnSelector($columnsArray, $columnsDefault = '', $id = 'column_options')
	{
		$columnsArray = array_filter($columnsArray);
        $text = "
		<div class='col-selection-cont'>
			<a href='#".$id."' class='e-show-if-js e-expandit' title='Click to select columns to display'>"
				."<img class='icon' src='".e_IMAGE_ABS."admin_images/select_columns_16.png' alt='select columns' />"
			."</a>
			<div id='".$id."' class='e-show-if-js e-hideme col-selection'><div class='col-selection-body'>
		";
        unset($columnsArray['options'], $columnsArray['checkboxes']);

		foreach($columnsArray as $key => $fld)
		{
			if (!varset($fld['forced']) && !vartrue($fld['nolist']))
			{
				$checked = (in_array($key,$columnsDefault)) ?  TRUE : FALSE;
				$text .= "
					<div class='field-spacer'>
						".$this->checkbox_label(varset($fld['title'], $key), 'e-columns[]', $key, $checked)."
					</div>
				";
			}
		}

		// has issues with the checkboxes.
        $text .= "
				<div id='{$id}-button' class='right'>
					".$this->admin_button('etrigger_ecolumns', LAN_SAVE, 'update')."
				</div>
			</div></div>
		</div>
		";  

		$text .= "";
		return $text;
	}

	function colGroup($fieldarray, $columnPref = '')
	{
        $text = "";
        $count = 0;
		foreach($fieldarray as $key=>$val)
		{
			if ((in_array($key, $columnPref) || $key=='options' || varsettrue($val['forced'])) && !vartrue($val['nolist']))
			{
				$class = vartrue($val['class']) ? ' class="'.$val['class'].'"' : '';
				$text .= '
					<col style="width: '.$val['width'].';"'.$class.'></col>
				';
				$count++;
			}
		}

		return '
			<colgroup span="'.$count.'">
				'.$text.'
			</colgroup>
		';
	}

	function thead($fieldarray, $columnPref = array(), $querypattern = '', $requeststr = '')
	{
        $text = "";

		// Recommended pattern: mode=list&field=[FIELD]&asc=[ASC]&from=[FROM]
		
		if(strpos($querypattern,'&')!==FALSE)
		{
			// we can assume it's always $_GET since that's what it will generate 
			// more flexible (e.g. pass default values for order/field when they can't be found in e_QUERY) & secure
			$tmp = str_replace('&amp;', '&', $requeststr ? $requeststr : e_QUERY); 
			parse_str($tmp, $tmp);
			
			$etmp = array();
			parse_str(str_replace('&amp;', '&', $querypattern), $etmp);
		}
		else // Legacy Queries. eg. main.[FIELD].[ASC].[FROM]
		{
			$tmp = explode(".", ($requeststr ? $requeststr : e_QUERY)); 
			$etmp = explode(".", $querypattern);
		}

		foreach($etmp as $key => $val)    // I'm sure there's a more efficient way to do this, but too tired to see it right now!.
		{
        	if($val == "[FIELD]")
			{
            	$field = $tmp[$key];
			}

			if($val == "[ASC]")
			{
            	$ascdesc = $tmp[$key];
			}
			if($val == "[FROM]")
			{
            	$fromval = $tmp[$key];
			}
		}

		if(!varset($fromval)){ $fromval = 0; }

        $ascdesc = (varset($ascdesc) == 'desc') ? 'asc' : 'desc';
		foreach($fieldarray as $key=>$val)
		{
     		if ((in_array($key, $columnPref) || $key == 'options' || (vartrue($val['forced']))) && !vartrue($val['nolist']))
			{
				$cl = (vartrue($val['thclass'])) ? " class='".$val['thclass']."'" : "";
				$text .= "
					<th id='e-column-".str_replace('_', '-', $key)."'{$cl}>
				";

                if($querypattern!="" && !varsettrue($val['nosort']) && $key != "options" && $key != "checkboxes")
				{
					$from = ($key == $field) ? $fromval : 0;
					$srch = array("[FIELD]","[ASC]","[FROM]");
					$repl = array($key,$ascdesc,$from);
                	$val['url'] = e_SELF."?".str_replace($srch,$repl,$querypattern);
				}
				
				$text .= (vartrue($val['url'])) ? "<a href='".str_replace(array('&amp;', '&'), array('&', '&amp;'),$val['url'])."'>" : "";  // Really this column-sorting link should be auto-generated, or be autocreated via unobtrusive js.
	            $text .= vartrue($val['title'], '');
				$text .= ($val['url']) ? "</a>" : "";
	            $text .= ($key == "options") ? $this->columnSelector($fieldarray, $columnPref) : "";
				$text .= ($key == "checkboxes") ? $this->checkbox_toggle('e-column-toggle', vartrue($val['toggle'], 'multiselect')) : "";


	 			$text .= "
					</th>
				";
			}
		}

		return "
		<thead>
	  		<tr>".$text."</tr>
		</thead>
		";

	}
	/**
	 * Render Table cells from field listing. 
	 * @param array $fieldarray - eg. $this->fields
	 * @param array $currentlist - eg $this->fieldpref
	 * @param array $fieldvalues - eg. $row
	 * @param string $pid - eg. table_id
	 * @return 
	 */
	
	function renderTableRow($fieldarray, $currentlist, $fieldvalues, $pid)
	{
		$cnt = 0;
		$ret = '';
		
		/*$fieldarray 	= $obj->fields;
		$currentlist 	= $obj->fieldpref;
		$pid 			= $obj->pid;*/
	
		$trclass = vartrue($fieldvalues['__trclass']) ?  ' class="'.$trclass.'"' : '';
		unset($fieldvalues['__trclass']);
		
		foreach ($fieldarray as $field => $data)
		{
		
			//Not found
			if((!$data['forced'] && !in_array($field, $currentlist)) || $data['nolist'])
			{
				continue;
			}
			elseif(!$data['forced'] && !isset($fieldvalues[$field]))
			{
				$ret .= "
					<td>
						Not Found!
					</td>
				";
				
				continue;
			}
			
			$tdclass = vartrue($data['class']);
			if($field == 'checkboxes') $tdclass = $tdclass ? $tdclass.' autocheck e-pointer' : 'autocheck e-pointer';
			$value = $this->renderValue($field, $fieldvalues[$field], $data, $fieldvalues[$pid]);

			if($tdclass)
			{
				$tdclass = ' class="'.$tdclass.'"';
			}
			$ret .= '
				<td'.$tdclass.'>
					'.$value.'
				</td>
			';
			
			$cnt++;
		}
		
		if($cnt)
		{
			return '
				<tr'.$trclass.'>
					'.$ret.'
				</tr>
			';
		}
		
		return '';
	}
	
	/**
	 * Render Field Value
	 * @param string $field field name
	 * @param mixed $value field value
	 * @param array $attributes field attributes including render parameters, element options - see e_admin_ui::$fields for required format
	 * @return string
	 */
	function renderValue($field, $value, $attributes, $id = 0)
	{
		$parms = array();
		if(isset($attributes['readParms']))
		{
			if(is_string($attributes['readParms'])) parse_str($attributes['readParms'], $attributes['readParms']);
			$parms = $attributes['readParms'];
		}
		$tp = e107::getParser();
		switch($field) // special fields
		{
			case 'options':
				if(!$value)
				{
					parse_str(str_replace('&amp;', '&', e_QUERY), $query); //FIXME - FIX THIS
					// keep other vars in tact
					$query['action'] = 'edit'; 
					$query['id'] = $id; 
					
					//$edit_query = array('mode' => varset($query['mode']), 'action' => varset($query['action']), 'id' => $id);
					$query = http_build_query($query);

					$value = "<a href='".e_SELF."?{$query}' title='".LAN_EDIT."'><img class='icon action edit' src='".ADMIN_EDIT_ICON_PATH."' alt='".LAN_EDIT."' /></a>&nbsp;";
					$value .= $this->submit_image('etrigger_delete['.$id.']', $id, 'delete', LAN_DELETE.' [ ID: '.$id.' ]');
				}
				//$attributes['type'] = 'text';
				return $value;
			break;
		
			case 'checkboxes':
				$value = $this->checkbox(vartrue($attributes['toggle'], 'multiselect').'['.$id.']', $id);
				//$attributes['type'] = 'text';
				return $value;
			break;
		}
		
		switch($attributes['type'])
		{
			case 'number':
				if($parms)
				{
					if(!isset($parms['sep'])) $value = number_format($number, $parms['decimals']);
					else $value = number_format($number, $parms['decimals'], vartrue($parms['point'], '.'), vartrue($parms['sep'], ' '));
				}
				$value = vartrue($parms['pre']).$value.vartrue($parms['post']);
				// else same
			break;
			
			case 'dropdown':
			case 'text':
				if(vartrue($parms['truncate']))
				{
					$value = $tp->text_truncate($value, $parms['truncate'], '...');
				}
				elseif(vartrue($parms['htmltruncate']))
				{
					$value = $tp->html_truncate($value, $parms['htmltruncate'], '...');
				}
				$value = vartrue($parms['pre']).$value.vartrue($parms['post']);
			break;
			
			case 'bbarea':
			case 'textarea':
				$expand = '...';
				$toexpand = false;
				if($attributes['type'] == 'bbarea' && !isset($parms['bb'])) $parms['bb'] = true; //force bb parsing for bbareas
				$elid = trim(str_replace('_', '-', $field)).'-'.$id;
				if(!vartrue($parms['noparse'])) $value = $tp->toHTML($value, (vartrue($parms['bb']) ? true : false), vartrue($parms['parse']));
				if(vartrue($parms['expand']))
				{
					$expand = '&nbsp;<a href="#'.$elid.'-expand" class="e-show-if-js e-expandit">'.defset($parms['expand'], $parms['expand'])."</a>";
				}
				
				$oldval = $value;
				if(vartrue($parms['truncate']))
				{
					$value = $oldval = strip_tags($value);
					$value = $tp->text_truncate($value, $parms['truncate'], $expand);
					$toexpand = $value != $oldval;
				}
				elseif(vartrue($parms['htmltruncate']))
				{
					$value = $tp->html_truncate($value, $parms['htmltruncate'], $expand);
					$toexpand = $value != $oldval;
				}
				if($toexpand)
				{
					// force hide! TODO - core style .expand-c (expand container)
					$value .= '<div class="expand-c" style="display: none" id="'.$elid.'-expand"><div>'.$oldval.'</div></div>';
				}
			break;
			
			case 'icon':
				$value = '<img src="'.$tp->replaceConstants(vartrue($parms['pre']).$value, 'abs').'" alt="'.basename($value).'" class="icon'.(vartrue($parms['class']) ? ' '.$parms['class'] : '').'" />';
			break;
			
			case 'image': //TODO - thumb, js tooltip...
				$ttl = vartrue($parms['title'], 'LAN_PREVIEW');
				$value = '<a href="'.$tp->replaceConstants(vartrue($parms['pre']).$value, 'abs').'" title="'.basename($value).'">'.defset($ttl, $ttl).'</a>';
			break;
			
			case 'datestamp':
				$value = e107::getDateConvert()->convert_date($value, vartrue($parms['mask'], 'short'));
			break;
			
			case 'userclass':
				$value = $this->_uc->uc_get_classname($value);
			break;
			
			case 'userclasses':
				$classes = explode(',', $value);
				$value = array();
				foreach ($classes as $cid)
				{
					$value[] = $this->_uc->uc_get_classname($cid);
				}
				$value = implode(vartrue($parms['separator']), $pieces);
			break;
			
			case 'user_name':
			case 'user_loginname':
			case 'user_login':
			case 'user_customtitle':
			case 'user_email':
				if(is_numeric($value))
				{
					$value = get_user_data($value);
					if($value)
					{
						$value = $value[$attributes['type']] ? $value[$attributes['type']] : $value['user_name'];
					}
					else 
					{
						$value = 'not found';
					}
				}
			break;
			
			case 'boolean':
				$value = $value ? ADMIN_TRUE_ICON : '';// TODO  - ADMIN_FALSE_ICON
			break;
							
			case 'url':
				$ttl = $value;
				if(vartrue($parms['truncate']))
				{
					$ttl = $tp->text_truncate($value, $parms['truncate'], '...');
				}
				$value = "<a href='".$tp->replaceConstants(vartrue($parms['pre']).$value, 'abs')."' title='{$value}'>".$ttl."</a>";
			break;
			
			case 'method': // Custom Function 
				$method = $field; 
				$value = call_user_func_array(array($this, $method), array($value, 'read', $parms));
			break;
			
			//TODO - form_userclass, order,... and maybe more types
			
			default:
				//unknown type
			break;
		}
		
		return $value;
	}
	
	/**
	 * Auto-render Form Element
	 * @param string $key
	 * @param mixed $value
	 * @param array $attributes field attributes including render parameters, element options - see e_admin_ui::$fields for required format
	 * @return string
	 */
	function renderElement($key, $value, $attributes)
	{
		$parms = vartrue($attributes['writeParms'], array());
		if(is_string($parms)) parse_str($parms, $parms);
		
		switch($attributes['type'])
		{
			case 'number':
				$maxlength = vartrue($parms['maxlength'], 255);
				unset($parms['maxlength']);
				if(!vartrue($parms['size'])) $parms['size'] = 15;
				if(!vartrue($parms['class'])) $parms['class'] = 'tbox number';
				return vartrue($parms['pre']).$this->text($key, $value, $maxlength, $parms).vartrue($parms['post']);
			break;
			
			case 'url':
			case 'text':
				$maxlength = vartrue($parms['maxlength'], 255);
				unset($parms['maxlength']);
				return vartrue($parms['pre']).$this->text($key, $value, $maxlength, vartrue($parms['__options'])).vartrue($parms['post']);
			break;
			
			case 'textarea':
				return $this->textarea($key, $value, vartrue($parms['rows'], 15), vartrue($parms['cols'], 40), vartrue($parms['__options']));
			break;
			
			case 'bbarea':
				return $this->bbarea($key, $value, vartrue($parms['help']), vartrue($parms['helptag']), vartrue($parms['size']));
			break;
			
			case 'image': //TODO - thumb, image list shortcode, js tooltip...
				$label = varset($parms['label']);
				unset($parms['label']);
				return $this->imagepicker($key, $value, $label, vartrue($parms['__options']));
			break;
			
			case 'icon': 
				$label = varset($parms['label']);
				$ajax = varset($parms['ajax']) ? true : false;
				unset($parms['label'], $parms['ajax']);
				return $this->iconpicker($key, $value, $label, $parms, $ajax);
			break;
			
			case 'datestamp':
				return $this->datepicker($key, $value, $parms);
			break;
			
			case 'dropdown':
				$eloptions  = vartrue($parms['__options'], array());
				if(is_string($eloptions)) parse_str($eloptions);
				unset($parms['dropdown']);
				return $this->selectbox($name, $parms, $value, $eloptions);
			break; 
			
			case 'userclass':
			case 'userclasses':
				$uc_options = vartrue($parms['classlist'], ''); // defaults to 'public,guest,nobody,member,classes' (userclass handler)
				unset($parms['classlist']);
				$method = $attributes['type'] == 'userclass' ? 'uc_select' : 'uc_checkbox';
				return $this->$method($key, $value, $uc_options, vartrue($parms['__options'], array()));
			break;
			
			case 'user_name':
			case 'user_loginname':
			case 'user_login':
			case 'user_customtitle':
			case 'user_email':
				//user_id expected
				//$value = get_user_data($value); 
				return $this->user($key, $value, $parms);
			break;
			
			case 'boolean':
				$lenabled = vartrue($parms['enabled'], 'LAN_ENABLED');
				$ldisabled = vartrue($parms['disabled'], 'LAN_DISABLED');
				unset($parms['enabled'], $parms['disabled']);
				return $this->radio_switch($key, $value, defset($lenabled, $lenabled), defset($ldisabled, $ldisabled));
			break;
			
			case 'method': // Custom Function 
				return call_user_func_array(array($this, $key), array($value, 'write', $parms));
			break;

			default:
				//unknown type
			break;
		}
	}
	
	/**
	 * Generic List Form, used internal by admin UI
	 * Expected options array format:
	 * <code>
	 * <?php
	 * $options = array(
	 * 		'id' => 'myplugin', // unique string used for building element ids, REQUIRED
	 * 		'pid' => 'primary_id', // primary field name, REQUIRED
	 * 		'url' => '{e_PLUGIN}myplug/admin_config.php', // if not set, e_SELF is used
	 * 		'query' => 'mode=main&amp;action=list', // or e_QUERY if not set
	 * 		'head_query' => 'mode=main&amp;action=list', // without field, asc and from vars, REQUIRED
	 * 		'np_query' => 'mode=main&amp;action=list', // without from var, REQUIRED for next/prev functionality
	 * 		'legend' => 'Fieldset Legend', // hidden by default
	 * 		'form_pre' => '', // markup to be added before opening form element (e.g. Filter form)
	 * 		'form_post' => '', // markup to be added after closing form element
	 * 		'fields' => array(...), // see e_admin_ui::$fields
	 * 		'fieldpref' => array(...), // see e_admin_ui::$fieldpref
	 * 		'table_pre' => '', // markup to be added before opening table element 
	 * 		'table_post' => '', // markup to be added after closing table element (e.g. Batch actions)
	 * 		'fieldset_pre' => '', // markup to be added before opening fieldset element 
	 * 		'fieldset_post' => '', // markup to be added after closing fieldset element
	 * 		'perPage' => 15, // if 0 - no next/prev navigation
	 * 		'from' => 0, // current page, default 0
	 * 		'field' => 'field_name', //current order field name, default - primary field
	 * 		'asc' => 'desc', //current 'order by' rule, default 'asc'
	 * );
	 * $list = new e_admin_tree_model($data);
	 * </code>
	 * @param array $options
	 * @param e_admin_tree_model $list
	 * @return string
	 */
	public function listForm($options, $list)
	{
		$tp = e107::getParser();
		$tree = $list->getTree();
		$total = $list->getTotal();
		
		$amount = $options['perPage'];
		$from = vartrue($options['from'], 0);
		$field = vartrue($options['field'], $options['pid']);
		$asc = strtoupper(vartrue($options['asc'], 'asc'));
		$elid = $options['id'];
		$query = isset($options['query']) ? $options['query'] : e_QUERY ;
		$url = (isset($options['url']) ? $tp->replaceConstants($options['url'], 'abs') : e_SELF);
		$formurl = $url.($query ? '?'.$query : '');
		$fields = $options['fields'];
		$current_fields = $options['fieldpref'];

        $text .= "
			".vartrue($options['form_pre'])."
			<form method='post' action='{$formurl}' id='{$elid}-list-form'>
				".vartrue($options['fieldset_pre'])."
				<fieldset id='{$elid}-list'>
					<legend class='e-hideme'>".$options['legend']."</legend>
					".vartrue($options['table_pre'])."
					<table cellpadding='0' cellspacing='0' class='adminlist' id='{$elid}-list-table'>
						".$this->colGroup($fields, $current_fields)."
						".$this->thead($fields, $current_fields, $options['head_query'], $options['query'])."
						<tbody>
		";

		if(!$tree)
		{
			$text .= "
							<tr>
								<td colspan='".count($current_fields)."' class='center middle'>".LAN_NO_RECORDS."</td>
							</tr>
			";
		}
		else
		{
			foreach($tree as $model)
			{
				$text .= $this->renderTableRow($fields, $current_fields, $model->getData(), $options['pid']);
			}

		}

		$text .= "
						</tbody>
					</table>
					".vartrue($options['table_post'])."
		";
		
		
		if($tree && $amount)
		{ 
			$parms = $total.",".$amount.",".$from.",".$url.'?'.($options['np_query'] ? $options['np_query'].'&amp;' : '').'from=[FROM]';
	    	$text .= $tp->parseTemplate("{NEXTPREV={$parms}}");
		}
		
		$text .= "
				</fieldset>
				".vartrue($options['fieldset_post'])."
			</form>
			".vartrue($options['form_post'])."
		";

		return $text;
	}
	
	/**
	 * Generic DB Record Management Form. 
	 * TODO - lans
	 * Expected arrays format:
	 * <code>
	 * <?php
	 * $forms[0] = array(
	 * 		'id'  => 'myplugin',
	 * 		'url' => '{e_PLUGIN}myplug/admin_config.php', //if not set, e_SELF is used
	 * 		'query' => 'mode=main&amp;action=edit&id=1', //or e_QUERY if not set
	 * 		'tabs' => true, // TODO - NOT IMPLEMENTED YET - enable tabs (only if fieldset count is > 1)
	 * 		'fieldsets' => array(
	 * 			'general' => array(
	 * 				'legend' => 'Fieldset Legend',
	 * 				'fields' => array(...), //see e_admin_ui::$fields
	 * 				'after_submit_options' => array('action' => 'Label'[,...]), // or true for default redirect options
	 * 				'after_submit_default' => 'action_name',
	 * 				'triggers' => 'auto', // standard create/update-cancel triggers 
	 * 				//or custom trigger array in format array('sibmit' => array('Title', 'create', '1'), 'cancel') - trigger name - title, action, optional hidden value (in this case named sibmit_value)
	 * 			),
	 * 			'advanced' => array(
	 * 				'legend' => 'Fieldset Legend',
	 * 				'fields' => array(...), //see e_admin_ui::$fields
	 * 				'after_submit_options' => array('__default' => 'action_name' 'action' => 'Label'[,...]), // or true for default redirect options
	 * 				'triggers' => 'auto', // standard create/update-cancel triggers 
	 * 				//or custom trigger array in format array('submit' => array('Title', 'create', '1'), 'cancel' => array('cancel', 'cancel')) - trigger name - title, action, optional hidden value (in this case named sibmit_value)
	 * 			)
	 * 		) 
	 * );
	 * $models[0] = new e_admin_model($data);
	 * $models[0]->setFieldIdName('primary_id'); // you need to do it if you don't use your own admin model extension
	 * </code>
	 * @param array $forms numerical array 
	 * @param array $models numerical array with values instance of e_admin_model
	 * @return string
	 */
	function createForm($forms, $models)
	{
		$text = '';
		foreach ($forms as $fid => $form) 
		{
			$model = $models[$fid];
			$query = isset($form['query']) ? $form['query'] : e_QUERY ;
			$url = (isset($form['url']) ? e107::getParser()->replaceConstants($form['url'], 'abs') : e_SELF).($query ? '?'.$query : '');
			
			$text .= "
				<form method='post' action='".$url."' id='{$form['id']}-form' enctype='multipart/form-data'>
			";
		
			foreach ($form['fieldsets'] as $elid => $data) 
			{
				$elid = $form['id'].'-'.$elid;
				$text .= "
					{$data['fieldset_pre']}
					<fieldset id='{$elid}'>
						<legend>{$data['legend']}</legend>
						{$data['table_pre']}
						<table cellpadding='0' cellspacing='0' class='adminedit'>
							<colgroup span='2'>
								<col class='col-label' />
								<col class='col-control' />
							</colgroup>
							<tbody>
				";
							
				foreach($data['fields'] as $key => $att)
				{
					
					$parms = vartrue($att['formparms'], array());
					if(!is_array($parms)) parse_str($parms, $parms);
					$label = vartrue($att['note']) ? '<div class="label-note">'.deftrue($att['note'], $att['note']).'</div>' : '';
					$help = vartrue($att['help']) ? '<div class="field-help">'.deftrue($att['help'], $att['help']).'</div>' : '';
					
					// type null - system (special) fields
					if($att['type'] !== null && !vartrue($att['noedit']) && $key != $model->getFieldIdName())
					{
						$text .= "
							<tr>
								<td class='label'>
									".defset($att['title'], $att['title']).$label."
								</td>
								<td class='control'>
									".$this->renderElement($key, $model->getIfPosted($key), $att)."
									{$help}
								</td>
							</tr>
						";
					}
									
				}
		
				$text .= "
							</tbody>
						</table>	
						{$data['table_post']}
						<div class='buttons-bar center'>
				";
							// After submit options
							$defsubmitopt = array('list' => 'go to list', 'create' => 'create another', 'edit' => 'edit current');
							$submitopt = isset($data['after_submit_options']) ? $data['after_submit_options'] : true;
							if(true === $submitopt)
							{
								$submitopt = $defsubmitopt;
							}
							
							if($submitopt)
							{
								$selected = isset($data['after_submit_default']) && array_key_exists($data['after_submit_default'], $submitopt) ? $data['after_submit_default'] : '';
								$text .= '
									<div class="options">
										After submit: '.$this->radio_multi('__after_submit_action', $submitopt, $selected, false).'
									</div>
								';
							}
							
							$triggers = vartrue($data['triggers'], 'auto');
							if(is_string($triggers) && 'auto' === $triggers)
							{
								$triggers = array();
								if($model->getId())
								{
									$triggers['submit'] = array(LAN_UPDATE, 'update', $model->getId());
								}
								else
								{
									$triggers['submit'] = array(LAN_CREATE, 'create', 0);
								}
								$triggers['cancel'] = array(LAN_CANCEL, 'cancel');
							}
							
							foreach ($triggers as $trigger => $tdata)
							{
								$text .= $this->admin_button('etrigger_'.$trigger, $tdata[0], $tdata[1]);
								if(isset($tdata[2]))
								{
									$text .= $this->hidden($trigger.'_value', $tdata[2]);
								}
							}
							
				$text .= "
						</div>
					</fieldset>
					{$data['fieldset_post']}
				";	
			}
			$text .= "
			</form>
			";	
		}
		return $text;
	}
	
	// The 2 functions below are for demonstration purposes only, and may be moved/modified before release.
	function filterType($fieldarray)
	{
		return " frm-> filterType() is Deprecated &nbsp;&nbsp;  ";
	}
		
	function filterValue($type = '', $fields = '')
	{
		return " frm-> filterValue() is Deprecated.&nbsp;&nbsp;   ";
	}
		
	/**
	 * Generates a batch options select component
	 * This component is generally associated with a table of items where one or more rows in the table can be selected (using checkboxes).
	 * The list options determine some processing that wil lbe applied to all checked rows when the form is submitted.
	 *
	 * @param array $options associative array of option elements, keyed on the option value
	 * @param array ucOptions [optional] associative array of userclass option groups to display, keyed on the option value prefix
	 * @return string the HTML for the form component
	 */
	function batchoptions($options, $ucOptions = null)
	{
			$text = "
         <div class='f-left'>
         <img src='".e_IMAGE_ABS."generic/branchbottom.gif' alt='' class='icon action' />
			".$this->select_open('execute_batch', array('class' => 'tbox select e-execute-batch', 'id' => false))."
				".$this->option('With selected...', '')."
		";
		
		
		//used for getperms() check 
		$permissions = vartrue($options['__permissions'], array());
		//used for check_classs() check
		$classes = vartrue($options['__check_class'], array());
		unset($options['__permissions'], $options['__check_class']);
		
		foreach ($options as $key => $val)
		{
			if(isset($permissions[$key]) && !getperms($permissions[$key]))
			{
				continue;
			}
			$disabled = false;
			if(isset($classes[$key]) && !is_array($classes[$key]) && !check_class($classes[$key]))
			{
				$disabled = true; 
			}
			if(!is_array($val))
			{
				if($disabled) $val = $val.' ('.LAN_NOPERMISSION.')';
				$text .= "\t".$this->option('&nbsp;&nbsp;&nbsp;&nbsp;'.$val, $key, false, array('disabled' => $disabled))."\n";		
			}
			else
			{
				if($disabled) $val[0] = $val[0].' ('.LAN_NOPERMISSION.')';
				
				$text .= "\t".$this->optgroup_open($val[0], $disabled)."\n";
		      	foreach ($val[1] as $k => $v)
		      	{
		      		$disabled = false; 
					if(isset($classes[$key][$k]) && !check_class($classes[$key][$k]))
					{
						$disabled = true; 
						$v = $v.' ('.LAN_NOPERMISSION.')';
					}
					$text .= "\t\t".$this->option($v, $key.'_selected_'.$k, false, array('disabled' => $disabled))."\n";	
		      	}
		      	$text .= $this->optgroup_close()."\n";
				
			}		   
		}


		if ($ucOptions) // Userclass List. 
		{
	   		foreach ($ucOptions as $ucKey => $ucVal)
			{
				$text .= "\t".$this->optgroup_open($ucVal[0])."\n";
	      		foreach ($ucVal[1] as $key => $val)
	      		{
	      			$text .= "\t\t".$this->option($val['userclass_name']['userclass_name'], $ucKey.'_selected_'.$val['userclass_name']['userclass_id'])."\n";
	      		}
	      		$text .= $this->optgroup_close()."\n";
			}
		}
		

		$text .= "
				".$this->select_close()."
				".$this->admin_button('trigger_execute_batch', 'trigger_execute_batch', 'submit multi e-hide-if-js', 'Go')."
			</div><div class='clear'></div>
		";
		
		return $text;
	}
}

class form {

	function form_open($form_method, $form_action, $form_name = "", $form_target = "", $form_enctype = "", $form_js = "") {
		$method = ($form_method ? "method='".$form_method."'" : "");
		$target = ($form_target ? " target='".$form_target."'" : "");
		$name = ($form_name ? " id='".$form_name."' " : " id='myform'");
		return "\n<form action='".$form_action."' ".$method.$target.$name.$form_enctype.$form_js.">";
	}

	function form_text($form_name, $form_size, $form_value, $form_maxlength = FALSE, $form_class = "tbox", $form_readonly = "", $form_tooltip = "", $form_js = "") {
		$name = ($form_name ? " id='".$form_name."' name='".$form_name."'" : "");
		$value = (isset($form_value) ? " value='".$form_value."'" : "");
		$size = ($form_size ? " size='".$form_size."'" : "");
		$maxlength = ($form_maxlength ? " maxlength='".$form_maxlength."'" : "");
		$readonly = ($form_readonly ? " readonly='readonly'" : "");
		$tooltip = ($form_tooltip ? " title='".$form_tooltip."'" : "");
		return "\n<input class='".$form_class."' type='text' ".$name.$value.$size.$maxlength.$readonly.$tooltip.$form_js." />";
	}

	function form_password($form_name, $form_size, $form_value, $form_maxlength = FALSE, $form_class = "tbox", $form_readonly = "", $form_tooltip = "", $form_js = "") {
		$name = ($form_name ? " id='".$form_name."' name='".$form_name."'" : "");
		$value = (isset($form_value) ? " value='".$form_value."'" : "");
		$size = ($form_size ? " size='".$form_size."'" : "");
		$maxlength = ($form_maxlength ? " maxlength='".$form_maxlength."'" : "");
		$readonly = ($form_readonly ? " readonly='readonly'" : "");
		$tooltip = ($form_tooltip ? " title='".$form_tooltip."'" : "");
		return "\n<input class='".$form_class."' type='password' ".$name.$value.$size.$maxlength.$readonly.$tooltip.$form_js." />";
	}

	function form_button($form_type, $form_name, $form_value, $form_js = "", $form_image = "", $form_tooltip = "") {
		$name = ($form_name ? " id='".$form_name."' name='".$form_name."'" : "");
		$image = ($form_image ? " src='".$form_image."' " : "");
		$tooltip = ($form_tooltip ? " title='".$form_tooltip."' " : "");
		return "\n<input class='button' type='".$form_type."' ".$form_js." value='".$form_value."'".$name.$image.$tooltip." />";
	}

	function form_textarea($form_name, $form_columns, $form_rows, $form_value, $form_js = "", $form_style = "", $form_wrap = "", $form_readonly = "", $form_tooltip = "") {
		$name = ($form_name ? " id='".$form_name."' name='".$form_name."'" : "");
		$readonly = ($form_readonly ? " readonly='readonly'" : "");
		$tooltip = ($form_tooltip ? " title='".$form_tooltip."'" : "");
		$wrap = ($form_wrap ? " wrap='".$form_wrap."'" : "");
		$style = ($form_style ? " style='".$form_style."'" : "");
		return "\n<textarea class='tbox' cols='".$form_columns."' rows='".$form_rows."' ".$name.$form_js.$style.$wrap.$readonly.$tooltip.">".$form_value."</textarea>";
	}

	function form_checkbox($form_name, $form_value, $form_checked = 0, $form_tooltip = "", $form_js = "") {
		$name = ($form_name ? " id='".$form_name.$form_value."' name='".$form_name."'" : "");
		$checked = ($form_checked ? " checked='checked'" : "");
		$tooltip = ($form_tooltip ? " title='".$form_tooltip."'" : "");
		return "\n<input type='checkbox' value='".$form_value."'".$name.$checked.$tooltip.$form_js." />";

	}

	function form_radio($form_name, $form_value, $form_checked = 0, $form_tooltip = "", $form_js = "") {
		$name = ($form_name ? " id='".$form_name.$form_value."' name='".$form_name."'" : "");
		$checked = ($form_checked ? " checked='checked'" : "");
		$tooltip = ($form_tooltip ? " title='".$form_tooltip."'" : "");
		return "\n<input type='radio' value='".$form_value."'".$name.$checked.$tooltip.$form_js." />";

	}

	function form_file($form_name, $form_size, $form_tooltip = "", $form_js = "") {
		$name = ($form_name ? " id='".$form_name."' name='".$form_name."'" : "");
		$tooltip = ($form_tooltip ? " title='".$form_tooltip."'" : "");
		return "<input type='file' class='tbox' size='".$form_size."'".$name.$tooltip.$form_js." />";
	}

	function form_select_open($form_name, $form_js = "") {
		return "\n<select id='".$form_name."' name='".$form_name."' class='tbox' ".$form_js." >";
	}

	function form_select_close() {
		return "\n</select>";
	}

	function form_option($form_option, $form_selected = "", $form_value = "", $form_js = "") {
		$value = ($form_value !== FALSE ? " value='".$form_value."'" : "");
		$selected = ($form_selected ? " selected='selected'" : "");
		return "\n<option".$value.$selected." ".$form_js.">".$form_option."</option>";
	}

	function form_hidden($form_name, $form_value) {
		return "\n<input type='hidden' id='".$form_name."' name='".$form_name."' value='".$form_value."' />";
	}

	function form_close() {
		return "\n</form>";
	}
}

/*
Usage
echo $rs->form_open("post", e_SELF, "_blank");
echo $rs->form_text("testname", 100, "this is the value", 100, 0, "tooltip");
echo $rs->form_button("submit", "testsubmit", "SUBMIT!", "", "Click to submit");
echo $rs->form_button("reset", "testreset", "RESET!", "", "Click to reset");
echo $rs->form_textarea("textareaname", 10, 10, "Value", "overflow:hidden");
echo $rs->form_checkbox("testcheckbox", 1, 1);
echo $rs->form_checkbox("testcheckbox2", 2);
echo $rs->form_hidden("hiddenname", "hiddenvalue");
echo $rs->form_radio("testcheckbox", 1, 1);
echo $rs->form_radio("testcheckbox", 1);
echo $rs->form_file("testfile", "20");
echo $rs->form_select_open("testselect");
echo $rs->form_option("Option 1");
echo $rs->form_option("Option 2");
echo $rs->form_option("Option 3", 1, "defaultvalue");
echo $rs->form_option("Option 4");
echo $rs->form_select_close();
echo $rs->form_close();
*/


?>