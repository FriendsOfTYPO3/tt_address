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
 * Adds the "pi1" item to the new content element wizard
 */
class NewContentElementWizardHook
{
    /**
     * Adds the tt_address pi1 wizard icon
     *
     * @param array $wizardItems Input array with wizard items for plugins
     * @return array Modified input array, having the item for tt_address pi1 added.
     */
    public function proc($wizardItems)
    {
        $languageService = $this->getLanguageService();
        $wizardItems['plugins_tx_ttaddress_pi1'] = [
            // @todo: change to icon identifier
            'icon'        => ExtensionManagementUtility::extPath('tt_address') . 'Resources/Public/Icons/ContentElementWizard.gif',
            'title'       => $languageService->sL('EXT:tt_address/Resources/Private/Language/locallang_pi1.xlf:pi1_title'),
            'description' => $languageService->sL('EXT:tt_address/Resources/Private/Language/locallang_pi1.xlf:pi1_plus_wiz_description'),
            'params'      => '&defVals[tt_content][CType]=list&defVals[tt_content][list_type]=tt_address_pi1'
        ];
        return $wizardItems;
    }

    /**
     * @return \TYPO3\CMS\Lang\LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }
}