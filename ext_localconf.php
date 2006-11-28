<?php


if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tt_address_group = 1
	options.saveDocNew.tt_address = 1
');

$GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tt_address']['useStoragePid'] = 0;

	/**
	 * TODO: add backwardscompatibility function which hooks into TCEmain and
	 * watches for tt_address records with changes to the first and last name
	 * fields to come by. That function shall then write changes back to the old
	 * combined name field in a configurable format - first name first or last
	 * name first and which glue string (comma, space, whatever)
	 */

//$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'tt_address_______'; 

?>