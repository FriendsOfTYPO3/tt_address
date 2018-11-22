<?php

namespace FriendsOfTypo3\TtAddress\Tests\Unit\Utility;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Utility\EvalcoordinatesUtility;
use TYPO3\TestingFramework\Core\BaseTestCase;

class EvalcoordinatesUtilityTest extends BaseTestCase
{

    /**
     * @param $given
     * @param $expected
     * @test
     * @dataProvider longIsProperlyEvaluatedDataProvider
     */
    public function longIsProperlyEvaluated($given, $expected)
    {
        $this->assertEquals($expected, EvalcoordinatesUtility::formatLongitude($given));
    }

    public function longIsProperlyEvaluatedDataProvider(): array
    {
        return [
            'empty string' => ['', '.000000000000'],
            'int' => ['12', '12.000000000000'],
            'too large number' => ['193.33', '180.000000000000'],
            'regular float' => ['13.312113', '13.312113000000'],
            'negative regular float' => ['-13.312113', '-13.312113000000'],
            'long float' => ['-11.3121131111111111212121212', '-11.312113111111'],
        ];
    }

    /**
     * @param $given
     * @param $expected
     * @test
     * @dataProvider latIsProperlyEvaluatedDataProvider
     */
    public function latIsProperlyEvaluated($given, $expected)
    {
        $this->assertEquals($expected, EvalcoordinatesUtility::formatLatitude($given));
    }

    public function latIsProperlyEvaluatedDataProvider(): array
    {
        return [
            'empty string' => ['', '.000000000000'],
            'int' => ['12', '12.000000000000'],
            'too large number' => ['95.33', '90.000000000000'],
            'regular float' => ['13.312113', '13.312113000000'],
            'negative regular float' => ['-13.312113', '-13.312113000000'],
            'long float' => ['-11.3121131111111111212121212', '-11.312113111111'],
        ];
    }
}
