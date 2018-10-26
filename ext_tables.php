<?php
defined('TYPO3_MODE') or die();


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tt_address');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords('tt_address');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tt_address', 'EXT:tt_address/Resources/Private/Language/locallang_csh_ttaddress.xlf');


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
  'TYPO3.' . 'tt_address',
  'ListView',
  'LLL:EXT:tt_address/Resources/Private/Language/db/locallang.xlf:extbase_title'
);


$extensionName = strtolower(\TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase('tt_address'));
$pluginName = strtolower('ListView');
$pluginSignature = $extensionName.'_'.$pluginName;

$TCA['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key,pages,recursive';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:tt_address/Configuration/FlexForms/List.xml');


// Legacy Plugin
$localExtConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tt_address']);

// if($localExtConf['activatePiBase'] === 1) {
    $extensionName = strtolower(\TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase('tt_address'));
    $pluginName = strtolower('pi1');
    $pluginSignature = $extensionName.'_'.$pluginName;

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
      'TYPO3.' . 'tt_address',
      'pi1',
      'LLL:EXT:tt_address/Resources/Private/Language/locallang_pi1.xlf:pi1_title'
    );

    $TCA['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key,pages,recursive';
    $TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:tt_address/Configuration/FlexForms/Pi1.xml');
// }

