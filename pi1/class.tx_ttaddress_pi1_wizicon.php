<?php
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
 * adds the wizard icon.
 *
 * @author Ingo Renner <typo3@ingo-renner.com>
 */
class tx_ttaddress_pi1_wizicon
{
    /**
     * Adds the tt_address pi1 wizard icon
     *
     * @param array Input array with wizard items for plugins
     * @return array Modified input array, having the item for tt_address pi1 added.
     */
    public function proc($wizardItems)
    {
        $wizardItems['plugins_tx_ttaddress_pi1'] = array(
            'icon'        => ExtensionManagementUtility::extRelPath('tt_address') . 'Resources/Public/Icons/ce_wiz.gif',
            'title'       => $GLOBALS['LANG']->sL('LLL:EXT:tt_address/Resources/Private/Language/locallang.xlf:pi1_title'),
            'description' => $GLOBALS['LANG']->sL('LLL:EXT:tt_address/Resources/Private/Language/locallang.xlf:pi1_plus_wiz_description'),
            'params'      => '&defVals[tt_content][CType]=list&defVals[tt_content][list_type]=tt_address_pi1'
        );

        return $wizardItems;
    }

}
