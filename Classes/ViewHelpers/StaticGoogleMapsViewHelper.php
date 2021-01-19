<?php
declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\ViewHelpers;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use FriendsOfTYPO3\TtAddress\Domain\Model\Address;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

class StaticGoogleMapsViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * Initialize arguments
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('addresses', 'mixed', 'Addresses', true);
        $this->registerArgument('parameters', 'array', 'Parameters', true);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $mapArguments = $arguments['parameters'];

        $markers = [];
        foreach ($arguments['addresses'] as $address) {
            /** @var Address $address */
            $markers[] = '&markers=' . $address->getLatitude() . ',' . $address->getLongitude();
        }
        if (count($markers) === 1) {
            $mapArguments['zoom'] = 13;
        }

        return 'https://maps.googleapis.com/maps/api/staticmap?' . GeneralUtility::implodeArrayForUrl('', $mapArguments, '', true) . implode('', $markers);
    }
}
