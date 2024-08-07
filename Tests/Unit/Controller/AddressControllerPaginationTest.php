<?php

namespace FriendsOfTypo3\TtAddress\Tests\Unit\Controller;

/*
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use FriendsOfTYPO3\TtAddress\Controller\AddressController;
use FriendsOfTYPO3\TtAddress\Domain\Model\Dto\Demand;
use FriendsOfTYPO3\TtAddress\Domain\Model\Dto\Settings;
use FriendsOfTYPO3\TtAddress\Domain\Repository\AddressRepository;
use TYPO3\CMS\Core\Pagination\PaginatorInterface;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Fluid\View\TemplateView;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\TestingFramework\Core\BaseTestCase;

class AddressControllerPaginationTest extends BaseTestCase
{
    protected function setUp(): void
    {
        $GLOBALS['TSFE'] = $this->getAccessibleMock(TypoScriptFrontendController::class, ['addCacheTags'], [], '', false);
    }

    /**
     * @test
     */
    public function listActionUsesNewPaginationWithArrayRecords()
    {
        if (!class_exists(SimplePagination::class)) {
            self::markTestSkipped('Ignore test as new pagination is not available');
        }
        $settings = [
            'singlePid' => 0,
            'singleRecords' => 1,
            'paginate' => [
                'itemsPerPage' => 3,
            ],
        ];
        $demand = new Demand();
        $demand->setSingleRecords('134');

        $mockedRepository = $this->getAccessibleMock(AddressRepository::class, ['getAddressesByCustomSorting'], [], '', false);

        $rows = [];
        for ($i = 1; $i <= 10; $i++) {
            $rows[] = [
                'uid' => $i,
                'title' => 'record #' . $i,
            ];
        }
        $assignments = [
            'demand' => $demand,
            'addresses' => $rows,
            'contentObjectData' => [],
        ];

        $mockedRepository->expects(self::once())->method('getAddressesByCustomSorting')->willReturn($rows);

        $mockedRequest = $this->getAccessibleMock(Request::class, ['hasArgument', 'getArgument', 'getAttribute'], [], '', false);
        $mockedRequest->expects(self::once())->method('hasArgument')->with('currentPage')->willReturn(true);
        $mockedRequest->expects(self::once())->method('getArgument')->with('currentPage')->willReturn(2);
        $mockedRequest->expects(self::any())->method('getAttribute')->willReturn([]);

        $mockedView = $this->getAccessibleMock(TemplateView::class, ['assignMultiple', 'assign'], [], '', false);
        $mockedView->expects(self::once())->method('assignMultiple')->with($assignments);

        $subject = $this->getAccessibleMock(AddressController::class, ['createDemandFromSettings', 'htmlResponse'], [], '', false);
        $subject->expects(self::once())->method('createDemandFromSettings')->willReturn($demand);
        $subject->expects(self::once())->method('htmlResponse');
        $subject->_set('settings', $settings);
        $subject->_set('view', $mockedView);
        $subject->_set('request', $mockedRequest);
        $subject->_set('addressRepository', $mockedRepository);
        $subject->_set('extensionConfiguration', $this->getMockedSettings());

        $subject->listAction();
    }

    /**
     * @test
     */
    public function paginationIsCorrectlyTriggered()
    {
        if (!class_exists(SimplePagination::class)) {
            self::markTestSkipped('Ignore test as new pagination is not available');
        }

        $settings = [
            'singlePid' => 0,
            'singleRecords' => 1,
            'paginate' => [
                'itemsPerPage' => 3,
            ],
        ];

        $rows = [];
        for ($i = 1; $i <= 10; $i++) {
            $rows[] = [
                'uid' => $i,
                'title' => 'record #' . $i,
            ];
        }

        $mockedRequest = $this->getAccessibleMock(Request::class, ['hasArgument', 'getArgument'], [], '', false);
        $mockedRequest->expects(self::once())->method('hasArgument')->with('currentPage')->willReturn(true);
        $mockedRequest->expects(self::once())->method('getArgument')->with('currentPage')->willReturn(2);

        $subject = $this->getAccessibleMock(AddressController::class, null, [], '', false);
        $subject->_set('settings', $settings);
        $subject->_set('request', $mockedRequest);

        /** @var PaginatorInterface $paginator */
        $paginator = $subject->_call('getPaginator', $rows);
        self::assertEquals($paginator->getPaginatedItems(), array_splice($rows, 3, 3));
    }

    protected function getMockedSettings()
    {
        $mockedSettings = $this->getAccessibleMock(Settings::class, ['getSettings'], [], '', false);
        $mockedSettings->expects(self::any())->method('getSettings')->willReturn([]);

        return $mockedSettings;
    }
}
