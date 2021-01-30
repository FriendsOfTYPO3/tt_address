<?php
declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\Tests\Functional\Hooks;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use FriendsOfTYPO3\TtAddress\Hooks\PageLayoutViewHook;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\PageLayoutView;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class PageLayoutViewHookTest extends FunctionalTestCase
{
    protected $testExtensionsToLoad = ['typo3conf/ext/tt_address'];

    public function setUp(): void
    {
        parent::setUp();

        $this->importDataSet(__DIR__ . '/../Fixtures/tt_address.xml');
        $this->importDataSet(__DIR__ . '/../Fixtures/tt_content.xml');
        $this->importDataSet(__DIR__ . '/../Fixtures/pages.xml');
    }

    /**
     * @test
     */
    public function previewRowIsEnriched(): void
    {
        $languageService = $this->getAccessibleMock(LanguageService::class, ['sl'], [], '', false, false);
        $languageService->expects($this->any())->method('sl')->willReturn('dummy label');

        $GLOBALS['LANG'] = $languageService;

        $pageLayoutViewMock = $this->getAccessibleMock(PageLayoutView::class, ['dummy'], [], '', false);
        $drawItem = true;
        $headerContent = 'header';
        $itemContent = 'item';
        $row = BackendUtility::getRecord('tt_content', 4);
        $subject = new PageLayoutViewHook();
        $subject->preProcess($pageLayoutViewMock, $drawItem, $headerContent, $itemContent, $row);

        $this->assertEquals('John', $row['_computed']['singleRecords'][0]['first_name']);
        $this->assertEquals('Jane', $row['_computed']['singleRecords'][1]['first_name']);
        $this->assertEquals('Madrid', $row['_computed']['groups'][0]['title']);
        $this->assertEquals('Kiev', $row['_computed']['groups'][1]['title']);
        $this->assertEquals('Storage', $row['_computed']['pages'][0]['title']);
        $this->assertEquals('Single Pid', $row['_computed']['singlePid']['title']);
    }
}
