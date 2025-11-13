<?php

defined('TYPO3') or die;

$pluginSignature = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'tt_address',
    'ListView',
    'LLL:EXT:tt_address/Resources/Private/Language/db/locallang.xlf:extbase_title',
    'tt-address-plugin',
    'plugins',
    'LLL:EXT:tt_address/Resources/Private/Language/db/locallang.xlf:extbase_description'
);

$GLOBALS['TCA']['tt_content']['types']['list']['previewRenderer'][$pluginSignature] = \FriendsOfTYPO3\TtAddress\FormEngine\TtAddressPreviewRenderer::class;
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'select_key,pages,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('*', 'FILE:EXT:tt_address/Configuration/FlexForms/List.xml', $pluginSignature);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin, pi_flexform',
    $pluginSignature,
    'after:palette:headers'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords('tt_address');
