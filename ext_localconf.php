<?php
defined('TYPO3_MODE') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig('options.saveDocNew.tt_address = 1');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43(
    'tt_address',
    'pi1/class.tx_ttaddress_pi1.php',
    '_pi1',
    'list_type',
    true
);

if (TYPO3_MODE === 'BE') {
    $settings = \TYPO3\TtAddress\Utility\SettingsUtility::getSettings();
    if ($settings->isStoreBackwardsCompatName()) {
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] =
            \TYPO3\TtAddress\Hooks\DataHandler\BackwardsCompatibilityNameFormat::class;
    }
}

// Update scripts
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['tt_address_group'] = \TYPO3\TtAddress\Updates\AddressGroupToSysCategory::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['tt_address_image'] = \TYPO3\TtAddress\Updates\ImageToFileReference::class;
