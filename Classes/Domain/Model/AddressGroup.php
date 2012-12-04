<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 b:dreizehn, Germany <typo3@b13.de>
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

/**
 * The domain model of a Address Group
 *
 * @package Tx_TtAddress
 * @subpackage Domain\Model
 * @entity
 */
class Tx_TtAddress_Domain_Model_AddressGroup extends Tx_Extbase_DomainObject_AbstractEntity {
	

	/**
	 * Title
	 * @var string
	 */
	protected $title;

	/**
	 * Parent Group
	 * @var Tx_TtAddress_Domain_Model_AddressGroup
	 */
	protected $parentGroup;

	/**
	 * Description
	 * @var string
	 */
	protected $description;

	/**
	 * sets the title attribute
	 * 
	 * @param	string	 $title
	 * @return	void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * returns the title attribute
	 * 
	 * @return	string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * sets the parentGroup attribute
	 * 
	 * @param	Tx_TtAddress_Domain_Model_AddressGroup	 $parentGroup
	 * @return	void
	 */
	public function setParentGroup($parentGroup) {
		$this->parentGroup = $parentGroup;
	}

	/**
	 * returns the parentGroup attribute
	 * 
	 * @return	Tx_TtAddress_Domain_Model_AddressGroup
	 */
	public function getParentGroup() {
		return $this->parentGroup;
	}

	/**
	 * sets the description attribute
	 * 
	 * @param	string	 $description
	 * @return	void
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * returns the description attribute
	 * 
	 * @return	string
	 */
	public function getDescription() {
		return $this->description;
	}

}
