<?php
defined('TYPO3_MODE') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('tt_address', 'static/pi1/', 'Addresses');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('tt_address', 'static/old/', 'Addresses (!!!old, only use if you need to!!!)');
