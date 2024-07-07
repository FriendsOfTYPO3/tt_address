<?php

declare(strict_types=1);

namespace FriendsOfTypo3\TtAddress\Tests\Unit\Utility;

/*
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Evaluation\LatitudeEvaluation;
use TYPO3\TestingFramework\Core\BaseTestCase;

class LatitudeEvaluationTest extends BaseTestCase
{
    protected LatitudeEvaluation $subject;

    public function setUp(): void
    {
        $this->subject = new LatitudeEvaluation();
    }

    /**
     * @test
     */
    public function jsEvaluationIsCalled()
    {
        self::markTestSkipped('Skipped as PageRenderer is called which leads into issues');
        self::assertNotEmpty($this->subject->returnFieldJS());
    }

    /**
     * @test
     *
     * @dataProvider latIsProperlyEvaluatedDataProvider
     */
    public function latitudeIsProperlyEvaluated($given, $expected)
    {
        self::assertEquals($expected, $this->subject->evaluateFieldValue($given));
    }

    /**
     * @test
     *
     * @dataProvider latIsProperlyEvaluatedDataProvider
     */
    public function latIsProperlyDeEvaluated($given, $expected)
    {
        $params = ['value' => $given];
        self::assertEquals($expected, $this->subject->deevaluateFieldValue($params));
    }

    public static function latIsProperlyEvaluatedDataProvider(): array
    {
        return [
            'empty string'           => ['', ''],
            'int'                    => ['12', '12.00000000'],
            'too large number'       => ['95.33', '90.00000000'],
            'regular float'          => ['13.312113', '13.31211300'],
            'negative regular float' => ['-13.312113', '-13.31211300'],
            'long float'             => ['-11.3121131111111111212121212', '-11.31211311'],
        ];
    }
}
