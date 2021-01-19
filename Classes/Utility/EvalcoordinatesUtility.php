<?php
declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\Utility;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Class EvalcoordinatesUtility
 */
class EvalcoordinatesUtility
{
    const LATITUDE_UPPER = '90.00000000';
    const LONGITUDE_UPPER = '180.00000000';

    /**
     * @param string $coordinate
     * @return float evaluated and well-formed coordinate
     */
    public static function formatLongitude(string $coordinate)
    {
        return self::validate($coordinate, self::LONGITUDE_UPPER);
    }

    /**
     * @param string $coordinate
     * @return float evaluated and well-formed coordinate
     */
    public static function formatLatitude(string $coordinate)
    {
        return self::validate($coordinate, self::LATITUDE_UPPER);
    }

    /**
     * @param string $coordinate
     * @param string $upperRange
     * @return string
     */
    protected static function validate($coordinate, string $upperRange): string
    {
        if ($coordinate === '') {
            return '.00000000';
        }

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

        // fill up with zeros or shorten to match our goal of decimal(latitude: 10,8 and longitude: 11,8) in DB
        if (\strlen($decimalPart) >= 8) {
            $decimalPart = substr($decimalPart, 0, 8);
        } else {
            $decimalPart = str_pad($decimalPart, 8, '0', STR_PAD_RIGHT);
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
