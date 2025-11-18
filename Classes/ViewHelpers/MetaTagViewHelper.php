<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\ViewHelpers;

/*
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\MetaTag\MetaTagManagerRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class MetaTagViewHelper extends AbstractViewHelper
{
    /**
     * Initialize arguments
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('property', 'string', 'Property to be set', true);
        $this->registerArgument('value', 'string', 'value');
    }

    /**
     * @return string
     */
    public function render()
    {
        $value = trim($this->arguments['value'] ?: $this->renderChildren());
        if ($value) {
            $property = $this->arguments['property'];
            $metaTagManager = GeneralUtility::makeInstance(MetaTagManagerRegistry::class)->getManagerForProperty($property);
            // @extensionScannerIgnoreLine
            $metaTagManager->addProperty($property, $value);
        }
    }
}
