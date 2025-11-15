<?php

declare(strict_types=1);

namespace FriendsOfTypo3\TtAddress\Tests\Unit\Utility;

/*
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Domain\Model\Address;
use FriendsOfTYPO3\TtAddress\Utility\CacheUtility;
use TYPO3\CMS\Core\Cache\CacheDataCollectorInterface;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\TestingFramework\Core\BaseTestCase;

class CacheUtilityTest extends BaseTestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function nonArrayRecordInstancesAreSkippedForCacheTags()
    {
        $addressRecords = ['dummy string'];

        $mockedCacheDataCollector = $this->getMockBuilder(CacheDataCollectorInterface::class)->getMock();
        $mockedCacheDataCollector
            ->expects(self::never())
            ->method('addCacheTags');

        $serverRequest = (new ServerRequest())
            ->withAttribute('extbase', new ExtbaseRequestParameters())
            ->withAttribute('frontend.cache.collector', $mockedCacheDataCollector);

        $GLOBALS['TYPO3_REQUEST'] = $serverRequest;

        CacheUtility::addCacheTagsByAddressRecords($addressRecords);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function addressRecordWithLocalizedIdAddsCacheTags()
    {
        $addressRecord = new Address();
        $addressRecord->_setProperty('uid', 42);
        $addressRecord->_setProperty('_localizedUid', 43);
        $addressRecords = [$addressRecord];

        $calls = [];
        $mockedCacheDataCollector = $this->getMockBuilder(CacheDataCollectorInterface::class)->getMock();
        $mockedCacheDataCollector
            ->expects(self::exactly(2))
            ->method('addCacheTags')
            ->willReturnCallback(function (...$args) use (&$calls) {
                $calls[] = $args;
            });

        $serverRequest = (new ServerRequest())
            ->withAttribute('extbase', new ExtbaseRequestParameters())
            ->withAttribute('frontend.cache.collector', $mockedCacheDataCollector);

        $GLOBALS['TYPO3_REQUEST'] = $serverRequest;

        CacheUtility::addCacheTagsByAddressRecords($addressRecords);
        self::assertEquals('tt_address_42', $calls[0][0]->name);
        self::assertEquals('tt_address_43', $calls[1][0]->name);
    }
}
