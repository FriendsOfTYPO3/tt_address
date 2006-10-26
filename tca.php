<?php

// ******************************************************************
// This is the standard TypoScript address table, tt_address
// ******************************************************************
$TCA['tt_address'] = array (
	'ctrl' => $TCA['tt_address']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'name,address,city,zip,country,phone,fax,email,www,title,company,image'
	),
	'feInterface' => $TCA['tt_address']['feInterface'],
	'columns' => array (
		'hidden' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => array (
				'type' => 'check'
			)
		),
		'name' => array (
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.name',
			'config' => array (
				'type' => 'input',
				'size' => '40',
				'max' => '256'
			)
		),
		'first_name' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.first_name',		
			'config' => array (
				'type' => 'input',
				'size' => '40',
				'eval' => 'trim',
				'max' => '256'
			)
		),
		'last_name' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.last_name',		
			'config' => array (
				'type' => 'input',
				'size' => '40',
				'eval' => 'trim',
				'max' => '256'
			)
		),
		'title' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.title_person',
			'config' => array (
				'type' => 'input',
				'size' => '20',
				'eval' => 'trim',
				'max' => '40'
			)
		),
		'address' => array (
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.address',
			'config' => array (
				'type' => 'text',
				'cols' => '20',
				'rows' => '3'
			)
		),
		'phone' => array (
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.phone',
			'config' => array (
				'type' => 'input',
				'eval' => 'trim',
				'size' => '20',
				'max' => '30'
			)
		),
		'fax' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.fax',
			'config' => array (
				'type' => 'input',
				'size' => '20',
				'eval' => 'trim',
				'max' => '30'
			)
		),
		'mobile' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.mobile',
			'config' => array (
				'type' => 'input',
				'eval' => 'trim',
				'size' => '20',
				'max' => '30'
			)
		),
		'www' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.www',
			'config' => array (
				'type' => 'input',
				'eval' => 'trim',
				'size' => '20',
				'max' => '80'
			)
		),
		'email' => array (
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.email',
			'config' => array (
				'type' => 'input',
				'size' => '40',
				'eval' => 'trim',
				'max' => '80'
			)
		),
		'company' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.company',
			'config' => array (
				'type' => 'input',
				'eval' => 'trim',
				'size' => '20',
				'max' => '80'
			)
		),
		'city' => array (
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.city',
			'config' => array (
				'type' => 'input',
				'size' => '20',
				'eval' => 'trim',
				'max' => '80'
			)
		),
		'zip' => array (
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.zip',
			'config' => array (
				'type' => 'input',
				'eval' => 'trim',
				'size' => '10',
				'max' => '20'
			)
		),
		'country' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.country',
			'config' => array (
				'type' => 'input',
				'size' => '20',
				'eval' => 'trim',
				'max' => '30'
			)
		),
		'image' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.image',
			'config' => array (
				'type' => 'group',
				'internal_type' => 'file',
				'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
				'max_size' => '1000',
				'uploadfolder' => 'uploads/pics',
				'show_thumbs' => '1',
				'size' => '3',
				'maxitems' => '6',
				'minitems' => '0'
			)
		),
		'description' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.description',
			'config' => array (
				'type' => 'text',
				'rows' => 5,
				'cols' => 48
			)
		)
	),
	'types' => array (
		'1' => array('showitem' => 'hidden;;;;1-1-1, name;;2;;3-3-3, address, zip, city;;3, email;;5, phone;;4, image;;;;4-4-4, description')
	),
	'palettes' => array (
		'2' => array('showitem' => 'title, company'),
		'3' => array('showitem' => 'country'),
		'4' => array('showitem' => 'mobile, fax'),
		'5' => array('showitem' => 'www')
	)
);


//-- start splitting name into first and last name

$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tt_address']);

// original values
$showitemOrig            = $TCA['tt_address']['types'][1]['showitem'];
$showRecordFieldListOrig = $TCA['tt_address']['interface']['showRecordFieldList'];
$fe_admin_fieldListOrig  = $TCA['tt_address']['feInterface']['fe_admin_fieldList'];

// shows both, the old and the new fields while converting to the new fields
$showItemReplace = ' name;;;;3-3-3, first_name, last_name;;2;;,'; 
$showRecordFieldListReplace = 'name,first_name,last_name,';
$fe_admin_fieldListReplace  = 'name,first_name,last_name,';


if($extConf['disableCombinedNameField']) {
	unset($TCA['tt_address']['columns']['name']);
	
	// shows only the new fields
	$showItemReplace            = ' first_name;;;;1-1-1, last_name;;2;;,';
	$showRecordFieldListReplace = 'first_name,last_name,';
	$fe_admin_fieldListReplace  = 'first_name,last_name,';

	$TCA['tt_address']['ctrl']['label_alt']       = 'last_name, first_name';
	$TCA['tt_address']['ctrl']['label_alt_force'] = 1;
	$TCA['tt_address']['ctrl']['default_sortby']  = 'ORDER BY last_name, first_name';
}

$showitemNew = str_replace(' name;;2;;3-3-3,', $showItemReplace, $showitemOrig);
$showRecordFieldListNew = str_replace(
	'name,',
	$showRecordFieldListReplace,
	$showRecordFieldListOrig
);
$fe_admin_fieldListNew = str_replace(
	'name,',
	$fe_admin_fieldListReplace,
	$fe_admin_fieldListOrig
);
$TCA['tt_address']['types'][1]['showitem'] = $showitemNew;
$TCA['tt_address']['interface']['showRecordFieldList'] = $showRecordFieldListNew;
$TCA['tt_address']['feInterface']['fe_admin_fieldList'] = $fe_admin_fieldListNew;

//-- end splitting name


?>