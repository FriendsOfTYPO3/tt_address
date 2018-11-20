<?php

namespace FriendsOfTYPO3\TtAddress\Controller;

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

use FriendsOfTYPO3\TtAddress\Utility\TypoScript;
use TYPO3\CMS\Core\Database\QueryGenerator;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * AddressController
 */
class AddressController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * @var \FriendsOfTYPO3\TtAddress\Domain\Repository\AddressRepository
     */
    protected $addressRepository;

    /** @var QueryGenerator */
    protected $queryGenerator;

    public function initializeAction()
    {
        $this->queryGenerator = GeneralUtility::makeInstance(QueryGenerator::class);
    }

    /**
     * @param \FriendsOfTYPO3\TtAddress\Domain\Model\Address $address
     */
    public function showAction(\FriendsOfTYPO3\TtAddress\Domain\Model\Address $address = null)
    {
        if (!$address) {
            $address = $this->addressRepository->findByUid((int)GeneralUtility::_GET('address'));
            if ($address === null) {
                $this->redirectToUri($this->uriBuilder->reset()->setTargetPageUid($GLOBALS['TSFE']->id)->build());
            }
        }
        $this->view->assign('address', $address);
    }

    /**
     * Lists addresses by settings in waterfall principle.
     * singleRecords take precedence over categories which take precedence over records from pages
     */
    public function listAction()
    {
        // set singlePid if empty
        if ($this->settings['singlePid'] == '') {
            $this->settings['singlePid'] = \intval($GLOBALS['TSFE']->id);
        }

        // set default sortBy to last_name, or singleSelection if singleRecords are there
        if ($this->settings['sortBy'] === 'default' && $this->settings['singleRecords'] == '') {
            $this->settings['sortBy'] = 'last_name';
        } elseif ($this->settings['sortBy'] === 'default' && $this->settings['singleRecords'] != '') {
            $this->settings['sortBy'] = 'singleSelection';
        }

        // set a working alternative in case there is no singleRecord and sorting is set to singleSelection
        if ($this->settings['singleRecords'] == '' &&
            $this->settings['sortBy'] === 'singleSelection') {
            $this->settings['sortBy'] = 'sorting';
        }

        // set the final orderings
        $orderings = $this->settings['sortOrder'] === 'ASC' ? [
            $this->settings['sortBy'] => QueryInterface::ORDER_ASCENDING
        ] : [
            $this->settings['sortBy'] => QueryInterface::ORDER_DESCENDING
        ];

        // get all the records
        if ($this->settings['singleRecords'] != '') {

            // get addresses by singleRecords
            $addresses = $this->addressRepository->findByUidListOrderByList($this->settings, $orderings);
        } elseif ($this->settings['groups'] != '') {

            // get addresses by category
            $addresses = $this->addressRepository->findTtAddressesByCategories($this->settings, $orderings);
        } elseif ($this->settings['pages'] != '') {

            // get records from pages
            // first add recursive option
            $storagePageIds = $this->getPidList();
            // set the query-settings
            $querySettings = $this->addressRepository->createQuery()->getQuerySettings();
            $querySettings->setRespectStoragePage(true);
            $querySettings->setStoragePageIds($storagePageIds);
            $this->addressRepository->setDefaultOrderings($orderings);
            $this->addressRepository->setDefaultQuerySettings($querySettings);
            $addresses = $this->addressRepository->findAll();
        } else {
            // Plugin settings are empty, just retrieve all records without respecting storagePage
            $querySettings = $this->addressRepository->createQuery()->getQuerySettings();
            $querySettings->setRespectStoragePage(false);
            $this->addressRepository->setDefaultOrderings($orderings);
            $this->addressRepository->setDefaultQuerySettings($querySettings);
            // no settings, fallback to findAll
            $addresses = $this->addressRepository->findAll();
        }

        $this->view->assign('settings', $this->settings);
        $this->view->assign('addresses', $addresses);
    }

    /**
     * Injects the Configuration Manager and is initializing the framework settings
     *
     * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager Instance of the Configuration Manager
     */
    public function injectConfigurationManager(
        \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
    )
    {
        $this->configurationManager = $configurationManager;

        // get the whole typoscript (_FRAMEWORK does not work anymore, don't know why)
        $tsSettings = $this->configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT,
            '',
            ''
        );

        // correct the array to be in same shape like the _SETTINGS array
        $tsSettings = $this->removeDots($tsSettings['plugin.']['tx_ttaddress.']);

        // get original settings
        // original means: what extbase does by munching flexform and TypoScript together, but leaving empty flexform-settings empty ...
        $originalSettings = $this->configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS
        );

        $propertiesNotAllowedViaFlexForms = ['orderByAllowed'];
        foreach ($propertiesNotAllowedViaFlexForms as $property) {
            $originalSettings[$property] = $tsSettings['settings'][$property];
        }

        // start override
        if (isset($tsSettings['settings']['overrideFlexformSettingsIfEmpty'])) {
            $typoScriptUtility = GeneralUtility::makeInstance(TypoScript::class);
            $originalSettings = $typoScriptUtility->override($originalSettings, $tsSettings);
        }
        // Re-set global settings
        $this->settings = $originalSettings;
    }

    /**
     * @param \FriendsOfTYPO3\TtAddress\Domain\Repository\AddressRepository $addressRepository
     */
    public function injectAddressRepository(\FriendsOfTYPO3\TtAddress\Domain\Repository\AddressRepository $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }

    /**
     * Removes dots at the end of a configuration array
     *
     * @param array $settings the array to transformed
     * @return array $settings the transformed array
     */
    protected function removeDots(array $settings): array
    {
        $conf = [];
        foreach ($settings as $key => $value) {
            $conf[$this->removeDotAtTheEnd($key)] = \is_array($value) ? $this->removeDots($value) : $value;
        }
        return $conf;
    }

    /**
     * Removes a dot in the end of a String
     *
     * @param string $string
     * @return string
     */
    protected function removeDotAtTheEnd($string): string
    {
        return preg_replace('/\.$/', '', $string);
    }

    /**
     * Retrieves subpages of given pageIds recursively until reached $this->settings['recursive']
     *
     * @return array an array with all pageIds
     */
    protected function getPidList(): array
    {
        $rootPIDs = explode(',', $this->settings['pages']);
        $pidList = $rootPIDs;

        // iterate through root-page ids and merge to array
        foreach ($rootPIDs as $pid) {
            $result = $this->queryGenerator->getTreeList($pid, $this->settings['recursive'], 0, 1);
            if ($result) {
                $subtreePids = explode(',', $result);
                $pidList = array_merge($pidList, $subtreePids);
            }
        }
        return $pidList;
    }
}
