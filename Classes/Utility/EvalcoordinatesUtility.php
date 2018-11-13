<?php

namespace TYPO3\TtAddress\Utility;

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

  /**
   * Format coordinates to our needs, depending on the recommended database field type, which is decimal(14,12) or decimal(15,12)
   * @param string $coordinate
   * @param bool $isLongitude 1=longitude, 0=latitude
   * @return evaluated and well-formed coordinate
   */
  public static function formatCoordinate($coordinate, $isLongitude = 0) {
  
    // ATTN: use quotation, otherwise it will be rounded to 180/90
    if ($isLongitude == 1) {
      $upperRange = "180.000000000000";
    } else {
      $upperRange = "90.000000000000";
    }
    
    // test if value is negative
    $negative = "";
    if($coordinate[0] == "-") {
      $negative = "-";
    }
    // remove all chars not being digits and point
    // therefore we will get a number
    $coordinate = preg_replace("/[^\d\.]/", "", $coordinate);
    
    // split up string at first occurrence decimal point without losing data
    $integerPart = strstr($coordinate, ".", TRUE);
    $decimalPart = strstr($coordinate, ".");
    
    // if coordinate is given as integer (no decimal point)
    if ($integerPart === FALSE) {
      $integerPart = $coordinate;
    }
    if ($decimalPart === FALSE) {
      $decimalPart = "00";
    }
    
    // remove all points from decimal-part
    $decimalPart = preg_replace("/[^\d]/", "", $decimalPart);
    
    // fill up with zeros or shorten to match our goal of decimal(14,12) in DB
    if (strlen($decimalPart) >= 12) {
      $decimalPart = substr($decimalPart, 0, 12);
    } else {
      $decimalPart = str_pad($decimalPart, 12, "0", STR_PAD_RIGHT);
    }
    
    // concatenate the whole string to a well-formed longitude and return
    $coordinate = $integerPart . "." . $decimalPart;
    
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
