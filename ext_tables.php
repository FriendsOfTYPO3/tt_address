<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tt_address'] = array (
	'ctrl' => array (
		'label'             => 'name',
		'label_alt'         => 'email',
		'default_sortby'    => 'ORDER BY name',
		'tstamp'            => 'tstamp',
		'prependAtCopy'     => 'LLL:EXT:lang/locallang_general.xml:LGL.prependAtCopy',
		'delete'            => 'deleted',
		'title'             => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address',
		'thumbnail'         => 'image',
		'enablecolumns'     => array (
			'disabled' => 'hidden'
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'ext_icon.gif'
	),
	'feInterface' => array (
		'fe_admin_fieldList' => 'pid,hidden,name,title,address,phone,fax,mobile,www,email,city,zip,company,region,country,description'
	)
);

$TCA['tt_address_group'] = array(
	'ctrl' => array(
		'title'                    => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address_group',		
		'label'                    => 'title',		
		'tstamp'                   => 'tstamp',
		'crdate'                   => 'crdate',
		'cruser_id'                => 'cruser_id',		
		'sortby'                   => 'sorting',	
		'delete'                   => 'deleted',		
		'treeParentField'          => 'parent_group',		
		'transOrigPointerField'    => 'l18n_parent',
		'transOrigDiffSourceField' => 'l18n_diffsource',
		'languageField'            => 'sys_language_uid',		
		'enablecolumns'            => array(		
			'disabled' => 'hidden',	
			'fe_group' => 'fe_group',
		),		
		'dynamicConfigFile'        => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'                 => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tt_address_group.gif',
	),
	'feInterface' => array(
		'fe_admin_fieldList' => 'hidden, fe_group, title, parent_group, description',
	)
);


t3lib_extMgm::addPlugin( 
	array(
		'LLL:EXT:tt_address/locallang_tca.xml:pi_tt_address', 
		$_EXTKEY.'_pi1'
	)
);
t3lib_extMgm::allowTableOnStandardPages('tt_address');
t3lib_extMgm::addToInsertRecords('tt_address');

t3lib_extMgm::addLLrefForTCAdescr('tt_address','EXT:tt_address/locallang_csh_ttaddress.xml');


// add flexform to pi1
t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1'] = 'pi_flexform';
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1'] = 'layout,select_key,pages,recursive';
t3lib_extMgm::addPiFlexFormValue($_EXTKEY .'_pi1', 'FILE:EXT:tt_address/pi1/flexform.xml');

t3lib_extMgm::addStaticFile($_EXTKEY, 'static/pi1/', 'Addresses');


if (TYPO3_MODE=='BE') {
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_ttaddress_pi1_wizicon'] = t3lib_extMgm::extPath($_EXTKEY).'pi1/class.tx_ttaddress_pi1_wizicon.php';
	
			// class for displaying the group tree in BE forms.
	include_once(t3lib_extMgm::extPath($_EXTKEY).'class.tx_ttaddress_tcefunc_selecttreeview.php');
	include_once(t3lib_extMgm::extPath($_EXTKEY).'class.tx_ttaddress_treeview.php');
	include_once(t3lib_extMgm::extPath($_EXTKEY).'class.tx_ttaddress_addfilestosel.php');

}



?>