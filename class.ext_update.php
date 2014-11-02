<?php
/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * class.ext_update.php
 *
 * @author  Ingo Renner
 */
class ext_update {

	/**
	 * Main function, returning the HTML content of the module
	 *
	 * @return string HTML
	 */
	public function main() {
		$onclick = 'document.forms[\'pageform\'].action = \'' . GeneralUtility::linkThisScript(array()) . '\';document.forms[\'pageform\'].submit();return false;';
		$content = '';

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid, pid, name',
			'tt_address',
			'name != \'\' AND deleted = 0',
			'',
			'uid'
		);

		$hasAddressgroups = FALSE;
		if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('addressgroups')) {
			$hasAddressgroups = TRUE;

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

		if (!GeneralUtility::_GP('do_update')) {
			// init
			$count = $GLOBALS['TYPO3_DB']->sql_num_rows($res);

			$content .= '<p>' . $count . ' address records found.</p>';

			if ($hasAddressgroups) {
				$groupCount = $GLOBALS['TYPO3_DB']->sql_num_rows($groupRes);
				$contentCount = $GLOBALS['TYPO3_DB']->sql_num_rows($contentRes);
				$content .= '<p>Additionally ' . $groupCount . ' groups from EXT:addresgroups and ' . $contentCount . ' plugin content elements were found.</p>';
			}

			$content .= '<br />';

			$content .= '<input type="hidden" name="do_update" value="1"/>';
			$content .= '<input type="button" value="UPDATE!" style="color: #fff; background-color: #f00;" onclick="' . $onclick . '" />';
		} else {
			$updateCount = 0;
			$groupUpdateCount = 0;
			$groupRelUpdateCount = 0;
			$contentUpdateCount = 0;

				// do the update
			if ($hasAddressgroups) {
				while ($groupRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($groupRes)) {
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
				foreach ($relRows as $relRow) {
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
						'uid = ' . $contentPlugin['uid'],
						array('list_type' => 'tt_address_pi1')
					);
					$contentUpdateCount++;
				}
			}

			if ($hasAddressgroups) {
				$content .= '<br /><strong>' . $groupUpdateCount . ' group records and ' . $groupRelUpdateCount . ' relations updated.';
				$content .= '<br />' . $contentUpdateCount . ' address plugin content elements updated.</strong>';
			}
		}

		return $content;
	}

	/**
	 * Checks whether the update menu item should be displayed
	 *  (this function is called from the extension manager)
	 *
	 * @return boolean
	 */
	public function access() {
		return TRUE;
	}

}