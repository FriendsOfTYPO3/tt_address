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

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;


/**
 * AutoConfiguration-Hook for RealURL
 *
 * @package TYPO3
 * @subpackage tt_address
 */
class RealUrlAutoConfiguration {

  /**
   * Generates additional RealURL configuration and merges it with provided configuration
   *
   * @param       array $params Default configuration
   * @return      array Updated configuration
   */
  public function addTtAddressConfig($params) {
    return array_merge_recursive($params['config'], array(
        'postVarSets' => array(
          '_DEFAULT' => array(
            'address-results' => array(
              array (
                'GETvar' => 'tx_ttaddress_listview[@widget_0][currentPage]',
              ),
            ),
            'address' => array(
              array(
                'GETvar' => 'tx_ttaddress_listview[action]',
                'valueMap' => array(
                  'show' => '',
                ),
                'noMatch' => 'bypass'
              ),
              array(
                'GETvar' => 'tx_ttaddress_listview[controller]',
                'valueMap' => array(
                  'Address' => '',
                ),
                'noMatch' => 'bypass'
              ),
              array(
                'GETvar' => 'tx_ttaddress_listview[address]',
                'lookUpTable' => array(
                  'table' => 'tt_address',
                  'id_field' => 'uid',
                  'alias_field' => "CONCAT(first_name, '-', last_name)",
                  'useUniqueCache' => 1,
                  'useUniqueCache_conf' => array(
                    'strtolower' => 1,
                    'spaceCharacter' => '-',
                  ),
                  'languageGetVar' => 'L',
                  'languageExceptionUids' => '',
                  'languageField' => 'sys_language_uid',
                  'transOrigPointerField' => 'l10n_parent',
                ),
              ),
            ),
          )
        )
      )
    );
  }
}