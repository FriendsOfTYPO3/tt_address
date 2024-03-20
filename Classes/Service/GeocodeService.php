<?php
declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\Service;

use TYPO3\CMS\Core\Cache\CacheManager;
/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryHelper;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Service for category related stuff
 *
 * thanks to https://github.com/b13/t3ext-geocoding for inspiration
 */
class GeocodeService implements SingletonInterface
{
    /** @var int */
    protected $cacheTime = 7776000;

    /** @var string  */
    protected $geocodingUrl = 'https://maps.googleapis.com/maps/api/geocode/json?language=de&sensor=false';

    public function __construct(string $googleMapsKey = '')
    {
        $this->geocodingUrl .= '&key=' . $googleMapsKey;
    }

    /**
     * geocodes all missing records in a DB table and then stores the values
     * in the DB record.
     *
     * only works if your DB table has the necessary fields
     * helpful when calculating a batch of addresses and save the latitude/longitude automatically
     *
     * @param string $addWhereClause
     * @return int
     */
    public function calculateCoordinatesForAllRecordsInTable($addWhereClause = ''): int
    {
        $tableName = 'tt_address';
        $latitudeField = 'latitude';
        $longitudeField = 'longitude';
        $streetField = 'address';
        $zipField = 'zip';
        $cityField = 'city';
        $countryField = 'country';

        // Fetch all records without latitude/longitude
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($tableName);
        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->getRestrictions()
            ->removeAll()
            ->add(GeneralUtility::makeInstance(DeletedRestriction::class));
        $queryBuilder
            ->select('*')
            ->from($tableName)
            ->where(
                $queryBuilder->expr()->or(
                    $queryBuilder->expr()->isNull($latitudeField),
                    $queryBuilder->expr()->eq($latitudeField, $queryBuilder->createNamedParameter(0, Connection::PARAM_INT)),
                    $queryBuilder->expr()->eq($latitudeField, 0.00000000000),
                    $queryBuilder->expr()->isNull($longitudeField),
                    $queryBuilder->expr()->eq($longitudeField, $queryBuilder->createNamedParameter(0, Connection::PARAM_INT)),
                    $queryBuilder->expr()->eq($longitudeField, 0.00000000000)
                )
            )
            ->setMaxResults(500);
        if (!empty($addWhereClause)) {
            $queryBuilder->andWhere(QueryHelper::stripLogicalOperatorPrefix($addWhereClause));
        }
        $records = $queryBuilder->executeQuery()->fetchAllAssociative();
        if (\count($records) > 0) {
            foreach ($records as $record) {
                $country = $record[$countryField];

                // do the geocoding
                if (!empty($record[$zipField]) || !empty($record[$cityField])) {
                    $coords = $this->getCoordinatesForAddress($record[$streetField], $record[$zipField], $record[$cityField], $country);
                    if ($coords) {
                        // Update the record to fill in the latitude and longitude values in the DB
                        $connection->update(
                            $tableName,
                            [
                                $latitudeField => $coords['latitude'],
                                $longitudeField => $coords['longitude'],
                            ],
                            [
                                'uid' => $record['uid']
                            ]
                        );
                    }
                }
            }
        }
        return \count($records);
    }

    /**
     * core functionality: asks google for the coordinates of an address
     * stores known addresses in a local cache.
     *
     * @param string $street
     * @param string $zip
     * @param string $city
     * @param string $country
     * @return array an array with latitude and longitude
     */
    public function getCoordinatesForAddress($street = null, $zip = null, $city = null, $country = ''): array
    {
        $addressParts = [];
        foreach ([$street, $zip . ' ' . $city, $country] as $addressPart) {
            if (empty($addressPart)) {
                continue;
            }
            $addressParts[] = trim($addressPart);
        }
        $address = ltrim(implode(',', $addressParts), ',');
        if (empty($address)) {
            return [];
        }
        $cacheObject = $this->initializeCache();
        $cacheKey = 'geocode-' . strtolower(str_replace(' ', '-', preg_replace('/[^0-9a-zA-Z ]/m', '', $address)));
        // Found in cache? Return it.
        if ($cacheObject->has($cacheKey)) {
            return $cacheObject->get($cacheKey);
        }
        $result = $this->getApiCallResult(
            $this->geocodingUrl . '&address=' . urlencode($address)
        );
        if (empty($result) || empty($result['results']) || empty($result['results'][0]['geometry'])) {
            return [];
        }
        $geometry = $result['results'][0]['geometry'];
        $result = [
            'latitude' => $geometry['location']['lat'],
            'longitude' => $geometry['location']['lng'],
        ];
        // Now store the $result in cache and return
        $cacheObject->set($cacheKey, $result, [], $this->cacheTime);
        return $result;
    }

    /**
     * @param string $url
     * @return array
     */
    protected function getApiCallResult(string $url): array
    {
        $response = GeneralUtility::getUrl($url);
        $result = json_decode($response, true);
        if ($result['status'] !== 'OVER_QUERY_LIMIT') {
            return $result;
        }
        return [];
    }

    /**
     * Initializes the cache for the DB requests.
     *
     * @param string $name
     * @return FrontendInterface Cache Object
     */
    protected function initializeCache(string $name = 'ttaddress_geocoding'): FrontendInterface
    {
        try {
            $cacheManager = GeneralUtility::makeInstance(CacheManager::class);
            return $cacheManager->getCache($name);
        } catch (NoSuchCacheException $e) {
            throw new \RuntimeException('Unable to load Cache!', 1548785854);
        }
    }
}
