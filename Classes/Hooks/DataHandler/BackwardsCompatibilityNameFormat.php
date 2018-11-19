<?php

namespace FriendsOfTYPO3\TtAddress\Hooks\DataHandler;

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

use FriendsOfTYPO3\TtAddress\Domain\Model\Dto\Settings;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class BackwardsCompatibilityNameFormat
 */
class BackwardsCompatibilityNameFormat
{

    /** @var Settings */
    protected $settings;

    public function __construct()
    {
        $this->settings = GeneralUtility::makeInstance(Settings::class);
    }

    /**
     * looks for tt_address records with changes to the first, middle, and
     * last name fields to come by. This function will then write changes back
     * to the old combined name field in a configurable format
     *
     * @param string $status action status: new/update is relevant for us
     * @param string $table db table
     * @param int $id record uid
     * @param array $fieldArray record
     */
    public function processDatamap_postProcessFieldArray($status, $table, $id, &$fieldArray)
    {
        if ($table === 'tt_address' && ($status === 'new' || $status === 'update')) {

            if ($this->settings->isStoreBackwardsCompatName()) {
                $address = $status === 'update' ? $this->getRecord($id) : $fieldArray;

                $newRecord = array_merge($address, $fieldArray);

                $combinedName = trim(sprintf(
                    $this->settings->getBackwardsCompatFormat(),
                    $newRecord['first_name'],
                    $newRecord['middle_name'],
                    $newRecord['last_name']
                ));

                if (!empty($combinedName)) {
                    $fieldArray['name'] = $combinedName;
                }
            }
        }
    }

    /**
     * @param int $id
     * @return array
     */
    protected function getRecord(int $id): array
    {
        return BackendUtility::getRecord('tt_address', $id);
    }
}
