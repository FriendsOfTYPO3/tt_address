<?php

$EM_CONF[$_EXTKEY] = [
    'title'            => 'Address List',
    'description'      => 'Displays a list of addresses from an address table on the page.',
    'category'         => 'plugin',
    'state'            => 'stable',
    'clearCacheOnLoad' => true,
    'author'           => 'tt_address Development Team',
    'author_email'     => 'friendsof@typo3.org',
    'version'          => '9.0.0',
    'constraints'      => [
        'depends' => [
            'typo3' => '12.4.0-13.9.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
    'autoload' => [
        'psr-4' => ['FriendsOfTYPO3\\TtAddress\\' => 'Classes'],
    ],
    'autoload-dev' => [
        'psr-4' => ['FriendsOfTYPO3\\TtAddress\\Tests\\' => 'Tests'],
    ],
];
