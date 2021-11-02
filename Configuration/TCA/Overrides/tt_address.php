<?php
defined('TYPO3_MODE') or die();

call_user_func(static function () {
    // Enable language synchronisation for the category field
    $GLOBALS['TCA']['tt_address']['columns']['categories']['config']['behaviour']['allowLanguageSynchronization'] = true;

    $versionInformation = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Information\Typo3Version::class);
    if ($versionInformation->getMajorVersion() === 11) {
        $GLOBALS['TCA']['tt_address']['columns']['sys_language_uid']['config'] = [
            'type' => 'language'
        ];
    }
});
