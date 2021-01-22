<?php

namespace FriendsOfTypo3\TtAddress\Tests\Unit\ViewHelpers;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\ViewHelpers\TelephoneViewHelper;
use TYPO3\TestingFramework\Core\BaseTestCase;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

class TelephoneViewHelperTest extends BaseTestCase
{
    /**
     * @var TelephoneViewHelper
     */
    protected $viewHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->viewHelper = new TelephoneViewHelper();
        $this->viewHelper->initializeArguments();
    }

    /**
     * @test
     */
    public function telephoneVhRemovesWhiteSpaces()
    {
        $actualResult = $this->viewHelper->renderStatic(
            ['value' => ' +43 123 56 34 34 '],
            function () {
            },
            $this->prophesize(RenderingContextInterface::class)->reveal()
        );

        $this->assertEquals('+43123563434', $actualResult);
    }

    /**
     * @test
     */
    public function telephoneVhRemovesSpecialChars()
    {
        $actualResult = $this->viewHelper->renderStatic(
            ['value' => '+43 123 / 56 34 - 34'],
            function () {
            },
            $this->prophesize(RenderingContextInterface::class)->reveal()
        );

        $this->assertEquals('+43123563434', $actualResult);
    }

    /**
     * @test
     */
    public function telephoneVhRemovesBrackets()
    {
        $actualResult = $this->viewHelper->renderStatic(
            ['value' => '+43 (123) 5634'],
            function () {
            },
            $this->prophesize(RenderingContextInterface::class)->reveal()
        );

        $this->assertEquals('+431235634', $actualResult);
    }

    /**
     * @test
     */
    public function telephoneVhRemoves0inBrackets()
    {
        $actualResult = $this->viewHelper->renderStatic(
            ['value' => '+43 (0) 123 5634'],
            function () {
            },
            $this->prophesize(RenderingContextInterface::class)->reveal()
        );

        $this->assertEquals('+431235634', $actualResult);
    }

    /**
     * @test
     */
    public function telephoneVhKeepsCellPhoneSpecificChars()
    {
        $actualResult = $this->viewHelper->renderStatic(
            ['value' => ' #06*'],
            function () {
            },
            $this->prophesize(RenderingContextInterface::class)->reveal()
        );

        $this->assertEquals('#06*', $actualResult);
    }
}
