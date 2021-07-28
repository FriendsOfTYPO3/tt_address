<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Address List',
    'description' => 'Displays a list of addresses from an address table on the page.',
    'category' => 'plugin',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'author' => 'tt_address Development Team',
    'author_email' => 'friendsof@typo3.org',
    'version' => '5.3.0',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.9-11.3.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
    'autoload' => [
        'classmap' => ['Classes'],
    ],
    'autoload-dev' =>
        [
            'psr-4' => ['FriendsOfTYPO3\\TtAddress\\Tests\\' => 'Tests']
        ],
];
