<?php
defined('TYPO3_MODE') or die();

// Add static templates
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('tt_address', 'Configuration/TypoScript/', 'Addresses (Extbase/Fluid)');

/* ===========================================================================
 BEGIN: Add old legacy plugin
=========================================================================== */
$localExtConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tt_address']);

// Old templates; to be removed soonish
if((bool)$localExtConf['activatePiBase']) {
  \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('tt_address', 'Configuration/TypoScript/LegacyPlugin/', 'Addresses (Legacy Plugin)');
}

/* ===========================================================================
 END: Add old legacy plugin
=========================================================================== */