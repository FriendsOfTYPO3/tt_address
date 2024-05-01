<?php
declare(strict_types=1);

namespace FriendsOfTypo3\TtAddress\Tests\Unit\Domain\Model\Dto;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Domain\Model\Dto\Settings;
use Prophecy\PhpUnit\ProphecyTrait;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\BaseTestCase;

class SettingsTest extends BaseTestCase
{
    use ProphecyTrait;

    public function setUp(): void
    {
        $packageManagerProphecy = $this->prophesize(PackageManager::class);
        GeneralUtility::setSingletonInstance(PackageManager::class, $packageManagerProphecy->reveal());
    }

    /**
     * @test
     */
    public function defaultSettingsAreAvailable()
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['tt_address'] = [];
        $subject = new Settings();

        $this->assertEquals('/[^\d\+\s\-]/', $subject->getTelephoneValidationPatternForPhp());
        $this->assertEquals('/[^\d\+\s\-]/g', $subject->getTelephoneValidationPatternForJs());
    }

    /**
     * @test
     */
    public function settingsAreSet()
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['tt_address'] = [
            'storeBackwardsCompatName' => false,
            'readOnlyNameField' => false,
            'telephoneValidationPatternForPhp' => 'regex1',
            'telephoneValidationPatternForJs' => 'regex2',
        ];
        $subject = new Settings();

        $this->assertEquals('regex1', $subject->getTelephoneValidationPatternForPhp());
        $this->assertEquals('regex2', $subject->getTelephoneValidationPatternForJs());
    }
}
