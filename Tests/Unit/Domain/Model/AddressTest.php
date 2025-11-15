<?php

declare(strict_types=1);

namespace FriendsOfTypo3\TtAddress\Tests\Unit\Domain\Model;

/*
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use FriendsOfTYPO3\TtAddress\Domain\Model\Address;
use TYPO3\CMS\Extbase\Domain\Model\Category;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\TestingFramework\Core\BaseTestCase;

class AddressTest extends BaseTestCase
{
    /** @var Address */
    protected $subject;

    public function setup(): void
    {
        $this->subject = new Address();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function genderCanBeSet()
    {
        $value = 'm';
        $this->subject->setGender($value);
        self::assertEquals($value, $this->subject->getGender());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function nameCanBeSet()
    {
        $value = 'Max Mustermann';
        $this->subject->setName($value);
        self::assertEquals($value, $this->subject->getName());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function firstNameCanBeSet()
    {
        $value = 'Max';
        $this->subject->setFirstName($value);
        self::assertEquals($value, $this->subject->getFirstName());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function middleNameCanBeSet()
    {
        $value = 'J.';
        $this->subject->setMiddleName($value);
        self::assertEquals($value, $this->subject->getMiddleName());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function lastNameCanBeSet()
    {
        $value = 'Mustermann';
        $this->subject->setLastName($value);
        self::assertEquals($value, $this->subject->getLastName());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function birthdayCanBeSet()
    {
        $value = new \DateTime();
        $this->subject->setBirthday($value);
        self::assertEquals($value, $this->subject->getBirthday());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function titleCanBeSet()
    {
        $value = 'dr.';
        $this->subject->setTitle($value);
        self::assertEquals($value, $this->subject->getTitle());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function addressCanBeSet()
    {
        $value = 'Dummystreet 134';
        $this->subject->setAddress($value);
        self::assertEquals($value, $this->subject->getAddress());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function latitudeCanBeSet()
    {
        $value = 123.121221;
        $this->subject->setLatitude($value);
        self::assertEquals($value, $this->subject->getLatitude());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function longitudeCanBeSet()
    {
        $value = 10.1291;
        $this->subject->setLongitude($value);
        self::assertEquals($value, $this->subject->getLongitude());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function buildingCanBeSet()
    {
        $value = 'building 1';
        $this->subject->setBuilding($value);
        self::assertEquals($value, $this->subject->getBuilding());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function roomCanBeSet()
    {
        $value = 'room 1';
        $this->subject->setRoom($value);
        self::assertEquals($value, $this->subject->getRoom());
    }

    public static function telephoneFormatDataProvider()
    {
        return [
            'phone number' => ['0122333', '0122333'],
            'phone number with spaces' => [' 0 122 333 ', '0122333'],
            'phone number with dashes' => ['0122-333-4444', '01223334444'],
            'phone number with slash' => ['0122/333', '0122333'],
            'phone number with invalid chars' => ['!0"1§2$2%/&3/3(3)', '0122333'],
            'phone number with allowed special chars' => ['#06*', '#06*'],
            'phone number with brackets in front' => ['(0)22333', '022333'],
            'phone number with brackets in middle' => ['+49(0)22333', '+4922333'],
            'phone number with number in brackets' => ['+49 (122) 333', '+49122333'],
            'phone number with letters' => ['tel: +49 122 333', '+49122333'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function phoneCanBeSet()
    {
        $value = '+43129';
        $this->subject->setPhone($value);
        self::assertEquals($value, $this->subject->getPhone());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\DataProvider('telephoneFormatDataProvider')]
    public function phoneWithCleanedChars($number, $expectedNumber)
    {
        $this->subject->setPhone($number);

        self::assertSame(
            $expectedNumber,
            $this->subject->getCleanedPhone()
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function faxCanBeSet()
    {
        $value = '+431294';
        $this->subject->setFax($value);
        self::assertEquals($value, $this->subject->getFax());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\DataProvider('telephoneFormatDataProvider')]
    public function faxWithCleanedChars($number, $expectedNumber)
    {
        $this->subject->setFax($number);

        self::assertSame(
            $expectedNumber,
            $this->subject->getCleanedFax()
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function mobileCanBeSet()
    {
        $value = '+431294111';
        $this->subject->setMobile($value);
        self::assertEquals($value, $this->subject->getMobile());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\DataProvider('telephoneFormatDataProvider')]
    public function mobileWithCleanedChars($number, $expectedNumber)
    {
        $this->subject->setMobile($number);

        self::assertSame(
            $expectedNumber,
            $this->subject->getCleanedMobile()
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function wwwCanBeSet()
    {
        $value = 'www.typo3.org';
        $this->subject->setWww($value);
        self::assertEquals($value, $this->subject->getWww());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\DataProvider('simplifiedWwwIsReturnedDataProvider')]
    public function simplifiedWwwIsReturned(string $given, string $expected)
    {
        $this->subject->setWww($given);
        self::assertEquals($expected, $this->subject->getWwwSimplified());
    }

    public static function simplifiedWwwIsReturnedDataProvider()
    {
        return [
            'empty' => ['', ''],
            'emptyAfterTrim' => [' ', ''],
            'simpleLink' => ['www.typo3.org', 'www.typo3.org'],
            'linkWithAdditionalAttributes' => ['https://typo3.com _blank', 'https://typo3.com'],
            'linkWithAdditionalAttributes2' => ['https://typo3.com _blank TYPO3', 'https://typo3.com'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function slugCanBeSet()
    {
        $value = '/testaddress/';
        $this->subject->setSlug($value);
        self::assertEquals($value, $this->subject->getSlug());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function skypeCanBeSet()
    {
        $value = 'fo.com';
        $this->subject->setSkype($value);
        self::assertEquals($value, $this->subject->getSkype());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function twitterCanBeSet()
    {
        $value = '@georg_ringer';
        $this->subject->setTwitter($value);
        self::assertEquals($value, $this->subject->getTwitter());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function wrongTwitterHandleThrowsErrorCanBeSet()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1357530444);
        $value = 'georg_ringer';
        $this->subject->setTwitter($value);
        self::assertEquals($value, $this->subject->getTwitter());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function facebookCanBeSet()
    {
        $value = '/fo';
        $this->subject->setFacebook($value);
        self::assertEquals($value, $this->subject->getFacebook());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function wrongFacebookHandleThrowsErrorCanBeSet()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1357530471);
        $value = 'some string';
        $this->subject->setFacebook($value);
        self::assertEquals($value, $this->subject->getFacebook());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function linkedinCanBeSet()
    {
        $value = 'www.linkedin.com/bar';
        $this->subject->setLinkedin($value);
        self::assertEquals($value, $this->subject->getLinkedin());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function emailCanBeSet()
    {
        $value = 'some@example.org';
        $this->subject->setEmail($value);
        self::assertEquals($value, $this->subject->getEmail());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function companyCanBeSet()
    {
        $value = 'ACME';
        $this->subject->setCompany($value);
        self::assertEquals($value, $this->subject->getCompany());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function positionCanBeSet()
    {
        $value = 'Boss';
        $this->subject->setPosition($value);
        self::assertEquals($value, $this->subject->getPosition());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function cityCanBeSet()
    {
        $value = 'Linz';
        $this->subject->setCity($value);
        self::assertEquals($value, $this->subject->getCity());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function zipCanBeSet()
    {
        $value = '30210';
        $this->subject->setZip($value);
        self::assertEquals($value, $this->subject->getZip());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function regionCanBeSet()
    {
        $value = 'OOE';
        $this->subject->setRegion($value);
        self::assertEquals($value, $this->subject->getRegion());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function countryCanBeSet()
    {
        $value = 'AT';
        $this->subject->setCountry($value);
        self::assertEquals($value, $this->subject->getCountry());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function descriptionCanBeSet()
    {
        $value = 'lorem ipsum';
        $this->subject->setDescription($value);
        self::assertEquals($value, $this->subject->getDescription());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function imagesCanBeSet()
    {
        $value = new ObjectStorage();

        $item = new FileReference();
        $item->setPid(123);
        $value->attach($item);
        $this->subject->setImage($value);
        self::assertEquals($value, $this->subject->getImage());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function imagesCanBeAttached()
    {
        $value = new ObjectStorage();

        $item = new FileReference();
        $item->setPid(123);
        $value->attach($item);

        $item2 = new FileReference();
        $item2->setPid(345);

        $this->subject->setImage($value);
        $this->subject->addImage($item2);
        self::assertEquals(2, $this->subject->getImage()->count());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function firstImageCanBeRetrieved()
    {
        $value = new ObjectStorage();

        $item = new FileReference();
        $item->setPid(123);
        $value->attach($item);

        $item2 = new FileReference();
        $item2->setPid(345);

        $this->subject->setImage($value);
        self::assertEquals($item, $this->subject->getFirstImage());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function firstImageIsNullIfNoImages()
    {
        $value = new ObjectStorage();

        $this->subject->setImage($value);
        self::assertNull($this->subject->getFirstImage());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function imagesCanBeRemoved()
    {
        $value = new ObjectStorage();

        $item = new FileReference();
        $item->setPid(123);
        $value->attach($item);

        $item2 = new FileReference();
        $item2->setPid(345);
        $value->attach($item2);

        $this->subject->setImage($value);
        $this->subject->removeImage($item2);
        self::assertEquals(1, $this->subject->getImage()->count());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function categoriesCanBeSet()
    {
        $value = new ObjectStorage();

        $item = new Category();
        $item->setPid(456);
        $value->attach($item);
        $this->subject->setCategories($value);
        self::assertEquals($value, $this->subject->getCategories());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\DataProvider('fullNameDataProvider')]
    public function fullNameIsReturned(string $expected, array $nameParts): void
    {
        $this->subject->setTitle($nameParts[0]);
        $this->subject->setFirstName($nameParts[1]);
        $this->subject->setLastName($nameParts[2]);
        $this->subject->setTitleSuffix($nameParts[3]);

        self::assertEquals($expected, $this->subject->getFullName());
    }

    public static function fullNameDataProvider(): array
    {
        return [
            'simple name' => ['John Doe', ['', 'John', 'Doe', '']],
            'name with title' => ['Dr. Jane Doe', ['Dr.', 'Jane', 'Doe', '']],
            'name with title and 2nd title' => ['Dr. Max Mustermann, Junior', ['Dr.', 'Max', 'Mustermann', 'Junior']],
        ];
    }
}
