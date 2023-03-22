<?php

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('reactions')) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
        'sys_reaction',
        'table_name',
        [
            'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address',
            'tt_address',
            'EXT:tt_address/Resources/Public/Icons/tt_address.svg',
        ]
    );
}
