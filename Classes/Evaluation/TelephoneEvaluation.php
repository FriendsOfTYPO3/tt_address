<?php

namespace FriendsOfTYPO3\TtAddress\Evaluation;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
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
         return value.replace(/[^\d\+\s]/g, "");
      ';
    }

    /**
     * Server-side validation/evaluation on saving the record
     *
     * @param string $value The field value to be evaluated
     * @return string Evaluated field value
     */
    public function evaluateFieldValue($value)
    {
        return $this->evaluate($value);
    }

    /**
     * Server-side validation/evaluation on opening the record
     *
     * @param array $parameters Array with key 'value' containing the field value from the database
     * @return string Evaluated field value
     */
    public function deevaluateFieldValue(array $parameters)
    {
        return $this->evaluate($parameters['value']);
    }

    private function evaluate(string $in)
    {
        $data = preg_replace("/[^\d\+\s]/", '', $in);
        return trim($data);
    }
}
