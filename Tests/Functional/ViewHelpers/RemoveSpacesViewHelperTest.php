<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\Tests\Functional\ViewHelpers;

/*
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\tt_address\Tests\Functional\ViewHelpers;

use PHPUnit\Framework\Attributes\IgnoreDeprecations;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextFactory;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3Fluid\Fluid\View\TemplateView;

final class RemoveSpacesViewHelperTest extends FunctionalTestCase
{
    protected bool $initializeDatabase = false;
    protected array $testExtensionsToLoad = ['typo3conf/ext/tt_address'];

    #[Test]
    #[IgnoreDeprecations]
    public function viewHelperFormatsDateCorrectly(): void
    {
        $context = $this->get(RenderingContextFactory::class)->create();
        $context->getTemplatePaths()->setTemplateSource('
            {namespace ttaddr=FriendsOfTYPO3\TtAddress\ViewHelpers}
            <ttaddr:removeSpaces>+43 123 56 34 34</ttaddr:removeSpaces>');
        self::assertSame('+43123563434', trim((new TemplateView($context))->render()));
    }
}
