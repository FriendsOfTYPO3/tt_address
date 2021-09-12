<?php
declare(strict_types=1);

namespace FriendsOfTypo3\TtAddress\Tests\Unit\ViewHelpers;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\ViewHelpers\RemoveSpacesViewHelper;
use Prophecy\PhpUnit\ProphecyTrait;
use TYPO3\TestingFramework\Core\BaseTestCase;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

class RemoveSpacesViewHelperTest extends BaseTestCase
{
    use ProphecyTrait;

    /**
     * @var RemoveSpacesViewHelper
     */
    protected $viewHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->viewHelper = new RemoveSpacesViewHelper();
        $this->viewHelper->initializeArguments();
    }

    /**
     * @test
     */
    public function spacelessVhIsCalled()
    {
        $actualResult = $this->viewHelper->renderStatic(
            ['value' => ' +43 123 56 34 34 '],
            function () {
            },
            $this->prophesize(RenderingContextInterface::class)->reveal()
        );

        $this->assertEquals('+43123563434', $actualResult);
    }
}
