<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2006 Ingo Renner (typo3@ingo-renner.com)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
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

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * main class for the tt_address plugin, outputs addresses either by direct
 * selection or by selection via groups or a combination of both
 * 
 * @author Ingo Renner <typo3@ingo-renner.com>
 */
class tx_ttaddress_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_ttaddress_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_ttaddress_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'tt_address';	// The extension key.
	var $pi_checkCHash = true;
	
	var $conf;
	var $ffData;
	
	/**
	 * main method which controls the data flow and outputs the addresses
	 * 
	 * @param	string	$content: content string, empty
	 * @param	array	$conf: configuration array with TS configuration
	 * @return	string	the processed addresses
	 */
	function main($content, $conf) {
		$this->init($conf);
		$content = '';
	
		$singleSelection = $this->getSingleRecords();		
		$groupSelection  = $this->getRecordsFromGroups();
		// merge both arrays so that we do not have any duplicates		
		$addresses = t3lib_div::array_merge($singleSelection, $groupSelection);

		$templateCode = $this->getTemplate();		
		$sorting = explode(',', $this->ffData['singleRecords']);

		// sorting the addresses by name
		$sort = array();
		foreach($addresses as $k => $v) {
			$sort[$k] = $v[$this->conf['sortByColumn']];
		}
		array_multisort($sort, $this->conf['sortOrder'], $addresses);
		
		// output the remaining addresses - should be the groupSelection	
		foreach($addresses as $address) {
			if(!empty($address)) {
				$markerArray = $this->getItemMarkerArray($address);
			
				$content .= $this->cObj->substituteMarkerArrayCached(
					$templateCode,
					$markerArray
				);
				$content .= chr(10).chr(10);	
			}			
		}		
	
		return $this->pi_wrapInBaseClass($content);
	}
	
	/**
	 * initializes the configuration for the plugin and gets the settings from
	 * the flexform
	 * 
	 * @param	array	$conf: array with TS configuration
	 * @return	void
	 */
	function init($conf) {
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		$this->pi_initPIflexForm();
		
		$this->ffData = array(
			'singleRecords'  => $this->pi_getFFvalue(
				$this->cObj->data['pi_flexform'], 
				'singleRecords',  
				'sDEF'
			),
			'groupSelection' => $this->pi_getFFvalue(
				$this->cObj->data['pi_flexform'], 
				'groupSelection', 
				'sDEF'
			),
			'combination'    => $this->pi_getFFvalue(
				$this->cObj->data['pi_flexform'], 
				'combination',    
				'sDEF'
			),
			'pages'          => $this->pi_getFFvalue(
				$this->cObj->data['pi_flexform'], 
				'pages',          
				'sDEF'
			),
			'recursive'      => $this->pi_getFFvalue(
				$this->cObj->data['pi_flexform'], 
				'recursive',      
				'sDEF'
			),
			'templateFile'   => $this->pi_getFFvalue(
				$this->cObj->data['pi_flexform'], 
				'templateFile',   
				'sDISPLAY'
			),
		);
		
		//set default combination to AND if no combination set
		$this->ffData['combination'] = intval($this->ffData['combination']) ?
			$this->ffData['combination'] :
			0;
			
		//set default sorting to name
		$this->conf['sortByColumn'] = $this->conf['sortByColumn'] ? 
			$this->conf['sortByColumn'] : 
			'name';
		//check a userdefined sorting criteria for validity
		$this->conf['sortByColumn'] = $this->checkSorting($this->conf['sortByColumn']);
		
		//set sorting, set to ASC if not valid
		$sortOrder = $this->conf['sortOrder'];
		if(strtoupper($sortOrder) == 'DESC') {
			$sortOrder = SORT_DESC;
		} else {
			$sortOrder = SORT_ASC;
		}
		$this->conf['sortOrder'] = $sortOrder;
				
		// overwrite TS pidList if set in flexform
		$pages = !empty($this->ffData['pages']) ? 
			$this->ffData['pages'] : 
			trim($this->cObj->stdWrap(
				$this->conf['pidList'], $this->conf['pidList.']
			));
		$pages = $pages ? 
			implode(t3lib_div::intExplode(',', $pages), ',') : 
			$GLOBALS['TSFE']->id;
		
		$recursive = $this->ffData['recursive'] ?
			$this->ffData['recursive'] :
			intval($this->conf['recursive']);
		
		$this->conf['pidList'] = $this->pi_getPidList(
			$pages,
			$recursive
		);
		
		$this->conf['templateName'] = $this->getTemplateName();
	}
	
	/**
	 * gets the records the user selected in the single address selection field
	 * 
	 * @return	array	array of addresses with their uids as array keys
	 */
	function getSingleRecords() {
		$singleRecords = array();
		$uidList = $GLOBALS['TYPO3_DB']->cleanIntList(
			$this->ffData['singleRecords']
		);
		
		if(!empty($uidList) && !empty($this->conf['pidList'])) {
			$addresses = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
				'*',
				'tt_address',
				'uid IN('.$uidList.') AND pid IN('.$this->conf['pidList'].')'
				.$this->cObj->enableFields('tt_address')
			);
			
			foreach($addresses as $k => $address) {
				$singleRecords[$address['uid']] = $this->getGroupsForAddress($address);
			}
		}
				
		return $singleRecords;
	}
	
	/**
	 * gets the addresses which meet the group selection
	 * 
	 * @return	array	array of addresses with their uids as array keys
	 */
	function getRecordsFromGroups() {
		$groupRecords = array();
		
		// similar to t3lib_db::cleanIntList(), but we need the count for AND combination
		$groups    = t3lib_div::intExplode(',',$this->ffData['groupSelection']);
		$count     = count($groups);		
		$groupList = implode(',', $groups);
		
		if(!empty($groupList) && !empty($this->conf['pidList'])) {
			if($this->ffData['combination'] == '0') {
				//AND
				$res = $GLOBALS['TYPO3_DB']->sql_query(
					'SELECT tt_address.*, COUNT(tt_address.uid) AS c '.
					'FROM tt_address '. 
					'JOIN tt_address_group_mm AS tta_txagg_mm ON tt_address.uid = tta_txagg_mm.uid_local '. 
					'JOIN tt_address_group AS txagg ON txagg.uid = tta_txagg_mm.uid_foreign '. 
					'WHERE uid_foreign IN ('.$groupList.') '.
						$this->cObj->enableFields('tt_address').
						' AND tt_address.pid IN('.$this->conf['pidList'].') '.
					'GROUP BY tt_address.uid '. 
					'HAVING c = '.$count.' '
				);
				
			} elseif($this->ffData['combination'] == '1') {
				//OR
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'DISTINCT tt_address.*',
					'tt_address, tt_address_group_mm, tt_address_group',
					'tt_address_group_mm.uid_foreign IN('.$groupList.
						') AND tt_address.uid = tt_address_group_mm.uid_local '.
						$this->cObj->enableFields('tt_address').
						' AND tt_address.pid IN('.$this->conf['pidList'].')'
				);
			}
			
			while($address = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$groupRecords[$address['uid']] = $this->getGroupsForAddress($address);
			}
		}
		
		return $groupRecords;
		
	}
	
	/**
	 * gets the groups an address record is in
	 * 
	 * @param	array	$address: an address record
	 * @return	array	the address plus its groups
	 */
	function getGroupsForAddress($address) {
		
		$res = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
			'tt_address_group.uid, tt_address_group.pid,  
			 tt_address_group.title, tt_address_group.sys_language_uid',			
			'tt_address',
			'tt_address_group_mm',
			'tt_address_group',
			'AND tt_address.uid = '.intval($address['uid']).' '.
				$this->cObj->enableFields('tt_address'),
			'',
			'tt_address_group_mm.sorting'
		);
		
		while($group = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			
			// localization handling
			if($GLOBALS['TSFE']->sys_language_content) {
				$group = $GLOBALS['TSFE']->sys_page->getRecordOverlay(
					'tt_address_group',
					$group,
					$GLOBALS['TSFE']->sys_language_content
				);
			}
			
			$address['groups'][] = $group;
			unset($group);
		}		
		
		return $address;
	}
	
	/**
	 * puts the fields of an address in markers
	 * 
	 * @param	array	$address: an address record
	 * @return	array	a marker array with filled markers acording to the
	 * address given
	 */
	function getItemMarkerArray($address) {
		$markerArray = array();
		
		//local configuration and local cObj
		$lConf = $this->conf['templates.'][$this->conf['templateName'].'.'];
		$lcObj = t3lib_div::makeInstance('tslib_cObj');
		$lcObj->data = $address;
		
		$markerArray['###UID###']         = $address['uid'];
				
		$markerArray['###NAME###']        = $lcObj->stdWrap($address['name'],               $lConf['name.']);
		$markerArray['###TITLE###']       = $lcObj->stdWrap($address['title'],              $lConf['title.']);
		$markerArray['###EMAIL###']       = $lcObj->stdWrap(
			$lcObj->getTypoLink($address['email'], $address['email']),
			$lConf['email.']
		);
		$markerArray['###PHONE###']       = $lcObj->stdWrap($address['phone'],              $lConf['phone.']);
		$markerArray['###MOBILE###']      = $lcObj->stdWrap($address['mobile'],             $lConf['mobile.']);
		$markerArray['###WWW###']         = $lcObj->stdWrap(
			$lcObj->getTypoLink($address['www'], $address['www']),
			$lConf['www.']
		);
		$markerArray['###ADDRESS###']     = $lcObj->stdWrap($address['address'],            $lConf['address.']);
		$markerArray['###COMPANY###']     = $lcObj->stdWrap($address['company'],            $lConf['company.']);
		$markerArray['###CITY###']        = $lcObj->stdWrap($address['city'],               $lConf['city.']);
		$markerArray['###ZIP###']         = $lcObj->stdWrap($address['zip'],                $lConf['zip.']);
		$markerArray['###COUNTRY###']     = $lcObj->stdWrap($address['country'],            $lConf['country.']);
		$markerArray['###FAX###']         = $lcObj->stdWrap($address['fax'],                $lConf['fax.']);
		$markerArray['###DESCRIPTION###'] = $lcObj->stdWrap($address['description'],        $lConf['description.']);
		$markerArray['###MAINGROUP###']   = $lcObj->stdWrap($address['groups'][0]['title'], $lConf['mainGroup.']);
	
		//the image
		$markerArray['###IMAGE###'] = '';
		if(!empty($address['image'])) {
			
			$iConf['image.'] = $lConf['image.'];
			$iConf['image.']['file']          = 'uploads/pics/'.$address['image'];
			$iConf['image.']['altText']       = $address['name'];
			$iConf['image.']['titleText']     = $address['name'];
			
			$image                      = $lcObj->IMAGE($iConf['image.']);
			$markerArray['###IMAGE###'] = $lcObj->stdWrap($image, $lConf['image.']);
		}			
	
		return $markerArray;
	}
	
	/**
	 * gets the filename fron the template file without the file extension
	 * 
	 * @return	string	the file name portion without the file extension
	 */
	function getTemplateName() {
		$templateName = '';
		$templateFile = $this->ffData['templateFile'];
		
		if($templateFile == 'default') {
			$templateFile = $this->conf['defaultTemplateFileName'];
		} 
		
		//cutting off the file extension
		$templateName = substr($templateFile, 0, strrpos($templateFile, '.'));
		
		return $templateName;
	}
	
	/**
	 * gets the html template code from the selected template, extracts the
	 * address subpart and returns the html with unreplaced marker
	 * 
	 * @return	string	html template code
	 */
	function getTemplate() {
		$templateFile = $this->ffData['templateFile'];
		
		if($templateFile == 'default') {
			$templateFile = $this->conf['defaultTemplateFileName'];
		}
		
		$templateCode = $this->cObj->fileResource(
			$this->conf['templatePath'].$templateFile
		);
		
		$subPart = $this->cObj->getSubpart(
			$templateCode, '###TEMPLATE_ADDRESS###'
		);
		
		return $subPart;
	}
	
	/*
	 * checks whether the given sorting criteria is a valid one. If it is valid
	 * the given criteria is returned as it was and the default 'name' is
	 * returned if the given criteria is not valid
	 * 
	 * @param	string	$sortBy: criteria you want to sort the addresses by
	 * @return	string	the given sorting criteria if it was valid, 'name' otherwise
	 */
	function checkSorting($sortBy) {
		$validSortings = array(
			'uid', 'pid', 'tstamp',
			'name', 'title', 'email', 'phone', 'mobile', 'www', 'address',
			'company', 'ciy', 'zip', 'country', 'image', 'fax', 'description'
		);
		
		if(!in_array($sortBy, $validSortings)) {
			$sortBy = 'name';
		}
		
		return $sortBy;
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tt_address/pi1/class.tx_ttaddress_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tt_address/pi1/class.tx_ttaddress_pi1.php']);
}

?>