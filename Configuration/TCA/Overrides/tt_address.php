<?php
defined('TYPO3_MODE') or die;

// Enable language synchronisation for the category field
$GLOBALS['TCA']['tt_address']['columns']['categories']['config']['behaviour']['allowLanguageSynchronization'] = true;
