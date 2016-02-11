<?php
defined('TYPO3_MODE') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
    array(
        'LLL:EXT:tt_address/locallang_tca.xml:pi_tt_address',
        'tt_address_pi1'
    ),
    'list_type',
    'tt_address'
);

// add flexform to pi1
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['tt_address_pi1'] = 'pi_flexform';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['tt_address_pi1'] = 'layout,select_key,pages,recursive';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('tt_address_pi1', 'FILE:EXT:tt_address/pi1/flexform.xml');
