<?php

namespace FriendsOfTYPO3\TtAddress\Utility;

/**
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
 * Class EvalcoordinatesUtility
 */
class EvalcoordinatesUtility
{

    const LATITUDE_UPPER = '90.000000000000';
    const LONGITUDE_UPPER = '180.000000000000';

    /**
     * @param $coordinate
     * @return float evaluated and well-formed coordinate
     */
    public static function formatLongitude(string $coordinate)
    {
        return self::validate($coordinate, self::LONGITUDE_UPPER);
    }

    /**
     * @param $coordinate
     * @return float evaluated and well-formed coordinate
     */
    public static function formatLatitude(string $coordinate)
    {
        return self::validate($coordinate, self::LATITUDE_UPPER);
    }

    /**
     * @param $coordinate
     * @param string $upperRange
     * @return string
     */
    protected static function validate($coordinate, string $upperRange): string
    {
// test if value is negative
        $negative = '';
        if ($coordinate[0] === '-') {
            $negative = '-';
        }
        // remove all chars not being digits and point
        // therefore we will get a number
        $coordinate = preg_replace("/[^\d\.]/", '', $coordinate);

        // split up string at first occurrence decimal point without losing data
        $integerPart = strstr($coordinate, '.', true);
        $decimalPart = strstr($coordinate, '.');

        // if coordinate is given as integer (no decimal point)
        if ($integerPart === false) {
            $integerPart = $coordinate;
        }
        if ($decimalPart === false) {
            $decimalPart = '00';
        }

        // remove all points from decimal-part
        $decimalPart = preg_replace("/[^\d]/", '', $decimalPart);

        // fill up with zeros or shorten to match our goal of decimal(14,12) in DB
        if (\strlen($decimalPart) >= 12) {
            $decimalPart = substr($decimalPart, 0, 12);
        } else {
            $decimalPart = str_pad($decimalPart, 12, '0', STR_PAD_RIGHT);
        }

        // concatenate the whole string to a well-formed longitude and return
        $coordinate = $integerPart . '.' . $decimalPart;

        // test if value is in the possible range. longitude can be -180 to +180.
        // latitude can be -90 to +90
        // At this point, our minus, if there, is stored to 'negative'
        // therefore we just test if integerpart is bigger than 90
        if ($coordinate > $upperRange) {
            $coordinate = $upperRange;
        }

        // reapply signed/unsigned and return
        return $negative . $coordinate;
    }
}
