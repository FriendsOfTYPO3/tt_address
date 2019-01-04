<?php
declare(strict_types = 1);

namespace FriendsOfTYPO3\TtAddress\FormEngine\FieldControl;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Backend\Form\AbstractNode;

class LocationMapWizard extends AbstractNode
{
    /**
     * @return array
     */
    public function render()
    {
        $row = $this->data['databaseRow'];
        $paramArray = $this->data['parameterArray'];
        $resultArray = $this->initializeResultArray();

        $nameLon = $paramArray['itemFormElName'];
        $nameLat = str_replace('longitude', 'latitude', $nameLon);
        $nameLatActive = str_replace('data', 'control[active]', $nameLat);
        $geoCodeUrl = '';

        if ($row['latitude'] != '') {
            $lat = htmlspecialchars($row['latitude']);
        } else {
            $lat = '';
        }
        if ($row['longitude'] != '') {
            $lon = htmlspecialchars($row['longitude']);
        } else {
            $lon = '';
        }

        if ($row['latitude'] || $row['longitude'] == '') {
            // remove all after first slash in address (top, floor ...)
            $address = preg_replace('/^([^\/]*).*$/', '$1', $row['address']) . ' ';
            $address .= $row['city'];
            // if we have at least some address part (saves geocoding calls)
            if ($address != '') {
                // base url
                $geoCodeUrl = 'https://nominatim.openstreetmap.org/search/';
                $geoCodeUrl .= $address;
                // urlparams for nominatim which are fixed.
                $geoCodeUrl .= '?format=json&addressdetails=1&limit=1&polygon_svg=1';
                // replace newlines with spaces; remove multiple spaces
                $geoCodeUrl = trim(preg_replace('/\s\s+/', ' ', $geoCodeUrl));
            } else {
                $geoCodeUrl = '';
            }
        }

        $resultArray['iconIdentifier'] = 'location-map-wizard';
        $resultArray['title'] = $GLOBALS['LANG']->sL('LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.locationMapWizard');
        $resultArray['linkAttributes']['class'] = 'locationMapWizard ';
        $resultArray['linkAttributes']['data-lat'] = $lat;
        $resultArray['linkAttributes']['data-lon'] = $lon;
        $resultArray['linkAttributes']['data-geocodeurl'] = $geoCodeUrl;
        $resultArray['linkAttributes']['data-namelat'] = htmlspecialchars($nameLat);
        $resultArray['linkAttributes']['data-namelon'] = htmlspecialchars($nameLon);
        $resultArray['linkAttributes']['data-namelat-active'] = htmlspecialchars($nameLatActive);
        $resultArray['linkAttributes']['id'] = 'location-map-container-a';
        $resultArray['stylesheetFiles'][] = 'EXT:tt_address/Resources/Public/Css/leaflet.css';
        $resultArray['stylesheetFiles'][] = 'EXT:tt_address/Resources/Public/Css/leafletBackend.css';
        $resultArray['requireJsModules'][] = 'TYPO3/CMS/TtAddress/leaflet-1.4.0';
        $resultArray['requireJsModules'][] = 'TYPO3/CMS/TtAddress/LeafletBackend';

        return $resultArray;
    }
}
