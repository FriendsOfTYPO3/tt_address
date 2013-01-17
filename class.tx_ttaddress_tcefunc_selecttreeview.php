<?php
/***************************************************************
*  Copyright notice
*
*  (c)  2006 Ingo Renner <typo3@ingo-renner.com>
* 
*  All rights reserved
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


require_once(PATH_t3lib.'class.t3lib_treeview.php');
	
/**
 * extends t3lib_treeview to change function wrapTitle().
 * 
 * This function displays a selector with nested groups. The original code is
 * borrowed from the extension "Digital Asset Management" (tx_dam), 
 * author: René Fritz <r.fritz@colorcube.de>
 *
 * @author	René Fritz <r.fritz@colorcube.de>
 * @author	Ingo Renner <typo3@ingo-renner.com>
 * @package TYPO3
 * @subpackage tt_address
 */
class tx_ttaddress_tceFunc_selectTreeView extends t3lib_treeview {

	var $TCEforms_itemFormElName = '';
	var $TCEforms_nonSelectableItemsArray = array();

	/**
	 * wraps the record titles in the tree with links or not depending on if 
	 * they are in the TCEforms_nonSelectableItemsArray.
	 *
	 * @param	string		$title: the title
	 * @param	array		$v: an array with uid and title of the current item.
	 * @return	string		the wrapped title
	 */
	function wrapTitle($title, $v)	{
		if($v['uid']>0) {
			if (in_array($v['uid'], $this->TCEforms_nonSelectableItemsArray)) {
				return '<a href="#" title="'.$v['description'].'"><span style="color:#999;cursor:default;">'.$title.'</span></a>';
			} else {
				$hrefTitle = $v['description'];
				$aOnClick = 'setFormValueFromBrowseWin(\''.$this->TCEforms_itemFormElName.'\','.$v['uid'].',\''.$title.'\'); return false;';
				return '<a href="#" onclick="'.htmlspecialchars($aOnClick).'" title="'.htmlentities($v['description']).'">'.$title.'</a>';
			}
		} else {
			return $title;
		}
	}
	
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tt_address/class.tx_ttaddress_tcefunc_selecttreeview.php'])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tt_address/class.tx_ttaddress_tcefunc_selecttreeview.php']);
}
?>
