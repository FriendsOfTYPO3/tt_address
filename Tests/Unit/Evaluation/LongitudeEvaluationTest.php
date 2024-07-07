<?php

declare(strict_types=1);

namespace FriendsOfTypo3\TtAddress\Tests\Unit\Utility;

/*
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Evaluation\LongitudeEvaluation;
use TYPO3\TestingFramework\Core\BaseTestCase;

class LongitudeEvaluationTest extends BaseTestCase
{
    protected LongitudeEvaluation $subject;

    public function setUp(): void
    {
        $this->subject = new LongitudeEvaluation();
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
     * @dataProvider lngIsProperlyEvaluatedDataProvider
     */
    public function longIsProperlyEvaluated($given, $expected)
    {
        self::assertEquals($expected, $this->subject->evaluateFieldValue($given));
    }

    /**
     * @test
     * @dataProvider lngIsProperlyEvaluatedDataProvider
     */
    public function lngIsProperlyDeEvaluated($given, $expected)
    {
        $params = ['value' => $given];
        self::assertEquals($expected, $this->subject->deevaluateFieldValue($params));
    }

    public function lngIsProperlyEvaluatedDataProvider(): array
    {
        return [
            'empty string' => ['', ''],
            'int' => ['12', '12.00000000'],
            'too large number' => ['193.33', '180.00000000'],
            'regular float' => ['13.312113', '13.31211300'],
            'negative regular float' => ['-13.312113', '-13.31211300'],
            'long float' => ['-11.3121131111111111212121212', '-11.31211311'],
        ];
    }
}
