<?php
namespace TYPO3\TtAddress\Domain\Repository;

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

use TYPO3\TtAddress\Service\CategoryService;
use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * The repository for the domain model Address
 */
class AddressRepository extends Repository
{

  /**
   * override the storagePid settings (do not use storagePid) of extbase
   *
   * @return void
   */
  public function initializeObject()
  {
    $this->defaultQuerySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
    $this->defaultQuerySettings->setRespectStoragePage(FALSE);
  }

  
  /**
   * Retrieves all offers by settings (TypoScript and Flexform)
   *
   * @param array settings
   * @param array orderings for query
   * @return Array<TYPO3\TtAddress\Domain\Model>  The result list.
   */
  public function findTtAddressesByCategories($settings, $orderings)
  {
    if ($settings['groups'] != '') {
      // if at least one category is set, return category based query
      $ttAddresses = $this->buildQueryCategories($orderings, $settings['groups'], $settings['groupsCombination']);      
    } else {
      // fallback find all
      $ttAddresses = $this->findAll();
    }
    return $ttAddresses;
  }
  
  
  /**
   * Find by multiple uids and maintain the list order
   *
   * @param string String containing the single uids
   * @param array orderings for query
   * @return Array<TYPO3\TtAddress\Domain\Model>  The result list.
   */
  public function findByUidListOrderByList($settings, $orderings)
  {
    $uidArray = explode(",", $settings['singleRecords']);
    $query = $this->createQuery();
    $query->matching(
      $query->in('uid', $uidArray),
      $query->logicalAnd(
        $query->equals('hidden', 0),
        $query->equals('deleted', 0)
      )
    );
    // set sorting only, if not singleSelection chosen
    // if singleSelection chosen, the sorting automates to sort-order of the uid-array
    if ($settings['sortBy'] != 'singleSelection') {
      $query->setOrderings($orderings);
    }
    return $query->execute();
  }
 

  /**
   * Retrieves all tt_address records by categories
   *
   * @param array orderings for query
   * @param String $categories   Comma-seperated list of Category IDs
   * @param integer $logicalOperaion: 1=OR; 0=AND
   * @return Array<TYPO3\TtAddress\Domain\Model>  The result list.
   */
  protected function buildQueryCategories($orderings, $categories, $logicalOperation = 0)
  {
    $query = $this->createQuery();
    $query->setOrderings($orderings);
    // get category constraint
    $categoryConstraints = self::createCategoryConstraint($query, $categories);
    // build the query
    if ($logicalOperation == 1) {
      $query->matching(
        $query->logicalOr(
          $categoryConstraints
        )
      );
    } else {
      $query->matching(
        $query->logicalAnd(
          $categoryConstraints
        )
      );
    }    
    return $query->execute();
  }
  
  
  /**
   * Returns a category constraint created by
   * a given list of categories and a junction string
   *
   * @param QueryInterface $query
   * @param  string $categories
   * @return \TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface|null
   */
  protected function createCategoryConstraint(QueryInterface $query, $categories)
  {
    $constraints = array();
    
    // get categories recursive
    $categoriesRecursive = self::getQueryCategoriesRecursive($categories);
    
    if (!is_array($categoriesRecursive)) {
      $categoriesRecursive = GeneralUtility::intExplode(',', $categoriesRecursive, TRUE);
    }
    foreach ($categoriesRecursive as $category) {
      $constraints[] = $query->contains('categories', $category);
    }
    return $constraints;
  }
  
  
  /**
   * Retrieves Categories recursively from CategoryService
   * @param string $categories   Comma-seperated list of Category IDs
   * @return string $subCategories
   */
  protected function getQueryCategoriesRecursive($categories)
  {
    $subCategories = CategoryService::getChildrenCategories($categories);
    return $subCategories;
  }
  

}