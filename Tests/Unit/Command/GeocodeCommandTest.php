<?php

declare(strict_types=1);

namespace FriendsOfTypo3\TtAddress\Tests\Unit\Command;

/*
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Command\GeocodeCommand;
use FriendsOfTYPO3\TtAddress\Service\GeocodeService;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use TYPO3\TestingFramework\Core\BaseTestCase;

class GeocodeCommandTest extends BaseTestCase
{
    /**
     * @test
     */
    public function configurationIsProperlyConfigured()
    {
        $subject = $this->getAccessibleMock(GeocodeCommand::class, ['addArgument'], [], '', false);
        $subject->_call('configure');
        self::assertEquals('Geocode tt_address records', $subject->getDescription());
    }

    /**
     * @test
     */
    public function geocodeServiceIsReturned()
    {
        $subject = $this->getAccessibleMock(GeocodeCommand::class, null, [], '', false);
        $service = $subject->_call('getGeocodeService', '123');
        self::assertInstanceOf(GeocodeService::class, $service);
    }

    /**
     * @test
     */
    public function geocodingIsCalled()
    {
        $geocodeService = $this->getAccessibleMock(GeocodeService::class, ['calculateCoordinatesForAllRecordsInTable'], [], '', false);
        $geocodeService->expects(self::once())->method('calculateCoordinatesForAllRecordsInTable');

        $subject = $this->getAccessibleMock(GeocodeCommand::class, ['getGeocodeService'], [], '', false);
        $subject->expects(self::once())->method('getGeocodeService')->willReturn($geocodeService);

        $input = $this->getAccessibleMock(StringInput::class, ['getArgument'], [], '', false);
        $input->expects(self::once())->method('getArgument')->willReturn('123');

        $output = $this->getAccessibleMock(ConsoleOutput::class, null, []);
        $subject->_call('execute', $input, $output);
    }
}
