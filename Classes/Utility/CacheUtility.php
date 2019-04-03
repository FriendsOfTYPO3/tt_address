<?php
declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\Utility;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Domain\Model\Address;

/**
 * Class CacheUtility handles cache tags
 */
class CacheUtility
{
    /**
     * Adds cache tags to page cache by tt_address-records.
     *
     * TYPO3 has a built-in mechanism which flushes the page cache
     * when editing a record. This is done by a tag that looks
     * like [table]_[record:uid]
     *
     * @param array $addressRecords array with address records
     */
    public static function addCacheTagsByAddressRecords(array $addressRecords)
    {
        $prefix = 'tt_address_';
        $cacheTags = [];
        foreach ($addressRecords as $addressRecord) {
            if (!$addressRecord instanceof Address) {
                continue;
            }
            // cache tag for each addressRecord record
            $cacheTags[] = $prefix . $addressRecord->getUid();

            if ($addressRecord->_getProperty('_localizedUid') != $addressRecord->getUid()) {
                $cacheTags[] = $prefix . $addressRecord->_getProperty('_localizedUid');
            }
        }

        $GLOBALS['TSFE']->addCacheTags($cacheTags);
    }
}
