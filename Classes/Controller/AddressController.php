<?php

namespace TYPO3\TtAddress\Controller;

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

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
 
/**
 * AddressController
 */
class AddressController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{ 

  /**
   * @var \TYPO3\TtAddress\Domain\Repository\AddressRepository
   * @inject
   */
  protected $addressRepository;
  
  
  /**
   * @param \TYPO3\TtAddress\Domain\Model\Address $address
   * @return void
   */
  public function showAction(\TYPO3\TtAddress\Domain\Model\Address $address = null)
  {
    if (!$address) {
      $address = $this->addressRepository->findByUid(intval(\TYPO3\CMS\Core\Utility\GeneralUtility::_GET('address')));
      if ($address == null) {
        $this->redirectToUri($this->uriBuilder->reset()->setTargetPageUid($GLOBALS['TSFE']->id)->build());
      }
    }
    $this->view->assign("address", $address);
  }

  /**
   * Lists addresses by settings in waterfall principle.
   * singleRecords take precedence over categories which take precedence over records from pages
   */
  public function listAction()
  {
    // set singlePid if empty
    if ($this->settings['singlePid'] == '') {
      $this->settings['singlePid'] = intval($GLOBALS['TSFE']->id);
    }
    
    // set default sortBy to last_name, or singleSelection if singleRecords are there
    if($this->settings['sortBy'] == 'default' && $this->settings['singleRecords'] == '') {
      
      $this->settings['sortBy'] = 'last_name';
      
    } else if ($this->settings['sortBy'] == 'default' && $this->settings['singleRecords'] != '') {
      $this->settings['sortBy'] = 'singleSelection';
    }
    
    // set a working alternative in case there is no singleRecord and sorting is set to singleSelection
    if($this->settings['singleRecords'] == '' &&
       $this->settings['sortBy'] == 'singleSelection') {
      $this->settings['sortBy'] = 'sorting';
    }
    
    // set the final orderings
    if($this->settings['sortOrder'] == 'ASC') {
      $orderings = array(
        $this->settings['sortBy'] => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
      );
    } else {
      $orderings = array(
        $this->settings['sortBy'] => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
      );
    }
    
    // get all the records
    if($this->settings['singleRecords'] != '') {
      
      // get addresses by singleRecords
      $addresses = $this->addressRepository->findByUidListOrderByList($this->settings, $orderings);
      
    } else if ($this->settings['groups'] != '') {
      
      // get addresses by category
      $addresses = $this->addressRepository->findTtAddressesByCategories($this->settings, $orderings);
      
    } else if ($this->settings['pages'] != '') {
      
      // get records from pages
      // first add recursive option
      $storagePageIds = $this->getTreePids();
      // set the query-settings
      $querySettings = $this->addressRepository->createQuery()->getQuerySettings();
      $querySettings->setRespectStoragePage(TRUE);
      $querySettings->setStoragePageIds($storagePageIds);
      $querySettings->setOrderings($orderings);
      $this->addressRepository->setDefaultQuerySettings($querySettings);
      $addresses = $this->addressRepository->findAll();
      
    } else {
      
      // Plugin settings are empty, just retrieve all records without respecting storagePage
      $querySettings = $this->addressRepository->createQuery()->getQuerySettings();
      $querySettings->setRespectStoragePage(FALSE);
      $querySettings->setOrderings($orderings);
      $this->addressRepository->setDefaultQuerySettings($querySettings);
      // no settings, fallback to findAll
      $addresses = $this->addressRepository->findAll();
      
    }
    
    $this->view->assign("settings", $this->settings);
    $this->view->assign("addresses", $addresses);
  }
  
  
  /**
   * Injects the Configuration Manager and is initializing the framework settings
   *
   * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager Instance of the Configuration Manager
   * @return void
   */
  public function injectConfigurationManager(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager)
  {
    $this->configurationManager = $configurationManager;

    // get the whole typoscript (_FRAMEWORK does not work anymore, don't know why)
    $tsSettings = $this->configurationManager->getConfiguration(
      \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT,
      '',
      ''
    );

    // correct the array to be in same shape like the _SETTINGS array
    $tsSettings = self::removeDots($tsSettings['plugin.']['tx_ttaddress.']);
    
    // get original settings
    // original means: what extbase does by munching flexform and TypoScript together, but leaving empty flexform-settings empty ...
    $originalSettings = $this->configurationManager->getConfiguration(
      \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS
    );
        
    $propertiesNotAllowedViaFlexForms = array('orderByAllowed');
    foreach($propertiesNotAllowedViaFlexForms as $property) {
      $originalSettings[$property] = $tsSettings['settings'][$property];
    }

    // Use stdWrap for given defined settings
    if (isset($originalSettings['useStdWrap']) && !empty($originalSettings['useStdWrap'])) {
      /** @var  \TYPO3\CMS\Extbase\Service\TypoScriptService $typoScriptService */
      $typoScriptService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Service\\TypoScriptService');
      $typoScriptArray = $typoScriptService->convertPlainArrayToTypoScriptArray($originalSettings);
      $stdWrapProperties = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $originalSettings['useStdWrap'], TRUE);
      foreach ($stdWrapProperties as $key) {
        if (is_array($typoScriptArray[$key . '.'])) {
          $originalSettings[$key] = $this->configurationManager->getContentObject()->stdWrap(
              $originalSettings[$key],
              $typoScriptArray[$key . '.']
          );
        }
      }
    }
    // start override
    if (isset($tsSettings['settings']['overrideFlexformSettingsIfEmpty'])) {
      /** @var \TYPO3\TtAddress\Utility\TypoScript $typoScriptUtility */
      $typoScriptUtility = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\TtAddress\\Utility\\TypoScript'); 
      $originalSettings = $typoScriptUtility->override($originalSettings, $tsSettings);
    }
    // Re-set global settings
    $this->settings = $originalSettings;
  }
  
  
  /**
   * Removes dots at the end of a configuration array
   * @param array $settings the array to transformed
   * @return array $settings the transformed array
   */
  private static function removeDots($settings)
  {
    $conf = array();
    foreach ($settings as $key => $value) {
      $conf[self::removeDotAtTheEnd($key)] = is_array($value) ? self::removeDots($value) : $value;
    }
    return $conf;
  }
  
  
  /**
   * Removes a dot in the end of a String
   * @param string $string
   * @return  string    $string
   */
  private static function removeDotAtTheEnd($string)
  {
    return preg_replace('/\.$/', '', $string);
  }
  
  /**
   * Retrieves subpages of given pageIds recursively until reached $this->settings['recursive']
   *
   * @return Array an array with all pageIds
   */
  private function getTreePids()
  {
    $queryGenerator = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( 'TYPO3\\CMS\\Core\\Database\\QueryGenerator' );
    // make array of root-page ids
    $rootPIDs = explode(",", $this->settings["pages"]);
    
    // build array which will finally hold all accepted storagePages
    $storagePIDsArray = explode(",", $this->settings["pages"]);
    
    // iterate through root-page ids and merge to array
    foreach($rootPIDs as $pid) {
      $subtreePids = explode(",", $queryGenerator->getTreeList($pid, $this->settings["recursive"], 0, 1));
      $storagePIDsArray = array_merge($storagePIDsArray, $subtreePids);
    }
    return $storagePIDsArray;
  }


}