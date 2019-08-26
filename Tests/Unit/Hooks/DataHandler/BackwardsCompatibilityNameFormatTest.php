<?php

namespace FriendsOfTYPO3\TtAddress\Tests\Unit\Hooks\DataHandler;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Domain\Model\Dto\Settings;
use FriendsOfTYPO3\TtAddress\Hooks\DataHandler\BackwardsCompatibilityNameFormat;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\BaseTestCase;

class BackwardsCompatibilityNameFormatTest extends BaseTestCase
{

    public function setUp(): void
    {
        $packageManagerProphecy = $this->prophesize(PackageManager::class);
        GeneralUtility::setSingletonInstance(PackageManager::class, $packageManagerProphecy->reveal());
    }

    /**
     * @test
     */
    public function constructorIsCalled()
    {
        $subject = $this->getAccessibleMock(BackwardsCompatibilityNameFormat::class, ['getRecord'], [], '', true);

        $settings = new Settings();
        $this->assertEquals($settings, $subject->_get('settings'));
    }

    /**
     * @test
     */
    public function updateWillFillName()
    {
        $row = [
            'first_name' => 'john',
            'middle_name' => 'j,',
            'last_name' => 'doe',
        ];
        $fieldArray = [
            'first_name' => 'max',
            'middle_name' => '',
            'last_name' => 'mustermann',
        ];
        $settings = new Settings();

        $subject = $this->getAccessibleMock(BackwardsCompatibilityNameFormat::class, ['getRecord'], [], '', false);
        $subject->_set('settings', $settings);
        $subject->expects($this->once())->method('getRecord')->with(1337)->willReturn($row);

        $subject->processDatamap_postProcessFieldArray('update', 'tt_address', 1337, $fieldArray);

        $this->assertEquals($fieldArray['name'], 'max mustermann');
    }

    /**
     * @test
     */
    public function newWillFillName()
    {
        $row = [
            'first_name' => 'john',
            'middle_name' => 'j,',
            'last_name' => 'doe',
        ];
        $fieldArray = [
            'first_name' => 'max',
            'middle_name' => '',
            'last_name' => 'mustermann',
        ];
        $settings = new Settings();

        $subject = $this->getAccessibleMock(BackwardsCompatibilityNameFormat::class, ['getRecord'], [], '', false);
        $subject->_set('settings', $settings);
        $subject->expects($this->never())->method('getRecord')->with(1337)->willReturn($row);

        $subject->processDatamap_postProcessFieldArray('new', 'tt_address', 1337, $fieldArray);

        $this->assertEquals($fieldArray['name'], 'max mustermann');
    }
}
