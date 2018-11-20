<?php

namespace FriendsOfTYPO3\TtAddress\Tests\Functional\Repository;

use FriendsOfTYPO3\TtAddress\Domain\Repository\AddressRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class AdressRepositoryTest extends FunctionalTestCase
{

    /** @var ObjectManager */
    protected $objectManager;

    /** @var AddressRepository */
    protected $addressRepository;

    protected $testExtensionsToLoad = ['typo3conf/ext/tt_address'];

    public function setUp()
    {
        parent::setUp();
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->addressRepository = $this->objectManager->get(AddressRepository::class);

        $this->importDataSet(__DIR__ . '/../Fixtures/tt_address.xml');
    }

    /**
     * Test if startingpoint is working
     *
     * @test
     */
    public function findRecordsByUid()
    {
        $address = $this->addressRepository->findByUid(1);

        $this->assertEquals($address->getFirstName(), 'john');
    }
}
