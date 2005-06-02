<?php

/**
* Allows Storage of arrays without use of serialize functions
*
*/
class ArrayData {

	/**
	* Return a string containg exported array data.
	*
	* @param array $ArrayData array to be stored
	* @param bool $AddSlashes default true, add slashes for db storage, else false
	* @return string
	*/
	function WriteArray(&$ArrayData, $AddSlashes = true) {
		if (!is_array($ArrayData)) {
			return false;
		}
		$Array = var_export($ArrayData, true);
		if ($AddSlashes == true) {
			$Array = addslashes($Array);
		}
		return $Array;
	}

	/**
	* Returns an array from stored array data.
	*
	* @param string $ArrayData
	* @return array stored data
	*/
	function ReadArray(&$ArrayData) {
		if ($ArrayData == ""){
			return false;
		}
		$ArrayData = '$data = '.trim($ArrayData).';';
		@eval($ArrayData);
		if (!is_array($data)) {
			return false;
		}
		return (isset($data)) ? $data : "";
	}
}

?>