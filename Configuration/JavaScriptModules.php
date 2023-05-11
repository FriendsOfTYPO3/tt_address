<?php
defined('TYPO3') or die;

return [
    'dependencies' => ['core'],
    'imports' => [
        '@friendsoftypo3/tt-address/' => 'EXT:tt_address/Resources/Public/JavaScript/esm/',
    ],
];
