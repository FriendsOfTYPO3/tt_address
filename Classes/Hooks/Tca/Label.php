<?php
declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\Hooks\Tca;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Dynamic label of the address record based on tsconfig
 */
class Label
{
    private const FALLBACK = [
        ['last_name', 'first_name'],
        ['email'],
    ];

    public function getAddressLabel(array &$params): void
    {
        if (!($params['row']['pid'] ?? 0)) {
            return;
        }

        if (is_numeric($params['row']['uid'])) {
            $row = BackendUtility::getRecordWSOL('tt_address', (int) $params['row']['uid']);
        } else {
            $row = $params['row'];
        }

        // record might be in deleting process
        if (!$row) {
            return;
        }

        $configuration = $this->getConfiguration((int) $row['pid']);
        if (!$configuration) {
            return;
        }

        foreach ($configuration as $fieldList) {
            $label = [];
            foreach ($fieldList as $field) {
                if (isset($row['uid'], $row[$field]) && !empty($row[$field])) {
                    $label[] = BackendUtility::getProcessedValue('tt_address', $field, $row[$field], 0, false, 0, $row['uid']);
                }
            }
            if (!empty($label)) {
                $params['title'] = implode(', ', $label);
                return;
            }
        }
    }

    protected function getConfiguration(int $pid): array
    {
        $labelConfiguration = BackendUtility::getPagesTSconfig($pid)['tt_address.']['label'] ?? '';
        if (!$labelConfiguration) {
            return self::FALLBACK;
        }

        $configuration = [];
        $options = GeneralUtility::trimExplode(';', $labelConfiguration, true);
        foreach ($options as $option) {
            $configuration[] = GeneralUtility::trimExplode(',', $option, true);
        }
        return $configuration;
    }
}
