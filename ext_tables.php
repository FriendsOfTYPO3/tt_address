<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

define('TT_ADDRESS_MAX_IMAGES', 6);

$TCA['tt_address'] = array (
	'ctrl' => array (
		'label'             => 'name',
		'label_alt'         => 'email',
		'default_sortby'    => 'ORDER BY last_name, first_name, middle_name',
		'tstamp'            => 'tstamp',
		'prependAtCopy'     => 'LLL:EXT:lang/locallang_general.xml:LGL.prependAtCopy',
		'delete'            => 'deleted',
		'title'             => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address',
		'thumbnail'         => 'image',
		'enablecolumns'     => array (
			'disabled' => 'hidden'
		),
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY).'tca.php',
		'iconfile'          => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY).'ext_icon.gif',
		'searchFields'      => 'name, first_name, middle_name, last_name, email',
		'dividers2tabs'     => 1,
	),
	'feInterface' => array (
		'fe_admin_fieldList' => 'pid,hidden,gender,name,title,address,building,room,birthday,phone,fax,mobile,www,email,city,zip,company,region,country,image,description'
	)
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::makeCategorizable(
	'tt_address',
	'tt_address'
);

	// start splitting name into first and last name
$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tt_address']);

$fe_admin_fieldListOrig  = $TCA['tt_address']['feInterface']['fe_admin_fieldList'];
$fe_admin_fieldListReplace  = 'name,first_name,middle_name,last_name,';

if ($extConf['disableCombinedNameField']) {
		// shows only the new fields
	$fe_admin_fieldListReplace  = 'first_name,middle_name,last_name,';
}

$fe_admin_fieldListNew = str_replace(
	'name,',
	$fe_admin_fieldListReplace,
	$fe_admin_fieldListOrig
);
$TCA['tt_address']['feInterface']['fe_admin_fieldList'] = $fe_admin_fieldListNew;
	// end splitting name


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
	array(
		'LLL:EXT:tt_address/locallang_tca.xml:pi_tt_address',
		$_EXTKEY.'_pi1'
	)
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tt_address');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords('tt_address');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tt_address','EXT:tt_address/locallang_csh_ttaddress.xml');


// add flexform to pi1
if (version_compare(TYPO3_branch, '6.1', '<')) {
	\TYPO3\CMS\Core\Utility\GeneralUtility::loadTCA('tt_content');
}
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1'] = 'pi_flexform';
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1'] = 'layout,select_key,pages,recursive';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($_EXTKEY .'_pi1', 'FILE:EXT:tt_address/pi1/flexform.xml');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'static/pi1/', 'Addresses');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'static/old/', 'Addresses (!!!old, only use if you need to!!!)');

if (TYPO3_MODE=='BE') {
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_ttaddress_pi1_wizicon'] = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY).'pi1/class.tx_ttaddress_pi1_wizicon.php';

			// classes for manipulating flexforms
	include_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'class.tx_ttaddress_addfilestosel.php');
	include_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'class.tx_ttaddress_addfieldstosel.php');

}



?>