<?php

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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class AddressRepositoryTest extends FunctionalTestCase
{

    /** @var ObjectManager */
    protected $objectManager;

    /** @var AddressRepository */
    protected $addressRepository;

    protected $testExtensionsToLoad = ['typo3conf/ext/tt_address'];

    protected $coreExtensionsToLoad = ['fluid', 'extensionmanager'];

    public function setUp()
    {
        parent::setUp();
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->addressRepository = $this->objectManager->get(AddressRepository::class);

        $this->importDataSet(__DIR__ . '/../Fixtures/tt_address.xml');
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
    public function findRecordsByCategoryWithSubCheck() {
        $demand = new Demand();
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
