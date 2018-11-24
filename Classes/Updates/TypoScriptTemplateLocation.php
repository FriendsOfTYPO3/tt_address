<?php

namespace FriendsOfTYPO3\TtAddress\Updates;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
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

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_template');
        $queryBuilder->getRestrictions()->removeAll();
        $affectedRows = $queryBuilder->count('uid')
            ->from('sys_template')
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like(
                        'constants',
                        $queryBuilder->createNamedParameter('%' . $this->oldLocation . '%', \PDO::PARAM_STR)
                    ),
                    $queryBuilder->expr()->like(
                        'config',
                        $queryBuilder->createNamedParameter('%' . $this->oldLocation . '%', \PDO::PARAM_STR)
                    ),
                    $queryBuilder->expr()->like(
                        'include_static_file',
                        $queryBuilder->createNamedParameter('%' . $this->oldLocation . '%', \PDO::PARAM_STR)
                    )
                )
            )
            ->execute()->fetchColumn(0);

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
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('sys_template');

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_template');
        $queryBuilder->getRestrictions()->removeAll();
        $records = $queryBuilder
            ->select('uid', 'include_static_file', 'constants', 'config')
            ->from('sys_template')
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like(
                        'constants',
                        $queryBuilder->createNamedParameter('%' . $this->oldLocation . '%', \PDO::PARAM_STR)
                    ),
                    $queryBuilder->expr()->like(
                        'config',
                        $queryBuilder->createNamedParameter('%' . $this->oldLocation . '%', \PDO::PARAM_STR)
                    ),
                    $queryBuilder->expr()->like(
                        'include_static_file',
                        $queryBuilder->createNamedParameter('%' . $this->oldLocation . '%', \PDO::PARAM_STR)
                    )
                )
            )
            ->execute()
            ->fetchAll();

        foreach ($records as $record) {
            $record = [
                'include_static_file' => str_replace($this->oldLocation, $this->newLocation, $record['include_static_file']),
                'constants' => str_replace($this->oldLocation, $this->newLocation, $record['constants']),
                'config' => str_replace($this->oldLocation, $this->newLocation, $record['config'])
            ];

            $queryBuilder = $connection->createQueryBuilder();
            $queryBuilder->update('sys_template')
                ->where(
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter($record['uid'], \PDO::PARAM_INT)
                    )
                )
                ->set('include_static_file', $record['include_static_file'])
                ->set('constants', $record['constants'])
                ->set('config', $record['config']);
            $queryBuilder->execute();

            $customMessage = 'Updated sys_template ' . $record['uid'] . '';
        }
        $this->markWizardAsDone();
        return true;
    }

}
