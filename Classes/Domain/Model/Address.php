<?php

namespace FriendsOfTYPO3\TtAddress\Domain\Model;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Extbase\Domain\Model\Category;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * The domain model of a Address
 *
 * @entity
 */
class Address extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
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
     * @var \DateTime
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
     * Latitude
     * @var string
     */
    protected $latitude;

    /**
     * Longitude
     * @var string
     */
    protected $longitude;

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
    protected $linkedin;

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
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected $image = null;

    /**
     * Description
     * @var string
     */
    protected $description;

    /**
     * Categories
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category>
     */
    protected $categories;

    /**
     * __construct
     */
    public function __construct()
    {
        $this->image = new ObjectStorage();
    }

    /**
     * sets the gender attribute
     *
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * returns the gender attribute
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * sets the name attribute
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * returns the name attribute
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * sets the firstName attribute
     *
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * returns the firstName attribute
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * sets the middleName attribute
     *
     * @param string $middleName
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;
    }

    /**
     * returns the middleName attribute
     *
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * sets the lastName attribute
     *
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * returns the lastName attribute
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * sets the birthday attribute
     *
     * @param \DateTime $birthday
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * returns the birthday attribute
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * sets the title attribute
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * returns the title attribute
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * sets the address attribute
     *
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * returns the address attribute
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * sets the latitude attribute
     *
     * @param string $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * returns the latitude attribute
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * sets the longitude attribute
     *
     * @param string $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * returns the longitude attribute
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * sets the building attribute
     *
     * @param string $building
     */
    public function setBuilding($building)
    {
        $this->building = $building;
    }

    /**
     * returns the building attribute
     *
     * @return string
     */
    public function getBuilding()
    {
        return $this->building;
    }

    /**
     * sets the room attribute
     *
     * @param string $room
     */
    public function setRoom($room)
    {
        $this->room = $room;
    }

    /**
     * returns the room attribute
     *
     * @return string
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * sets the phone attribute
     *
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * returns the phone attribute
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * sets the fax attribute
     *
     * @param string $fax
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
    }

    /**
     * returns the fax attribute
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * sets the mobile attribute
     *
     * @param string $mobile
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    /**
     * returns the mobile attribute
     *
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * sets the www attribute
     *
     * @param string $www
     */
    public function setWww($www)
    {
        $this->www = $www;
    }

    /**
     * returns the www attribute
     *
     * @return string
     */
    public function getWww()
    {
        return $this->www;
    }

    public function getWwwSimplified()
    {
        $www = trim($this->www);
        if (!$www) {
            return '';
        }
        $parts = str_replace(['\\\\', '\\"'], ['\\', '"'], str_getcsv($www, ' '));
        return $parts[0];
    }

    /**
     * sets the Skype attribute
     *
     * @param string $skype
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;
    }

    /**
     * returns the Skype attribute
     *
     * @return string
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * sets the twitter attribute
     *
     * @param string $twitter
     */
    public function setTwitter($twitter)
    {
        if ($twitter[0] !== '@') {
            throw new \InvalidArgumentException('twitter name must start with @', 1357530444);
        }

        $this->twitter = $twitter;
    }

    /**
     * returns the twitter attribute
     *
     * @return string
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * sets the Facebook attribute
     *
     * @param string $facebook
     */
    public function setFacebook($facebook)
    {
        if ($facebook[0] !== '/') {
            throw new \InvalidArgumentException('Facebook name must start with /', 1357530471);
        }

        $this->facebook = $facebook;
    }

    /**
     * returns the Facebook attribute
     *
     * @return string
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * sets the LinkedIn attribute
     *
     * @param string $linkedin
     */
    public function setLinkedin($linkedin)
    {
        $this->linkedin = $linkedin;
    }

    /**
     * returns the LinkedIn attribute
     *
     * @return string
     */
    public function getLinkedin()
    {
        return $this->linkedin;
    }

    /**
     * sets the email attribute
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * returns the email attribute
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * sets the company attribute
     *
     * @param string $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * returns the company attribute
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * sets the position attribute
     *
     * @param string $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * returns the position attribute
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * sets the city attribute
     *
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * returns the city attribute
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * sets the zip attribute
     *
     * @param string $zip
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    /**
     * returns the zip attribute
     *
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * sets the region attribute
     *
     * @param string $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * returns the region attribute
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * sets the country attribute
     *
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * returns the country attribute
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Adds a FileReference
     *
     * @param FileReference $image
     */
    public function addImage(FileReference $image)
    {
        $this->image->attach($image);
    }

    /**
     * Removes a FileReference
     *
     * @param FileReference $imageToRemove The FileReference to be removed
     */
    public function removeImage(FileReference $imageToRemove)
    {
        $this->image->detach($imageToRemove);
    }

    /**
     * Returns the images
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Get first image
     *
     * @return FileReference|null
     */
    public function getFirstImage()
    {
        $images = $this->getImage();
        if ($images) {
            foreach ($images as $image) {
                return $image;
            }
        }

        return null;
    }

    /**
     * Sets the images
     *
     * @param ObjectStorage<FileReference> $image
     */
    public function setImage(ObjectStorage $image)
    {
        $this->image = $image;
    }

    /**
     * sets the description attribute
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * returns the description attribute
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * returns the categories
     *
     * @return ObjectStorage<Category> $categories
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * sets the categories
     *
     * @param ObjectStorage<Category> $categories
     */
    public function setCategories(ObjectStorage $categories)
    {
        $this->categories = $categories;
    }
}
