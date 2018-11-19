<?php

namespace FriendsOfTYPO3\TtAddress\Service;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\TimeTracker\TimeTracker;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Service for category related stuff
 */
class CategoryService
{

    /**
     * Get child categories by calling recursive function
     * and using the caching framework to save some queries
     *
     * @param string $idList list of category ids to start
     * @param int $counter
     * @param string $additionalWhere additional where clause
     * @return string comma separated list of category ids
     */
    public static function getChildrenCategories(
        $idList,
        $counter = 0
    ) {
        /** @var \TYPO3\CMS\Core\Cache\Frontend\FrontendInterface $cache */
        $cache = GeneralUtility::makeInstance(CacheManager::class)->getCache('cache_ttaddress_category');
        $cacheIdentifier = sha1('children' . $idList);

        $entry = $cache->get($cacheIdentifier);
        if (!$entry) {
            $entry = self::getChildrenCategoriesRecursive($idList, $counter);
            $cache->set($cacheIdentifier, $entry);
        }

        return $entry;
    }

    /**
     * Get child categories
     *
     * @param string $idList list of category ids to start
     * @param int $counter
     * @return string comma separated list of category ids
     */
    private static function getChildrenCategoriesRecursive($idList, $counter = 0): string
    {
        $result = [];

        // add id list to the output too
        if ($counter === 0) {
            $result[] = $idList;
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_category');
        $res = $queryBuilder
            ->select('uid')
            ->from('sys_category')
            ->where(
                $queryBuilder->expr()->in('parent', $queryBuilder->createNamedParameter($idList, Connection::PARAM_INT_ARRAY))
            )
            ->execute();

        while ($row = $res->fetch()) {
            $counter++;
            if ($counter > 10000) {
                GeneralUtility::makeInstance(TimeTracker::class)->setTSlogMessage('EXT:news: one or more recursive categories where found');
                return implode(',', $result);
            }
            $subcategories = self::getChildrenCategoriesRecursive($row['uid'], $counter);
            $result[] = $row['uid'] . ($subcategories ? ',' . $subcategories : '');
        }

        $result = implode(',', $result);
        return $result;
    }

    /**
     * @return TimeTracker
     */
    protected static function getTimeTracker(): TimeTracker
    {
        return $GLOBALS['TT'];
    }
}
