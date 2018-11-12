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
 * Class for telephonenumber validation/evaluation to be used in 'eval' of TCA
 * removes everything except numbers and the plus-sign
 */
class TelephoneEvaluation
{

   /**
    * JavaScript code for client side validation/evaluation
    *
    * @return string JavaScript code for client side validation/evaluation
    */
   public function returnFieldJS()
   {
      return '
         return value.replace(/[^\d\+]/g, "");
      ';
   }

   /**
    * Server-side validation/evaluation on saving the record
    *
    * @param string $value The field value to be evaluated
    * @param string $is_in The "is_in" value of the field configuration from TCA
    * @param bool $set Boolean defining if the value is written to the database or not. Must be passed by reference and changed if needed.
    * @return string Evaluated field value
    */
   public function evaluateFieldValue($value, $is_in, &$set)
   {
      $value = preg_replace("/[^\d\+]/", "", $value);
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
     $parameters['value'] = preg_replace("/[^\d\+]/", "", $parameters['value']);
     return $parameters['value'];
   }

}