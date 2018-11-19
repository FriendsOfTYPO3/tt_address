<?php

namespace FriendsOfTypo3\TtAddress\Tests\Unit\ViewHelpers;

use FriendsOfTYPO3\TtAddress\ViewHelpers\RemoveSpacesViewHelper;
use TYPO3\TestingFramework\Core\BaseTestCase;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

class RemoveSpacesViewHelperTest extends BaseTestCase
{
    /**
     * @var RemoveSpacesViewHelper
     */
    protected $viewHelper;

    protected function setUp()
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
