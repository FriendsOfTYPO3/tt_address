<?php

namespace FriendsOfTypo3\TtAddress\Tests\Unit\Controller;

use FriendsOfTYPO3\TtAddress\Controller\AddressController;
use FriendsOfTYPO3\TtAddress\Domain\Repository\AddressRepository;
use TYPO3\CMS\Core\Database\QueryGenerator;
use TYPO3\TestingFramework\Core\BaseTestCase;

class AddressControllerTest extends BaseTestCase
{

    /**
     * @param $given
     * @param $expected
     * @test
     * @dataProvider dotIsRemovedFromEndDataProvider
     */
    public function dotIsRemovedFromEnd($given, $expected)
    {
        $subject = $this->getAccessibleMock(AddressController::class, ['dummy'], [], '', false);
        $this->assertEquals($expected, $subject->_call('removeDotAtTheEnd', $given));
    }

    public function dotIsRemovedFromEndDataProvider(): array
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
        $subject = $this->getAccessibleMock(AddressController::class, ['dummy'], [], '', false);
        $given = [
            'example' => 'some string',
            'example2' => '123',
            'example with dot.' => 'bla',
            'array' => [
                'sub' => 'string',
                'sub-with-dot.' => 'stringvalue'
            ]
        ];
        $expected = [
            'example' => 'some string',
            'example2' => '123',
            'example with dot' => 'bla',
            'array' => [
                'sub' => 'string',
                'sub-with-dot' => 'stringvalue'
            ]
        ];
        $this->assertEquals($expected, $subject->_call('removeDots', $given));
    }

    /**
     * @test
     */
    public function initializeActionWorks()
    {
        $subject = $this->getAccessibleMock(AddressController::class, ['dummy'], [], '', false);
        $subject->initializeAction();

        $expected = new QueryGenerator();

        $this->assertEquals($expected, $subject->_get('queryGenerator'));
    }

    /**
     * @test
     */
    public function injectAddressRepositoryWorks()
    {
        $mockedRepository = $this->getAccessibleMock(AddressRepository::class, ['dummy'], [], '', false);

        $subject = $this->getAccessibleMock(AddressController::class, ['dummy'], [], '', false);
        $subject->injectAddressRepository($mockedRepository);

        $this->assertEquals($mockedRepository, $subject->_get('addressRepository'));
    }

    /**
     * @test
     */
    public function pidListIsReturned()
    {
        $mockedQueryGenerator = $this->getAccessibleMock(QueryGenerator::class, ['getTreeList'], [], '', false);
        $mockedQueryGenerator->expects($this->any())->method('getTreeList')
            ->withConsecutive([123, 3], [456, 3])
            ->willReturnOnConsecutiveCalls('7,8,9', '');
//        $mockedQueryGenerator->expects($this->any())->method('getTreeList')->with(123)->willReturn([7, 8, 9]);
//        $mockedQueryGenerator->expects($this->any())->method('getTreeList')->with(456)->willReturn([]);

        $subject = $this->getAccessibleMock(AddressController::class, ['dummy'], [], '', false);
        $subject->_set('queryGenerator', $mockedQueryGenerator);
        $subject->_set('settings', [
            'pages' => '123,456',
            'recursive' => 3
        ]);

        $this->assertEquals(['123', '456', '7', '8', '9'], $subject->_call('getPidList'));
    }
}
