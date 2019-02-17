<?php

namespace FriendsOfTYPO3\TtAddress\Tests\Unit\Hooks\Updates;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Updates\TypoScriptTemplateLocation;
use TYPO3\TestingFramework\Core\BaseTestCase;

class TypoScriptTemplateLocationTest extends BaseTestCase
{

    /**
     * @test
     */
    public function identifierIsReturned()
    {
        $subject = new TypoScriptTemplateLocation();
        $this->assertEquals('tt_address_legacyplugintyposcript', $subject->getIdentifier());
    }
}
