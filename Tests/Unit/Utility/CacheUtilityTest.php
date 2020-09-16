<?php
declare(strict_types=1);

namespace FriendsOfTypo3\TtAddress\Tests\Unit\Utility;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Domain\Model\Address;
use FriendsOfTYPO3\TtAddress\Utility\CacheUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\TestingFramework\Core\BaseTestCase;

class CacheUtilityTest extends BaseTestCase
{
    protected function setUp():void
    {
        $GLOBALS['TSFE'] = $this->getAccessibleMock(
            TypoScriptFrontendController::class,
            ['addCacheTags'],
            [],
            '',
            false
        );
    }

    /**
     * @test
     */
    public function nonArrayRecordInstancesAreSkippedForCacheTags()
    {
        $addressRecords = ['dummy string'];

        $GLOBALS['TSFE']->expects($this->once())->method('addCacheTags')->with([]);

        CacheUtility::addCacheTagsByAddressRecords($addressRecords);
    }

    /**
     * @test
     */
    public function addressRecordWithLocalizedIdAddsCacheTags()
    {
        $addressRecord = new Address();
        $addressRecord->_setProperty('uid', 42);
        $addressRecord->_setProperty('_localizedUid', 43);
        $addressRecords = [$addressRecord];

        $GLOBALS['TSFE']->expects($this->once())->method('addCacheTags')->with(['tt_address_42', 'tt_address_43']);

        CacheUtility::addCacheTagsByAddressRecords($addressRecords);
    }
}
