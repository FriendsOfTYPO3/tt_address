<?php

namespace FriendsOfTypo3\TtAddress\Tests\Unit\Utility;

use FriendsOfTYPO3\TtAddress\Evaluation\LatitudeEvaluation;
use TYPO3\TestingFramework\Core\BaseTestCase;

class LatitudeEvaluationTest extends BaseTestCase
{

    /** @var LatitudeEvaluation */
    protected $subject;

    public function setUp()
    {
        $this->subject = new LatitudeEvaluation();
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
     * @dataProvider latIsProperlyEvaluatedDataProvider
     */
    public function latitudeIsProperlyEvaluated($given, $expected)
    {
        $this->assertEquals($expected, $this->subject->evaluateFieldValue($given));
    }

    public function latIsProperlyEvaluatedDataProvider(): array
    {
        return [
            'empty string' => ['', ''],
            'int' => ['12', '12.000000000000'],
            'too large number' => ['95.33', '90.000000000000'],
            'regular float' => ['13.312113', '13.312113000000'],
            'negative regular float' => ['-13.312113', '-13.312113000000'],
            'long float' => ['-11.3121131111111111212121212', '-11.312113111111'],
        ];
    }


}