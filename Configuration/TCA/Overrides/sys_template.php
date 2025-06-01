<?php

defined('TYPO3_MODE') or defined('TYPO3') or die;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addStaticFile('tt_address', 'Configuration/TypoScript/', 'Addresses (Extbase/Fluid)');
