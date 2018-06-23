<?php
defined('TYPO3_MODE') or die();


$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tt_address']);

// Add static templates
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('tt_address', 'Configuration/TypoScript/', 'Addresses (Extbase/Fluid)');

// Old templates; to be removed soonish
if($extConf['activatePiBase'] == 1) {
  \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('tt_address', 'Configuration/TypoScript/LegacyPlugin/', 'Addresses (Legacy Plugin)');
}

