<?php

namespace FriendsOfTypo3\TtAddress\Tests\Unit\Command;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Command\GeocodeCommand;
use FriendsOfTYPO3\TtAddress\Domain\Model\Dto\Settings;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\TestingFramework\Core\BaseTestCase;

class GeocodeCommandTest extends BaseTestCase
{

    /**
     * @test
     */
    public function constructorWorks()
    {
        $settings = new Settings();
        $subject = $this->getAccessibleMock(GeocodeCommand::class, ['dummy'], [], '', true);
        $this->assertEquals($settings, $subject->_get('extensionSettings'));
    }

    /**
     * @test
     */
    public function geocodingIsIgnoredIfDisabled()
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tt_address'] = serialize([
            'enableGeo' => false,
            'googleMapsKeyGeocoding' => '123',
        ]);
        $settings = new Settings();
        $mockedIo = $this->getAccessibleMock(SymfonyStyle::class, ['warning'], [], '', false);
        $mockedIo->expects($this->once())->method('warning')->with('Geo stuff is not enabled for tt_address!');

        $subject = $this->getAccessibleMock(GeocodeCommand::class, ['getSymfonyStyle'], [], '', false);
        $subject->_set('extensionSettings', $settings);
        $subject->expects($this->once())->method('getSymfonyStyle')->willReturn($mockedIo);

        $input = $this->getAccessibleMock(StringInput::class, ['dummy'], [], '', false);
        $output = $this->getAccessibleMock(ConsoleOutput::class, ['warning'], []);
        $subject->_call('execute', $input, $output);
    }

    /**
     * @test
     */
    public function geocodingIsIgnoredWithNoKey()
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tt_address'] = serialize([
            'enableGeo' => true,
            'googleMapsKeyGeocoding' => '',
        ]);
        $settings = new Settings();
        $mockedIo = $this->getAccessibleMock(SymfonyStyle::class, ['warning'], [], '', false);
        $mockedIo->expects($this->once())->method('warning')->with('No google maps key configured!');

        $subject = $this->getAccessibleMock(GeocodeCommand::class, ['getSymfonyStyle'], [], '', false);
        $subject->_set('extensionSettings', $settings);
        $subject->expects($this->once())->method('getSymfonyStyle')->willReturn($mockedIo);

        $input = $this->getAccessibleMock(StringInput::class, ['dummy'], [], '', false);
        $output = $this->getAccessibleMock(ConsoleOutput::class, ['warning'], []);
        $subject->_call('execute', $input, $output);
    }

    /**
     * @test
     */
    public function geocodingIsCalled()
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tt_address'] = serialize([
            'enableGeo' => true,
            'googleMapsKeyGeocoding' => '123',
        ]);
        $settings = new Settings();

        $geocodeService = $this->getAccessibleMock(GeocodeCommand::class, ['calculateCoordinatesForAllRecordsInTable'], [], '', false);
        $subject = $this->getAccessibleMock(GeocodeCommand::class, ['calculateCoordinatesForAllRecordsInTable'], [], '', false);
        $subject->_set('extensionSettings', $settings);
        $subject->_set('geocodeService', $geocodeService);
        $geocodeService->expects($this->once())->method('calculateCoordinatesForAllRecordsInTable');

        $input = $this->getAccessibleMock(StringInput::class, ['dummy'], [], '', false);
        $output = $this->getAccessibleMock(ConsoleOutput::class, ['warning'], []);
        $subject->_call('execute', $input, $output);
    }
}
