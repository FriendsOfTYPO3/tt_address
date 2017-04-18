<?php

//#######################################################################
// Extension Manager/Repository config file for ext: "tt_address"
//
// Auto generated 24-12-2007 00:40
//
// Manual updates:
// Only the data in the array - anything else is removed by next write.
// "version" and "dependencies" must not be touched!
//#######################################################################

$EM_CONF[$_EXTKEY] = [
    'title' => 'Address List',
    'description' => 'Displays a list of addresses from an address table on the page.',
    'category' => 'plugin',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 1,
    'author' => 'tt_address Development Team',
    'author_email' => 'friendsof@typo3.org',
    'version' => '4.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '7.6.0-8.7.99',
            'php' => '5.6.0-7.2.99'
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
    'autoload' =>  [
        'classmap' => ['Classes'],
    ]
];
