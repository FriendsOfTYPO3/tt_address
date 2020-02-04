<?php

namespace FriendsOfTypo3\TtAddress\Tests\Unit\Utility;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Evaluation\LatitudeEvaluation;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\BaseTestCase;

class LatitudeEvaluationTest extends BaseTestCase
{

    /** @var LatitudeEvaluation */
    protected $subject;

    public function setUp():void
    {
        $this->subject = new LatitudeEvaluation();

        $packageManagerProphecy = $this->prophesize(PackageManager::class);
        GeneralUtility::setSingletonInstance(PackageManager::class, $packageManagerProphecy->reveal());
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

    /**
     * @param $given
     * @param $expected
     * @test
     * @dataProvider latIsProperlyEvaluatedDataProvider
     */
    public function latIsProperlyDeEvaluated($given, $expected)
    {
        $params = ['value' => $given];
        $this->assertEquals($expected, $this->subject->deevaluateFieldValue($params));
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
