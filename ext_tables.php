<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tt_address'] = Array (
	'ctrl' => Array (
		'label' => 'name',
		'label_alt' => 'email',
		'default_sortby' => 'ORDER BY name',
		'tstamp' => 'tstamp',
		'prependAtCopy' => 'LLL:EXT:lang/locallang_general.php:LGL.prependAtCopy',
		'delete' => 'deleted',
		'title' => 'LLL:EXT:tt_address/locallang_tca.php:tt_address',
		'enablecolumns' => Array (
			'disabled' => 'hidden'
		),
		'thumbnail' => 'image',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY).'ext_icon.gif',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php'
	),
	'feInterface' => Array (
		'fe_admin_fieldList' => 'pid,hidden,name,title,address,phone,fax,mobile,www,email,city,zip,company,country,description'
	)
);
t3lib_extMgm::addPlugin(Array('LLL:EXT:tt_address/locallang_tca.php:pi_tt_address', '0'));
t3lib_extMgm::allowTableOnStandardPages('tt_address');
t3lib_extMgm::addToInsertRecords('tt_address');

t3lib_extMgm::addLLrefForTCAdescr('tt_address','EXT:tt_address/locallang_csh_ttaddr.php');
?>