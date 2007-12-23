<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2006 - 2007 Ingo Renner <typo3@ingo-renner.com>
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

			// sorting the addresses
		$sortBy = array();
		foreach($addresses as $k => $v) {
			$sortBy[$k] = $v[$this->conf['sortByColumn']];
		}
		array_multisort($sortBy, $this->conf['sortOrder'], $addresses);

			// limit output to max listMaxItems addresses
		if( ((int) $this->conf['listMaxItems']) > 0) {
			$addresses = array_slice($addresses, 0, int($this->conf['listMaxItems']));
		}

			// output
		foreach($addresses as $address) {
			if(!empty($address)) {
				$markerArray  = $this->getItemMarkerArray($address);
				$subpartArray = $this->getSubpartArray($templateCode, $markerArray, $address);

				$addressContent = $this->cObj->substituteMarkerArrayCached(
					$templateCode,
					$markerArray,
					$subpartArray
				);

				$wrap = $this->conf['templates.'][$this->conf['templateName'].'.']['wrap'];
				$content .= $this->cObj->wrap($addressContent, $wrap);

				$content .= chr(10).chr(10);
			}
		}

		$allWrap = $this->conf['wrap'];
		$content = $this->cObj->wrap($content, $allWrap);

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

			// flexform data
		$flexKeyMapping = array(
			'sDEF.singleRecords'    => 'singleRecords',
			'sDEF.groupSelection'   => 'groupSelection',
			'sDEF.combination'      => 'combination',
			'sDEF.sortBy'           => 'sortBy',
			'sDEF.sortOrder'        => 'sortOrder',
			'sDEF.pages'            => 'pages',
			'sDEF.recursive'        => 'recursive',
			'sDEF.pages'            => 'pages',
			'sDEF.recursive'        => 'recursive',
			'sDISPLAY.templateFile' => 'templateFile',
		);
		$this->ffData = $this->getFlexFormConfig($flexKeyMapping);

			//set default combination to AND if no combination set
		$combination = 'AND';
		if(!empty($this->ffData['combination']) || !empty($this->conf['combination'])) {
				// 0 and '0' are considered empty, therefore anything else means 1/true => OR
			$combination = 'OR';
		}
		$this->conf['combination'] = $combination;

			//set default sorting to name
		$this->conf['sortByColumn'] = $this->ffData['sortBy'] ?
			$this->ffData['sortBy'] :
				$this->conf['sortByColumn'] ?
					$this->conf['sortByColumn'] :
					'name';
			//check a userdefined sorting criteria for validity
		$this->conf['sortByColumn'] = $this->checkSorting($this->conf['sortByColumn']);

			//set sorting, set to ASC if not valid
		$sortOrder = $this->ffData['sortOrder'] ?
			$this->ffData['sortOrder'] :
			$this->conf['sortOrder'];
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

		$this->conf['singleSelection'] = $this->ffData['singleRecords'] ?
			$this->ffData['singleRecords'] :
			$this->cObj->stdWrap($this->conf['singleSelection'], $this->conf['singleSelection.']);

		$this->conf['groupSelection'] = $this->ffData['groupSelection'] ?
			$this->ffData['groupSelection'] :
			$this->conf['groupSelection'];

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
			$this->conf['singleSelection']
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
		$groups    = t3lib_div::intExplode(',',$this->conf['groupSelection']);
		$count     = count($groups);
		$groupList = implode(',', $groups);

		if(!empty($groupList) && !empty($this->conf['pidList'])) {
			if($this->conf['combination'] == 'AND') {
					// AND
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
			} elseif($this->conf['combination'] == 'OR') {
					// OR
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
		$groupTitles = array();

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
			$groupTitles[]       = $group['title'];
			unset($group);
		}

		$groupList = implode(', ', $groupTitles);
		$address['groupList'] = $groupList;
		unset($groupTitles);

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

		$markerArray['###UID###']          = $address['uid'];

		$markerArray['###GENDER###']       = $lcObj->stdWrap($address['gender'],             $lConf['gender.']);
		$markerArray['###FIRSTNAME###']    = $lcObj->stdWrap($address['first_name'],         $lConf['first_name.']);
		$markerArray['###MIDDLENAME###']   = $lcObj->stdWrap($address['middle_name'],        $lConf['middle_name.']);
		$markerArray['###LASTNAME###']     = $lcObj->stdWrap($address['last_name'],          $lConf['last_name.']);
		$markerArray['###TITLE###']        = $lcObj->stdWrap($address['title'],              $lConf['title.']);
		$markerArray['###EMAIL###']        = $lcObj->stdWrap($address['email'],              $lConf['email.']);
		$markerArray['###PHONE###']        = $lcObj->stdWrap($address['phone'],              $lConf['phone.']);
		$markerArray['###MOBILE###']       = $lcObj->stdWrap($address['mobile'],             $lConf['mobile.']);
		$markerArray['###WWW###']          = $lcObj->stdWrap($address['www'],                $lConf['www.']);
		$markerArray['###ADDRESS###']      = $lcObj->stdWrap($address['address'],            $lConf['address.']);
		$markerArray['###BUILDING###']     = $lcObj->stdWrap($address['building'],           $lConf['building.']);
		$markerArray['###ROOM###']         = $lcObj->stdWrap($address['room'],               $lConf['room.']);
		$markerArray['###BIRTHDAY###']     = $lcObj->stdWrap($address['birthday'],           $lConf['birthday.']);
		$markerArray['###ORGANIZATION###'] = $lcObj->stdWrap($address['company'],            $lConf['organization.']);
		$markerArray['###COMPANY###']      = $markerArray['###ORGANIZATION###']; // alias
		$markerArray['###CITY###']         = $lcObj->stdWrap($address['city'],               $lConf['city.']);
		$markerArray['###ZIP###']          = $lcObj->stdWrap($address['zip'],                $lConf['zip.']);
		$markerArray['###REGION###']       = $lcObj->stdWrap($address['region'],             $lConf['region.']);
		$markerArray['###COUNTRY###']      = $lcObj->stdWrap($address['country'],            $lConf['country.']);
		$markerArray['###FAX###']          = $lcObj->stdWrap($address['fax'],                $lConf['fax.']);
		$markerArray['###DESCRIPTION###']  = $lcObj->stdWrap($address['description'],        $lConf['description.']);
		$markerArray['###MAINGROUP###']    = $lcObj->stdWrap($address['groups'][0]['title'], $lConf['mainGroup.']);
		$markerArray['###GROUPLIST###']    = $lcObj->stdWrap($address['groupList'], 			$lConf['groupList.']);

			//the image
		$markerArray['###IMAGE###'] = '';
		if(!empty($address['image'])) {
			$iConf = $lConf['image.'];
			$images = explode(',', $address['image']);

				// somehow (at least on my dev machine) the TT_ADDRESS_MAX_IMAGES constant doesn't work here
			for($i = 0; $i < 6; $i++) {
				$iConf['file'] = 'uploads/pics/'.$images[$i];

				$iConf['altText'] = !empty($iConf['altText']) ?
					$iConf['altText'] :
					$address['name'];
				$iConf['titleText'] = !empty($iConf['titleText']) ?
					$iConf['titleText'] :
					$address['name'];

					// ensuring compatibility with the ###IMAGE### marker
				$markerArray['###IMAGE'.($i == 0 ? '' : $i).'###'] = $lcObj->IMAGE($iConf);
			}

		}

			// adds hook for processing of extra item markers
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['tt_address']['extraItemMarkerHook'])) {
			foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['tt_address']['extraItemMarkerHook'] as $_classRef) {
				$_procObj = & t3lib_div::getUserObj($_classRef);
				$markerArray = $_procObj->extraItemMarkerProcessor($markerArray, $address, $lConf, $this);
			}
		}

		return $markerArray;
	}

	/**
	 * gets the user defined subparts and returns their content as an array
	 *
	 * @param	string	$templateCode: (HTML) template code
	 * @param	array	$markerArray: markers with content
	 * @param	array	$address: a tt_address record
	 * @return	array	array of subparts
	 */
	function getSubpartArray($templateCode, $markerArray, $address) {
		$subpartArray = array();

		if(is_array($this->conf['templates.'][$this->conf['templateName'].'.']['subparts.'])) {
			$lcObj = t3lib_div::makeInstance('tslib_cObj'); // local cObj
			$lcObj->data = $address;

			foreach($this->conf['templates.'][$this->conf['templateName'].'.']['subparts.'] as $spName => $spConf) {
				$spName = '###SUBPART_'.strtoupper(substr($spName, 0, -1)).'###';

				$spTemplate = $lcObj->getSubpart($templateCode, $spName);
				$content    = $lcObj->stdWrap(
					$lcObj->substituteMarkerArrayCached(
						$spTemplate,
						$markerArray
					),
					$spConf
				);

				if($spConf['hasOneOf'] && !$this->hasOneOf($spConf['hasOneOf'], $address)) {
					$content = '';
				}

				$subpartArray[$spName] = $content;
			}
		}

		return $subpartArray;
	}

	/**
	 * gets the filename from the template file without the file extension
	 *
	 * @return	string	the file name portion without the file extension
	 */
	function getTemplateName() {
		$templateName = '';
		$templateFile = $this->ffData['templateFile'];

		if($templateFile == $this->conf['defaultTemplateFileName'] ||
		   $templateFile == 'default') {
			$templateName = 'default';
		}

			//cutting off the file extension
		if($templateName != 'default') {
			$templateName = substr($templateFile, 0, strrpos($templateFile, '.'));
		}

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

	/**
	 * checks whether the given sorting criteria is a valid one. If it is valid
	 * the given criteria is returned as it was, the default 'name' is
	 * returned if the given criteria is not valid
	 *
	 * @param	string	$sortBy: criteria you want to sort the addresses by
	 * @return	string	the given sorting criteria if it was valid, 'name' otherwise
	 */
	function checkSorting($sortBy) {
			// TODO add all fields from TCA (extract them from TCA) or add a method to add new sorting fields
		$validSortings = array(
			'uid', 'pid', 'tstamp',
			'name', 'gender', 'first_name', 'middle_name', 'last_name', 'title', 'email',
			'phone', 'mobile', 'www', 'address', 'building', 'room', 'birthday', 'company', 'city', 'zip',
			'region', 'country', 'image', 'fax', 'description'
		);

		if(!in_array($sortBy, $validSortings)) {
			$sortBy = 'name';
		}

		return $sortBy;
	}

	/**
	 * gets the flexform values as an array like defined by $flexKeyMapping
	 *
	 * @param	array	$flexKeyMapping: mapping of sheet.flexformFieldName => variable name
	 * @return	array	flexform configuration as an array
	 */
	function getFlexFormConfig($flexKeyMapping) {
		$conf = array();
		foreach($flexKeyMapping as $sheetField => $confName) {
			list($sheet, $field) = explode('.', $sheetField);
			$conf[$confName] = $this->pi_getFFvalue(
				$this->cObj->data['pi_flexform'],
				$field,
				$sheet
			);
		}
		return $conf;
	}

	/**
	 * checks for the 'hasOneOf' constraint, at least one of the fields in
	 * $fieldList must not be empty to return true
	 *
	 * @param	string	$fieldList: comma separated list of field names to check
	 * @param	array	$address: a tt_address record
	 * @return	boolean	true if at least one of the given fields is not empty
	 */
	function hasOneOf($fieldList, $address) {
		$checkFields = t3lib_div::trimExplode(',', $fieldList, 1);
		$flag = false;

		foreach($checkFields as $fieldName) {
			if(!empty($address[$fieldName])) {
				$flag = true;
				break;
			}
		}

		return $flag;
	}

}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tt_address/pi1/class.tx_ttaddress_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tt_address/pi1/class.tx_ttaddress_pi1.php']);
}

?>