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
 * The domain model of a Address
 *
 * @package Tx_TtAddress
 * @subpackage Domain\Model
 * @entity
 */
class Tx_TtAddress_Domain_Model_Address extends Tx_Extbase_DomainObject_AbstractEntity {

	/**
	 * Gender
	 * @var string
	 */
	protected $gender;

	/**
	 * Name
	 * @var string
	 */
	protected $name;

	/**
	 * First Name
	 * @var string
	 */
	protected $firstName;

	/**
	 * Middle Name
	 * @var string
	 */
	protected $middleName;

	/**
	 * Last Name
	 * @var string
	 */
	protected $lastName;

	/**
	 * Birthday
	 * @var DateTime
	 */
	protected $birthday;

	/**
	 * Title
	 * @var string
	 */
	protected $title;

	/**
	 * Address
	 * @var string
	 */
	protected $address;

	/**
	 * Building
	 * @var string
	 */
	protected $building;

	/**
	 * Room
	 * @var string
	 */
	protected $room;

	/**
	 * Phone
	 * @var string
	 */
	protected $phone;

	/**
	 * Fax
	 * @var string
	 */
	protected $fax;

	/**
	 * Mobile
	 * @var string
	 */
	protected $mobile;

	/**
	 * www
	 * @var string
	 */
	protected $www;

	/**
	 * Skype
	 * @var string
	 */
	protected $skype;

	/**
	 * twitter
	 * @var string
	 */
	protected $twitter;

	/**
	 * Facebook
	 * @var string
	 */
	protected $facebook;

	/**
	 * LinkedIn
	 * @var string
	 */
	protected $linkedIn;

	/**
	 * Email
	 * @var string
	 */
	protected $email;

	/**
	 * Organization
	 * @var string
	 */
	protected $company;

	/**
	 * Position
	 * @var string
	 */
	protected $position;

	/**
	 * City
	 * @var string
	 */
	protected $city;

	/**
	 * Zipcode
	 * @var string
	 */
	protected $zip;

	/**
	 * Region/State
	 * @var string
	 */
	protected $region;

	/**
	 * Country
	 * @var string
	 */
	protected $country;

	/**
	 * Image
	 * @var string
	 */
	protected $image;

	/**
	 * Description
	 * @var string
	 */
	protected $description;

	/**
	 * Address Group
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_TtAddress_Domain_Model_AddressGroup>
	 */
	protected $addressgroup;


	public function __construct() {
		$this->addressgroup = new Tx_Extbase_Persistence_ObjectStorage();
	}

	/**
	 * sets the gender attribute
	 *
	 * @param	string	 $gender
	 * @return	void
	 */
	public function setGender($gender) {
		$this->gender = $gender;
	}

	/**
	 * returns the gender attribute
	 *
	 * @return	string
	 */
	public function getGender() {
		return $this->gender;
	}

	/**
	 * sets the name attribute
	 *
	 * @param	string	 $name
	 * @return	void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * returns the name attribute
	 *
	 * @return	string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * sets the firstName attribute
	 *
	 * @param	string	 $firstName
	 * @return	void
	 */
	public function setFirstName($firstName) {
		$this->firstName = $firstName;
	}

	/**
	 * returns the firstName attribute
	 *
	 * @return	string
	 */
	public function getFirstName() {
		return $this->firstName;
	}

	/**
	 * sets the middleName attribute
	 *
	 * @param	string	 $middleName
	 * @return	void
	 */
	public function setMiddleName($middleName) {
		$this->middleName = $middleName;
	}

	/**
	 * returns the middleName attribute
	 *
	 * @return	string
	 */
	public function getMiddleName() {
		return $this->middleName;
	}

	/**
	 * sets the lastName attribute
	 *
	 * @param	string	 $lastName
	 * @return	void
	 */
	public function setLastName($lastName) {
		$this->lastName = $lastName;
	}

	/**
	 * returns the lastName attribute
	 *
	 * @return	string
	 */
	public function getLastName() {
		return $this->lastName;
	}

	/**
	 * sets the birthday attribute
	 *
	 * @param	DateTime	 $birthday
	 * @return	void
	 */
	public function setBirthday($birthday) {
		$this->birthday = $birthday;
	}

	/**
	 * returns the birthday attribute
	 *
	 * @return	DateTime
	 */
	public function getBirthday() {
		return $this->birthday;
	}

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
	 * sets the address attribute
	 *
	 * @param	string	 $address
	 * @return	void
	 */
	public function setAddress($address) {
		$this->address = $address;
	}

	/**
	 * returns the address attribute
	 *
	 * @return	string
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * sets the building attribute
	 *
	 * @param	string	 $building
	 * @return	void
	 */
	public function setBuilding($building) {
		$this->building = $building;
	}

	/**
	 * returns the building attribute
	 *
	 * @return	string
	 */
	public function getBuilding() {
		return $this->building;
	}

	/**
	 * sets the room attribute
	 *
	 * @param	string	 $room
	 * @return	void
	 */
	public function setRoom($room) {
		$this->room = $room;
	}

	/**
	 * returns the room attribute
	 *
	 * @return	string
	 */
	public function getRoom() {
		return $this->room;
	}

	/**
	 * sets the phone attribute
	 *
	 * @param	string	 $phone
	 * @return	void
	 */
	public function setPhone($phone) {
		$this->phone = $phone;
	}

	/**
	 * returns the phone attribute
	 *
	 * @return	string
	 */
	public function getPhone() {
		return $this->phone;
	}

	/**
	 * sets the fax attribute
	 *
	 * @param	string	 $fax
	 * @return	void
	 */
	public function setFax($fax) {
		$this->fax = $fax;
	}

	/**
	 * returns the fax attribute
	 *
	 * @return	string
	 */
	public function getFax() {
		return $this->fax;
	}

	/**
	 * sets the mobile attribute
	 *
	 * @param	string	 $mobile
	 * @return	void
	 */
	public function setMobile($mobile) {
		$this->mobile = $mobile;
	}

	/**
	 * returns the mobile attribute
	 *
	 * @return	string
	 */
	public function getMobile() {
		return $this->mobile;
	}

	/**
	 * sets the www attribute
	 *
	 * @param	string	 $www
	 * @return	void
	 */
	public function setWww($www) {
		$this->www = $www;
	}

	/**
	 * returns the www attribute
	 *
	 * @return	string
	 */
	public function getWww() {
		return $this->www;
	}

	/**
	 * sets the Skype attribute
	 *
	 * @param	string	 $skype
	 * @return	void
	 */
	public function setSkype($skype) {
		$this->skype = $skype;
	}

	/**
	 * returns the Skype attribute
	 *
	 * @return	string
	 */
	public function getSkype() {
		return $this->skype;
	}

	/**
	 * sets the twitter attribute
	 *
	 * @param	string	 $twitter
	 * @return	void
	 */
	public function setTwitter($twitter) {
		if (substr($twitter, 0, 1) != '@') {
			throw new \InvalidArgumentException('twitter name must start with @', 1357530444);
		}

		$this->twitter = $twitter;
	}

	/**
	 * returns the twitter attribute
	 *
	 * @return	string
	 */
	public function getTwitter() {
		return $this->twitter;
	}

	/**
	 * sets the Facebook attribute
	 *
	 * @param	string	 $facebook
	 * @return	void
	 */
	public function setFacebook($facebook) {
		if (substr($twitter, 0, 1) != '/') {
			throw new \InvalidArgumentException('Facebook name must start with /', 1357530471);
		}

		$this->facebook = $facebook;
	}

	/**
	 * returns the Facebook attribute
	 *
	 * @return	string
	 */
	public function getFacebook() {
		return $this->facebook;
	}

	/**
	 * sets the LinkedIn attribute
	 *
	 * @param	string	 $linkedIn
	 * @return	void
	 */
	public function setLinkedIn($linkedIn) {
		$this->linkedIn = $linkedIn;
	}

	/**
	 * returns the LinkedIn attribute
	 *
	 * @return	string
	 */
	public function getLinkedIn() {
		return $this->linkedIn;
	}

	/**
	 * sets the email attribute
	 *
	 * @param	string	 $email
	 * @return	void
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * returns the email attribute
	 *
	 * @return	string
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * sets the company attribute
	 *
	 * @param	string	 $company
	 * @return	void
	 */
	public function setCompany($company) {
		$this->company = $company;
	}

	/**
	 * returns the company attribute
	 *
	 * @return	string
	 */
	public function getCompany() {
		return $this->company;
	}

	/**
	 * sets the position attribute
	 *
	 * @param	string	 $position
	 * @return	void
	 */
	public function setPosition($position) {
		$this->position = $position;
	}

	/**
	 * returns the position attribute
	 *
	 * @return	string
	 */
	public function getPosition() {
		return $this->position;
	}

	/**
	 * sets the city attribute
	 *
	 * @param	string	 $city
	 * @return	void
	 */
	public function setCity($city) {
		$this->city = $city;
	}

	/**
	 * returns the city attribute
	 *
	 * @return	string
	 */
	public function getCity() {
		return $this->city;
	}

	/**
	 * sets the zip attribute
	 *
	 * @param	string	 $zip
	 * @return	void
	 */
	public function setZip($zip) {
		$this->zip = $zip;
	}

	/**
	 * returns the zip attribute
	 *
	 * @return	string
	 */
	public function getZip() {
		return $this->zip;
	}

	/**
	 * sets the region attribute
	 *
	 * @param	string	 $region
	 * @return	void
	 */
	public function setRegion($region) {
		$this->region = $region;
	}

	/**
	 * returns the region attribute
	 *
	 * @return	string
	 */
	public function getRegion() {
		return $this->region;
	}

	/**
	 * sets the country attribute
	 *
	 * @param	string	 $country
	 * @return	void
	 */
	public function setCountry($country) {
		$this->country = $country;
	}

	/**
	 * returns the country attribute
	 *
	 * @return	string
	 */
	public function getCountry() {
		return $this->country;
	}

	/**
	 * sets the image attribute
	 *
	 * @param	string	 $image
	 * @return	void
	 */
	public function setImage($image) {
		$this->image = $image;
	}

	/**
	 * returns the image attribute
	 *
	 * @return	string
	 */
	public function getImage() {
		return $this->image;
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

	/**
	 * sets the addressgroup attribute
	 *
	 * @param	Tx_Extbase_Persistence_ObjectStorage<Tx_TtAddress_Domain_Model_AddressGroup>	 $addressgroup
	 * @return	void
	 */
	public function setAddressgroup($addressgroup) {
		$this->addressgroup = $addressgroup;
	}

	/**
	 * returns the addressgroup attribute
	 *
	 * @return	Tx_Extbase_Persistence_ObjectStorage<Tx_TtAddress_Domain_Model_AddressGroup>
	 */
	public function getAddressgroup() {
		return $this->addressgroup;
	}

}

?>