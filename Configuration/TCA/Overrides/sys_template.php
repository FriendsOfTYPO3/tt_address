<?php
defined('TYPO3_MODE') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('tt_address', 'Configuration/TypoScript/', 'Addresses (Extbase/Fluid)');

$settings = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\FriendsOfTYPO3\TtAddress\Domain\Model\Dto\Settings::class);

if ($settings->isActivatePiBase()) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('tt_address', 'Configuration/TypoScript/LegacyPlugin/', 'Addresses (Legacy Plugin)');
}