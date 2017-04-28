<?php
namespace TYPO3\TtAddress\Hooks\Tca;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class AddFieldsToSelector
 */
class AddFieldsToSelector
{
    /**
     * Manipulating the input array, $params, adding new selectorbox items.
     *
     * @param	array	$params array of select field options (reference)
     * @param	object	$pObj parent object (reference)
     * @return	void
     */
    public function main(&$params, &$pObj)
    {
        // TODO consolidate with list in pi1
        $coreSortFields = 'gender, first_name, middle_name, last_name, title, company, '
            . 'address, building, room, birthday, zip, city, region, country, email, www, phone, mobile, '
            . 'fax';

        $sortFields = GeneralUtility::trimExplode(',', $coreSortFields);

        $selectOptions = array();
        foreach ($sortFields as $field) {
            $label = $GLOBALS['LANG']->sL($GLOBALS['TCA']['tt_address']['columns'][$field]['label']);
            $label = substr($label, 0, -1);

            $selectOptions[] = array(
                'field' => $field,
                'label' => $label
            );
        }

        // add sorting by order of single selection
        $selectOptions[] = array(
            'field' => 'singleSelection',
            'label' => $GLOBALS['LANG']->sL('LLL:EXT:tt_address/pi1/locallang_ff.xml:pi1_flexform.sortBy.singleSelection')
        );

        // sort by labels
        $labels = array();
        foreach ($selectOptions as $key => $v) {
            $labels[$key] = $v['label'];
        }
        $labels = array_map('strtolower', $labels);
        array_multisort($labels, SORT_ASC, $selectOptions);

        // add fields to <select>
        foreach ($selectOptions as $option) {
            $params['items'][] = array(
                $option['label'],
                $option['field']
            );
        }
    }
}
