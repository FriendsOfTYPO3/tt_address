<?php

########################################################################
# Extension Manager/Repository config file for ext: "tt_address"
#
# Auto generated 24-12-2007 00:40
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Address List',
	'description' => 'Displays a list of addresses from an address table on the page.',
	'category' => 'plugin',
	'shy' => 0,
	'dependencies' => 'cms',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'author' => 'Ingo Renner',
	'author_email' => 'typo3@ingo-renner.com',
	'author_company' => 'ingo-renner.com',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'version' => '3.0.0-dev',
	'constraints' => array(
		'depends' => array(
			'typo3' => '6.2.2-7.9.99'
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'suggests' => array(
	),
    'autoload' =>
        array(
            'classmap' => array('Classes')
        ),
);
