<?php

namespace FriendsOfTYPO3\TtAddress\Tests\Unit\Hooks\Updates;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use FriendsOfTYPO3\TtAddress\Updates\MigrateLegacyPlugin;
use TYPO3\TestingFramework\Core\BaseTestCase;

class MigrationLegacyPluginTest extends BaseTestCase
{

    /**
     * @test
     */
    public function identifierIsReturned()
    {
        $subject = new MigrateLegacyPlugin();
        $this->assertEquals('tt_address_legacyplugintoextbase', $subject->getIdentifier());
    }
}
