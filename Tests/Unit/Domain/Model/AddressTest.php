<?php
declare(strict_types=1);

namespace FriendsOfTypo3\TtAddress\Tests\Unit\Domain\Model;

/**
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

    /**
     * @test
     */
    public function genderCanBeSet()
    {
        $value = 'm';
        $this->subject->setGender($value);
        $this->assertEquals($value, $this->subject->getGender());
    }

    /**
     * @test
     */
    public function nameCanBeSet()
    {
        $value = 'Max Mustermann';
        $this->subject->setName($value);
        $this->assertEquals($value, $this->subject->getName());
    }

    /**
     * @test
     */
    public function firstNameCanBeSet()
    {
        $value = 'Max';
        $this->subject->setFirstName($value);
        $this->assertEquals($value, $this->subject->getFirstName());
    }

    /**
     * @test
     */
    public function middleNameCanBeSet()
    {
        $value = 'J.';
        $this->subject->setMiddleName($value);
        $this->assertEquals($value, $this->subject->getMiddleName());
    }

    /**
     * @test
     */
    public function lastNameCanBeSet()
    {
        $value = 'Mustermann';
        $this->subject->setLastName($value);
        $this->assertEquals($value, $this->subject->getLastName());
    }

    /**
     * @test
     */
    public function birthdayCanBeSet()
    {
        $value = new \DateTime();
        $this->subject->setBirthday($value);
        $this->assertEquals($value, $this->subject->getBirthday());
    }

    /**
     * @test
     */
    public function titleCanBeSet()
    {
        $value = 'dr.';
        $this->subject->setTitle($value);
        $this->assertEquals($value, $this->subject->getTitle());
    }

    /**
     * @test
     */
    public function addressCanBeSet()
    {
        $value = 'Dummystreet 134';
        $this->subject->setAddress($value);
        $this->assertEquals($value, $this->subject->getAddress());
    }

    /**
     * @test
     */
    public function latitudeCanBeSet()
    {
        $value = 123.121221;
        $this->subject->setLatitude($value);
        $this->assertEquals($value, $this->subject->getLatitude());
    }

    /**
     * @test
     */
    public function longitudeCanBeSet()
    {
        $value = 10.1291;
        $this->subject->setLongitude($value);
        $this->assertEquals($value, $this->subject->getLongitude());
    }

    /**
     * @test
     */
    public function buildingCanBeSet()
    {
        $value = 'building 1';
        $this->subject->setBuilding($value);
        $this->assertEquals($value, $this->subject->getBuilding());
    }

    /**
     * @test
     */
    public function roomCanBeSet()
    {
        $value = 'room 1';
        $this->subject->setRoom($value);
        $this->assertEquals($value, $this->subject->getRoom());
    }

    public function telephoneFormatDataProvider()
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

    /**
     * @test
     */
    public function phoneCanBeSet()
    {
        $value = '+43129';
        $this->subject->setPhone($value);
        $this->assertEquals($value, $this->subject->getPhone());
    }

    /**
     * @test
     * @dataProvider telephoneFormatDataProvider
     */
    public function phoneWithCleanedChars($number, $expectedNumber)
    {
        $this->subject->setPhone($number);

        self::assertSame(
            $expectedNumber,
            $this->subject->getCleanedPhone()
        );
    }

    /**
     * @test
     */
    public function faxCanBeSet()
    {
        $value = '+431294';
        $this->subject->setFax($value);
        $this->assertEquals($value, $this->subject->getFax());
    }

    /**
     * @test
     * @dataProvider telephoneFormatDataProvider
     */
    public function faxWithCleanedChars($number, $expectedNumber)
    {
        $this->subject->setFax($number);

        self::assertSame(
            $expectedNumber,
            $this->subject->getCleanedFax()
        );
    }

    /**
     * @test
     */
    public function mobileCanBeSet()
    {
        $value = '+431294111';
        $this->subject->setMobile($value);
        $this->assertEquals($value, $this->subject->getMobile());
    }

    /**
     * @test
     * @dataProvider telephoneFormatDataProvider
     */
    public function mobileWithCleanedChars($number, $expectedNumber)
    {
        $this->subject->setMobile($number);

        self::assertSame(
            $expectedNumber,
            $this->subject->getCleanedMobile()
        );
    }

    /**
     * @test
     */
    public function wwwCanBeSet()
    {
        $value = 'www.typo3.org';
        $this->subject->setWww($value);
        $this->assertEquals($value, $this->subject->getWww());
    }

    /**
     * @test
     * @dataProvider simplifiedWwwIsReturnedDataProvider
     */
    public function simplifiedWwwIsReturned(string $given, string $expected)
    {
        $this->subject->setWww($given);
        $this->assertEquals($expected, $this->subject->getWwwSimplified());
    }

    public function simplifiedWwwIsReturnedDataProvider()
    {
        return [
            'empty' => ['', ''],
            'emptyAfterTrim' => [' ', ''],
            'simpleLink' => ['www.typo3.org', 'www.typo3.org'],
            'linkWithAdditionalAttributes' => ['https://typo3.com _blank', 'https://typo3.com'],
            'linkWithAdditionalAttributes2' => ['https://typo3.com _blank TYPO3', 'https://typo3.com'],
        ];
    }

    /**
     * @test
     */
    public function slugCanBeSet()
    {
        $value = '/testaddress/';
        $this->subject->setSlug($value);
        $this->assertEquals($value, $this->subject->getSlug());
    }

    /**
     * @test
     */
    public function skypeCanBeSet()
    {
        $value = 'fo.com';
        $this->subject->setSkype($value);
        $this->assertEquals($value, $this->subject->getSkype());
    }

    /**
     * @test
     */
    public function twitterCanBeSet()
    {
        $value = '@georg_ringer';
        $this->subject->setTwitter($value);
        $this->assertEquals($value, $this->subject->getTwitter());
    }

    /**
     * @test
     */
    public function wrongTwitterHandleThrowsErrorCanBeSet()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1357530444);
        $value = 'georg_ringer';
        $this->subject->setTwitter($value);
        $this->assertEquals($value, $this->subject->getTwitter());
    }

    /**
     * @test
     */
    public function facebookCanBeSet()
    {
        $value = '/fo';
        $this->subject->setFacebook($value);
        $this->assertEquals($value, $this->subject->getFacebook());
    }

    /**
     * @test
     */
    public function wrongFacebookHandleThrowsErrorCanBeSet()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1357530471);
        $value = 'some string';
        $this->subject->setFacebook($value);
        $this->assertEquals($value, $this->subject->getFacebook());
    }

    /**
     * @test
     */
    public function linkedinCanBeSet()
    {
        $value = 'www.linkedin.com/bar';
        $this->subject->setLinkedin($value);
        $this->assertEquals($value, $this->subject->getLinkedin());
    }

    /**
     * @test
     */
    public function emailCanBeSet()
    {
        $value = 'some@example.org';
        $this->subject->setEmail($value);
        $this->assertEquals($value, $this->subject->getEmail());
    }

    /**
     * @test
     */
    public function companyCanBeSet()
    {
        $value = 'ACME';
        $this->subject->setCompany($value);
        $this->assertEquals($value, $this->subject->getCompany());
    }

    /**
     * @test
     */
    public function positionCanBeSet()
    {
        $value = 'Boss';
        $this->subject->setPosition($value);
        $this->assertEquals($value, $this->subject->getPosition());
    }

    /**
     * @test
     */
    public function cityCanBeSet()
    {
        $value = 'Linz';
        $this->subject->setCity($value);
        $this->assertEquals($value, $this->subject->getCity());
    }

    /**
     * @test
     */
    public function zipCanBeSet()
    {
        $value = '30210';
        $this->subject->setZip($value);
        $this->assertEquals($value, $this->subject->getZip());
    }

    /**
     * @test
     */
    public function regionCanBeSet()
    {
        $value = 'OOE';
        $this->subject->setRegion($value);
        $this->assertEquals($value, $this->subject->getRegion());
    }

    /**
     * @test
     */
    public function countryCanBeSet()
    {
        $value = 'AT';
        $this->subject->setCountry($value);
        $this->assertEquals($value, $this->subject->getCountry());
    }

    /**
     * @test
     */
    public function descriptionCanBeSet()
    {
        $value = 'lorem ipsum';
        $this->subject->setDescription($value);
        $this->assertEquals($value, $this->subject->getDescription());
    }

    /**
     * @test
     */
    public function imagesCanBeSet()
    {
        $value = new ObjectStorage();

        $item = new FileReference();
        $item->setPid(123);
        $value->attach($item);
        $this->subject->setImage($value);
        $this->assertEquals($value, $this->subject->getImage());
    }

    /**
     * @test
     */
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
        $this->assertEquals(2, $this->subject->getImage()->count());
    }

    /**
     * @test
     */
    public function firstImageCanBeRetrieved()
    {
        $value = new ObjectStorage();

        $item = new FileReference();
        $item->setPid(123);
        $value->attach($item);

        $item2 = new FileReference();
        $item2->setPid(345);

        $this->subject->setImage($value);
        $this->assertEquals($item, $this->subject->getFirstImage());
    }

    /**
     * @test
     */
    public function firstImageIsNullIfNoImages()
    {
        $value = new ObjectStorage();

        $this->subject->setImage($value);
        $this->assertNull($this->subject->getFirstImage());
    }

    /**
     * @test
     */
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
        $this->assertEquals(1, $this->subject->getImage()->count());
    }

    /**
     * @test
     */
    public function categoriesCanBeSet()
    {
        $value = new ObjectStorage();

        $item = new Category();
        $item->setPid(456);
        $value->attach($item);
        $this->subject->setCategories($value);
        $this->assertEquals($value, $this->subject->getCategories());
    }

    /**
     * @test
     * @dataProvider fullNameDataProvider
     */
    public function fullNameIsReturned(string $expected, array $nameParts): void
    {
        $this->subject->setTitle($nameParts[0]);
        $this->subject->setFirstName($nameParts[1]);
        $this->subject->setLastName($nameParts[2]);
        $this->subject->setTitleSuffix($nameParts[3]);

        $this->assertEquals($expected, $this->subject->getFullName());
    }

    public function fullNameDataProvider(): array
    {
        return [
            'simple name' => ['John Doe', ['', 'John', 'Doe', '']],
            'name with title' => ['Dr. Jane Doe', ['Dr.', 'Jane', 'Doe', '']],
            'name with title and 2nd title' => ['Dr. Max Mustermann, Junior', ['Dr.', 'Max', 'Mustermann', 'Junior']],
        ];
    }
}
