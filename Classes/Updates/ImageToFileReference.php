<?php

namespace TYPO3\TtAddress\Updates;

/**
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
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\AbstractUpdate;

class ImageToFileReference extends AbstractUpdate
{
    const FOLDER_ContentUploads = '_migrated/tt_address';

    /**
     * @var string
     */
    protected $title = 'Migrate image relations of EXT:tt_address';

    /**
     * @var string
     */
    protected $targetDirectory;

    /**
     * @var \TYPO3\CMS\Core\Resource\ResourceFactory
     */
    protected $fileFactory;

    /**
     * @var \TYPO3\CMS\Core\Resource\FileRepository
     */
    protected $fileRepository;

    /**
     * @var \TYPO3\CMS\Core\Resource\ResourceStorage
     */
    protected $storage;

    /**
     * Initialize all required repository and factory objects.
     *
     * @throws \RuntimeException
     */
    protected function init()
    {
        $fileadminDirectory = rtrim($GLOBALS['TYPO3_CONF_VARS']['BE']['fileadminDir'], '/') . '/';
        /** @var $storageRepository \TYPO3\CMS\Core\Resource\StorageRepository */
        $storageRepository = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\StorageRepository');
        $storages = $storageRepository->findAll();
        foreach ($storages as $storage) {
            $storageRecord = $storage->getStorageRecord();
            $configuration = $storage->getConfiguration();
            $isLocalDriver = $storageRecord['driver'] === 'Local';
            $isOnFileadmin = !empty($configuration['basePath']) && GeneralUtility::isFirstPartOfStr($configuration['basePath'], $fileadminDirectory);
            if ($isLocalDriver && $isOnFileadmin) {
                $this->storage = $storage;
                break;
            }
        }
        if (!isset($this->storage)) {
            throw new \RuntimeException('Local default storage could not be initialized - might be due to missing sys_file* tables.');
        }
        $this->fileFactory = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\ResourceFactory');
        $this->fileRepository = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\FileRepository');
        $this->targetDirectory = PATH_site . $fileadminDirectory . self::FOLDER_ContentUploads . '/';
    }

    /**
     * Checks if an update is needed
     *
     * @param string &$description : The description for the update
     * @return bool TRUE if an update is needed, FALSE otherwise
     */
    public function checkForUpdate(&$description)
    {
        $notMigratedImageRowsCount = $this->getDatabaseConnection()->exec_SELECTcountRows(
            '*',
            'tt_address',
            $this->getWhereClauseForImagesToUpdate()
        );
        if ($notMigratedImageRowsCount > 0) {
            $description = 'There are <strong>' . $notMigratedImageRowsCount . '</strong> addresses which are using the old image relation. '
                . 'This wizard will copy the files to "fileadmin/' . self::FOLDER_ContentUploads . '".'
                . '<br /><br /><strong>Important:</strong> The <strong>first</strong> local storage inside "' . $GLOBALS['TYPO3_CONF_VARS']['BE']['fileadminDir']
                . '" will be used for the migration. If you have multiple storages, only enable the one which should be used for the migration.';

            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    protected function getWhereClauseForImagesToUpdate()
    {
        return 'tt_address.image <> \'\' AND CAST(CAST(tt_address.image AS DECIMAL) AS CHAR) <> CAST(tt_address.image AS CHAR) AND tt_address.deleted=0';
    }

    /**
     * Performs the database update.
     *
     * @param array &$dbQueries Queries done in this update
     * @param mixed &$customMessages Custom messages
     * @return bool TRUE on success, FALSE on error
     */
    public function performUpdate(array &$dbQueries, &$customMessages)
    {
        $this->init();
        $this->checkPrerequisites();

        $addresses = $this->getDatabaseConnection()->exec_SELECTgetRows(
            '*',
            'tt_address',
            $this->getWhereClauseForImagesToUpdate()
        );
        if ($addresses) {
            foreach ($addresses as $address) {
                $this->migrateImages($address);
            }
        }

        return true;
    }

    /**
     * Ensures a new folder "fileadmin/content_upload/" is available.
     *
     * @return void
     */
    protected function checkPrerequisites()
    {
        if (!$this->storage->hasFolder(self::FOLDER_ContentUploads)) {
            $this->storage->createFolder(self::FOLDER_ContentUploads, $this->storage->getRootLevelFolder());
        }
    }

    /**
     * Processes the actual transformation to sys_file_references
     *
     * @param array $address
     *
     * @return void
     */
    protected function migrateImages(array $address)
    {
        if ($address['image']) {
            $images = GeneralUtility::trimExplode(',', $address['image']);
            $imageCount = 0;
            foreach ($images as $image) {
                if (!empty($image) && file_exists(PATH_site . 'uploads/pics/' . $image)) {
                    GeneralUtility::upload_copy_move(
                        PATH_site . 'uploads/pics/' . $image,
                        $this->targetDirectory . $image);

                    $fileObject = $this->storage->getFile(self::FOLDER_ContentUploads . '/' . $image);
                    if ($fileObject instanceof File) {
                        $this->fileRepository->add($fileObject);
                        $dataArray = array(
                            'uid_local' => $fileObject->getUid(),
                            'tablenames' => 'tt_address',
                            'fieldname' => 'image',
                            'uid_foreign' => $address['uid'],
                            'table_local' => 'sys_file',
                            'cruser_id' => 999,
                            'pid' => $address['pid'],
                            'sorting_foreign' => $imageCount,
                            'hidden' => $address['hidden'],
                            'sys_language_uid' => 0
                        );

                        if ($this->getDatabaseConnection()->exec_INSERTquery('sys_file_reference', $dataArray)) {
                            $imageCount++;
                        }
                    }
                }
            }

            $this->getDatabaseConnection()->exec_UPDATEquery(
                'tt_address',
                'uid = ' . (int)$address['uid'],
                array('image' => $imageCount)
            );
        }
    }

    /**
     * @return DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
