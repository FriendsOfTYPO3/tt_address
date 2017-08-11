<?php
defined('TYPO3_MODE') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tt_address');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords('tt_address');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tt_address', 'EXT:tt_address/Resources/Private/Language/locallang_csh_ttaddress.xml');

if (TYPO3_MODE === 'BE') {
    $GLOBALS['TBE_MODULES_EXT']['xMOD_db_new_content_el']['addElClasses']['tx_ttaddress_pi1_wizicon'] =
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('tt_address') . 'Classes/Hooks/NewContentElementWizardHook.php';
}
