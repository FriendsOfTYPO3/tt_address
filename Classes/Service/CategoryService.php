<?php

namespace FriendsOfTYPO3\TtAddress\Service;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\TimeTracker\TimeTracker;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Service for category related stuff
 */
class CategoryService
{
    /** @var TimeTracker */
    protected $timeTracker;

    /** @var FrontendInterface */
    protected $cache;

    public function __construct()
    {
        $this->timeTracker = GeneralUtility::makeInstance(TimeTracker::class);
        $this->cache = GeneralUtility::makeInstance(CacheManager::class)->getCache('cache_ttaddress_category');
    }

    /**
     * Get child categories by calling recursive function
     * and using the caching framework to save some queries
     *
     * @param string $idList list of category ids to start
     * @param int $counter
     * @return string comma separated list of category ids
     */
    public function getChildrenCategories($idList, int $counter = 0)
    {
        $cacheIdentifier = sha1('children' . $idList);

        $entry = $this->cache->get($cacheIdentifier);
        if (!$entry) {
            $entry = $this->getChildrenCategoriesRecursive($idList, $counter);
            $this->cache->set($cacheIdentifier, $entry);
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
    protected function getChildrenCategoriesRecursive($idList, $counter = 0): string
    {
        $result = [];

        // add id list to the output
        if ($counter === 0) {
            $newList = $this->getUidListFromRecords($idList);
            if ($newList) {
                $result[] = $newList;
            }
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_category');
        $res = $queryBuilder
            ->select('uid')
            ->from('sys_category')
            ->where(
                $queryBuilder->expr()->in('parent', $queryBuilder->createNamedParameter(explode(',', $idList), Connection::PARAM_INT_ARRAY))
            )
            ->execute();

        while ($row = $res->fetch()) {
            $counter++;
            if ($counter > 10000) {
                $this->timeTracker->setTSlogMessage('EXT:tt_address: one or more recursive categories where found');
                return implode(',', $result);
            }
            $subcategories = $this->getChildrenCategoriesRecursive($row['uid'], $counter);
            $result[] = $row['uid'] . ($subcategories ? ',' . $subcategories : '');
        }

        $result = implode(',', $result);
        return $result;
    }

    /**
     * Fetch ids again from DB to avoid false positives
     *
     * @param string $idList
     * @return string
     */
    protected function getUidListFromRecords(string $idList): string
    {
        $list = [];
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_category');
        $rows = $queryBuilder
            ->select('uid')
            ->from('sys_category')
            ->where(
                $queryBuilder->expr()->in('uid', $queryBuilder->createNamedParameter(explode(',', $idList), Connection::PARAM_INT_ARRAY))
            )
            ->execute()
            ->fetchAll();
        foreach ($rows as $row) {
            $list[] = $row['uid'];
        }

        return implode(',', $list);
    }
}
