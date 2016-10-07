<?php
defined('TYPO3_MODE') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig('
	options.saveDocNew.tt_address_group = 1
	options.saveDocNew.tt_address = 1
');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43(
    $_EXTKEY,
    'pi1/class.tx_ttaddress_pi1.php',
    '_pi1',
    'list_type',
    1
);

if (TYPO3_MODE === 'BE') {
    $settings = \TYPO3\TtAddress\Utility\SettingsUtility::getSettings();
    if ($settings->isStoreBackwardsCompatName()) {
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] =
            'TYPO3\\TtAddress\\Hooks\\DataHandler\\BackwardsCompatibilityNameFormat';
    }
}

// Update scripts
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['tt_address_group'] = 'TYPO3\\TtAddress\\Updates\\AddressGroupToSysCategory';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['tt_address_image'] = 'TYPO3\\TtAddress\\Updates\\ImageToFileReference';

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin ( "TYPO3." . $_EXTKEY, 'SingleView', array (
    'Main' => 'show'
), array (
    'Main' => 'show'
), \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_PLUGIN );

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin ( "TYPO3." . $_EXTKEY, 'ListView', array (
    'Main' => 'list'
), array (
    'Main' => 'list'
), \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_PLUGIN );