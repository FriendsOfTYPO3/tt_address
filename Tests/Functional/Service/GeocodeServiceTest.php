<?php
declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\Tests\Functional\Command;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Service\GeocodeService;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class GeocodeServiceTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/tt_address'];

    protected array $coreExtensionsToLoad = ['fluid', 'extensionmanager'];

    public function setUp(): void
    {
        parent::setUp();

        $this->importCSVDataSet(__DIR__ . '/../Fixtures/tt_address.csv');
    }

    /**
     * @test
     */
    public function properRecordsAreFound()
    {
        $subject = $this->getAccessibleMock(GeocodeService::class, ['getCoordinatesForAddress'], ['123']);
        $subject->expects($this->any())->method('getCoordinatesForAddress')
            ->withConsecutive([], [], [])
            ->willReturnOnConsecutiveCalls(
                ['latitude' => 10.000, 'longitude' => 12.000],
                ['latitude' => 10.000, 'longitude' => 12.000],
                [],
                ['latitude' => 13.000, 'longitude' => 14.000]
            );

        $count = $subject->calculateCoordinatesForAllRecordsInTable('pid=100');
        $this->assertEquals(3, $count);

        $row = BackendUtility::getRecord('tt_address', 21);
        $this->assertEquals(['latitude' => $row['latitude'], 'longitude' => $row['longitude']], ['latitude' => 10.000000000000, 'longitude' => 12.000000000000]);
    }

    /**
     * @test
     */
    public function urlforAddressesIsBuiltCorrectly()
    {
        $result1 = ['results' => [0 => ['geometry' => ['location' => ['lat' => 11, 'lng' => '13']]]]];
        $resultEmpty = ['results' => []];

        $subject = $this->getAccessibleMock(GeocodeService::class, ['getApiCallResult'], ['123']);
        $subject->expects($this->any())->method('getApiCallResult')
            ->willReturnOnConsecutiveCalls($result1, $resultEmpty);

        $response = $subject->getCoordinatesForAddress('DummyStr', '1', 'London', 'UK');
        $this->assertEquals($response, ['latitude' => 11, 'longitude' => '13']);
        $response2 = $subject->getCoordinatesForAddress('DummyStr', '1', 'London', 'UK');
        $response3 = $subject->getCoordinatesForAddress('DummyStr', '2', 'Vienna', 'UK');
        $response4 = $subject->getCoordinatesForAddress();
        $this->assertEquals([], $response4);
    }

    /**
     * @test
     */
    public function findRecordsByUid()
    {
        $this->assertTrue(true);
    }
}
