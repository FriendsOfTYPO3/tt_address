<?php
defined('TYPO3_MODE') or defined('TYPO3') or die;

call_user_func(static function () {
    // Enable language synchronisation for the category field
    $GLOBALS['TCA']['tt_address']['columns']['categories']['config']['behaviour']['allowLanguageSynchronization'] = true;

    $versionInformation = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Information\Typo3Version::class);
    if ($versionInformation->getMajorVersion() > 11) {
        unset($GLOBALS['TCA']['tt_address']['ctrl']['cruser_id']);
        $GLOBALS['TCA']['tt_address']['columns']['birthday']['config'] = [
            'type' => 'datetime',
        ];
        $GLOBALS['TCA']['tt_address']['columns']['starttime']['config'] = [
            'type' => 'datetime',
        ];
        $GLOBALS['TCA']['tt_address']['columns']['endtime']['config'] = [
            'type' => 'datetime',
        ];
        $GLOBALS['TCA']['tt_address']['columns']['crdate']['config'] = [
            'type' => 'datetime',
        ];
        $GLOBALS['TCA']['tt_address']['columns']['tstamp']['config'] = [
            'type' => 'datetime',
        ];
        $GLOBALS['TCA']['tt_address']['columns']['email']['config'] = [
            'type' => 'email',
        ];
        $GLOBALS['TCA']['tt_address']['columns']['www']['config'] = [
            'type' => 'link',
            'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
        ];
    }
});
