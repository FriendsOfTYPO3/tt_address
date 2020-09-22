<?php
declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\Hooks;

use Doctrine\DBAL\Connection;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\PageLayoutView;
use TYPO3\CMS\Backend\View\PageLayoutViewDrawItemHookInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PageLayoutViewHook implements PageLayoutViewDrawItemHookInterface
{
    protected $recordMapping = [
        'singleRecords' => [
            'table' => 'tt_address',
            'multiValue' => true,
        ],
        'pages' => [
            'table' => 'pages',
            'multiValue' => true,
        ],
        'singlePid' => [
            'table' => 'pages',
            'multiValue' => false,
        ],
        'groups' => [
            'table' => 'sys_category',
            'multiValue' => true,
        ],
    ];

    public function preProcess(PageLayoutView &$parentObject, &$drawItem, &$headerContent, &$itemContent, array &$row)
    {
        if ($row['list_type'] === 'ttaddress_listview' && $row['CType'] === 'list') {
            $row = $this->enrichRow($row);
        }
    }

    protected function enrichRow(array $row): array
    {
        $settings = $this->getFlexFormData($row['pi_flexform'] ?? '');

        foreach ($this->recordMapping as $fieldName => $fieldConfiguration) {
            if ($settings['settings'][$fieldName]) {
                $records = $this->getRecords($fieldConfiguration['table'], $settings['settings'][$fieldName]);

                if ($fieldConfiguration['multiValue']) {
                    $row['_computed'][$fieldName] = $records;
                } else {
                    $row['_computed'][$fieldName] = $records[0] ?: [];
                }
            }
        }
        $row['_computed']['lll'] = 'LLL:EXT:tt_address/Resources/Private/Language/ff/locallang_ff.xlf:';
        return $row;
    }

    protected function getRecords(string $table, string $idList)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        $queryBuilder->getRestrictions()->removeByType(HiddenRestriction::class);

        $rows = $queryBuilder
            ->select('*')
            ->from($table)
            ->where(
                $queryBuilder->expr()->in(
                    'uid',
                    $queryBuilder->createNamedParameter(GeneralUtility::intExplode(',', $idList, true), Connection::PARAM_INT_ARRAY)
                )
            )
            ->execute()
            ->fetchAll();

        foreach ($rows as &$row) {
            $row['_computed']['title'] = BackendUtility::getRecordTitle($table, $row);
        }
        return $rows;
    }

    protected function getFlexFormData(string $flexforms): array
    {
        $settings = [];
        if (!empty($flexforms)) {
            $settings = GeneralUtility::makeInstance(FlexFormService::class)->convertFlexFormContentToArray($flexforms);
        }
        return $settings;
    }
}
