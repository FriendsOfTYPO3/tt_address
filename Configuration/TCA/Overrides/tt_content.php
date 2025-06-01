<?php

defined('TYPO3_MODE') or defined('TYPO3') or die;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use FriendsOfTYPO3\TtAddress\FormEngine\TtAddressPreviewRenderer;

ExtensionUtility::registerPlugin(
    'tt_address',
    'ListView',
    'LLL:EXT:tt_address/Resources/Private/Language/db/locallang.xlf:extbase_title',
    'tt-address-plugin',
    'plugins',
    'LLL:EXT:tt_address/Resources/Private/Language/db/locallang.xlf:extbase_description'
);

$pluginSignature = 'ttaddress_listview';

$GLOBALS['TCA']['tt_content']['types']['list']['previewRenderer'][$pluginSignature] = TtAddressPreviewRenderer::class;
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'select_key,pages,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:tt_address/Configuration/FlexForms/List.xml');

ExtensionManagementUtility::addToInsertRecords('tt_address');
