<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\ViewHelpers\Clean;

/*
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use FriendsOfTYPO3\TtAddress\Utility\PropertyModification;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class DomainViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('value', 'string', 'value');
    }

    /**
     * @return string
     */
    public function render()
    {
        $value = $this->arguments['value'] ?: $this->renderChildren();
        return PropertyModification::getCleanedDomain($value);
    }
}
