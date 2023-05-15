<?php
declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\Evaluation;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Utility\EvalcoordinatesUtility;

/**
 * Class for validation/evaluation of longitude to be used in 'eval' of TCA
 * removes everything except numbers and digit-sign (dot). Fills coordinates up with zeros if too short
 */
class LatitudeEvaluation
{
    /**
     * Server-side validation/evaluation on saving the record
     * Tests if latutide is between -90 and +90, fills up with zeros to mach decimal (14,12) in database
     *
     * @param string $value The field value to be evaluated
     * @return string Evaluated field value
     */
    public function evaluateFieldValue($value)
    {
        // test if we have any latitude
        if ($value && $value !== '') {
            return EvalcoordinatesUtility::formatLatitude($value);
        }
        return null;
    }

    /**
     * Server-side validation/evaluation on opening the record
     *
     * @param array $parameters Array with key 'value' containing the field value from the database
     * @return string Evaluated field value
     */
    public function deevaluateFieldValue(array $parameters)
    {
        // test if we have any latitude
        if ($parameters['value'] && $parameters['value'] !== '') {
            $parameters['value'] = EvalcoordinatesUtility::formatLatitude($parameters['value']);
        }
        return $parameters['value'];
    }
}
