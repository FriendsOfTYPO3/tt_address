<?php
declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\Hooks\Tca;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Localization\LanguageService;

/**
 * Class AddFieldsToSelector
 */
class AddFieldsToSelector
{
    /** @var LanguageService */
    protected $languageService;

    public function __construct()
    {
        $this->languageService = $GLOBALS['LANG'];
    }

    // TODO consolidate with list in pi1
    const sortFields = ['gender', 'name', 'first_name', 'middle_name', 'last_name', 'title', 'company', 'address', 'building', 'room', 'birthday', 'zip', 'city', 'region', 'country', 'email', 'www', 'phone', 'mobile', 'fax'];

    /**
     * Manipulating the input array, $params, adding new selectorbox items.
     *
     * @param array $params array of select field options (reference)
     */
    public function main(array &$params)
    {
        $selectOptions = [];
        foreach (self::sortFields as $field) {
            $label = $this->languageService->sL($GLOBALS['TCA']['tt_address']['columns'][$field]['label']);
            $label = rtrim($label, ':');

            $selectOptions[] = [
                'field' => $field,
                'label' => $label
            ];
        }

        // add sorting by order of single selection
        $selectOptions[] = [
            'field' => 'singleSelection',
            'label' => $this->languageService->sL('LLL:EXT:tt_address/Resources/Private/Language/ff/locallang_ff.xlf:pi1_flexform.sortBy.singleSelection')
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
                $option['field']
            ];
        }
    }
}
