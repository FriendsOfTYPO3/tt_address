<?php

namespace TYPO3\TtAddress\ViewHelpers\Widget;

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

use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * This ViewHelper renders a Pagination of objects.
 *
 * = Examples =
 *
 * <code title="required arguments">
 * <f:widget.paginate objects="{addresses}" as="paginatedAddresses">
 *   // use {paginatedAddresses} as you used {adresses} before, most certainly inside
 *   // a <f:for> loop.
 * </f:widget.paginate>
 * </code>
 */
class PaginateViewHelper extends \TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetViewHelper
{

    /**
     * @var \TYPO3\TtAddress\ViewHelpers\Widget\Controller\PaginateController
     */
    protected $controller;

    /**
     * Inject controller
     *
     * @param \TYPO3\TtAddress\ViewHelpers\Widget\Controller\PaginateController $controller
     */
    public function injectController(\TYPO3\TtAddress\ViewHelpers\Widget\Controller\PaginateController $controller)
    {
        $this->controller = $controller;
    }

    /**
     * Initialize arguments
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('objects', QueryResultInterface::class, 'Objects to auto-complete', true);
        $this->registerArgument('as', 'string', 'Property to fill', true);
        $this->registerArgument('configuration', 'array', 'Configuration', false, ['itemsPerPage' => 10, 'insertAbove' => false, 'insertBelow' => true]);
        $this->registerArgument('initial', 'array', 'Initial configuration', false, []);
    }

    /**
     * Render everything
     *
     * @return string
     */
    public function render()
    {
        return $this->initiateSubRequest();
    }
}
