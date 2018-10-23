<?php
defined('TYPO3_MODE') or die();


$localExtConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tt_address']);

// Add old legacy plugin
if($localExtConf['activatePiBase'] === 1) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
        [
            'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:pi_tt_address',
            'tt_address_pi1'
        ],
        'list_type',
        'tt_address'
    );

    // add flexform to pi1
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['tt_address_pi1'] = 'pi_flexform';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['tt_address_pi1'] = 'layout,select_key,pages,recursive';
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('tt_address_pi1', 'FILE:EXT:tt_address/Configuration/FlexForms/Pi1.xml');
}
