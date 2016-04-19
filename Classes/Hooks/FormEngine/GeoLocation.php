<?php
namespace TYPO3\TtAddress\Hooks\FormEngine;

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

use TYPO3\CMS\Backend\Form\AbstractNode;

/**
 * Class GeoLocation
 */
class GeoLocation extends AbstractNode
{
    /**
     * @return array
     */
    public function render()
    {
        $resultArray = $this->initializeResultArray();

        $row = $this->data['databaseRow'];

        $nameLat = $this->data['parameterArray']['itemFormElName'];
        $nameLng = str_replace('latitude', 'longitude', $nameLat);

        $markup = [];
        $markup[] = '<input type="hidden" name="' . htmlspecialchars($nameLat)
                    . '" value="' . htmlspecialchars($row['latitude']) . '" />';
        $markup[] = '<div class="t3js-google-maps" data-field-lat="' . htmlspecialchars($nameLat)
                    . '" data-field-lng="' . htmlspecialchars($nameLng) . '" style="width: 60%; height:300px"></div>';
        $markup[] = '<script src="https://maps.googleapis.com/maps/api/js"></script>';

        $resultArray['html'] = implode(LF, $markup);
        $resultArray['requireJsModules'][] = 'TYPO3/CMS/TtAddress/GoogleMaps';
        return $resultArray;
    }
}
