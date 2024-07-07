<?php

declare(strict_types=1);

namespace FriendsOfTypo3\TtAddress\Tests\Unit\Utility;

/*
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Domain\Model\Dto\Settings;
use FriendsOfTYPO3\TtAddress\Evaluation\TelephoneEvaluation;
use Prophecy\PhpUnit\ProphecyTrait;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\BaseTestCase;

class TelephoneEvaluationTest extends BaseTestCase
{
    use ProphecyTrait;

    /** @var TelephoneEvaluation */
    protected $subject;

    public function setUp(): void
    {
        $this->subject = new TelephoneEvaluation();

        $packageManagerProphecy = $this->prophesize(PackageManager::class);
        GeneralUtility::setSingletonInstance(PackageManager::class, $packageManagerProphecy->reveal());
    }

    /**
     * @test
     */
    public function constructorIsCalled()
    {
        $subject = $this->getAccessibleMock(TelephoneEvaluation::class, null, [], '', true);

        $settings = new Settings();
        self::assertEquals($settings, $subject->_get('extensionSettings'));
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
     * @dataProvider telephoneIsProperlyEvaluatedDataProvider
     */
    public function telephoneIsProperlyEvaluated($given, $expected)
    {
        self::assertEquals($expected, $this->subject->evaluateFieldValue($given));
    }

    /**
     * @test
     * @dataProvider telephoneIsProperlyEvaluatedDataProvider
     */
    public function telephoneIsProperlyDeEvaluated($given, $expected)
    {
        $params = ['value' => $given];
        self::assertEquals($expected, $this->subject->deevaluateFieldValue($params));
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
