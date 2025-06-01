<?php

defined('TYPO3_MODE') or defined('TYPO3') or die;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

// Override news icon
$GLOBALS['TCA']['pages']['columns']['module']['config']['items'][] = [
    'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address',
    'value' => 'tt_address',
    'icon' => 'apps-pagetree-folder-contains-tt-address',
];

$GLOBALS['TCA']['pages']['ctrl']['typeicon_classes']['contains-tt-address'] = 'apps-pagetree-folder-contains-tt-address';
$GLOBALS['TCA']['pages']['ctrl']['typeicon_classes']['contains-tt_address'] = 'apps-pagetree-folder-contains-tt-address';
$GLOBALS['TCA']['pages']['ctrl']['typeicon_classes']['contains-ttaddress'] = 'apps-pagetree-folder-contains-tt-address';

ExtensionManagementUtility::registerPageTSConfigFile(
    'tt_address',
    'Configuration/TSconfig/AllowedNewTables.typoscript',
    'EXT:tt_address :: Restrict pages to tt_address records'
);
