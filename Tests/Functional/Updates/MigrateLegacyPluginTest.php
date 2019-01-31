<?php

namespace FriendsOfTYPO3\TtAddress\Tests\Functional\Service;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Updates\MigrateLegacyPlugin;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class MigrateLegacyPluginTest extends FunctionalTestCase
{
    protected $testExtensionsToLoad = ['typo3conf/ext/tt_address'];

    public function setUp()
    {
        parent::setUp();

        $this->importDataSet(__DIR__ . '/../Fixtures/tt_content.xml');
    }

    /**
     * @test
     */
    public function doneWizardReturnsFalse()
    {
        $description = 'some description';
        $mockedSubject = $this->getAccessibleMock(MigrateLegacyPlugin::class, ['isWizardDone'], [], '', false);
        $mockedSubject->expects($this->once())->method('isWizardDone')->willReturn(true);

        $this->assertFalse($mockedSubject->checkForUpdate($description));
    }

    /**
     * @test
     */
    public function wizardReturnsTrueIfSomethingNeedsToBeUpdated()
    {
        $description = 'some description';
        $subject = GeneralUtility::makeInstance(MigrateLegacyPlugin::class);
        $result = $subject->checkForUpdate($description);
        $this->assertTrue($result);
        $this->assertNotEquals('some description', $description);
    }

    /**
     * @test
     */
    public function updateWorks()
    {
        $customMessage = 'dummy message';
        $dbQueries = [];
        $subject = $this->getAccessibleMock(MigrateLegacyPlugin::class, ['markWizardAsDone'], [], '', false);
        $subject->expects($this->once())->method('markWizardAsDone');
        $result = $subject->performUpdate($dbQueries, $customMessage);

        $this->assertTrue($result);

        $row = BackendUtility::getRecord('tt_content', 1);
        $this->assertEquals('ttaddress_listview', trim($row['list_type']));

        $templateFileToDisplayModeIsCorrect =
            (bool)strpos($row['pi_flexform'], '<value index="vDEF">your_template_name</value>');
        $this->assertTrue($templateFileToDisplayModeIsCorrect);

        $rowWithDefaultTemplateFile = BackendUtility::getRecord('tt_content', 2);
        $defaultTemplateFileIsDefaultDisplayMode =
            (bool)strpos($rowWithDefaultTemplateFile['pi_flexform'], '<value index="vDEF">list</value>');
        $this->assertTrue($defaultTemplateFileIsDefaultDisplayMode);
    }
}
