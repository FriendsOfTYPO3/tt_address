<?php
namespace TYPO3\TtAddress\Updates;

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

use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Install\Updates\AbstractUpdate;

/**
 * Migrate "tt_address" static template location of Legacy plugin from static/pi1 to Configuration/TypoScript/LegacyPlugin
 */
class TypoScriptTemplateLocation extends AbstractUpdate
{
    /**
     * @var string
     */
    protected $title = 'Migrate "tt_address" static template location';

    protected $oldLocation = 'EXT:tt_address/static/pi1';
    protected $newLocation = 'EXT:tt_address/Configuration/TypoScript/LegacyPlugin';

    /**
     * Checks if an update is needed
     *
     * @param string &$description The description for the update
     * @return bool Whether an update is needed (TRUE) or not (FALSE)
     */
    public function checkForUpdate(&$description)
    {
        if ($this->isWizardDone()) {
            return false;
        }

        $affectedRows = $this->getDatabaseConnection()->exec_SELECTcountRows(
            'uid',
            'sys_template',
            'deleted=0 AND'
            . ' (constants LIKE "%' . $this->getDatabaseConnection()->escapeStrForLike($this->oldLocation, 'sys_template') . '%"'
            . ' OR config LIKE "%' . $this->getDatabaseConnection()->escapeStrForLike($this->oldLocation, 'sys_template') . '%"'
            . ' OR include_static_file LIKE "%' . $this->getDatabaseConnection()->escapeStrForLike($this->oldLocation, 'sys_template') . '%")'
        );
        if ($affectedRows) {
            $description = 'tt_address\' Static templates have been relocated to EXT:tt_address/Configuration/TypoScript/LegacyPlugin';
        }
        return (bool)$affectedRows;
    }

    /**
     * Performs the database update
     *
     * @param array &$databaseQueries Queries done in this update
     * @param string &$customMessage Custom message
     * @return bool
     */
    public function performUpdate(array &$databaseQueries, &$customMessage)
    {
        $records = $this->getDatabaseConnection()->exec_SELECTgetRows(
            'uid, include_static_file, constants, config',
            'sys_template',
            'deleted=0 AND'
            . ' (constants LIKE "%' . $this->getDatabaseConnection()->escapeStrForLike($this->oldLocation, 'sys_template') . '%"'
            . ' OR config LIKE "%' . $this->getDatabaseConnection()->escapeStrForLike($this->oldLocation, 'sys_template') . '%"'
            . ' OR include_static_file LIKE "%' . $this->getDatabaseConnection()->escapeStrForLike($this->oldLocation, 'sys_template') . '%")'
        );

        foreach ($records as $record) {
            $newData = [
                'include_static_file' => str_replace($this->oldLocation, $this->newLocation, $record['include_static_file']),
                'constants' => str_replace($this->oldLocation, $this->newLocation, $record['constants']),
                'config' => str_replace($this->oldLocation, $this->newLocation, $record['config'])
            ];

            $updateQuery = $this->getDatabaseConnection()->UPDATEquery(
                'sys_template',
                'uid=' . (int)$record['uid'],
                $newData
            );

            $this->getDatabaseConnection()->sql_query($updateQuery);

            $customMessage = 'Updated sys_template ' . $record['uid'] . '';
            $databaseQueries[] = $updateQuery;
        }
        $this->markWizardAsDone();
        return true;
    }

    /**
     * @return DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
