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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\AbstractUpdate;

/**
 * Class that migrates all tt_address_group records to sys_category records
 */
class AddressGroupToSysCategory extends AbstractUpdate
{
    const OLD_MM_TABLE = 'tt_address_group_mm';
    const OLD_GROUP_TABLE = 'tt_address_group';

    protected $messageArray;

    protected $title = 'EXT:tt_address Migrate tt_address_group to sys_category';

    /**
     * Checks whether updates are required.
     *
     * @param string &$description The description for the update
     * @return bool Whether an update is required (TRUE) or not (FALSE)
     */
    public function checkForUpdate(&$description)
    {
        $status = false;

        $databaseTables = $this->getDatabaseConnection()->admin_get_tables();
        if (!isset($databaseTables[self::OLD_MM_TABLE])) {
            $description = sprintf('The database table "%s" does not exist, nothing to do!', self::OLD_MM_TABLE);
        } else {
            $countRows = $this->getDatabaseConnection()->exec_SELECTcountRows('*', self::OLD_MM_TABLE, '1=1');
            if ($countRows === 0) {
                $description = sprintf('The database table "%s" is empty, nothing to do', self::OLD_MM_TABLE);
            } else {
                $description = sprintf('The database table "%s" contains <strong>%s</strong> entries which will be migrated!',
                    self::OLD_MM_TABLE, $countRows);
                $status = true;
            }
        }

        return $status;
    }

    /**
     * Performs the accordant updates.
     *
     * @param array &$dbQueries Queries done in this update
     * @param mixed &$customMessages Custom messages
     * @return bool Whether everything went smoothly or not
     */
    public function performUpdate(array &$dbQueries, &$customMessages)
    {

        // check if tt_address_group still exists
        $oldGroupTableFields = $this->getDatabaseConnection()->admin_get_fields(self::OLD_GROUP_TABLE);
        if (count($oldGroupTableFields) === 0) {
            $customMessages = 'Old group table does not exist anymore so no update needed.';
            return false;
        }

        // check if there are groups present else no update is needed
        $oldGroupCount = $this->getDatabaseConnection()->exec_SELECTcountRows(
            'uid',
            self::OLD_GROUP_TABLE,
            'deleted = 0'
        );

        if ($oldGroupCount === 0) {
            $customMessages = 'Old groups found so no update needed.';
            return false;
        }

        // A temporary migration column is needed in old category table. Add this when not already present
        if (!array_key_exists('migrate_sys_category_uid', $oldGroupTableFields)) {
            $this->getDatabaseConnection()->admin_query(
                'ALTER TABLE ' . self::OLD_GROUP_TABLE . " ADD migrate_sys_category_uid int(11) DEFAULT '0' NOT NULL"
            );
        }
        // convert tt_address_group records
        $result = $this->migrateAddressGroupRecords();
        if (!$result) {
            $customMessages = implode('<br />', $this->messageArray);
            return false;
        }

        // set/update all relations
        $oldNewCategoryUidMapping = $this->getOldNewCategoryUidMapping();
        $this->updateParentFieldOfMigratedCategories($oldNewCategoryUidMapping);
        $this->migrateCategoryMmRecords($oldNewCategoryUidMapping);
        $this->updateFlexformCategories('tt_address_pi1', $oldNewCategoryUidMapping, 'groupSelection');

        /**
         * Finished category migration
         */
        return true;
    }

    /**
     * Process not yet migrated tt_address group records to sys_category records
     */
    protected function migrateAddressGroupRecords()
    {

        // migrate default language category records
        $rows = $this->getDatabaseConnection()->exec_SELECTgetRows(
            'uid, pid, tstamp, crdate, cruser_id, sorting, ' .
            'sys_language_uid, l18n_parent, l18n_diffsource, ' .
            'title, description',
            self::OLD_GROUP_TABLE,
            'migrate_sys_category_uid = 0 AND deleted = 0 AND sys_language_uid = 0'
        );

        if ($this->getDatabaseConnection()->sql_error()) {
            $this->messageArray[] = 'Failed selecting old default language group records';
            return false;
        }

        // Create a new sys_category record for each found record in default language
        $newCategoryRecords = 0;

        $oldNewDefaultLanguageCategoryUidMapping = array();
        foreach ($rows as $row) {
            $oldUid = $row['uid'];
            unset($row['uid']);

            // l10n_diffsource was falsely named l18n_diffsource in tt_address_group
            if (is_null($row['l18n_diffsource'])) {
                $row['l10n_diffsource'] = '';
            } else {
                $row['l10n_diffsource'] = $row['l18n_diffsource'];
            }
            unset($row['l18n_diffsource']);

            // l10n_parent was falsely named l18n_parent in tt_address_group
            $row['l10n_parent'] = 0;
            unset($row['l18n_parent']);

            if (is_null($row['description'])) {
                $row['description'] = '';
            }

            if ($this->getDatabaseConnection()->exec_INSERTquery('sys_category', $row) !== false) {
                $newUid = $this->getDatabaseConnection()->sql_insert_id();
                $oldNewDefaultLanguageCategoryUidMapping[$oldUid] = $newUid;
                $this->getDatabaseConnection()->exec_UPDATEquery(
                    self::OLD_GROUP_TABLE,
                    'uid=' . $oldUid,
                    array('migrate_sys_category_uid' => $newUid)
                );
                $newCategoryRecords++;
            } else {
                $this->messageArray[] = 'Failed copying [' . $oldUid . '] ' . htmlspecialchars($row['title']) . ' to sys_category';
                return false;
            }
        }

        // migrate non-default language category records
        $rows = $this->getDatabaseConnection()->exec_SELECTgetRows(
            'uid, pid, tstamp, crdate, cruser_id, sorting, ' .
            'sys_language_uid, l18n_parent, l18n_diffsource, ' .
            'title, description',
            self::OLD_GROUP_TABLE,
            'migrate_sys_category_uid = 0 AND deleted = 0 AND sys_language_uid != 0'
        );

        if ($this->getDatabaseConnection()->sql_error()) {
            $this->messageArray[] = 'Failed selecting old non-default language group records';
            return false;
        }

        foreach ($rows as $row) {
            $oldUid = $row['uid'];
            unset($row['uid']);

            // l10n_diffsource was falsely named l18n_diffsource in tt_address_group
            if (is_null($row['l18n_diffsource'])) {
                $row['l10n_diffsource'] = '';
            } else {
                $row['l10n_diffsource'] = $row['l18n_diffsource'];
            }
            unset($row['l18n_diffsource']);

            // l10n_parent was falsely named l18n_parent in tt_address_group
            $row['l10n_parent'] = 0;
            unset($row['l18n_parent']);

            if (is_null($row['description'])) {
                $row['description'] = '';
            }
            // set l10n_parent if category is a localized version
            if (array_key_exists($row['l10n_parent'], $oldNewDefaultLanguageCategoryUidMapping)) {
                $row['l10n_parent'] = $oldNewDefaultLanguageCategoryUidMapping[$row['l10n_parent']];
            }

            if ($this->getDatabaseConnection()->exec_INSERTquery('sys_category', $row) !== false) {
                $newUid = $this->getDatabaseConnection()->sql_insert_id();
                $oldNewDefaultLanguageCategoryUidMapping[$oldUid] = $newUid;
                $this->getDatabaseConnection()->exec_UPDATEquery(
                    self::OLD_GROUP_TABLE,
                    'uid=' . $oldUid,
                    array('migrate_sys_category_uid' => $newUid)
                );
                $newCategoryRecords++;
            } else {
                $this->messageArray[] = 'Failed copying [' . $oldUid . '] ' . htmlspecialchars($row['title']) . ' to sys_category';
                return false;
            }
        }
        return true;
    }

    /**
     * Create a mapping array of old->new category uids
     *
     * @return array
     */
    protected function getOldNewCategoryUidMapping()
    {
        $rows = $this->getDatabaseConnection()->exec_SELECTgetRows(
            'uid, migrate_sys_category_uid',
            self::OLD_GROUP_TABLE,
            'migrate_sys_category_uid > 0'
        );

        $oldNewCategoryUidMapping = array();
        foreach ($rows as $row) {
            $oldNewCategoryUidMapping[$row['uid']] = $row['migrate_sys_category_uid'];
        }

        return $oldNewCategoryUidMapping;
    }

    /**
     * Update parent column of migrated categories
     *
     * @param array $oldNewCategoryUidMapping
     * @return bool
     */
    protected function updateParentFieldOfMigratedCategories(array $oldNewCategoryUidMapping)
    {
        $updatedRecords = 0;
        $toUpdate = $this->getDatabaseConnection()->exec_SELECTgetRows('uid, parent_group',
            self::OLD_GROUP_TABLE, 'parent_group > 0');
        foreach ($toUpdate as $row) {
            if (!empty($oldNewCategoryUidMapping[$row['parent_group']])) {
                $sysCategoryUid = $oldNewCategoryUidMapping[$row['uid']];
                $newParentUid = $oldNewCategoryUidMapping[$row['parent_group']];
                $this->getDatabaseConnection()->exec_UPDATEquery('sys_category', 'uid=' . $sysCategoryUid,
                    array('parent' => $newParentUid));
                $updatedRecords++;
            }
        }
        return true;
    }

    /**
     * Create new category MM records
     *
     * @param array $oldNewCategoryUidMapping
     * @return bool
     */
    protected function migrateCategoryMmRecords(array $oldNewCategoryUidMapping)
    {
        $newMmCount = 0;
        $oldMmRecords = $this->getDatabaseConnection()->exec_SELECTgetRows('uid_local, uid_foreign, tablenames, sorting',
            self::OLD_MM_TABLE, '');
        foreach ($oldMmRecords as $oldMmRecord) {
            $oldCategoryUid = $oldMmRecord['uid_foreign'];

            if (!empty($oldNewCategoryUidMapping[$oldCategoryUid])) {
                $newMmRecord = array(
                    'uid_local' => (int)$oldNewCategoryUidMapping[$oldCategoryUid],
                    'uid_foreign' => $oldMmRecord['uid_local'],
                    'tablenames' => $oldMmRecord['tablenames'] ?: 'tt_address',
                    'sorting_foreign' => $oldMmRecord['sorting'],
                    'fieldname' => 'categories',
                );

                // check if relation already exists
                $foundRelations = $this->getDatabaseConnection()->exec_SELECTcountRows(
                    'uid_local',
                    'sys_category_record_mm',
                    'uid_local=' . $newMmRecord['uid_local'] .
                    ' AND uid_foreign=' . $newMmRecord['uid_foreign'] .
                    ' AND tablenames="' . $newMmRecord['tablenames'] . '"' .
                    ' AND fieldname="' . $newMmRecord['fieldname'] . '"'
                );

                if ($foundRelations === 0) {
                    $this->getDatabaseConnection()->exec_INSERTquery('sys_category_record_mm', $newMmRecord);
                    if ($this->getDatabaseConnection()->sql_affected_rows()) {
                        $newMmCount++;
                    }
                }
            }
        }

        $this->messageArray[] = 'Created ' . $newMmCount . ' new MM relations';
        return true;
    }

    /**
     * Update categories in flexforms
     *
     * @param string $pluginName
     * @param array $oldNewCategoryUidMapping
     * @param string $flexformField name of the flexform's field to look for
     * @return bool
     */
    protected function updateFlexformCategories($pluginName, $oldNewCategoryUidMapping, $flexformField)
    {
        $count = 0;
        $title = 'Update flexforms categories (' . $pluginName . ':' . $flexformField . ')';
        $res = $this->getDatabaseConnection()->exec_SELECTquery('uid, pi_flexform',
            'tt_content',
            'CType="list" AND list_type=' . $this->getDatabaseConnection()->fullQuoteStr($pluginName, 'tt_content') . ' AND deleted=0');

        /** @var \TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools $flexformTools */
        $flexformTools = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Configuration\\FlexForm\\FlexFormTools');

        while ($row = $this->getDatabaseConnection()->sql_fetch_assoc($res)) {
            $status = null;
            $xmlArray = GeneralUtility::xml2array($row['pi_flexform']);

            if (!is_array($xmlArray) || !isset($xmlArray['data'])) {
                $this->messageArray[] = 'Flexform data of plugin "' . $pluginName . '" not found.';
            } elseif (!isset($xmlArray['data']['sDEF']['lDEF'])) {
                $this->messageArray[] = 'Flexform data of record tt_content:' . $row['uid'] . ' did not contain sheet: sDEF';
            } elseif (isset($xmlArray[$flexformField . '_updated'])) {
                $this->messageArray[] = 'Flexform data of record tt_content:' . $row['uid'] . ' is already updated for ' . $flexformField . '. No update needed...';
            } else {
                // Some flexforms may have displayCond
                if (isset($xmlArray['data']['sDEF']['lDEF'][$flexformField]['vDEF'])) {
                    $updated = false;
                    $oldCategories = GeneralUtility::trimExplode(',',
                        $xmlArray['data']['sDEF']['lDEF'][$flexformField]['vDEF'], true);

                    if (!empty($oldCategories)) {
                        $newCategories = array();

                        foreach ($oldCategories as $uid) {
                            if (isset($oldNewCategoryUidMapping[$uid])) {
                                $newCategories[] = $oldNewCategoryUidMapping[$uid];
                                $updated = true;
                            } else {
                                $this->messageArray[] = 'The category ' . $uid . ' of record tt_content:' . $row['uid'] . ' was not found in sys_category records. Maybe the category was deleted before the migration? Please check manually...';
                            }
                        }

                        if ($updated) {
                            $count++;
                            $xmlArray[$flexformField . '_updated'] = 1;
                            $xmlArray['data']['sDEF']['lDEF'][$flexformField]['vDEF'] = implode(',', $newCategories);
                            $this->getDatabaseConnection()->exec_UPDATEquery('tt_content', 'uid=' . (int)$row['uid'], array(
                                'pi_flexform' => $flexformTools->flexArray2Xml($xmlArray)
                            ));
                        }
                    }
                }
            }
        }
        $this->messageArray[] = 'Updated ' . $count . ' tt_content flexforms for  "' . $pluginName . ':' . $flexformField . '"';
    }

    /**
     * @return DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
