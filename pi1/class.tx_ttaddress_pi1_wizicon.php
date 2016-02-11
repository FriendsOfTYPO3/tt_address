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
        $LL = $this->includeLocalLang();

        $wizardItems['plugins_tx_ttaddress_pi1'] = array(
            'icon'        => ExtensionManagementUtility::extRelPath('tt_address') . 'pi1/ce_wiz.gif',
            'title'       => $GLOBALS['LANG']->getLLL('pi1_title', $LL),
            'description' => $GLOBALS['LANG']->getLLL('pi1_plus_wiz_description', $LL),
            'params'      => '&defVals[tt_content][CType]=list&defVals[tt_content][list_type]=tt_address_pi1'
        );

        return $wizardItems;
    }

    /**
     * Includes the locallang file for the 'tt_address' extension
     *
     * @return array The LOCAL_LANG array
     */
    protected function includeLocalLang()
    {
        $llFile = ExtensionManagementUtility::extPath('tt_address') . 'locallang.xml';

        $localLanguageParser = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Localization\\Parser\\LocallangXmlParser');
        return $localLanguageParser->getParsedData($llFile, $GLOBALS['LANG']->lang);
    }
}
