<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2007 Ingo Renner <typo3@ingo-renner.com>
*  All  rights reserved
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
 * Class/Function which manipulates the item-array for the pi1 sort by field
 * selector
 *
 * @author	Ingo Renner <typo3@ingo-renner.com>
 */
class tx_ttaddress_addfieldstosel {

   /**
    * Manipulating the input array, $params, adding new selectorbox items.
    *
    * @param	array	array of select field options (reference)
    * @param	object	parent object (reference)
    * @return	void
    */
	function main(&$params, &$pObj)	{

		t3lib_div::loadTCA('tt_address');

			// TODO consolidate with list in pi1
		$coreSortFields = 'gender, first_name, middle_name, last_name, title, company, '
			.'address, building, room, birthday, zip, city, region, country, email, www, phone, mobile, '
			.'fax, addressgroup';

		$sortFields = t3lib_div::trimExplode(',', $coreSortFields);

		$selectOptions = array();
		foreach($sortFields as $field) {
			$label = $GLOBALS['LANG']->sL($GLOBALS['TCA']['tt_address']['columns'][$field]['label']);
			$label = substr($label, 0, -1);

			$selectOptions[] = array(
				'field' => $field,
				'label' => $label
			);
		}

			// add sorting by order of single selection
		$selectOptions[] = array (
			'field' => 'singleSelection',
			'label' => $GLOBALS['LANG']->sL('LLL:EXT:tt_address/pi1/locallang_ff.xml:pi1_flexform.sortBy.singleSelection')
		);

			// sort by labels
		$labels = array();
		foreach($selectOptions as $key => $v) {
			$labels[$key] = $v['label'];
		}
		$labels = array_map('strtolower', $labels);
		array_multisort($labels, SORT_ASC, $selectOptions);

			// add fields to <select>
		foreach($selectOptions as $option) {
			$params['items'][] = array(
				$option['label'],
				$option['field']
			);
		}
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tt_address/class.tx_ttaddress_addfieldstosel.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tt_address/class.tx_ttaddress_addfieldstosel.php']);
}

?>