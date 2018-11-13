<?php
defined('TYPO3_MODE') or die();


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
  'TYPO3.' . 'tt_address',
  'ListView',
  'LLL:EXT:tt_address/Resources/Private/Language/db/locallang.xlf:extbase_title'
);


$extensionName = strtolower(\TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase('tt_address'));
$pluginName = strtolower('ListView');
$pluginSignature = $extensionName.'_'.$pluginName;

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key,pages,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:tt_address/Configuration/FlexForms/List.xml');


/* ===========================================================================
 BEGIN: Add old legacy plugin
=========================================================================== */
$localExtConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tt_address']);

if((bool)$localExtConf['activatePiBase']) {
    $pluginSignature = "tt_address_pi1";
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
        [
            'LLL:EXT:tt_address/Resources/Private/Language/locallang_pi1.xlf:pi1_title',
            $pluginSignature
        ],
        'list_type',
        'tt_address'
    );
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key,pages,recursive';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:tt_address/Configuration/FlexForms/Pi1.xml');
}

/* ===========================================================================
 END: Add old legacy plugin
=========================================================================== */
