<?php

if ((new \TYPO3\CMS\Core\Information\Typo3Version())->getMajorVersion() >= 14) {
    unset($GLOBALS['TCA']['tt_address']['ctrl']['searchFields']);
}
