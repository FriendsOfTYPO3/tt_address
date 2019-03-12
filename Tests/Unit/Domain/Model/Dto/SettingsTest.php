<?php

namespace FriendsOfTypo3\TtAddress\Tests\Unit\Domain\Model\Dto;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Domain\Model\Dto\Settings;
use TYPO3\TestingFramework\Core\BaseTestCase;

class SettingsTest extends BaseTestCase
{

    /**
     * @test
     */
    public function defaultSettingsAreAvailable()
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tt_address'] = serialize([]);
        $subject = new Settings();

        $this->assertEquals('%1$s %3$s', $subject->getBackwardsCompatFormat());
        $this->assertTrue($subject->isStoreBackwardsCompatName());
        $this->assertTrue($subject->isReadOnlyNameField());
        $this->assertFalse($subject->isActivatePiBase());
    }

    /**
     * @test
     */
    public function settingsAreSet()
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tt_address'] = serialize([
            'backwardsCompatFormat' => '%s%s',
            'storeBackwardsCompatName' => false,
            'readOnlyNameField' => false,
            'activatePiBase' => true,
        ]);
        $subject = new Settings();

        $this->assertEquals('%s%s', $subject->getBackwardsCompatFormat());
        $this->assertFalse($subject->isStoreBackwardsCompatName());
        $this->assertFalse($subject->isReadOnlyNameField());
        $this->assertTrue($subject->isActivatePiBase());
    }
}
