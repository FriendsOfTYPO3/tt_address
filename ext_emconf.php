<?php

########################################################################
# Extension Manager/Repository config file for ext: "tt_address"
# 
# Auto generated 26-03-2007 14:51
# 
# Manual updates:
# Only the data in the array - anything else is removed by next write
########################################################################

$EM_CONF[$_EXTKEY] = Array (
	'title' => 'Address list',
	'description' => 'Displays a list of addresses from an address table on the page.',
	'category' => 'plugin',
	'shy' => 0,
	'dependencies' => 'cms',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'TYPO3_version' => '',
	'PHP_version' => '',
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
	'version' => '2.0.0',	// Don't modify this! Managed automatically during upload to repository.
	'_md5_values_when_last_written' => 'a:123:{s:8:".project";s:4:"7276";s:9:"ChangeLog";s:4:"dbcd";s:4:"ToDo";s:4:"5da4";s:9:"build.xml";s:4:"d7c6";s:20:"class.ext_update.php";s:4:"42a2";s:36:"class.tx_ttaddress_addfilestosel.php";s:4:"9a1d";s:29:"class.tx_ttaddress_compat.php";s:4:"8b4f";s:45:"class.tx_ttaddress_tcefunc_selecttreeview.php";s:4:"0b4c";s:31:"class.tx_ttaddress_treeview.php";s:4:"ca8f";s:21:"ext_conf_template.txt";s:4:"a7cf";s:12:"ext_icon.gif";s:4:"e1bc";s:15:"ext_icon__h.gif";s:4:"1bdd";s:15:"ext_icon__x.gif";s:4:"aec5";s:17:"ext_localconf.php";s:4:"082f";s:14:"ext_tables.php";s:4:"9e8c";s:14:"ext_tables.sql";s:4:"558b";s:28:"ext_typoscript_constants.txt";s:4:"a11f";s:24:"ext_typoscript_setup.txt";s:4:"c944";s:25:"icon_tt_address_group.gif";s:4:"156c";s:13:"locallang.xml";s:4:"6b88";s:27:"locallang_csh_ttaddress.xml";s:4:"1fd9";s:17:"locallang_tca.xml";s:4:"1f36";s:7:"tca.php";s:4:"aa92";s:16:".svn/all-wcprops";s:4:"632d";s:18:".svn/dir-prop-base";s:4:"d7f4";s:12:".svn/entries";s:4:"c369";s:11:".svn/format";s:4:"c30f";s:60:".svn/prop-base/class.tx_ttaddress_addfilestosel.php.svn-base";s:4:"c4b9";s:69:".svn/prop-base/class.tx_ttaddress_tcefunc_selecttreeview.php.svn-base";s:4:"c4b9";s:55:".svn/prop-base/class.tx_ttaddress_treeview.php.svn-base";s:4:"c4b9";s:45:".svn/prop-base/ext_conf_template.txt.svn-base";s:4:"4160";s:38:".svn/prop-base/ext_emconf.php.svn-base";s:4:"4160";s:36:".svn/prop-base/ext_icon.gif.svn-base";s:4:"945a";s:39:".svn/prop-base/ext_icon__h.gif.svn-base";s:4:"945a";s:39:".svn/prop-base/ext_icon__x.gif.svn-base";s:4:"945a";s:41:".svn/prop-base/ext_localconf.php.svn-base";s:4:"cd30";s:38:".svn/prop-base/ext_tables.php.svn-base";s:4:"4160";s:38:".svn/prop-base/ext_tables.sql.svn-base";s:4:"4160";s:52:".svn/prop-base/ext_typoscript_constants.txt.svn-base";s:4:"4160";s:48:".svn/prop-base/ext_typoscript_setup.txt.svn-base";s:4:"4160";s:49:".svn/prop-base/icon_tt_address_group.gif.svn-base";s:4:"c5ac";s:37:".svn/prop-base/locallang.xml.svn-base";s:4:"4160";s:51:".svn/prop-base/locallang_csh_ttaddress.xml.svn-base";s:4:"4160";s:41:".svn/prop-base/locallang_tca.xml.svn-base";s:4:"4160";s:31:".svn/prop-base/tca.php.svn-base";s:4:"4160";s:33:".svn/text-base/ChangeLog.svn-base";s:4:"849a";s:28:".svn/text-base/ToDo.svn-base";s:4:"5da4";s:33:".svn/text-base/build.xml.svn-base";s:4:"d7c6";s:44:".svn/text-base/class.ext_update.php.svn-base";s:4:"8ed1";s:60:".svn/text-base/class.tx_ttaddress_addfilestosel.php.svn-base";s:4:"75c7";s:69:".svn/text-base/class.tx_ttaddress_tcefunc_selecttreeview.php.svn-base";s:4:"0b4c";s:55:".svn/text-base/class.tx_ttaddress_treeview.php.svn-base";s:4:"ca8f";s:45:".svn/text-base/ext_conf_template.txt.svn-base";s:4:"a841";s:38:".svn/text-base/ext_emconf.php.svn-base";s:4:"55b6";s:36:".svn/text-base/ext_icon.gif.svn-base";s:4:"e1bc";s:39:".svn/text-base/ext_icon__h.gif.svn-base";s:4:"1bdd";s:39:".svn/text-base/ext_icon__x.gif.svn-base";s:4:"aec5";s:41:".svn/text-base/ext_localconf.php.svn-base";s:4:"bf69";s:38:".svn/text-base/ext_tables.php.svn-base";s:4:"9e8c";s:38:".svn/text-base/ext_tables.sql.svn-base";s:4:"558b";s:52:".svn/text-base/ext_typoscript_constants.txt.svn-base";s:4:"a11f";s:48:".svn/text-base/ext_typoscript_setup.txt.svn-base";s:4:"c944";s:49:".svn/text-base/icon_tt_address_group.gif.svn-base";s:4:"156c";s:37:".svn/text-base/locallang.xml.svn-base";s:4:"6b88";s:51:".svn/text-base/locallang_csh_ttaddress.xml.svn-base";s:4:"1fd9";s:41:".svn/text-base/locallang_tca.xml.svn-base";s:4:"1f36";s:31:".svn/text-base/tca.php.svn-base";s:4:"aa92";s:13:"doc/.DS_Store";s:4:"1945";s:14:"doc/manual.sxw";s:4:"7688";s:16:"doc/.svn/entries";s:4:"09e5";s:15:"doc/.svn/format";s:4:"c30f";s:38:"doc/.svn/prop-base/manual.sxw.svn-base";s:4:"c5ac";s:43:"doc/.svn/prop-base/wizard_form.dat.svn-base";s:4:"4160";s:44:"doc/.svn/prop-base/wizard_form.html.svn-base";s:4:"4160";s:38:"doc/.svn/text-base/manual.sxw.svn-base";s:4:"9596";s:43:"doc/.svn/text-base/wizard_form.dat.svn-base";s:4:"bf6d";s:44:"doc/.svn/text-base/wizard_form.html.svn-base";s:4:"0c6d";s:14:"pi1/ce_wiz.gif";s:4:"2648";s:30:"pi1/class.tx_ttaddress_pi1.php";s:4:"3773";s:38:"pi1/class.tx_ttaddress_pi1_wizicon.php";s:4:"919c";s:16:"pi1/flexform.xml";s:4:"01cd";s:17:"pi1/locallang.xml";s:4:"222a";s:20:"pi1/locallang_ff.xml";s:4:"ccdd";s:20:"pi1/.svn/all-wcprops";s:4:"ff60";s:16:"pi1/.svn/entries";s:4:"f225";s:15:"pi1/.svn/format";s:4:"c30f";s:38:"pi1/.svn/prop-base/ce_wiz.gif.svn-base";s:4:"c5ac";s:54:"pi1/.svn/prop-base/class.tx_ttaddress_pi1.php.svn-base";s:4:"c4b9";s:62:"pi1/.svn/prop-base/class.tx_ttaddress_pi1_wizicon.php.svn-base";s:4:"c4b9";s:40:"pi1/.svn/prop-base/flexform.xml.svn-base";s:4:"4160";s:41:"pi1/.svn/prop-base/locallang.xml.svn-base";s:4:"4160";s:44:"pi1/.svn/prop-base/locallang_ff.xml.svn-base";s:4:"4160";s:38:"pi1/.svn/text-base/ce_wiz.gif.svn-base";s:4:"2648";s:54:"pi1/.svn/text-base/class.tx_ttaddress_pi1.php.svn-base";s:4:"1dc6";s:62:"pi1/.svn/text-base/class.tx_ttaddress_pi1_wizicon.php.svn-base";s:4:"919c";s:40:"pi1/.svn/text-base/flexform.xml.svn-base";s:4:"01cd";s:41:"pi1/.svn/text-base/locallang.xml.svn-base";s:4:"222a";s:44:"pi1/.svn/text-base/locallang_ff.xml.svn-base";s:4:"ccdd";s:21:"res/default_hcard.gif";s:4:"25a0";s:21:"res/default_hcard.htm";s:4:"7a26";s:20:"res/.svn/all-wcprops";s:4:"2ba8";s:16:"res/.svn/entries";s:4:"4549";s:15:"res/.svn/format";s:4:"c30f";s:45:"res/.svn/prop-base/default_hcard.gif.svn-base";s:4:"c5ac";s:45:"res/.svn/prop-base/default_hcard.htm.svn-base";s:4:"4160";s:45:"res/.svn/prop-base/default_hcrad.gif.svn-base";s:4:"c5ac";s:45:"res/.svn/prop-base/example_hcard.htm.svn-base";s:4:"4160";s:45:"res/.svn/text-base/default_hcard.gif.svn-base";s:4:"25a0";s:45:"res/.svn/text-base/default_hcard.htm.svn-base";s:4:"700b";s:45:"res/.svn/text-base/default_hcrad.gif.svn-base";s:4:"25a0";s:45:"res/.svn/text-base/example_hcard.htm.svn-base";s:4:"dff2";s:23:"static/.svn/all-wcprops";s:4:"5585";s:19:"static/.svn/entries";s:4:"2f96";s:18:"static/.svn/format";s:4:"c30f";s:24:"static/pi1/constants.txt";s:4:"d41d";s:20:"static/pi1/setup.txt";s:4:"99d8";s:27:"static/pi1/.svn/all-wcprops";s:4:"f91d";s:23:"static/pi1/.svn/entries";s:4:"4dcd";s:22:"static/pi1/.svn/format";s:4:"c30f";s:48:"static/pi1/.svn/prop-base/constants.txt.svn-base";s:4:"4160";s:44:"static/pi1/.svn/prop-base/setup.txt.svn-base";s:4:"4160";s:48:"static/pi1/.svn/text-base/constants.txt.svn-base";s:4:"d41d";s:44:"static/pi1/.svn/text-base/setup.txt.svn-base";s:4:"99d8";}',
);

?>