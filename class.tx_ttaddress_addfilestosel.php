<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2007 Ingo Renner <typo3@ingo-renner.com>
*  All  rights reserved
* 
*  This script is part of the Typo3 project. The Typo3 project is 
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
* 
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
* 
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

require_once(PATH_t3lib.'class.t3lib_page.php');
require_once(PATH_t3lib.'class.t3lib_tstemplate.php');
require_once(PATH_t3lib.'class.t3lib_tsparser_ext.php');


/** 
 * Class/Function which manipulates the item-array for the pi1 template
 * selector in the flexform.
 *
 * @author	Ingo Renner <typo3@ingo-renner.com>
 */
class tx_ttaddress_addfilestosel {
   
   /**
    * Manipulating the input array, $params, adding new selectorbox items.
    * 
    * @param	array	array of select field options (reference)
    * @param	object	parent object (reference)
    * @return	void
    */
	function main(&$params,&$pObj)	{

			// get the current page ID
		$thePageId = $params['row']['pid']; 

		$template = t3lib_div::makeInstance('t3lib_tsparser_ext');
			// do not log time-performance information
		$template->tt_track = 0;
		$template->init();
		$sys_page = t3lib_div::makeInstance('t3lib_pageSelect');
		$rootLine = $sys_page->getRootLine($thePageId);
			// generate the constants/config + hierarchy info for the template.
		$template->runThroughTemplates($rootLine);
		$template->generateConfig();

			// get value for the path containing the template files
		$readPath = t3lib_div::getFileAbsFileName(
			$template->setup['plugin.']['tx_ttaddress_pi1.']['templatePath']
		);
	     
			// if that direcotry is valid and is a directory then select files in it
		if (@is_dir($readPath)) {

			$template_files = t3lib_div::getFilesInDir($readPath,'tmpl,html,htm',1,1);
			$parseHTML = t3lib_div::makeInstance('t3lib_parseHTML');
	      		      
			foreach ($template_files as $htmlFilePath) {
					// reset vars
				$selectorBoxItem_title = '';
				$selectorBoxItem_icon  = '';
 
					// read template content
				$content = t3lib_div::getUrl($htmlFilePath);
					// ... and extract content of the title-tags
				$parts = $parseHTML->splitIntoBlock('title', $content);
				$titleTagContent = $parseHTML->removeFirstAndLastTag($parts[1]);
				
					// set the item label
				$selectorBoxItem_title = trim($titleTagContent.' ('.basename($htmlFilePath).')');
 
					// try to look up an image icon for the template
				$fI = t3lib_div::split_fileref($htmlFilePath);
				$testImageFilename=$readPath.$fI['filebody'].'.gif';
				if(@is_file($testImageFilename)) {
					$selectorBoxItem_icon = '../'.substr($testImageFilename, strlen(PATH_site));
				}
 
					// finally add the new item
				$params['items'][] = array(
					$selectorBoxItem_title,
					basename($htmlFilePath),
					$selectorBoxItem_icon
				);
			}
		}		
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tt_address/class.tx_ttaddress_addfilestosel.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tt_address/class.tx_ttaddress_addfilestosel.php']);
}

?>