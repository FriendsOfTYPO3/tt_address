<?php

namespace FriendsOfTYPO3\TtAddress\Tests\Functional\Service;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Service\CategoryService;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\TimeTracker\TimeTracker;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class CategoryServiceTest extends FunctionalTestCase
{

    /** @var CategoryService */
    protected $subject;

    protected $testExtensionsToLoad = ['typo3conf/ext/tt_address'];

    public function setUp(): void
    {
        parent::setUp();
        $this->subject = GeneralUtility::makeInstance(CategoryService::class);

        $this->importDataSet(__DIR__ . '/../Fixtures/sys_categories.xml');
    }

    /**
     * @test
     */
    public function findChildCategories()
    {
        $categories = $this->subject->getChildrenCategories('2,4');
        $this->assertEquals('2,4,20,21,211,212,30,31,32', $categories);

        $categories = $this->subject->getChildrenCategories('4,5,10919,6,7,8');
        $this->assertEquals('4,5,8', $categories);
    }

    /**
     * @test
     */
    public function loggerInvokedWithTooManyCategories()
    {
        $mockedTimeTracker = $this->getAccessibleMock(TimeTracker::class, ['setTSlogMessage'], [], '', false);
        $mockedTimeTracker->expects($this->any())->method('setTSlogMessage');

        $subject = $this->getAccessibleMock(CategoryService::class, ['dummy'], [], '', false);
        $subject->_set('timeTracker', $mockedTimeTracker);
        $subject->_set('cache', GeneralUtility::makeInstance(CacheManager::class)->getCache('cache_ttaddress_category'));

        $categories = $subject->getChildrenCategories('2,4', 100000);
    }
}
