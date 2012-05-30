<?php
/***************************************************************
*  Copyright notice
*
*  (c)  2007 Ingo Renner (typo3@ingo-renner.com)
*
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/


/**
 * Class to maintain backwards compatibility with extensions building on
 * tt_address
 *
 * @author	Ingo Renner <typo3@ingo-renner.com>
 */
class tx_ttaddress_compat {

   	/**
	 * looks for tt_address records with changes to the first, middle, and
	 * last name fields to come by. This function will then write changes back
	 * to the old combined name field in a configurable format
	 *
	 * @param	string		action status: new/update is relevant for us
	 * @param	string		db table
	 * @param	integer		record uid
	 * @param	array		record
	 * @param	object		parent object
	 * @return	void
	 */
	function processDatamap_postProcessFieldArray($status, $table, $id, &$fieldArray, $pObj) {

		if($table == 'tt_address' && ($status == 'new' || $status == 'update')) {
			if($status == 'update') {
				$address = $this->getFullRecord($id);
			} else {
				$address = $fieldArray;
			}

			$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tt_address']);
			$format = $extConf['backwardsCompatFormat'];

			$newRecord = array_merge($address, $fieldArray);

			$combinedName = trim(sprintf(
				$format,
				$newRecord['first_name'],
				$newRecord['middle_name'],
				$newRecord['last_name']
			));

			if(!empty($combinedName)) {
				$fieldArray['name'] = $combinedName;
			}
		}
	}

	/**
	 * gets a full tt_address record
	 *
	 * @param	integer	$uid: unique id of the tt_address record to get
	 * @return	array	full tt_address record with associative keys
	 */
	function getFullRecord($uid) {
		$row = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'*',
			'tt_address',
			'uid = '.$uid
		);

		return $row[0];
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tt_address/class.tx_ttaddress_compat.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tt_address/class.tx_ttaddress_compat.php']);
}

?>