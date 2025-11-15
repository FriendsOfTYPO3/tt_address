<?php

declare(strict_types=1);

namespace FriendsOfTypo3\TtAddress\Tests\Unit\Utility;

/*
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Utility\EvalcoordinatesUtility;
use TYPO3\TestingFramework\Core\BaseTestCase;

class EvalcoordinatesUtilityTest extends BaseTestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\DataProvider('longIsProperlyEvaluatedDataProvider')]
    public function longIsProperlyEvaluated($given, $expected)
    {
        self::assertEquals($expected, EvalcoordinatesUtility::formatLongitude($given));
    }

    public static function longIsProperlyEvaluatedDataProvider(): array
    {
        return [
            'empty string' => ['', '.00000000'],
            'int' => ['12', '12.00000000'],
            'too large number' => ['193.33', '180.00000000'],
            'regular float' => ['13.312113', '13.31211300'],
            'negative regular float' => ['-13.312113', '-13.31211300'],
            'long float' => ['-11.3121131111111111212121212', '-11.31211311'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\DataProvider('latIsProperlyEvaluatedDataProvider')]
    public function latIsProperlyEvaluated($given, $expected)
    {
        self::assertEquals($expected, EvalcoordinatesUtility::formatLatitude($given));
    }

    public static function latIsProperlyEvaluatedDataProvider(): array
    {
        return [
            'empty string' => ['', '.00000000'],
            'int' => ['12', '12.00000000'],
            'too large number' => ['95.33', '90.00000000'],
            'regular float' => ['13.312113', '13.31211300'],
            'negative regular float' => ['-13.312113', '-13.31211300'],
            'long float' => ['-11.3121131111111111212121212', '-11.31211311'],
        ];
    }
}
