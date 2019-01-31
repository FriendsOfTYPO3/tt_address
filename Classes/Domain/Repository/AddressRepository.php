<?php

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
        $this->defaultQuerySettings = $this->objectManager->get(Typo3QuerySettings::class);
        $this->defaultQuerySettings->setRespectStoragePage(false);
    }

    /**
     * @param Demand $demand
     * @return QueryResultInterface
     * @throws InvalidQueryException
     */
    public function findByDemand(Demand $demand)
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
            $categoryConstraints = $this->createCategoryConstraint($query, $categories);
            if ($demand->getCategoryCombination() === 'or') {
                $constraints['categories'] = $query->logicalOr($categoryConstraints);
            } else {
                $constraints['categories'] = $query->logicalAnd($categoryConstraints);
            }
        }

        if (!empty($constraints)) {
            $query->matching($query->logicalAnd($constraints));
        }
        return $query->execute();
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

        if ($sortBy && $sortBy !== 'default') {
            $query = $this->createQuery();

            $order = strtolower($demand->getSortOrder()) === 'desc' ? QueryInterface::ORDER_DESCENDING : QueryInterface::ORDER_ASCENDING;
            $query->setOrderings([$sortBy => $order]);

            $constraints = [
                $query->in('uid', $idList)
            ];

            $query->matching($query->logicalAnd($constraints));
            return $query->execute();
        } else {
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

    }

    /**
     * Returns a category constraint created by
     * a given list of categories and a junction string
     *
     * @param QueryInterface $query
     * @param  string $categories
     * @return array
     * @throws InvalidQueryException
     */
    protected function createCategoryConstraint(QueryInterface $query, $categories): array
    {
        $constraints = [];

        $categoryService = GeneralUtility::makeInstance(CategoryService::class);
        $categoriesRecursive = $categoryService->getChildrenCategories($categories);
        if (!\is_array($categoriesRecursive)) {
            $categoriesRecursive = GeneralUtility::intExplode(',', $categoriesRecursive, true);
        }
        foreach ($categoriesRecursive as $category) {
            $constraints[] = $query->contains('categories', $category);
        }
        return $constraints;
    }
}
