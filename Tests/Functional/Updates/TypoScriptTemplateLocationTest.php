<?php

namespace FriendsOfTYPO3\TtAddress\Tests\Functional\Service;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Updates\TypoScriptTemplateLocation;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class TypoScriptTemplateLocationTest extends FunctionalTestCase
{
    protected $testExtensionsToLoad = ['typo3conf/ext/tt_address'];

    public function setUp()
    {
        parent::setUp();

        $this->importDataSet(__DIR__ . '/../Fixtures/sys_template.xml');
    }

    /**
     * @test
     */
    public function doneWizardReturnsFalse()
    {
        $description = 'some description';
        $mockedSubject = $this->getAccessibleMock(TypoScriptTemplateLocation::class, ['isWizardDone'], [], '', false);
        $mockedSubject->expects($this->once())->method('isWizardDone')->willReturn(true);

        $this->assertFalse($mockedSubject->checkForUpdate($description));
    }

    /**
     * @test
     */
    public function wizardReturnsTrueIfSomethingNeedsToBeUpdated()
    {
        $description = 'some description';
        $subject = GeneralUtility::makeInstance(TypoScriptTemplateLocation::class);
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
        $subject = $this->getAccessibleMock(TypoScriptTemplateLocation::class, ['markWizardAsDone'], [], '', false);
        $subject->expects($this->once())->method('markWizardAsDone');
        $result = $subject->performUpdate($dbQueries, $customMessage);

        $this->assertTrue($result);
        $this->assertEquals('Updated sys_templates with UID: 2, 3, 4', $customMessage);

        $rowConstants = BackendUtility::getRecord('sys_template', 2);
        $this->assertEquals('# constants
            <INCLUDE_TYPOSCRIPT: source="EXT:tt_address/Configuration/TypoScript/LegacyPlugin/constants.txt">', trim($rowConstants['constants']));

        $rowConfig = BackendUtility::getRecord('sys_template', 3);
        $this->assertEquals('# setup
            <INCLUDE_TYPOSCRIPT: source="EXT:tt_address/Configuration/TypoScript/LegacyPlugin/setup.txt">
            # code in between
            # code in between
            <INCLUDE_TYPOSCRIPT: source="EXT:tt_address/Configuration/TypoScript/LegacyPlugin/setup.txt">', trim($rowConfig['config']));

        $rowStaticInclude = BackendUtility::getRecord('sys_template', 4);
        $this->assertEquals(
            'EXT:fluid_styled_content/Configuration/TypoScript/,EXT:fluid_styled_content/Configuration/TypoScript/Styling/,EXT:tt_address/Configuration/TypoScript/,EXT:tt_address/Configuration/TypoScript/LegacyPlugin/',
            trim($rowStaticInclude['include_static_file'])
        );
    }
}
