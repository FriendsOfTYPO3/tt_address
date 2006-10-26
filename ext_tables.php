<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tt_address'] = array (
	'ctrl' => array (
		'label' => 'name',
		'label_alt' => 'email',
		'default_sortby' => 'ORDER BY name',
		'tstamp' => 'tstamp',
		'prependAtCopy' => 'LLL:EXT:lang/locallang_general.xml:LGL.prependAtCopy',
		'delete' => 'deleted',
		'title' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address',
		'enablecolumns' => array (
			'disabled' => 'hidden'
		),
		'thumbnail' => 'image',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY).'ext_icon.gif',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php'
	),
	'feInterface' => array (
		'fe_admin_fieldList' => 'pid,hidden,name,title,address,phone,fax,mobile,www,email,city,zip,company,country,description'
	)
);


t3lib_extMgm::addPlugin( 
	array(
		'LLL:EXT:tt_address/locallang_tca.xml:pi_tt_address', 
		'0'
	)
);
t3lib_extMgm::allowTableOnStandardPages('tt_address');
t3lib_extMgm::addToInsertRecords('tt_address');

t3lib_extMgm::addLLrefForTCAdescr('tt_address','EXT:tt_address/locallang_csh_ttaddr.xml');
?>