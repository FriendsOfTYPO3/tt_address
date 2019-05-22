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
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Lang\LanguageService;

/**
 * Adds a wizard for location selection via map
 */
class LocationMapWizard extends AbstractNode
{
    /**
     * @return array
     */
    public function render(): array
    {
        $row = $this->data['databaseRow'];
        $paramArray = $this->data['parameterArray'];
        $resultArray = $this->initializeResultArray();

        $nameLongitude = $paramArray['itemFormElName'];
        $nameLatitude = str_replace('longitude', 'latitude', $nameLongitude);
        $nameLatitudeActive = str_replace('data', 'control[active]', $nameLatitude);
        $geoCodeUrl = '';
        $gLat = '55.6760968';
        $gLon = '12.5683371';

        $lat = $row['latitude'] != '' ? htmlspecialchars($row['latitude']) : '';
        $lon = $row['longitude'] != '' ? htmlspecialchars($row['longitude']) : '';

        $geoCodeUrlBase = 'https://nominatim.openstreetmap.org/search/';
        $geoCodeUrlQuery = '?format=json&addressdetails=1&limit=1&polygon_svg=1';
        if ($row['latitude'] || $row['longitude'] == '') {
            // remove all after first slash in address (top, floor ...)
            $address = preg_replace('/^([^\/]*).*$/', '$1', $row['address']) . ' ';
            $address .= $row['city'];
            // if we have at least some address part (saves geocoding calls)
            if ($address) {
                $geoCodeUrlAddress = $address;
                $geoCodeUrlCityOnly = $row['city'];
                // replace newlines with spaces; remove multiple spaces
                $geoCodeUrl = trim(preg_replace('/\s\s+/', ' ', $geoCodeUrlBase . $geoCodeUrlAddress . $geoCodeUrlQuery));
                $geoCodeUrlShort = trim(preg_replace('/\s\s+/', ' ', $geoCodeUrlBase . $geoCodeUrlCityOnly . $geoCodeUrlQuery));
            }
        }

        $pageRenderer = $this->getPageRenderer();
        $labels = ['tt_address.locationMapWizard', 'tt_address.locationMapWizard.close', 'tt_address.locationMapWizard.import'];
        foreach ($labels as $label) {
            $pageRenderer->addInlineLanguageLabel('ttaddress-' . $label, $this->getLanguageService()->sL('LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:' . $label));
        }

        $resultArray['iconIdentifier'] = 'location-map-wizard';
        $resultArray['title'] = $this->getLanguageService()->sL('LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.locationMapWizard');
        $resultArray['linkAttributes']['class'] = 'locationMapWizard ';
        $resultArray['linkAttributes']['id'] = 'location-map-container-a';
        $resultArray['linkAttributes']['data-lat'] = $lat;
        $resultArray['linkAttributes']['data-lon'] = $lon;
        $resultArray['linkAttributes']['data-glat'] = $gLat;
        $resultArray['linkAttributes']['data-glon'] = $gLon;
        $resultArray['linkAttributes']['data-geocodeurl'] = $geoCodeUrl;
        $resultArray['linkAttributes']['data-geocodeurlshort'] = $geoCodeUrlShort;
        $resultArray['linkAttributes']['data-searchurl'] = $geoCodeUrlBase . '###ADDRESS###' . $geoCodeUrlQuery;
        $resultArray['linkAttributes']['data-namelat'] = htmlspecialchars($nameLatitude);
        $resultArray['linkAttributes']['data-namelon'] = htmlspecialchars($nameLongitude);
        $resultArray['linkAttributes']['data-namelat-active'] = htmlspecialchars($nameLatitudeActive);
        $resultArray['linkAttributes']['data-tiles'] = htmlspecialchars('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
        $resultArray['linkAttributes']['data-copy'] = '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors';
        $resultArray['stylesheetFiles'][] = 'EXT:tt_address/Resources/Public/Contrib/leaflet-core-1.4.0.css';
        $resultArray['stylesheetFiles'][] = 'EXT:tt_address/Resources/Public/Backend/LocationMapWizard/leafletBackend.css';
        $resultArray['requireJsModules'][] = 'TYPO3/CMS/TtAddress/leaflet-core-1.4.0';
        $resultArray['requireJsModules'][] = 'TYPO3/CMS/TtAddress/LeafletBackend';

        return $resultArray;
    }

    /**
     * @return LanguageService
     */
    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }

    /**
     * @return PageRenderer
     */
    protected function getPageRenderer(): PageRenderer
    {
        return GeneralUtility::makeInstance(PageRenderer::class);
    }
}
