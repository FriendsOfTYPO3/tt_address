<?php

defined('TYPO3') or die;

if ((new \TYPO3\CMS\Core\Information\Typo3Version())->getMajorVersion() >= 14) {
    $pluginSignature = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'tt_address',
        'ListView',
        'LLL:EXT:tt_address/Resources/Private/Language/db/locallang.xlf:extbase_title',
        'tt-address-plugin',
        'plugins',
        'LLL:EXT:tt_address/Resources/Private/Language/db/locallang.xlf:extbase_description',
        'FILE:EXT:tt_address/Configuration/FlexForms/List.xml'
    );
} else {
    $pluginSignature = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'tt_address',
        'ListView',
        'LLL:EXT:tt_address/Resources/Private/Language/db/locallang.xlf:extbase_title',
        'tt-address-plugin',
        'plugins',
        'LLL:EXT:tt_address/Resources/Private/Language/db/locallang.xlf:extbase_description'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('*', 'FILE:EXT:tt_address/Configuration/FlexForms/List.xml', $pluginSignature);
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin, pi_flexform',
    $pluginSignature,
    'after:palette:headers'
);

$GLOBALS['TCA']['tt_content']['types'][$pluginSignature]['previewRenderer'] = \FriendsOfTYPO3\TtAddress\FormEngine\TtAddressPreviewRenderer::class;

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords('tt_address');
