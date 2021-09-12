<?php
declare(strict_types=1);

namespace FriendsOfTypo3\TtAddress\Tests\Unit\Utility;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Evaluation\LongitudeEvaluation;
use Prophecy\PhpUnit\ProphecyTrait;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\BaseTestCase;

class LongitudeEvaluationTest extends BaseTestCase
{
    use ProphecyTrait;

    /** @var LongitudeEvaluation */
    protected $subject;

    public function setUp(): void
    {
        $this->subject = new LongitudeEvaluation();

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
     * @dataProvider lngIsProperlyEvaluatedDataProvider
     */
    public function longIsProperlyEvaluated($given, $expected)
    {
        $this->assertEquals($expected, $this->subject->evaluateFieldValue($given));
    }

    /**
     * @param $given
     * @param $expected
     * @test
     * @dataProvider lngIsProperlyEvaluatedDataProvider
     */
    public function lngIsProperlyDeEvaluated($given, $expected)
    {
        $params = ['value' => $given];
        $this->assertEquals($expected, $this->subject->deevaluateFieldValue($params));
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
