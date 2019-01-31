<?php

namespace FriendsOfTYPO3\TtAddress\Updates;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\AbstractUpdate;

/**
 * Migrate "tt_address" legacy plugins to extbase plugins
 */
class MigrateLegacyPlugin extends AbstractUpdate
{
    /**
     * @var string
     */
    protected $title = 'Migrate "tt_address" legacy plugins to extbase plugins';

    protected $configurationKeyMap = [
        'singleRecords' => 'settings.singleRecords',
        'groupSelection' => 'settings.groups',
        'combination' => 'settings.groupsCombination',
        'sortBy' => 'settings.sortBy',
        'sortOrder' => 'settings.sortOrder',
        'pages' => 'settings.pages',
        'recursive' => 'settings.recursive',
        'templateFile' => 'settings.displayMode'
    ];

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

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
        $queryBuilder->getRestrictions()->removeAll();
        $affectedRows = $queryBuilder->count('uid')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq(
                    'list_type',
                    $queryBuilder->createNamedParameter('tt_address_pi1', \PDO::PARAM_STR)
                )
            )
            ->execute()->fetchColumn(0);

        $description = 'The flexform field templateFile is migrated to settings.displayMode. Custom templateFile\'s need to be added in settings.displayMode. Example: When templateFile is "my_template.html" then settings.displayMode is set to "my_template" and the template can be registered like TCEFORM.tt_content.pi_flexform.ttaddress_listview.sDISPLAY.settings\.displayMode.addItems.my_template = my_template';

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
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content');

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
        $queryBuilder->getRestrictions()->removeAll();
        $records = $queryBuilder
            ->select('uid', 'pi_flexform')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq(
                    'list_type',
                    $queryBuilder->createNamedParameter('tt_address_pi1', \PDO::PARAM_STR)
                )
            )
            ->execute()
            ->fetchAll();

        $listOfUpdateIds = [];
        $customTemplates = [];
        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        foreach ($records as $record) {
            $flexformArray = GeneralUtility::xml2array($record['pi_flexform']);
            $newFlexformArray = $flexformArray;

            foreach ($flexformArray['data']['sDEF']['lDEF'] as $key => $value) {
                if (isset($this->configurationKeyMap[$key])) {
                    $newFlexformArray['data']['sDEF']['lDEF'][$this->configurationKeyMap[$key]] = $value;
                    unset($newFlexformArray['data']['sDEF']['lDEF'][$key]);
                }
            }

            foreach ($flexformArray['data']['sDISPLAY']['lDEF'] as $key => $value) {
                if (isset($this->configurationKeyMap[$key])) {
                    $newFlexformArray['data']['sDISPLAY']['lDEF'][$this->configurationKeyMap[$key]] = $value;
                    unset($newFlexformArray['data']['sDISPLAY']['lDEF'][$key]);
                }
            }

            // Build display mode from templateFile
            $templateFile = $flexformArray['data']['sDISPLAY']['lDEF']['templateFile']['vDEF'];
            if ($templateFile !== 'default') {
                $displayMode = pathinfo($templateFile, PATHINFO_FILENAME);
                $customTemplates[] = $displayMode;
            } else {
                $displayMode = 'list';
            }
            $newFlexformArray['data']['sDISPLAY']['lDEF']['settings.displayMode']['vDEF'] = $displayMode;

            $newFlexform = $dataHandler->checkValue_flexArray2Xml($newFlexformArray, true);
            // ToDo: Add missing default values here

            $queryBuilder = $connection->createQueryBuilder();
            $queryBuilder->update('tt_content')
                ->where(
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter($record['uid'], \PDO::PARAM_INT)
                    )
                )
                ->set('pi_flexform', $newFlexform)
                ->set('list_type', 'ttaddress_listview');
            $queryBuilder->execute();
            $listOfUpdateIds[] = $record['uid'];
        }

        $customMessage =
            sprintf('Migrated tt_address legacy plugins with UID: %s. Found custom templates: %s',
                implode(', ', $listOfUpdateIds),
                implode(',', array_unique($customTemplates))
            );
        $this->markWizardAsDone();
        return true;
    }
}
