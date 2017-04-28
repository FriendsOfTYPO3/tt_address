<?php
namespace TYPO3\TtAddress\Hooks\DataHandler;

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

use TYPO3\TtAddress\Utility\SettingsUtility;

/**
 * Class BackwardsCompatibilityNameFormat
 */
class BackwardsCompatibilityNameFormat
{
    /**
     * looks for tt_address records with changes to the first, middle, and
     * last name fields to come by. This function will then write changes back
     * to the old combined name field in a configurable format
     *
     * @param string $status action status: new/update is relevant for us
     * @param string $table db table
     * @param int $id record uid
     * @param array $fieldArray record
     * @param object $pObj parent object
     * @return void
     */
    public function processDatamap_postProcessFieldArray($status, $table, $id, &$fieldArray, $pObj)
    {
        if ($table == 'tt_address' && ($status == 'new' || $status == 'update')) {
            $settings = SettingsUtility::getSettings();
            if ($settings->isStoreBackwardsCompatName()) {
                if ($status == 'update') {
                    $address = $this->getFullRecord($id);
                } else {
                    $address = $fieldArray;
                }

                $format = $settings->getBackwardsCompatFormat();

                $newRecord = array_merge($address, $fieldArray);

                $combinedName = trim(sprintf(
                    $format,
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
     * gets a full tt_address record
     *
     * @param int $uid unique id of the tt_address record to get
     * @return array full tt_address record with associative keys
     */
    protected function getFullRecord($uid)
    {
        $row = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
            '*',
            'tt_address',
            'uid = ' . $uid
        );

        return $row[0];
    }
}
