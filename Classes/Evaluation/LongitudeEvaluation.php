<?php

namespace TYPO3\TtAddress\Evaluation;

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
 
/**
 * Class for validation/evaluation of Longitude to be used in 'eval' of TCA
 * removes everything except numbers and digit-sign (dot). Fills coordinates up with zeros if too short
 */
class LongitudeEvaluation
{

   /**
   * JavaScript code for client side validation/evaluation
   *
   * @return string JavaScript code for client side validation/evaluation
   */
  public function returnFieldJS()
  {
    // Nice to have: add javascript-code for evalution on blur
    return '
      return value;
    ';
  }

  /**
   * Server-side validation/evaluation on saving the record
   * Tests if latutide is between -90 and +90, fills up with zeros to mach decimal (14,12) in database
   *
   * @param string $value The field value to be evaluated
   * @param string $is_in The "is_in" value of the field configuration from TCA
   * @param bool $set Boolean defining if the value is written to the database or not. Must be passed by reference and changed if needed.
   * @return string Evaluated field value
   */
  public function evaluateFieldValue($value, $is_in, &$set)
  {
    // test if we have any longitude
    if ($value && $value != "") {
      $value = \TYPO3\TtAddress\Utility\EvalcoordinatesUtility::formatCoordinate($value, 1);
    }
    return $value;
  }

  /**
   * Server-side validation/evaluation on opening the record
   *
   * @param array $parameters Array with key 'value' containing the field value from the database
   * @return string Evaluated field value
   */
  public function deevaluateFieldValue(array $parameters)
  {
    // test if we have any longitude
    if ($parameters['value'] && $parameters['value'] != "") {
      $parameters['value'] = \TYPO3\TtAddress\Utility\EvalcoordinatesUtility::formatCoordinate($parameters['value'], 1);
    }
    return $parameters['value'];
  }
  
}