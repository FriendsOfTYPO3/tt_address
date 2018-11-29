<?php
defined('TYPO3_MODE') or die();

// Override news icon
$GLOBALS['TCA']['pages']['columns']['module']['config']['items'][] = [
    0 => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address',
    1 => 'tt_address',
    2 => 'apps-pagetree-folder-contains-tt-address'
];

$GLOBALS['TCA']['pages']['ctrl']['typeicon_classes']['contains-tt-address'] = 'apps-pagetree-folder-contains-tt-address';
$GLOBALS['TCA']['pages']['ctrl']['typeicon_classes']['contains-tt_address'] = 'apps-pagetree-folder-contains-tt-address';
$GLOBALS['TCA']['pages']['ctrl']['typeicon_classes']['contains-ttaddress'] = 'apps-pagetree-folder-contains-tt-address';
