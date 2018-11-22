<?php

namespace FriendsOfTypo3\TtAddress\Tests\Unit\Utility;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use FriendsOfTYPO3\TtAddress\Evaluation\TelephoneEvaluation;
use TYPO3\TestingFramework\Core\BaseTestCase;

class TelephoneEvaluationTest extends BaseTestCase
{

    /** @var TelephoneEvaluation */
    protected $subject;

    public function setUp()
    {
        $this->subject = new TelephoneEvaluation();
    }

    /**
     * @test
     */
    public function jsEvaluationIsCalled()
    {
        $this->assertNotEmpty($this->subject->returnFieldJS());
    }

    /**
     * @param $given
     * @param $expected
     * @test
     * @dataProvider telephoneIsProperlyEvaluatedDataProvider
     */
    public function telephoneIsProperlyEvaluated($given, $expected)
    {
        $this->assertEquals($expected, $this->subject->evaluateFieldValue($given));
    }

    /**
     * @param $given
     * @param $expected
     * @test
     * @dataProvider telephoneIsProperlyEvaluatedDataProvider
     */
    public function telephoneIsProperlyDeEvaluated($given, $expected)
    {
        $params = ['value' => $given];
        $this->assertEquals($expected, $this->subject->deevaluateFieldValue($params));
    }

    public function telephoneIsProperlyEvaluatedDataProvider(): array
    {
        return [
            'empty string' => ['', ''],
            'example 1' => ['+43 699 12 54 12 1', '+43 699 12 54 12 1'],
            'example 2' => ['+43 (0)699 12 54 12 1', '+43 0699 12 54 12 1'],
            'example 3' => [' +43 (0)699 12 54 12 1 DW:4 ', '+43 0699 12 54 12 1 4'],
        ];
    }
}
