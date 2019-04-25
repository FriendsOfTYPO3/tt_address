<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Address List',
    'description' => 'Displays a list of addresses from an address table on the page.',
    'category' => 'plugin',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'author' => 'tt_address Development Team',
    'author_email' => 'friendsof@typo3.org',
    'version' => '4.3.0',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.19-9.5.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
    'autoload' => [
        'classmap' => ['Classes'],
    ]
];
