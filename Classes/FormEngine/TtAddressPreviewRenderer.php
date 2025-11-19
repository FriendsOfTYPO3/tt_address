<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\FormEngine;

/*
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Doctrine\DBAL\ArrayParameterType;
use TYPO3\CMS\Backend\Preview\StandardContentPreviewRenderer;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Domain\RecordInterface;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Improve the rendering of the plugin in page module
 */
class TtAddressPreviewRenderer extends StandardContentPreviewRenderer
{
    protected array $recordMapping = [
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

    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        if (class_exists(StandaloneView::class)) {
            $view = GeneralUtility::makeInstance(StandaloneView::class);
            $view->setTemplatePathAndFilename(GeneralUtility::getFileAbsFileName('EXT:tt_address/Resources/Private/Templates/Backend/PluginPreview.html'));
        } else {
            $viewFactoryData = new ViewFactoryData(
                templatePathAndFilename: 'EXT:tt_address/Resources/Private/Templates/Backend/PluginPreview.html'
            );
            $view = GeneralUtility::makeInstance(ViewFactoryInterface::class)->create($viewFactoryData);
        }
        $row = $item->getRecord();
        if ((new Typo3Version())->getMajorVersion() >= 14) {
            /** @var RecordInterface $row */
            $row = $row->getRawRecord()->toArray();
        }

        $view->assignMultiple($this->enrichRow($row));

        return $view->render();
    }

    protected function enrichRow(array $row): array
    {
        $settings = $this->getFlexFormData($row['pi_flexform'] ?? '');
        $row['pi_flexform_transformed'] = $settings;
        foreach ($this->recordMapping as $fieldName => $fieldConfiguration) {
            if ($settings['settings'][$fieldName] ?? false) {
                $records = $this->getRecords($fieldConfiguration['table'], $settings['settings'][$fieldName]);

                if ($fieldConfiguration['multiValue']) {
                    $row['computed'][$fieldName] = $records;
                } else {
                    $row['computed'][$fieldName] = $records[0] ?: [];
                }
            }
        }
        $row['computed']['lll'] = 'LLL:EXT:tt_address/Resources/Private/Language/ff/locallang_ff.xlf:pi1_flexform.';
        return $row;
    }

    protected function getRecords(string $table, string $idList): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        $queryBuilder->getRestrictions()->removeByType(HiddenRestriction::class);

        $rows = $queryBuilder
            ->select('*')
            ->from($table)
            ->where(
                $queryBuilder->expr()->in(
                    'uid',
                    $queryBuilder->createNamedParameter(GeneralUtility::intExplode(',', $idList, true), ArrayParameterType::INTEGER)
                )
            )
            ->executeQuery()
            ->fetchAllAssociative();

        foreach ($rows as &$row) {
            $row['computed']['title'] = BackendUtility::getRecordTitle($table, $row);
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
