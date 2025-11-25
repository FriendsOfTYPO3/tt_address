<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Address List',
    'description' => 'Displays a list of addresses from an address table on the page.',
    'category' => 'plugin',
    'state' => 'stable',
    'author' => 'tt_address Development Team',
    'author_email' => 'friendsof@typo3.org',
    'version' => '10.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.20-14.4.99',
        ],
    ],
    'autoload' => [
        'psr-4' => ['FriendsOfTYPO3\\TtAddress\\' => 'Classes'],
    ],
    'autoload-dev' => [
        'psr-4' => ['FriendsOfTYPO3\\TtAddress\\Tests\\' => 'Tests'],
    ],
];
