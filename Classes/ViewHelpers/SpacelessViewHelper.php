<?php

namespace TYPO3\TtAddress\ViewHelpers;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

class SpacelessViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /**
     * Removes all whitespaces from given string.
     * @param mixed $value The value
     * @return string
     */
    public function render($value = null)
    {
        if ($value === null) {
            $value =  $this->renderChildren();
        }
        // remove all whitespaces
        $value = str_replace(' ', '', $value);
        return $value;
    }
}
