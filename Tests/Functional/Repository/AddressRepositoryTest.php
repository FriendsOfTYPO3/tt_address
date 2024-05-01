<?php
declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\Tests\Functional\Repository;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use FriendsOfTYPO3\TtAddress\Domain\Model\Address;
use FriendsOfTYPO3\TtAddress\Domain\Model\Dto\Demand;
use FriendsOfTYPO3\TtAddress\Domain\Repository\AddressRepository;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class AddressRepositoryTest extends FunctionalTestCase
{
    /** @var AddressRepository */
    protected $addressRepository;

    protected array $testExtensionsToLoad = ['typo3conf/ext/tt_address'];

    protected array $coreExtensionsToLoad = ['fluid', 'extensionmanager'];

    public function setUp(): void
    {
        parent::setUp();
        $versionInformation = GeneralUtility::makeInstance(Typo3Version::class);
        if ($versionInformation->getMajorVersion() >= 11) {
            $this->addressRepository = $this->getContainer()->get(AddressRepository::class);
        } else {
            $this->addressRepository = GeneralUtility::makeInstance(ObjectManager::class)->get(AddressRepository::class);
        }

        $this->importCSVDataSet(__DIR__ . '/../Fixtures/tt_address.csv');
    }

    /**
     * @test
     */
    public function rawQueryReturnsCorrectQuery()
    {
        $demand = new Demand();
        $demand->setPages([1, 2]);
        $demand->setIgnoreWithoutCoordinates(true);
        $result = $this->addressRepository->getSqlQuery($demand);
        $time = $GLOBALS['SIM_ACCESS_TIME'];
        $sql = 'SELECT `tt_address`.* FROM `tt_address` `tt_address` WHERE (((`tt_address`.`pid` IN (1, 2)) AND ( NOT((`tt_address`.`latitude` IS NULL) OR (`tt_address`.`latitude` = 0)))) AND ( NOT((`tt_address`.`longitude` IS NULL) OR (`tt_address`.`longitude` = 0)))) AND (`tt_address`.`sys_language_uid` IN (0, -1)) AND (`tt_address`.`t3ver_oid` = 0) AND ((`tt_address`.`hidden` = 0) AND (`tt_address`.`starttime` <= ' . $time . ') AND ((`tt_address`.`endtime` = 0) OR (`tt_address`.`endtime` > ' . $time . ')) AND tt_address.deleted=0)';

        if ((new Typo3Version())->getMajorVersion() >= 12) {
            $sql = 'SELECT `tt_address`.* FROM `tt_address` `tt_address` WHERE (((((`tt_address`.`pid` IN (1, 2)) AND ( NOT(((`tt_address`.`latitude` IS NULL) OR (`tt_address`.`latitude` = 0)))))) AND ( NOT(((`tt_address`.`longitude` IS NULL) OR (`tt_address`.`longitude` = 0)))))) AND (`tt_address`.`sys_language_uid` IN (0, -1)) AND (`tt_address`.`t3ver_oid` = 0) AND (((`tt_address`.`hidden` = 0) AND (`tt_address`.`starttime` <= ' . $time . ') AND (((`tt_address`.`endtime` = 0) OR (`tt_address`.`endtime` > ' . $time . ')))) AND tt_address.deleted=0)';
        }
        $this->assertEquals($sql, $result);
    }

    /**
     * @test
     */
    public function findRecordsByUid()
    {
        $address = $this->addressRepository->findByIdentifier(1);
        $this->assertEquals($address->getFirstName(), 'John');
    }

    /**
     * @test
     */
    public function findRecordsByCustomSorting()
    {
        $demand = new Demand();
        $demand->setPages(['1', '2', '3', '23']);
        $demand->setSingleRecords('3,6,2');
        $addresses = $this->addressRepository->getAddressesByCustomSorting($demand);

        $this->assertEquals([3, 6, 2], $this->getListOfIds($addresses));
    }

    /**
     * @test
     */
    public function findRecordsByCustomSortingDesc()
    {
        $demand = new Demand();
        $demand->setPages(['1', '2', '3', '23']);
        $demand->setSortBy('');
        $demand->setSingleRecords('3,6,2');
        $demand->setSortOrder('DESC');
        $addresses = $this->addressRepository->getAddressesByCustomSorting($demand);

        $this->assertEquals([2, 6, 3], $this->getListOfIds($addresses));
    }

    /**
     * @test
     */
    public function findRecordsByCustomSortingAndSortFieldDesc()
    {
        $demand = new Demand();
        $demand->setSortBy('last_name');
        $demand->setSingleRecords('3,6,2');
        $demand->setSortOrder('DESC');
        $addresses = $this->addressRepository->getAddressesByCustomSorting($demand);

        $this->assertEquals([3, 2, 6], $this->getListOfIds($addresses));
    }

    /**
     * @test
     */
    public function findRecordsByPageAndCustomSortingDesc()
    {
        $demand = new Demand();
        $demand->setPages(['2', '10', '', '3']);
        $demand->setSortBy('lastName');
        $demand->setSortOrder('DESC');
        $addresses = $this->addressRepository->findByDemand($demand);
        $this->assertEquals([7, 5, 6], $this->getListOfIds($addresses));
    }

    /**
     * @test
     */
    public function findRecordsByPageAndCustomSortingAsc()
    {
        $demand = new Demand();
        $demand->setPages(['2', '10', '', '3']);
        $demand->setSortBy('lastName');
        $addresses = $this->addressRepository->findByDemand($demand);
        $this->assertEquals([6, 5, 7], $this->getListOfIds($addresses));
    }

    /**
     * @test
     */
    public function findRecordsByCategory()
    {
        $demand = new Demand();
        $demand->setPages(['1', '2', '3', '23']);
        $demand->setSortBy('uid');
        $demand->setCategories('5');
        $addresses = $this->addressRepository->findByDemand($demand);
        $this->assertEquals([2, 5, 6], $this->getListOfIds($addresses));

        $demand->setCategories('5,6');
        $addresses = $this->addressRepository->findByDemand($demand);
        $this->assertEquals([2], $this->getListOfIds($addresses));

        $demand->setCategoryCombination('or');
        $addresses = $this->addressRepository->findByDemand($demand);
        $this->assertEquals([2, 5, 6, 7], $this->getListOfIds($addresses));
    }

    /**
     * @test
     */
    public function findRecordsByCategoryWithSubCheck()
    {
        $demand = new Demand();
        $demand->setPages(['1', '2', '3', '21', '23']);
        $demand->setSortBy('uid');
        $demand->setCategoryCombination('or');
        $demand->setCategories('1');
        $addresses = $this->addressRepository->findByDemand($demand);
        $this->assertEquals([1, 6], $this->getListOfIds($addresses));

        $demand->setIncludeSubCategories(true);
        $addresses = $this->addressRepository->findByDemand($demand);
        $this->assertEquals([1, 6, 8], $this->getListOfIds($addresses));
    }

    /**
     * @test
     */
    public function findRecordsByCoordinates()
    {
        $demand = new Demand();
        $demand->setPages(['25']);
        $demand->setIgnoreWithoutCoordinates(true);
        $addresses = $this->addressRepository->findByDemand($demand);
        foreach ($addresses as $a) {
            echo $a->getUid() . ' - ' . $a->getLongitude() . '/' . $a->getLatitude() . chr(10);
        }
        $this->assertEquals([14], $this->getListOfIds($addresses));
    }

    /**
     * @param Address[] $list
     * @param string $field
     * @return array
     */
    private function getListOfIds($list, string $field = 'uid'): array
    {
        $getter = 'get' . ucfirst($field);
        $idList = [];
        foreach ($list as $address) {
            $idList[] = $address->$getter();
        }

        return $idList;
    }
}
