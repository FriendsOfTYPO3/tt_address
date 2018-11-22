<?php

namespace FriendsOfTYPO3\TtAddress\Tests\Functional\Hooks\DataHandler;

use FriendsOfTYPO3\TtAddress\Hooks\DataHandler\BackwardsCompatibilityNameFormat;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class BackwardsCompatibilityNameFormatTest extends FunctionalTestCase
{
    protected $testExtensionsToLoad = ['typo3conf/ext/tt_address'];

    public function setUp()
    {
        parent::setUp();

        $this->importDataSet(__DIR__ . '/../../Fixtures/tt_address.xml');
    }

    /**
     * @test
     */
    public function getRecordGetsRecord()
    {
        $subject = $this->getAccessibleMock(BackwardsCompatibilityNameFormat::class, ['dummy'], [], '', false);

        $row = $subject->_call('getRecord', 7);
        $this->assertEquals('Zargo', $row['last_name']);
    }
}
