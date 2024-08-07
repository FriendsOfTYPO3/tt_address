<?php

declare(strict_types=1);

namespace FriendsOfTypo3\TtAddress\Tests\Unit\Domain\Model\Dto;

/*
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use FriendsOfTYPO3\TtAddress\Domain\Model\Dto\Settings;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\BaseTestCase;

class SettingsTest extends BaseTestCase
{
    public function setUp(): void
    {
        $mockedPackageManager = $this->getAccessibleMock(PackageManager::class, null, [], '', false);
        GeneralUtility::setSingletonInstance(PackageManager::class, $mockedPackageManager);
    }

    /**
     * @test
     */
    public function defaultSettingsAreAvailable(): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['tt_address'] = [];
        $subject = new Settings();

        self::assertEquals('/[^\d\+\s\-]/', $subject->getTelephoneValidationPatternForPhp());
        self::assertEquals('/[^\d\+\s\-]/g', $subject->getTelephoneValidationPatternForJs());
    }

    /**
     * @test
     */
    public function settingsAreSet(): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['tt_address'] = [
            'storeBackwardsCompatName' => false,
            'readOnlyNameField' => false,
            'telephoneValidationPatternForPhp' => 'regex1',
            'telephoneValidationPatternForJs' => 'regex2',
        ];
        $subject = new Settings();

        self::assertEquals('regex1', $subject->getTelephoneValidationPatternForPhp());
        self::assertEquals('regex2', $subject->getTelephoneValidationPatternForJs());
    }
}
