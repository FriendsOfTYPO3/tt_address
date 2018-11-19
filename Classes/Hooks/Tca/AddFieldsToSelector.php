<?php

namespace FriendsOfTYPO3\TtAddress\Hooks\Tca;

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

/**
 * Class AddFieldsToSelector.
 */
class AddFieldsToSelector
{
    // TODO consolidate with list in pi1
    const sortFields = ['gender', 'first_name', 'middle_name', 'last_name', 'title', 'company', 'address', 'building', 'room', 'birthday', 'zip', 'city', 'region', 'country', 'email', 'www', 'phone', 'mobile', 'fax'];

    /**
     * Manipulating the input array, $params, adding new selectorbox items.
     *
     * @param array $params array of select field options (reference)
     */
    public function main(array &$params)
    {
        $selectOptions = [];
        foreach (self::sortFields as $field) {
            $label = $GLOBALS['LANG']->sL($GLOBALS['TCA']['tt_address']['columns'][$field]['label']);
            $label = rtrim($label, ':');

            $selectOptions[] = [
                'field' => $field,
                'label' => $label,
            ];
        }

        // add sorting by order of single selection
        $selectOptions[] = [
            'field' => 'singleSelection',
            'label' => $GLOBALS['LANG']->sL('LLL:EXT:tt_address/Resources/Private/Language/locallang_pi1.xlf:pi1_flexform.sortBy.singleSelection'),
        ];

        // sort by labels
        $labels = [];
        foreach ($selectOptions as $key => $v) {
            $labels[$key] = $v['label'];
        }
        $labels = array_map('strtolower', $labels);
        array_multisort($labels, SORT_ASC, $selectOptions);

        // add fields to <select>
        foreach ($selectOptions as $option) {
            $params['items'][] = [
                $option['label'],
                $option['field'],
            ];
        }
    }
}
