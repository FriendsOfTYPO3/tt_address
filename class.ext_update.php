<?php
/***************************************************************
*  Copyright notice
*
*  (c)   2006 Ingo Renner (typo3@ingo-renner.com)
*  All   rights reserved
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


/**
 * class.ext_update.php
 * 
 * @author  Ingo Renner
 * @package TYPO3
 * @subpackage tt_address
 */
class ext_update {

	/**
	 * Main function, returning the HTML content of the module
	 *
	 * @return	string		HTML
	 */
	function main() {
		
		$onclick = 'document.forms[\'pageform\'].action = \''.t3lib_div::linkThisScript(array()).'\';document.forms[\'pageform\'].submit();return false;';
		$content = '';
		
		
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid, pid, name',
			'tt_address',
			'name != \'\' AND deleted = 0',
			'',
			'uid'
		);
		
		$hasAddressgroups = false;
		if(t3lib_extMgm::isLoaded('addressgroups')) {
			$hasAddressgroups = true;
			
			$groupRes = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'*',
				'tx_addressgroups_group',
				'title != \'\' AND deleted = 0'
			);
			
			$contentRes = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'uid, list_type',
				'tt_content',
				'list_type = \'addressgroups_pi1\' AND deleted = 0'
			);
		}
		
		if(!t3lib_div::_GP('do_update')){
				// init
			$count = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
			
			$content .= '<p>'.$count.' address records found.</p>';
			
			if($hasAddressgroups) {
				$groupCount = $GLOBALS['TYPO3_DB']->sql_num_rows($groupRes);
				$contentCount = $GLOBALS['TYPO3_DB']->sql_num_rows($contentRes);
				$content .= '<p>Additionally '.$groupCount.' groups from EXT:addresgroups and '.$contentCount.' plugin content elements were found.</p>';
			}
			
			
			$content .= '<br />';
			
			$content .= '<input type="hidden" name="do_update" value="1"/>';
			$content .= '<input type="button" value="UPDATE!" style="color: #fff; background-color: #f00;" onclick="'.$onclick.'" />';
		} else {
			$updateCount = 0;
			$groupUpdateCount = 0;
			$groupRelUpdateCount = 0;
			$contentUpdateCount = 0;
			
				// do the update
			if($hasAddressgroups) {
				while($groupRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($groupRes)) {
						// could easily be done with a INSERT INTO ... SELECT FROM ... but I don't know how this would work with DBAL
					$GLOBALS['TYPO3_DB']->exec_INSERTquery(
						'tt_address_group',
						$groupRow
					);
					
					$groupUpdateCount++;
				}
				
				$relRows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
					'*',
					'tt_address_tx_addressgroups_group_mm',
					'1 = 1'
				);
				foreach($relRows as $relRow) {
					$GLOBALS['TYPO3_DB']->exec_INSERTquery(
						'tt_address_group_mm',
						$relRow
					);
					
					$groupRelUpdateCount++;
				}
				
				$contentPlugins = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
					'uid, list_type',
					'tt_content',
					'list_type = \'addressgroups_pi1\' AND deleted = 0'
				);
				foreach($contentPlugins as $contentPlugin) {
					$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
						'tt_content',
						'uid = '.$contentPlugin['uid'],
						array('list_type' => 'tt_address_pi1')
					);
					$contentUpdateCount++;
				}
			}
			
			
			$content .= '<br /><br /><h2>Update Log</h2>'
					   .'Updates have been  made to address records which were '
					   .'split by either <em>comma</em> or <em>space</em>. '
					   .'<br />Please check the records marked red, they may '
					   .'contain errors! Records with field values <em>???</em> '
					   .'have not been updated, please edit them by hand!<br /><br />';
			$content .= '<table border="1">'.chr(10);
			$content .= '<tr><th>uid</th><th>pid</th><th>name</th><th>first name</th><th>last name</th><th>split by</th></tr>'.chr(10);
			
			while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$type = 'space';
				
				if(strpos($row['name'], ',') !== false) {
					list($lname, $fname) = explode(',', $row['name']);
					
					$firstName = trim($fname);
					$lastName  = trim($lname);
					$type = 'comma';
				} else if(strpos($row['name'], ' ') !== false) {
					$name = strrev($row['name']);
					$lastSpace = strpos($name, ' ');
					
					$firstName = strrev(trim(substr($name, $lastSpace)));
					$lastName  = strrev(trim(substr($name, 0, $lastSpace)));
				} else {
					$type      = '???';
					$firstName = '???';
					$lastName  = '???';
				}
				
				$updateFields = array(
					'first_name' => $firstName,
					'last_name'  => $lastName
				);
				
				if($type != '???') {
					$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
						'tt_address',
						'uid = '.$row['uid'],
						$updateFields
					);
					$updateCount++;
				}
				
				$content .= $this->makeTr($row, $firstName, $lastName, $type);
				
			}
			
			$content .=  '</table><br />'
						.'<strong>'.$updateCount.' address records updated.</strong>';
			if($hasAddressgroups) {
				$content .= '<br /><strong>'.$groupUpdateCount.' group records and '.$groupRelUpdateCount.' relations updated.';
				$content .= '<br />'.$contentUpdateCount.' address plugin content elements updated.</strong>';
			}
		}
		
		return $content;
	}

	/**
	 * Checks whether the update menu item should be displayed
	 *  (this function is called from the extension manager)
	 *
	 * @return	boolean
	 */
	function access() {
		
		return true;
	}
	
	/**
	 * prepares a row for output in the update result overview
	 * 
	 * @param array $row: the original row with uid, pid, name
	 * @param string $fname: the new first name
	 * @param string $lname: the new last name
	 * @param string $type: either space, comma or ???, determines by what the
	 * name was split or ??? if it wasn't split because it was unclear
	 * @return string a table row ready to print
	 */
	function makeTr($row, $fname, $lname, $type) {
		
		$red        = '';
		$spaceCount = substr_count(trim($row['name']), ' ');
		
		if($spaceCount != 1 || $type == '???') {
			$red = ' style="color: #f00;"';
		}
		
		$tr = '<tr>'
			 .'<td'.$red.'>'.$row['uid'].'</td>'
			 .'<td'.$red.'>'.$row['pid'].'</td>'
			 .'<td'.$red.'>'.$row['name'].'</td>'
			 .'<td'.$red.'>'.$fname.'</td>'
			 .'<td'.$red.'>'.$lname.'</td>'
			 .'<td'.$red.'>'.$type.'</td>'
			 .'</tr>'.chr(10);
		
		return $tr;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tt_address/class.ext_update.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tt_address/class.ext_update.php']);
}
?>
