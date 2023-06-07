<?php
declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\Domain\Repository;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Domain\Model\Dto\Demand;
use FriendsOfTYPO3\TtAddress\Service\CategoryService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbQueryParser;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * The repository for the domain model Address
 */
class AddressRepository extends Repository
{
    /**
     * override the storagePid settings (do not use storagePid) of extbase
     */
    public function initializeObject()
    {
        $this->defaultQuerySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $this->defaultQuerySettings->setRespectStoragePage(false);
    }

    /**
     * @param Demand $demand
     * @return QueryResultInterface
     * @throws InvalidQueryException
     */
    public function findByDemand(Demand $demand)
    {
        $query = $this->createDemandQuery($demand);
        return $query->execute();
    }

    /**
     * @param Demand $demand
     * @return QueryInterface
     * @throws InvalidQueryException
     */
    protected function createDemandQuery(Demand $demand): QueryInterface
    {
        $query = $this->createQuery();

        // sorting
        $sortBy = $demand->getSortBy();
        if ($sortBy && $sortBy !== 'singleSelection' && $sortBy !== 'default') {
            $order = strtolower($demand->getSortOrder()) === 'desc' ? QueryInterface::ORDER_DESCENDING : QueryInterface::ORDER_ASCENDING;
            $query->setOrderings([$sortBy => $order]);
        }

        $constraints = [];
        $pages = $demand->getPages();
        if (!empty($pages)) {
            $constraints['pages'] = $query->in('pid', $pages);
        }
        $categories = $demand->getCategories();
        if ($categories) {
            $categoryConstraints = $this->createCategoryConstraint($query, $categories, $demand->getIncludeSubCategories());
            if ($demand->getCategoryCombination() === 'or') {
                $constraints['categories'] = $query->logicalOr(...$categoryConstraints);
            } else {
                $constraints['categories'] = $query->logicalAnd(...$categoryConstraints);
            }
        }

        if ($demand->getIgnoreWithoutCoordinates()) {
            $constraints['coordinatesLat'] = $query->logicalNot(
                $query->logicalOr(
                    $query->equals('latitude', null),
                    $query->equals('latitude', 0.0)
                )
            );
            $constraints['coordinatesLng'] = $query->logicalNot(
                $query->logicalOr(
                    $query->equals('longitude', null),
                    $query->equals('longitude', 0.0)
                )
            );
        }

        if (!empty($constraints)) {
            $query->matching($query->logicalAnd(...array_values($constraints)));
        }
        return $query;
    }

    /**
     * Returns the database query to get the matching, see findByDemand()
     *
     * @param Demand $demand
     * @return string
     * @throws InvalidQueryException
     */
    public function getSqlQuery(Demand $demand): string
    {
        $query = $this->createDemandQuery($demand);
        $queryParser = GeneralUtility::makeInstance(Typo3DbQueryParser::class);

        $queryBuilder = $queryParser->convertQueryToDoctrineQueryBuilder($query);
        $queryParameters = $queryBuilder->getParameters();
        $params = [];
        foreach ($queryParameters as $key => $value) {
            // prefix array keys with ':'
            $params[':' . $key] = (\is_numeric($value)) ? $value : "'" . $value . "'"; //all non numeric values have to be quoted
            unset($params[$key]);
        }
        // replace placeholders with real values
        $query = strtr($queryBuilder->getSQL(), $params);
        return $query;
    }

    /**
     * @param Demand $demand
     * @return array|QueryResultInterface
     * @throws InvalidQueryException
     */
    public function getAddressesByCustomSorting(Demand $demand)
    {
        $idList = GeneralUtility::intExplode(',', $demand->getSingleRecords(), true);
        $sortBy = $demand->getSortBy();

        if ($sortBy && $sortBy !== 'default' && $sortBy !== 'singleSelection') {
            $query = $this->createQuery();

            $order = strtolower($demand->getSortOrder()) === 'desc' ? QueryInterface::ORDER_DESCENDING : QueryInterface::ORDER_ASCENDING;
            $query->setOrderings([$sortBy => $order]);

            $constraints = [
                $query->in('uid', $idList)
            ];

            $query->matching($query->logicalAnd(...$constraints));
            return $query->execute();
        }

        if ($demand->getSortOrder() === 'DESC') {
            $idList = array_reverse($idList);
        }

        $list = [];
        foreach ($idList as $id) {
            $item = $this->findByIdentifier($id);
            if ($item) {
                $list[] = $item;
            }
        }

        return $list;
    }

    /**
     * Returns a category constraint created by
     * a given list of categories and a junction string
     *
     * @param QueryInterface $query
     * @param string $categories
     * @param bool $includeSubCategories
     * @return array
     * @throws InvalidQueryException
     */
    protected function createCategoryConstraint(QueryInterface $query, string $categories, bool $includeSubCategories = false): array
    {
        $constraints = [];

        if ($includeSubCategories) {
            $categoryService = GeneralUtility::makeInstance(CategoryService::class);
            $allCategories = $categoryService->getChildrenCategories($categories);
            if (!\is_array($allCategories)) {
                $allCategories = GeneralUtility::intExplode(',', $allCategories, true);
            }
        } else {
            $allCategories = GeneralUtility::intExplode(',', $categories, true);
        }

        foreach ($allCategories as $category) {
            $constraints[] = $query->contains('categories', $category);
        }
        return $constraints;
    }
}
