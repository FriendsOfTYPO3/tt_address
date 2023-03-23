<?php
namespace FriendsOfTYPO3\TtAddress\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;


/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Claus Due <claus@wildside.dk>, Wildside A/S
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Returns the first element of $haystack
 *
 * @author Claus Due, Wildside A/S, modified by Hendrik Reimers
 * @package Vhs
 * @subpackage ViewHelpers\Iterator
 */
class GpViewHelper extends AbstractViewHelper  {

	/**
	 * Initialize arguments
	 *
	 * @return void
	 */
	public function initializeArguments() {
		$this->registerArgument('gp', 'string', 'Global Parameter', FALSE, NULL);
		$this->registerArgument('check', 'string', 'Regular expression to check the value', FALSE, NULL);
		$this->registerArgument('secure', 'boolean', 'Escapes the param string', FALSE, NULL);
	}
	
	/**
	 * Render method
	 *
	 * @return mixed|NULL
	 */
	public function render() {
		$gp   = $this->arguments['gp'];
		$expr = $this->arguments['check'];
		
		if ( $gp === NULL ) $gp = $this->renderChildren();
		if ( $gp === NULL ) return NULL;
		
		if ( $this->controllerContext->getRequest()->hasArgument($gp) ) {
			$retParam = ( $this->arguments['secure'] ) ? htmlentities(strip_tags($this->controllerContext->getRequest()->getArgument($gp))) : $this->controllerContext->getRequest()->getArgument($gp);
			
			if ( $expr !== NULL ) {
				if ( preg_match(stripslashes($expr), $retParam) ) {
					return $retParam;
				} else return NULL;
			} else return $retParam;
		} else return NULL;
	}

}
