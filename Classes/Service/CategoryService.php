<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\Service;

/*
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
 * Service for category related stuff.
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
        $this->cache = GeneralUtility::makeInstance(CacheManager::class)->getCache('ttaddress_category');
    }

    /**
     * Get child categories by calling recursive function
     * and using the caching framework to save some queries.
     *
     * @param string $idList list of category ids to start
     *
     * @return string comma separated list of category ids
     */
    public function getChildrenCategories(string $idList, int $counter = 0)
    {
        $cacheIdentifier = sha1('children'.$idList);

        $entry = $this->cache->get($cacheIdentifier);
        if (!$entry) {
            $entry = $this->getChildrenCategoriesRecursive($idList, $counter);
            $this->cache->set($cacheIdentifier, $entry);
        }

        return $entry;
    }

    /**
     * Get child categories.
     *
     * @param string $idList  list of category ids to start
     * @param int    $counter
     *
     * @return string comma separated list of category ids
     */
    protected function getChildrenCategoriesRecursive(string $idList, $counter = 0): string
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
            ->executeQuery();

        while ($row = $res->fetchAssociative()) {
            $counter++;
            if ($counter > 10000) {
                $this->timeTracker->setTSlogMessage('EXT:tt_address: one or more recursive categories where found');

                return implode(',', $result);
            }
            $subcategories = $this->getChildrenCategoriesRecursive((string) $row['uid'], $counter);
            $result[] = $row['uid'].($subcategories ? ','.$subcategories : '');
        }

        return implode(',', $result);
    }

    /**
     * Fetch ids again from DB to avoid false positives.
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
            ->executeQuery()
            ->fetchAllAssociative();
        foreach ($rows as $row) {
            $list[] = $row['uid'];
        }

        return implode(',', $list);
    }
}
