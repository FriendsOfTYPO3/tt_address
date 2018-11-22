<?php

namespace FriendsOfTYPO3\TtAddress\Controller;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\Service\MarkerBasedTemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Frontend\Plugin\AbstractPlugin;

/**
 * main class for the tt_address plugin, outputs addresses either by direct
 * selection or by selection via groups or a combination of both
 */
class LegacyPluginController extends AbstractPlugin
{
    /**
     * @var string
     */
    public $prefixId = 'tx_ttaddress_pi1';

    /**
     * @var string
     */
    public $extKey = 'tt_address';

    /**
     * @var bool
     */
    public $pi_checkCHash = true;

    /**
     * @var array
     */
    public $conf;

    /**
     * @var array
     */
    protected $ffData;

    /**
     * @var MarkerBasedTemplateService
     */
    protected $templateService;

    /**
     * main method which controls the data flow and outputs the addresses
     *
     * @param string $content Content string, empty
     * @param array $conf Configuration array with TS configuration
     * @return string The processed addresses
     */
    public function main($content, $conf)
    {
        $this->templateService = GeneralUtility::makeInstance(MarkerBasedTemplateService::class);
        $this->init($conf);
        $singleSelection = $this->getSingleRecords();
        $groupSelection = $this->getRecordsFromGroups();

        $templateCode = $this->getTemplate();

        // apply sorting
        $addresses = $this->sortAddresses($singleSelection, $groupSelection);

        // limit output to max listMaxItems addresses
        if (((int)$this->conf['listMaxItems']) > 0) {
            $addresses = array_slice($addresses, 0, (int)$this->conf['listMaxItems']);
        }

        // output
        foreach ($addresses as $address) {
            if (!empty($address)) {
                $markerArray = $this->getItemMarkerArray($address);
                $subpartArray = $this->getSubpartArray($templateCode, $markerArray, $address);

                $addressContent = $this->templateService->substituteMarkerArrayCached(
                    $templateCode,
                    $markerArray,
                    $subpartArray
                );

                $wrap = $this->conf['templates.'][$this->conf['templateName'] . '.']['wrap'];
                $content .= $this->cObj->wrap($addressContent, $wrap);
                $content .= LF . LF;
            }
        }

        $templateAllWrap = $this->conf['templates.'][$this->conf['templateName'] . '.']['allWrap'];
        $content = $this->cObj->wrap($content, $templateAllWrap);

        $content = $this->cObj->wrap($content, $this->conf['wrap']);

        return $this->pi_wrapInBaseClass($content);
    }

    /**
     * initializes the configuration for the plugin and gets the settings from
     * the flexform
     *
     * @param array $conf Array with TS configuration
     */
    protected function init($conf)
    {
        $this->conf = $conf;
        $this->pi_setPiVarDefaults();
        $this->pi_loadLL('EXT:tt_address/Resources/Private/Language/locallang_pi1.xlf');
        $this->pi_initPIflexForm();

        // flexform data
        $flexKeyMapping = [
            'sDEF.singleRecords' => 'singleRecords',
            'sDEF.groupSelection' => 'groupSelection',
            'sDEF.combination' => 'combination',
            'sDEF.sortBy' => 'sortBy',
            'sDEF.sortOrder' => 'sortOrder',
            'sDEF.pages' => 'pages',
            'sDEF.recursive' => 'recursive',
            'sDISPLAY.templateFile' => 'templateFile',
        ];
        $this->ffData = $this->getFlexFormConfig($flexKeyMapping);

        //set default combination to AND if no combination set
        $combination = 'AND';
        if (!empty($this->ffData['combination']) || !empty($this->conf['combination'])) {
            // 0 and '0' are considered empty, therefore anything else means 1/true => OR
            $combination = 'OR';
        }
        $this->conf['combination'] = $combination;

        // check sorting, priorize FlexForm configuration over TypoScript
        if ($this->ffData['sortBy'] && $this->ffData['sortBy'] !== 'default') {
            // sortBy from FlexForm overrides TypoScript configuration
            $this->conf['sortByColumn'] = $this->ffData['sortBy'];
        }

        // check sorting column for validity, use default column "name" if column is invalid or not set
        $this->conf['sortByColumn'] = $this->checkSorting($this->conf['sortByColumn']);

        //set sorting, set to ASC if not valid
        $sortOrder = $this->ffData['sortOrder'] ?: $this->conf['sortOrder'];
        $this->conf['sortOrder'] = strtoupper($sortOrder) === 'DESC' ? SORT_DESC : SORT_ASC;

        // overwrite TS pidList if set in flexform
        $pages = !empty($this->ffData['pages']) ?:
            trim($this->cObj->stdWrap($this->conf['pidList'], $this->conf['pidList.']));
        $pages = $pages ?
            implode(GeneralUtility::intExplode(',', $pages), ',') :
            $this->getTypoScriptFrontendController()->id;

        $recursive = (int)($this->ffData['recursive'] ?: $this->conf['recursive']);

        $this->conf['pidList'] = $this->pi_getPidList($pages, $recursive);

        $this->conf['singleSelection'] = $this->ffData['singleRecords'] ?:
            $this->cObj->stdWrap($this->conf['singleSelection'], $this->conf['singleSelection.']);

        $this->conf['groupSelection'] = $this->ffData['groupSelection'] ?:
            $this->cObj->stdWrap($this->conf['groupSelection'], $this->conf['groupSelection.']);

        $this->conf['templateName'] = $this->getTemplateName();
    }

    /**
     * gets the records the user selected in the single address selection field
     *
     * @return array Array of addresses with their uids as array keys
     */
    public function getSingleRecords()
    {
        $singleRecords = [];
        $singleRecordUids = GeneralUtility::intExplode(',', $this->conf['singleSelection'], true);
        $pids = GeneralUtility::intExplode(',', $this->conf['pidList'], true);

        if (!empty($singleRecordUids)) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_address');
            $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));
            $queryBuilder
                ->select('*')
                ->from('tt_address')
                ->where(
                    $queryBuilder->expr()->in('uid', $singleRecordUids)
                );

            if (!empty($pids)) {
                $queryBuilder->andWhere($queryBuilder->expr()->in('pid', $pids));
            }

            $result = $queryBuilder->execute();
            while ($address = $result->fetch()) {
                $singleRecords[$address['uid']] = $this->getGroupsForAddress($address);
            }
        }
        return $singleRecords;
    }

    /**
     * gets the addresses which meet the group selection
     *
     * @return array Array of addresses with their uids as array keys
     */
    public function getRecordsFromGroups()
    {
        $groupRecords = [];

        $pageIds = GeneralUtility::intExplode(',', $this->conf['pidList'], true);
        $groups = GeneralUtility::intExplode(',', $this->conf['groupSelection'], true);
        if (!empty($groups) && !empty($this->conf['pidList'])) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_address');
            $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));

            if ($this->conf['combination'] === 'AND') {
                $queryBuilder
                    ->select('tt_address.*', 'COUNT(tt_address.uid) AS c')
                    ->from('tt_address')
                    ->join(
                        'tt_address',
                        'sys_category_record_mm',
                        'sys_category_record_mm',
                        $queryBuilder->expr()->eq(
                            'tt_address.uid',
                            $queryBuilder->quoteIdentifier('sys_category_record_mm.uid_foreign')
                        )
                    )
                    ->join(
                        'sys_category_record_mm',
                        'sys_category',
                        'sys_category',
                        $queryBuilder->expr()->eq(
                            'sys_category.uid',
                            $queryBuilder->quoteIdentifier('sys_category_record_mm.uid_local')
                        )
                    )
                    ->where(
                        $queryBuilder->expr()->in('sys_category_record_mm.uid_local', $groups),
                        $queryBuilder->expr()->in('tt_address.pid', $pageIds),
                        $queryBuilder->expr()->eq('sys_category_record_mm.fieldname', 'categories'),
                        $queryBuilder->expr()->eq('sys_category_record_mm.tablenames', 'tt_address')
                    )
                    ->groupBy('tt_address.uid')
                    ->having(
                        $queryBuilder->expr()->eq('c', count($groups))
                    );
            } elseif ($this->conf['combination'] === 'OR') {
                $queryBuilder
                    ->select('tt_address.*')
                    ->from('tt_address')
                    ->join(
                        'tt_address',
                        'sys_category_record_mm',
                        'sys_category_record_mm'
                    )
                    ->join(
                        'sys_category_record_mm',
                        'sys_category',
                        'sys_category'
                    )
                    ->where(
                        $queryBuilder->expr()->in('sys_category_record_mm.uid_local', $groups),
                        $queryBuilder->expr()->in('tt_address.pid', $pageIds),
                        $queryBuilder->expr()->eq('sys_category_record_mm.fieldname', 'categories'),
                        $queryBuilder->expr()->eq('sys_category_record_mm.tablenames', 'tt_address')
                    )
                    ->groupBy('tt_address.uid');
            }

            $result = $queryBuilder->execute();
            while ($address = $result->fetch()) {
                $groupRecords[$address['uid']] = $this->getGroupsForAddress($address);
            }
        }

        return $groupRecords;
    }

    /**
     * gets the groups an address record is in
     *
     * @param array $address An address record
     * @return array The address plus its groups
     */
    public function getGroupsForAddress($address)
    {
        $groupTitles = [];

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_category');
        $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));
        $result = $queryBuilder
            ->select('c.*')
            ->from('sys_category', 'c')
            ->join(
                'sys_category',
                'sys_category_record_mm',
                'mm',
                $queryBuilder->expr()->eq(
                    'c.uid',
                    $queryBuilder->quoteIdentifier('mm.uid_local')
                )
            )
            ->where(
                $queryBuilder->expr()->eq('mm.uid_foreign', $queryBuilder->createNamedParameter((int)$address['uid'], \PDO::PARAM_INT)),
                $queryBuilder->expr()->eq('mm.tablenames', 'tt_address'),
                $queryBuilder->expr()->eq('mm.fieldname', 'categories')
            )
            ->orderBy('mm.sorting_foreign')
            ->execute();
        while ($groupRecord = $result->fetch()) {
            if ($this->getTypoScriptFrontendController()->sys_language_content) {
                $groupRecord = $this->getTypoScriptFrontendController()->sys_page->getRecordOverlay('sys_category',
                    $groupRecord, $this->getTypoScriptFrontendController()->sys_language_content);
            }
            if ($groupRecord) {
                $address['groups'][] = $groupRecord;
                $groupTitles[] = $groupRecord['title'];
            }
        }

        $groupList = implode(', ', $groupTitles);
        $address['groupList'] = $groupList;
        return $address;
    }

    /**
     * @param array $singleSelection
     * @param array $groupSelection
     * @return array
     */
    protected function sortAddresses($singleSelection, $groupSelection)
    {
        // merge both arrays so that we do not have any duplicates
        $addresses = $groupSelection + $singleSelection;
        if ($this->conf['sortByColumn'] === 'singleSelection' && empty($groupSelection)) {

            // we want to sort by single selection and only have single record selection
            $sortedAdressesUid = explode(',', $this->conf['singleSelection']);
            $sortedAddresses = [];

            foreach ($sortedAdressesUid as $uid) {
                $sortedAddresses[] = $addresses[$uid];
            }
            $addresses = $sortedAddresses;
        } else {
            // if sortByColumn was set to singleSelection, but we don't have a single selection, switch to default column "name"
            if ($this->conf['sortByColumn'] === 'singleSelection') {
                $this->conf['sortByColumn'] = 'name';
            }

            // sorting the addresses by any other field
            $sortBy = [];
            foreach ($addresses as $k => $v) {
                $sortBy[$k] = $this->normalizeSortingString($v[$this->conf['sortByColumn']]);
            }
            array_multisort($sortBy, $this->conf['sortOrder'], $addresses);
        }

        return $addresses;
    }

    /**
     * puts the fields of an address in markers
     *
     * @param array $address An address record
     * @return array A marker array with filled markers acording to the address given
     */
    protected function getItemMarkerArray($address)
    {
        $markerArray = [];

        //local configuration and local cObj
        $lConf = $this->conf['templates.'][$this->conf['templateName'] . '.'];
        $lcObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $lcObj->data = $address;

        $markerArray['###UID###'] = $address['uid'];

        $markerArray['###GENDER###'] = $lcObj->stdWrap($address['gender'], $lConf['gender.']);
        $markerArray['###NAME###'] = $lcObj->stdWrap($address['name'], $lConf['name.']);
        $markerArray['###FIRSTNAME###'] = $lcObj->stdWrap($address['first_name'], $lConf['first_name.']);
        $markerArray['###MIDDLENAME###'] = $lcObj->stdWrap($address['middle_name'], $lConf['middle_name.']);
        $markerArray['###LASTNAME###'] = $lcObj->stdWrap($address['last_name'], $lConf['last_name.']);
        $markerArray['###TITLE###'] = $lcObj->stdWrap($address['title'], $lConf['title.']);
        $markerArray['###EMAIL###'] = $lcObj->stdWrap($address['email'], $lConf['email.']);
        $markerArray['###PHONE###'] = $lcObj->stdWrap($address['phone'], $lConf['phone.']);
        $markerArray['###FAX###'] = $lcObj->stdWrap($address['fax'], $lConf['fax.']);
        $markerArray['###MOBILE###'] = $lcObj->stdWrap($address['mobile'], $lConf['mobile.']);
        $markerArray['###WWW###'] = $lcObj->stdWrap($address['www'], $lConf['www.']);
        $markerArray['###ADDRESS###'] = $lcObj->stdWrap($address['address'], $lConf['address.']);
        $markerArray['###BUILDING###'] = $lcObj->stdWrap($address['building'], $lConf['building.']);
        $markerArray['###ROOM###'] = $lcObj->stdWrap($address['room'], $lConf['room.']);
        $markerArray['###BIRTHDAY###'] = $lcObj->stdWrap($address['birthday'], $lConf['birthday.']);
        $markerArray['###ORGANIZATION###'] = $lcObj->stdWrap($address['company'], $lConf['organization.']);
        $markerArray['###COMPANY###'] = $markerArray['###ORGANIZATION###']; // alias
        $markerArray['###POSITION###'] = $lcObj->stdWrap($address['position'], $lConf['position.']);
        $markerArray['###CITY###'] = $lcObj->stdWrap($address['city'], $lConf['city.']);
        $markerArray['###ZIP###'] = $lcObj->stdWrap($address['zip'], $lConf['zip.']);
        $markerArray['###REGION###'] = $lcObj->stdWrap($address['region'], $lConf['region.']);
        $markerArray['###COUNTRY###'] = $lcObj->stdWrap($address['country'], $lConf['country.']);
        $markerArray['###DESCRIPTION###'] = $lcObj->stdWrap($address['description'], $lConf['description.']);
        $markerArray['###SKYPE###'] = $lcObj->stdWrap($address['skype'], $lConf['skype.']);
        $markerArray['###TWITTER###'] = $lcObj->stdWrap($address['twitter'], $lConf['twitter.']);
        $markerArray['###FACEBOOK###'] = $lcObj->stdWrap($address['facebook'], $lConf['facebook.']);
        $markerArray['###LINKEDIN###'] = $lcObj->stdWrap($address['linkedin'], $lConf['linkedin.']);
        $markerArray['###MAINGROUP###'] = $lcObj->stdWrap($address['groups'][0]['title'], $lConf['mainGroup.']);
        $markerArray['###GROUPLIST###'] = $lcObj->stdWrap($address['groupList'], $lConf['groupList.']);

        // the image
        $markerArray['###IMAGE###'] = '';
        if (!empty($address['image'])) {
            $filesConf = [
                'references.' => [
                    'uid' => (int)$address['uid'],
                    'table' => 'tt_address',
                    'fieldName' => 'image'
                ],
                'begin' => '0',
                'maxItems' => '1',

                'renderObj' => 'IMAGE',
                'renderObj.' => [
                    'file.' => [
                        'import.' => [
                            'data' => 'file:current:uid_local // file:current:uid'
                        ],
                        'treatIdAsReference' => '1'
                    ],
                    'altText.' => [
                        'data' => 'file:current:alternative'
                    ],
                    'titleText.' => [
                        'data' => 'file:current:title'
                    ]
                ]
            ];
            if (is_array($lConf['image.'])) {
                $filesConf['renderObj.'] = array_merge_recursive($filesConf['renderObj.'], $lConf['image.']);
            }
            for ($filesIndex = 0; $filesIndex < 6; $filesIndex++) {
                $filesConf['begin'] = $filesIndex;
                $markerArray['###IMAGE' . ($filesIndex == 0 ? '' : $filesIndex) . '###'] = $lcObj->cObjGetSingle('FILES',
                    $filesConf);
            }
        } elseif (!empty($lConf['placeholderImage'])) {
            // we have no image, but a default image
            $iConf = $lConf['image.'];
            $iConf['file'] = $lcObj->stdWrap($lConf['placeholderImage'], $lConf['placeholderImage.']);
            $iConf['altText'] = !empty($iConf['altText']) ? $iConf['altText'] : $address['name'];
            $iConf['titleText'] = !empty($iConf['titleText']) ? $iConf['titleText'] : $address['name'];

            $markerArray['###IMAGE###'] = $lcObj->cObjGetSingle('IMAGE', $iConf);
        }

        // adds hook for processing of extra item markers
        if (\is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['tt_address']['extraItemMarkerHook'])) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['tt_address']['extraItemMarkerHook'] as $_classRef) {
                $_procObj = GeneralUtility::makeInstance($_classRef);
                $markerArray = $_procObj->extraItemMarkerProcessor($markerArray, $address, $lConf, $this);
            }
        }

        return $markerArray;
    }

    /**
     * gets the user defined subparts and returns their content as an array
     *
     * @param string $templateCode (HTML) template code
     * @param array $markerArray markers with content
     * @param array $address a tt_address record
     * @return array Array of subparts
     */
    protected function getSubpartArray($templateCode, $markerArray, $address)
    {
        $subpartArray = [];

        if (\is_array($this->conf['templates.'][$this->conf['templateName'] . '.']['subparts.'])) {
            $lcObj = GeneralUtility::makeInstance(ContentObjectRenderer::class); // local cObj
            $lcObj->data = $address;

            foreach ($this->conf['templates.'][$this->conf['templateName'] . '.']['subparts.'] as $spName => $spConf) {
                $spName = '###SUBPART_' . strtoupper(substr($spName, 0, -1)) . '###';

                $spTemplate = $lcObj->getSubpart($templateCode, $spName);
                $content = $lcObj->stdWrap(
                    $lcObj->substituteMarkerArrayCached(
                        $spTemplate,
                        $markerArray
                    ),
                    $spConf
                );

                if (isset($spConf['hasOneOf']) && !$this->hasOneOf($spConf['hasOneOf'], $address)) {
                    $content = '';
                }

                $subpartArray[$spName] = $content;
            }
        }

        return $subpartArray;
    }

    /**
     * gets the filename from the template file without the file extension
     *
     * @return string The file name portion without the file extension
     */
    protected function getTemplateName()
    {
        $templateName = '';
        $templateFile = '';

        if (isset($this->ffData['templateFile'])) {
            $templateFile = $this->ffData['templateFile'];
        } elseif (isset($this->conf['defaultTemplateFileName'])) {
            $templateFile = $this->conf['defaultTemplateFileName'];
        }

        if ($templateFile === $this->conf['defaultTemplateFileName'] ||
            $templateFile === 'default') {
            $templateName = 'default';
        }

        // cutting off the file extension
        if ($templateName !== 'default') {
            $templateName = substr($templateFile, 0, strrpos($templateFile, '.'));
        }

        return $templateName;
    }

    /**
     * gets the html template code from the selected template, extracts the
     * address subpart and returns the html with unreplaced marker
     *
     * @return string html template code
     */
    protected function getTemplate()
    {
        $templateFile = '';
        if (isset($this->ffData['templateFile']) && !empty($this->ffData['templateFile'])) {
            $templateFile = $this->ffData['templateFile'];
        } elseif (isset($this->conf['defaultTemplateFileName'])) {
            $templateFile = $this->conf['defaultTemplateFileName'];
        }

        if ($templateFile === 'default') {
            $templateFile = $this->conf['defaultTemplateFileName'];
        }

        $templateCode = file_get_contents(GeneralUtility::getFileAbsFileName($this->conf['templatePath'] . $templateFile));
        return $this->templateService->getSubpart($templateCode, '###TEMPLATE_ADDRESS###');
    }

    /**
     * checks whether the given sorting criteria is a valid one. If it is valid
     * the given criteria is returned as it was, the default 'name' is
     * returned if the given criteria is not valid
     *
     * @param string $sortBy criteria you want to sort the addresses by
     * @return string the given sorting criteria if it was valid, 'name' otherwise
     */
    protected function checkSorting($sortBy)
    {
        // TODO add all fields from TCA (extract them from TCA) or add a method to add new sorting fields
        $validSortings = [
            'uid',
            'pid',
            'tstamp',
            'name',
            'gender',
            'first_name',
            'middle_name',
            'last_name',
            'title',
            'email',
            'phone',
            'mobile',
            'www',
            'address',
            'building',
            'room',
            'birthday',
            'company',
            'city',
            'zip',
            'region',
            'country',
            'image',
            'fax',
            'description',
            'singleSelection'
        ];

        if (!\in_array($sortBy, $validSortings, true)) {
            $sortBy = 'name';
        }

        return $sortBy;
    }

    /**
     * gets the flexform values as an array like defined by $flexKeyMapping
     *
     * @param array $flexKeyMapping mapping of sheet.flexformFieldName => variable name
     * @return array flexform configuration as an array
     */
    protected function getFlexFormConfig($flexKeyMapping)
    {
        $conf = [];
        foreach ($flexKeyMapping as $sheetField => $confName) {
            list($sheet, $field) = explode('.', $sheetField);
            $conf[$confName] = $this->pi_getFFvalue(
                $this->cObj->data['pi_flexform'],
                $field,
                $sheet
            );
        }
        return $conf;
    }

    /**
     * checks for the 'hasOneOf' constraint, at least one of the fields in
     * $fieldList must not be empty to return true
     *
     * @param string $fieldList comma separated list of field names to check
     * @param array $address a tt_address record
     * @return bool true if at least one of the given fields is not empty
     */
    protected function hasOneOf($fieldList, $address)
    {
        $checkFields = GeneralUtility::trimExplode(',', $fieldList, true);
        foreach ($checkFields as $fieldName) {
            if (!empty($address[$fieldName])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Removes whitespaces, hyphens and replaces umlauts to allow a correct
     * sorting with multisort.
     *
     * @param mixed $value : value to clean
     * @return string cleaned value
     */
    protected function normalizeSortingString($value)
    {
        if (!is_string($value)) {
            // return if value is not a string
            return $value;
        }

        $value = mb_strtolower($value, 'utf-8');
        $value = preg_replace("/\s+/", '', $value); // remove whitespace
        $value = preg_replace('/-/', '', $value); // remove hyphens e.g. from double names
        $value = preg_replace('/ü/', 'u', $value); // remove umlauts
        $value = preg_replace('/ä/', 'a', $value);
        $value = preg_replace('/ö/', 'o', $value);
        $value = preg_replace('/ë/', 'e', $value);
        $value = preg_replace('/é/', 'e', $value);
        $value = preg_replace('/è/', 'e', $value);
        $value = preg_replace('/ç/', 'c', $value);
        return $value;
    }

    /**
     * @return TypoScriptFrontendController
     */
    protected function getTypoScriptFrontendController()
    {
        return $GLOBALS['TSFE'];
    }
}
