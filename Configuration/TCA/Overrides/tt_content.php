<?php
defined('TYPO3_MODE') or defined('TYPO3') or die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'tt_address',
    'ListView',
    'LLL:EXT:tt_address/Resources/Private/Language/db/locallang.xlf:extbase_title'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'tt_address',
    'AbcListView',
    'LLL:EXT:tt_address/Resources/Private/Language/db/locallang.xlf:extbase_title'
);



$pluginSignature = 'ttaddress_listview';

$GLOBALS['TCA']['tt_content']['types']['list']['previewRenderer'][$pluginSignature] = \FriendsOfTYPO3\TtAddress\FormEngine\TtAddressPreviewRenderer::class;
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'select_key,pages,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:tt_address/Configuration/FlexForms/List.xml');

$abcpluginSignature = 'ttaddress_abclistview';

$GLOBALS['TCA']['tt_content']['types']['list']['previewRenderer'][$abcpluginSignature] = \FriendsOfTYPO3\TtAddress\FormEngine\TtAddressPreviewRenderer::class;
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$abcpluginSignature] = 'select_key,pages,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$abcpluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($abcpluginSignature, 'FILE:EXT:tt_address/Configuration/FlexForms/List.xml');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords('tt_address');
