<?php
defined('TYPO3_MODE') or die();

// Enable language synchronisation for the category field
$GLOBALS['TCA']['tt_address']['columns']['categories']['config']['behaviour']['allowLanguageSynchronization'] = true;

if (version_compare(TYPO3_branch, '9.2', '<')) {
    unset($GLOBALS['TCA']['tt_address']['columns']['slug']);
}
