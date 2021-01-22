<?php

namespace FriendsOfTYPO3\TtAddress\ViewHelpers;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Prepare a given telephone or fax number to be valid for <input>-attribute "tel".
 * This ViewHelper removes all chars which are not possible to enter on your cell phone.
 */
class TelephoneViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    public function initializeArguments()
    {
        $this->registerArgument(
            'value',
            'string',
            'Enter a telephone, fax or similar number',
            false,
            ''
        );
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $value = trim($arguments['value'] ?: $renderChildrenClosure());

        // Remove 0 on +49(0)221, but keep 0 on (0)221
        if (strpos($value, '(0)') > 0) {
            $value = str_replace('(0)', '', $value);
        }

        return preg_replace('/[^0-9#+*]/', '', $value);
    }
}
