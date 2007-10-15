<?php


if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tt_address_group = 1
	options.saveDocNew.tt_address = 1
');

t3lib_extMgm::addPItoST43(
	$_EXTKEY,
	'pi1/class.tx_ttaddress_pi1.php',
	'_pi1',
	'list_type',
	1
);

/**
 * backwardscompatibility function which hooks into TCEmain and watches for
 * tt_address records with changes to the first, middle, and last name fields to
 * come by. That function shall then write changes back to the old combined name
 * field in a configurable format - first name first or last name first and
 * which glue string (comma, space, whatever)
 */
if (TYPO3_MODE)	{
	require_once(t3lib_extMgm::extPath('tt_address').'class.tx_ttaddress_compat.php');
}
$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'tx_ttaddress_compat'; 

?>