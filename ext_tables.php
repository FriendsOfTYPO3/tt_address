<?php
defined('TYPO3_MODE') or die();

$_EXTCONF = unserialize($_EXTCONF);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tt_address');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords('tt_address');


