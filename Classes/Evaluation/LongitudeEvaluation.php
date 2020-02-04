<?php

namespace FriendsOfTYPO3\TtAddress\Evaluation;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Utility\EvalcoordinatesUtility;

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
        // Nice to have: add javascript-code for evaluation on blur
        return '
      return value;
    ';
    }

    /**
     * Server-side validation/evaluation on saving the record
     * Tests if latitude is between -90 and +90, fills up with zeros to mach decimal (14,12) in database
     *
     * @param string $value The field value to be evaluated
     * @return string Evaluated field value
     */
    public function evaluateFieldValue($value)
    {
        // test if we have any longitude
        if ($value && $value != '') {
            $value = EvalcoordinatesUtility::formatLongitude($value);
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
        if ($parameters['value'] && $parameters['value'] != '') {
            $parameters['value'] = EvalcoordinatesUtility::formatLongitude($parameters['value']);
        }
        return $parameters['value'];
    }
}
