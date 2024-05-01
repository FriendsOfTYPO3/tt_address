<?php
declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\Controller;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use FriendsOfTYPO3\TtAddress\Database\QueryGenerator;
use FriendsOfTYPO3\TtAddress\Domain\Model\Address;
use FriendsOfTYPO3\TtAddress\Domain\Model\Dto\Demand;
use FriendsOfTYPO3\TtAddress\Domain\Model\Dto\Settings;
use FriendsOfTYPO3\TtAddress\Domain\Repository\AddressRepository;
use FriendsOfTYPO3\TtAddress\Seo\AddressTitleProvider;
use FriendsOfTYPO3\TtAddress\Utility\CacheUtility;
use FriendsOfTYPO3\TtAddress\Utility\TypoScript;
use TYPO3\CMS\Core\Pagination\ArrayPaginator;
use TYPO3\CMS\Core\Pagination\PaginatorInterface;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * AddressController
 */
class AddressController extends ActionController
{
    /** @var AddressRepository */
    protected $addressRepository;

    /** @var QueryGenerator */
    protected $queryGenerator;

    /** @var Settings */
    protected $extensionConfiguration;

    public function initializeAction()
    {
        $this->queryGenerator = GeneralUtility::makeInstance(QueryGenerator::class);
        $this->extensionConfiguration = GeneralUtility::makeInstance(Settings::class);
    }

    public function showAction(Address $address = null)
    {
        if (is_a($address, Address::class) && ($this->settings['detail']['checkPidOfAddressRecord'] ?? false)) {
            $address = $this->checkPidOfAddressRecord($address);
        }

        if ($address !== null) {
            $provider = GeneralUtility::makeInstance(AddressTitleProvider::class);
            $provider->setTitle($address, (array)($this->settings['seo']['pageTitle'] ?? []));
            CacheUtility::addCacheTagsByAddressRecords([$address]);
        }

        $this->view->assignMultiple([
            'address' => $address,
            'contentObjectData' => $this->configurationManager->getContentObject()->data,
        ]);
        return $this->htmlResponse();
    }

    /**
     * Lists addresses by settings in waterfall principle.
     * singleRecords take precedence over categories which take precedence over records from pages
     *
     */
    public function listAction(?array $override = [])
    {
        $contentData = $this->configurationManager->getContentObject()->data;
        $demand = $this->createDemandFromSettings();

        if (isset($contentData['first_name'], $contentData['birthday']) && (int)($this->settings['insertRecord'] ?? 0) === 1) {
            $demand->setSingleRecords((string)$contentData['uid']);
        }

        if (!empty($override) && $this->settings['allowOverride']) {
            $this->overrideDemand($demand, $override);
        }

        if ($demand->getSingleRecords()) {
            $addresses = $this->addressRepository->getAddressesByCustomSorting($demand);
        } else {
            $addresses = $this->addressRepository->findByDemand($demand);
        }

        $paginator = $this->getPaginator($addresses);
        $pagination = new SimplePagination($paginator);
        // @todo remove with version 8
        $this->view->assign('newPagination', true);
        $this->view->assign('pagination', [
            'paginator' => $paginator,
            'pagination' => $pagination,
        ]);

        $this->view->assignMultiple([
            'demand' => $demand,
            'addresses' => $addresses,
            'contentObjectData' => $contentData,
        ]);

        CacheUtility::addCacheTagsByAddressRecords(
            $addresses instanceof QueryResultInterface ? $addresses->toArray() : $addresses
        );
        return $this->htmlResponse();
    }

    /**
     * Injects the Configuration Manager and is initializing the framework settings
     *
     * @param ConfigurationManagerInterface $configurationManager Instance of the Configuration Manager
     */
    public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager)
    {
        parent::injectConfigurationManager($configurationManager);
        $this->configurationManager = $configurationManager;

        // get the whole typoscript (_FRAMEWORK does not work anymore, don't know why)
        $tsSettings = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT,
            '',
            ''
        );

        // correct the array to be in same shape like the _SETTINGS array
        $tsSettings = $this->removeDots((array)($tsSettings['plugin.']['tx_ttaddress.'] ?? []));

        // get original settings
        // original means: what extbase does by munching flexform and TypoScript together, but leaving empty flexform-settings empty ...
        $originalSettings = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS
        );

        $propertiesNotAllowedViaFlexForms = ['orderByAllowed'];
        foreach ($propertiesNotAllowedViaFlexForms as $property) {
            if (isset($tsSettings['settings'][$property])) {
                $originalSettings[$property] = $tsSettings['settings'][$property];
            }
        }

        // start override
        if (isset($tsSettings['settings']['overrideFlexformSettingsIfEmpty'])) {
            $typoScriptUtility = GeneralUtility::makeInstance(TypoScript::class);
            $originalSettings = $typoScriptUtility->override($originalSettings, $tsSettings);
        }
        // Re-set global settings
        $this->settings = $originalSettings;
    }

    protected function createDemandFromSettings(): Demand
    {
        $demand = new Demand();
        $demand->setCategories((string)($this->settings['groups'] ?? ''));
        $categoryCombination = (int)($this->settings['groupsCombination'] ?? 1) === 1 ? 'or' : 'and';
        $demand->setCategoryCombination($categoryCombination);
        $demand->setIncludeSubCategories((bool)($this->settings['includeSubcategories'] ?? false));

        if ($this->settings['pages']) {
            $demand->setPages($this->getPidList());
        }
        $demand->setSingleRecords((string)($this->settings['singleRecords'] ?? ''));
        $demand->setSortBy((string)($this->settings['sortBy'] ?? ''));
        $demand->setSortOrder((string)($this->settings['sortOrder'] ?? ''));
        $demand->setIgnoreWithoutCoordinates((bool)($this->settings['ignoreWithoutCoordinates'] ?? false));

        return $demand;
    }

    protected function overrideDemand(Demand $demand, array $override = []): Demand
    {
        $ignoredValues = ['singleRecords', 'pages'];
        $ignoredValuesLower = array_map('strtolower', $ignoredValues);

        foreach ($ignoredValues as $property) {
            unset($override[$property]);
        }

        // check if field exists
        if (isset($override['sortBy']) && !isset($GLOBALS['TCA']['tt_address']['columns'][$override['sortBy']])) {
            unset($override['sortBy']);
        }

        foreach ($override as $propertyName => $propertyValue) {
            if (in_array(strtolower($propertyName), $ignoredValuesLower, true)) {
                continue;
            }
            if ($propertyValue !== '' || $this->settings['allowEmptyStringsForOverwriteDemand']) {
                ObjectAccess::setProperty($demand, $propertyName, $propertyValue);
            }
        }
        return $demand;
    }

    /**
     * @param AddressRepository $addressRepository
     */
    public function injectAddressRepository(AddressRepository $addressRepository)
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
        return preg_replace('/\.$/', '', (string)$string);
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
            $result = $this->queryGenerator->getTreeList($pid, (int)($this->settings['recursive'] ?? 0));
            if ($result) {
                $subtreePids = explode(',', $result);
                $pidList = array_merge($pidList, $subtreePids);
            }
        }
        return $pidList;
    }

    /**
     * @param QueryResultInterface|array $addresses
     * @return ArrayPaginator|QueryResultPaginator
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
     */
    protected function getPaginator($addresses): PaginatorInterface
    {
        $currentPage = $this->request->hasArgument('currentPage') ? (int)$this->request->getArgument('currentPage') : 1;
        $itemsPerPage = (int)($this->settings['paginate']['itemsPerPage'] ?? 10);
        if ($itemsPerPage === 0) {
            $itemsPerPage = 10;
        }

        if (is_array($addresses)) {
            $paginator = new ArrayPaginator($addresses, $currentPage, $itemsPerPage);
        } elseif ($addresses instanceof QueryResultInterface) {
            $paginator = new QueryResultPaginator($addresses, $currentPage, $itemsPerPage);
        } else {
            throw new \RuntimeException(sprintf('Only array and query result interface allowed for pagination, given "%s"', get_class($addresses)), 1611168593);
        }
        return $paginator;
    }

    /**
     * Checks if the address PID could be found in the storagePage settings of the detail plugin and
     * if the pid is not found null is returned
     *
     * @param Address $address
     * @return Address|null
     */
    protected function checkPidOfAddressRecord(Address $address): ?Address
    {
        $allowedStoragePages = array_map('intval', $this->getPidList());
        if (count($allowedStoragePages) > 0 && !in_array($address->getPid(), $allowedStoragePages, true)) {
            $address = null;
        }

        return $address;
    }
}
