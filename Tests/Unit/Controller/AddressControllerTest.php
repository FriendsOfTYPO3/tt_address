<?php

declare(strict_types=1);

namespace FriendsOfTypo3\TtAddress\Tests\Unit\Controller;

/*
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use FriendsOfTYPO3\TtAddress\Controller\AddressController;
use FriendsOfTYPO3\TtAddress\Database\QueryGenerator;
use FriendsOfTYPO3\TtAddress\Domain\Model\Address;
use FriendsOfTYPO3\TtAddress\Domain\Model\Dto\Demand;
use FriendsOfTYPO3\TtAddress\Domain\Model\Dto\Settings;
use FriendsOfTYPO3\TtAddress\Domain\Repository\AddressRepository;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Fluid\View\TemplateView;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\TestingFramework\Core\BaseTestCase;

class AddressControllerTest extends BaseTestCase
{
    protected function setUp(): void
    {
        $GLOBALS['TSFE'] = $this->getAccessibleMock(TypoScriptFrontendController::class, ['addCacheTags'], [], '', false);
    }

    /**
     * @test
     * @dataProvider dotIsRemovedFromEndDataProvider
     */
    public function dotIsRemovedFromEnd($given, $expected)
    {
        $subject = $this->getAccessibleMock(AddressController::class, null, [], '', false);
        self::assertEquals($expected, $subject->_call('removeDotAtTheEnd', $given));
    }

    public static function dotIsRemovedFromEndDataProvider(): array
    {
        return [
            'empty string' => ['', ''],
            'dot at end' => ['foBar.', 'foBar'],
        ];
    }

    /**
     * @test
     */
    public function dotsAreRemovedFromArray()
    {
        $subject = $this->getAccessibleMock(AddressController::class, null, [], '', false);
        $given = [
            'example' => 'some string',
            'example2' => '123',
            'example with dot.' => 'bla',
            'array' => [
                'sub' => 'string',
                'sub-with-dot.' => 'stringvalue',
            ],
        ];
        $expected = [
            'example' => 'some string',
            'example2' => '123',
            'example with dot' => 'bla',
            'array' => [
                'sub' => 'string',
                'sub-with-dot' => 'stringvalue',
            ],
        ];
        self::assertEquals($expected, $subject->_call('removeDots', $given));
    }

    /**
     * @test
     */
    public function initializeActionWorks()
    {
        $mockedPackageManager = $this->getAccessibleMock(PackageManager::class, null, [], '', false);
        GeneralUtility::setSingletonInstance(PackageManager::class, $mockedPackageManager);

        $subject = $this->getAccessibleMock(AddressController::class, null, [], '', false);
        $subject->_set('extensionConfiguration', $this->getMockedSettings());
        $subject->initializeAction();

        $expected = new QueryGenerator();

        self::assertEquals($expected, $subject->_get('queryGenerator'));
    }

    /**
     * @test
     */
    public function injectAddressRepositoryWorks()
    {
        $mockedRepository = $this->getAccessibleMock(AddressRepository::class, null, [], '', false);

        $subject = $this->getAccessibleMock(AddressController::class, null, [], '', false);
        $subject->injectAddressRepository($mockedRepository);

        self::assertEquals($mockedRepository, $subject->_get('addressRepository'));
    }

    /**
     * @test
     */
    public function pidListIsReturned()
    {
        $mockedQueryGenerator = $this->getAccessibleMock(QueryGenerator::class, ['getTreeList'], [], '', false);
        $mockedQueryGenerator->expects(self::any())->method('getTreeList');

        $subject = $this->getAccessibleMock(AddressController::class, null, [], '', false);
        $subject->_set('queryGenerator', $mockedQueryGenerator);
        $subject->_set('settings', [
            'pages' => '123,456',
            'recursive' => 3,
        ]);

        self::assertEquals(['123', '456'], $subject->_call('getPidList'));
    }

    /**
     * @test
     */
    public function settingsAreProperlyInjected()
    {
        self::markTestSkipped('Skipped until fixed');
        $mockedConfigurationManager = $this->getAccessibleMock(ConfigurationManager::class, ['getConfiguration'], [], '', false);
        $mockedConfigurationManager->expects(self::any())->method('getConfiguration')
            ->withConsecutive([ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT], [ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS])
            ->willReturnOnConsecutiveCalls(
                [
                    'plugin.' => [
                        'tx_ttaddress.' => [
                            'settings' => [
                                'orderByAllowed' => 'sorting',
                                'overrideFlexformSettingsIfEmpty' => 'key4,key5,key6',
                                'key2' => 'abc',
                                'key4' => 'fo',
                                'key5' => '',
                            ],
                        ],
                    ],
                ],
                [
                    'key1' => 'value1',
                    'orderByAllowed' => 'custom',
                    'key2' => '',
                    'key3' => '',
                    'key4' => '',
                    'key5' => '',
                ]
            );

        $subject = $this->getAccessibleMock(AddressController::class, null, [], '', false);
        $expectedSettings = [
            'key1' => 'value1',
            'orderByAllowed' => 'sorting',
            'key2' => '',
            'key3' => '',
            'key4' => 'fo',
            'key5' => '',
        ];
        $subject->injectConfigurationManager($mockedConfigurationManager);
        self::assertEquals($expectedSettings, $subject->_get('settings'));
    }

    /**
     * @test
     */
    public function demandIsCreated()
    {
        $demand = new Demand();

        $subject = $this->getAccessibleMock(AddressController::class, ['getPidList'], [], '', false);
        $subject->expects(self::any())->method('getPidList')->willReturn(['123', '456']);
        $subject->_set('settings', [
            'pages' => '123,456',
            'singleRecords' => '7,4',
            'recursive' => 3,
            'groups' => '4,5,6',
            'groupsCombination' => 1,
        ]);

        $expected = new Demand();
        $expected->setPages(['123', '456']);
        $expected->setSingleRecords('7,4');
        $expected->setCategoryCombination('or');
        $expected->setCategories('4,5,6');

        self::assertEquals($expected, $subject->_call('createDemandFromSettings'));
    }

    /**
     * @test
     */
    public function showActionFillsView()
    {
        $address = new Address();
        $address->setLastName('Doe');
        $assigned = [
            'address' => $address,
            'contentObjectData' => [],
        ];
        $mockedView = $this->getAccessibleMock(TemplateView::class, ['assignMultiple'], [], '', false);
        $mockedView->expects(self::once())->method('assignMultiple')->with($assigned);
        $mockContentObject = $this->createMock(ContentObjectRenderer::class);
        $mockConfigurationManager = $this->createMock(ConfigurationManager::class);
        $mockConfigurationManager->method('getContentObject')
            ->willReturn($mockContentObject);

        $subject = $this->getAccessibleMock(AddressController::class, ['redirectToUri', 'htmlResponse'], [], '', false);
        $subject->_set('view', $mockedView);
        $subject->_set('configurationManager', $mockConfigurationManager);
        $subject->expects(self::once())->method('htmlResponse');

        $subject->showAction($address);
    }

    /**
     * @test
     */
    public function listActionFillsViewForSingleRecords()
    {
        $settings = [
            'singlePid' => 0,
            'singleRecords' => 1,
        ];
        $demand = new Demand();
        $demand->setSingleRecords('134');

        $mockedRepository = $this->getAccessibleMock(AddressRepository::class, ['getAddressesByCustomSorting'], [], '', false);
        $mockedRepository->expects(self::once())->method('getAddressesByCustomSorting')->willReturn(['dummy return single']);

        $assignments = [
            'demand' => $demand,
            'addresses' => ['dummy return single'],
            'contentObjectData' => [],
        ];

        $mockedView = $this->getAccessibleMock(TemplateView::class, ['assignMultiple', 'assign'], [], '', false);
        $mockedView->expects(self::once())->method('assignMultiple')->with($assignments);
        $mockConfigurationManager = $this->createMock(ConfigurationManager::class);
        $mockContentObject = $this->createMock(ContentObjectRenderer::class);
        $mockConfigurationManager->method('getContentObject')
            ->willReturn($mockContentObject);
        $mockedRequest = $this->getAccessibleMock(Request::class, ['hasArgument', 'getArgument'], [], '', false);

        $subject = $this->getAccessibleMock(AddressController::class, ['createDemandFromSettings', 'htmlResponse'], [], '', false);
        $subject->expects(self::once())->method('createDemandFromSettings')->willReturn($demand);
        $subject->expects(self::once())->method('htmlResponse');
        $subject->_set('settings', $settings);
        $subject->_set('view', $mockedView);
        $subject->_set('request', $mockedRequest);
        $subject->_set('addressRepository', $mockedRepository);
        $subject->_set('extensionConfiguration', $this->getMockedSettings());
        $subject->_set('configurationManager', $mockConfigurationManager);

        $subject->listAction();
    }

    /**
     * @test
     */
    public function listActionFillsViewForDemand()
    {
        $settings = [
            'singleRecords' => 1,
        ];
        $demand = new Demand();
        $demand->setPages(['12']);

        $mockedRepository = $this->getAccessibleMock(AddressRepository::class, ['findByDemand'], [], '', false);
        $mockedRepository->expects(self::once())->method('findByDemand')->willReturn(['dummy return']);
        $mockContentObject = $this->createMock(ContentObjectRenderer::class);
        $mockConfigurationManager = $this->createMock(ConfigurationManager::class);
        $mockConfigurationManager->method('getContentObject')
            ->willReturn($mockContentObject);
        $assignments = [
            'demand' => $demand,
            'addresses' => ['dummy return'],
            'contentObjectData' => [],
        ];

        $mockedRequest = $this->getAccessibleMock(Request::class, ['hasArgument', 'getArgument'], [], '', false);

        $mockedView = $this->getAccessibleMock(TemplateView::class, ['assignMultiple', 'assign'], [], '', false);
        $mockedView->expects(self::once())->method('assignMultiple')->with($assignments);

        $subject = $this->getAccessibleMock(AddressController::class, ['createDemandFromSettings', 'htmlResponse'], [], '', false);
        $subject->expects(self::once())->method('createDemandFromSettings')->willReturn($demand);
        $subject->expects(self::any())->method('htmlResponse');
        $subject->_set('settings', $settings);
        $subject->_set('view', $mockedView);
        $subject->_set('request', $mockedRequest);
        $subject->_set('addressRepository', $mockedRepository);
        $subject->_set('extensionConfiguration', $this->getMockedSettings());
        $subject->_set('configurationManager', $mockConfigurationManager);

        $subject->listAction();
    }

    /**
     * @test
     */
    public function overrideDemandMethodIsCalledIfEnabled()
    {
        $mockedRequest = $this->getAccessibleMock(Request::class, ['hasArgument', 'getArgument'], [], '', false);
        $mockedRepository = $this->getAccessibleMock(AddressRepository::class, ['getAddressesByCustomSorting', 'findByDemand'], [], '', false);
        $mockedRepository->expects(self::any())->method('findByDemand')->willReturn([]);
        $mockedView = $this->getAccessibleMock(TemplateView::class, ['assignMultiple', 'assign'], [], '', false);
        $mockedView->expects(self::once())->method('assignMultiple');
        $mockContentObject = $this->createMock(ContentObjectRenderer::class);
        $mockConfigurationManager = $this->createMock(ConfigurationManager::class);
        $mockConfigurationManager->method('getContentObject')
            ->willReturn($mockContentObject);

        $subject = $this->getAccessibleMock(AddressController::class, ['overrideDemand', 'createDemandFromSettings', 'htmlResponse'], [], '', false);
        $subject->_set('extensionConfiguration', $this->getMockedSettings());
        $subject->_set('configurationManager', $mockConfigurationManager);
        $subject->expects(self::any())->method('overrideDemand');
        $subject->expects(self::any())->method('htmlResponse');

        $demand = new Demand();
        $subject->expects(self::any())->method('createDemandFromSettings')->willReturn($demand);

        $settings = [
            'allowOverride' => true,
        ];
        $subject->_set('settings', $settings);
        $subject->_set('addressRepository', $mockedRepository);
        $subject->_set('view', $mockedView);
        $subject->_set('request', $mockedRequest);

        $subject->listAction(['not', 'empty']);
    }

    /**
     * @test
     * @dataProvider overrideDemandWorksDataProvider
     */
    public function overrideDemandWorks(Demand $demandIn, Demand $demandOut, array $override)
    {
        $subject = $this->getAccessibleMock(AddressController::class, null, [], '', false);

        self::assertEquals($demandOut, $subject->_call('overrideDemand', $demandIn, $override));
    }

    public static function overrideDemandWorksDataProvider(): array
    {
        $data = [];

        // simple override + skipped field including different case
        $demand1In = new Demand();
        $demand1In->setCategories('12,34');
        $demand1In->setSortBy('uid');
        $demand1Out = clone $demand1In;
        $demand1Out->setCategories('56');
        $demand1Out->setSortBy('title');
        $data['skipSimple'] = [$demand1In, $demand1Out, ['categories' => '56', 'sortby' => 'title']];

        // not existing field
        $demand2In = new Demand();
        $demand2In->setCategories('7');
        $demand2Out = clone $demand2In;
        $data['ignoreNotExisting'] = [$demand2In, $demand2Out, ['categoriesX' => '56', 'ysortby' => 'title']];

        return $data;
    }

    protected function getMockedSettings()
    {
        $mockedSettings = $this->getAccessibleMock(Settings::class, ['getSettings'], [], '', false);
        $mockedSettings->expects(self::any())->method('getSettings')->willReturn([]);

        return $mockedSettings;
    }
}
