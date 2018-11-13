<?php

namespace TYPO3\TtAddress\Hooks;

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
 * AutoConfiguration-Hook for RealURL
 */
class RealUrlAutoConfiguration
{

    /**
     * Generates additional RealURL configuration and merges it with provided configuration
     *
     * @param       array $params Default configuration
     * @return      array Updated configuration
     */
    public function addTtAddressConfig($params)
    {
        return array_merge_recursive(
            $params['config'],
            [
                'postVarSets' => [
                    '_DEFAULT' => [
                        'address-results' => [
                            [
                                'GETvar' => 'tx_ttaddress_listview[@widget_0][currentPage]',
                            ],
                        ],
                        'address' => [
                            [
                                'GETvar' => 'tx_ttaddress_listview[action]',
                                'valueMap' => [
                                    'show' => '',
                                ],
                                'noMatch' => 'bypass'
                            ],
                            [
                                'GETvar' => 'tx_ttaddress_listview[controller]',
                                'valueMap' => [
                                    'Address' => '',
                                ],
                                'noMatch' => 'bypass'
                            ],
                            [
                                'GETvar' => 'tx_ttaddress_listview[address]',
                                'lookUpTable' => [
                                    'table' => 'tt_address',
                                    'id_field' => 'uid',
                                    'alias_field' => "CONCAT(first_name, '-', last_name)",
                                    'useUniqueCache' => 1,
                                    'useUniqueCache_conf' => [
                                        'strtolower' => 1,
                                        'spaceCharacter' => '-',
                                    ],
                                    'languageGetVar' => 'L',
                                    'languageExceptionUids' => '',
                                    'languageField' => 'sys_language_uid',
                                    'transOrigPointerField' => 'l10n_parent',
                                ],
                            ],
                        ],
                    ]
                ]
            ]
        );
    }
}
