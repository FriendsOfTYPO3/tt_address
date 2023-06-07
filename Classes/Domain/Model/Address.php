<?php
declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\Domain\Model;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use FriendsOfTYPO3\TtAddress\Utility\PropertyModification;
use TYPO3\CMS\Extbase\Domain\Model\Category;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * The domain model of a Address
 */
class Address extends AbstractEntity
{
    /**
     * Hidden
     *
     * @var bool
     */
    protected $hidden = false;

    /**
     * @var string
     */
    protected $gender = '';

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $firstName = '';

    /**
     * @var string
     */
    protected $middleName = '';

    /**
     * @var string
     */
    protected $lastName = '';

    /**
     * @var \DateTime|null
     */
    protected $birthday;

    /**
     * @var string
     */
    protected $title = '';

    /** @var string */
    protected $titleSuffix = '';

    /**
     * @var string
     */
    protected $address = '';

    /**
     * @var float
     */
    protected $latitude = 0;

    /**
     * @var float
     */
    protected $longitude = 0;

    /**
     * @var string
     */
    protected $building = '';

    /**
     * @var string
     */
    protected $room = '';

    /**
     * @var string
     */
    protected $phone = '';

    /**
     * @var string
     */
    protected $fax = '';

    /**
     * @var string
     */
    protected $mobile = '';

    /**
     * @var string
     */
    protected $www = '';

    /**
     * @var string
     */
    protected $slug = '';

    /**
     * @var string
     */
    protected $skype = '';

    /**
     * @var string
     */
    protected $twitter = '';

    /**
     * @var string
     */
    protected $facebook = '';

    /**
     * @var string
     */
    protected $instagram = '';

    /**
     * @var string
     */
    protected $tiktok = '';

    /**
     * @var string
     */
    protected $linkedin = '';

    /**
     * @var string
     */
    protected $email = '';

    /**
     * @var string
     */
    protected $company = '';

    /**
     * @var string
     */
    protected $position = '';

    /**
     * @var string
     */
    protected $city = '';

    /**
     * @var string
     */
    protected $zip = '';

    /**
     * @var string
     */
    protected $region = '';

    /**
     * @var string
     */
    protected $country = '';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected $image;

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Category>
     */
    protected $categories;

    public function __construct()
    {
        $this->image = new ObjectStorage();
        $this->categories = new ObjectStorage();
    }

    public function setHidden($hidden): void
    {
        $this->hidden = $hidden;
    }

    public function getHidden(): bool
    {
        return $this->hidden;
    }

    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setMiddleName(string $middleName): void
    {
        $this->middleName = $middleName;
    }

    public function getMiddleName(): string
    {
        return $this->middleName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setBirthday(?\DateTime $birthday = null): void
    {
        $this->birthday = $birthday;
    }

    public function getBirthday(): ?\DateTime
    {
        return $this->birthday;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getTitleSuffix(): string
    {
        return $this->titleSuffix;
    }

    /**
     * @param string $titleSuffix
     */
    public function setTitleSuffix(string $titleSuffix): void
    {
        $this->titleSuffix = $titleSuffix;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setBuilding(string $building): void
    {
        $this->building = $building;
    }

    public function getBuilding(): string
    {
        return $this->building;
    }

    public function setRoom(string $room): void
    {
        $this->room = $room;
    }

    public function getRoom(): string
    {
        return $this->room;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getCleanedPhone(): string
    {
        return PropertyModification::getCleanedNumber($this->phone);
    }

    public function setFax(string $fax): void
    {
        $this->fax = $fax;
    }

    public function getFax(): string
    {
        return $this->fax;
    }

    public function getCleanedFax(): string
    {
        return PropertyModification::getCleanedNumber($this->fax);
    }

    public function setMobile(string $mobile): void
    {
        $this->mobile = $mobile;
    }

    public function getMobile(): string
    {
        return $this->mobile;
    }

    public function getCleanedMobile(): string
    {
        return PropertyModification::getCleanedNumber($this->mobile);
    }

    public function setWww(string $www): void
    {
        $this->www = $www;
    }

    public function getWww(): string
    {
        return $this->www;
    }

    public function getWwwSimplified(): string
    {
        return PropertyModification::getCleanedDomain($this->www);
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSkype(string $skype): void
    {
        $this->skype = $skype;
    }

    public function getSkype(): string
    {
        return $this->skype;
    }

    public function setTwitter(string $twitter): void
    {
        if ($twitter[0] !== '@') {
            throw new \InvalidArgumentException('twitter name must start with @', 1357530444);
        }

        $this->twitter = $twitter;
    }

    public function getTwitter(): string
    {
        return $this->twitter;
    }

    public function setFacebook(string $facebook): void
    {
        if ($facebook[0] !== '/') {
            throw new \InvalidArgumentException('Facebook name must start with /', 1357530471);
        }

        $this->facebook = $facebook;
    }

    public function getFacebook(): string
    {
        return $this->facebook;
    }

    public function setInstagram(string $instagram): void
    {
        $this->instagram = $instagram;
    }

    public function getInstagram(): string
    {
        return $this->instagram;
    }

    public function setTiktok(string $tiktok): void
    {
        $this->tiktok = $tiktok;
    }

    public function getTiktok(): string
    {
        return $this->tiktok;
    }

    public function setLinkedin(string $linkedin): void
    {
        $this->linkedin = $linkedin;
    }

    public function getLinkedin(): string
    {
        return $this->linkedin;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setCompany(string $company): void
    {
        $this->company = $company;
    }

    public function getCompany(): string
    {
        return $this->company;
    }

    public function setPosition(string $position): void
    {
        $this->position = $position;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setZip(string $zip): void
    {
        $this->zip = $zip;
    }

    public function getZip(): string
    {
        return $this->zip;
    }

    public function setRegion(string $region): void
    {
        $this->region = $region;
    }

    public function getRegion(): string
    {
        return $this->region;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function addImage(FileReference $image): void
    {
        $this->image->attach($image);
    }

    public function removeImage(FileReference $imageToRemove): void
    {
        $this->image->detach($imageToRemove);
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    public function getImage(): ?ObjectStorage
    {
        return $this->image;
    }

    public function getFirstImage(): ?FileReference
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
     * @param ObjectStorage<FileReference> $image
     */
    public function setImage(ObjectStorage $image): void
    {
        $this->image = $image;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return ObjectStorage<Category>
     */
    public function getCategories(): ObjectStorage
    {
        return $this->categories;
    }

    /**
     * @param ObjectStorage<Category> $categories
     */
    public function setCategories(ObjectStorage $categories): void
    {
        $this->categories = $categories;
    }

    public function getSysLanguageUid(): int
    {
        return $this->_languageUid;
    }

    /**
     * Get full name including title, first, middle and last name
     *
     * @return string
     */
    public function getFullName(): string
    {
        $list = [
            $this->getTitle(),
            $this->getFirstName(),
            $this->getMiddleName(),
            $this->getLastName(),
        ];

        $name = implode(' ', array_filter($list));
        if ($this->titleSuffix) {
            $name .= ', ' . $this->titleSuffix;
        }
        return $name;
    }

    /**
     * Gets the first category
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\Category|null the first category
     */
    public function getFirstCategory(): ?Category
    {
        $categories = $this->getCategories();
        if (!is_null($categories)) {
            $categories->rewind();
            return $categories->current();
        }

        return null;
    }

    public function getAddressInOneLine(): string
    {
        return implode(', ', array_filter([
            str_replace(chr(10), ',', $this->address),
            $this->city,
            $this->region,
            $this->country,
        ]));
    }
}
