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
 * Modify properties of the address model
 */
class PropertyModification
{
    /**
     * Get cleaned number of a given telephone, fax or mobile number.
     * It removes all chars which are not possible to enter on your cell phone.
     *
     * @param string $number
     * @return string
     */
    public static function getCleanedNumber(string $number): string
    {
        $number = trim($number);

        // Remove 0 on +49(0)221, but keep 0 on (0)221
        if (strpos($number, '(0)') > 0) {
            $number = str_replace('(0)', '', $number);
        }

        return preg_replace('/[^0-9#+*]/', '', $number);
    }

    public static function getCleanedDomain(string $domain): string
    {
        $domain = trim($domain);
        if (!$domain) {
            return '';
        }
        $parts = str_replace(['\\\\', '\\"'], ['\\', '"'], str_getcsv($domain, ' '));
        return $parts[0];
    }
}
