<?php
declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\Utility;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * TypoScript Utility class
 */
class TypoScript
{
    /**
     * @param array $previousData
     * @param array $tsData
     * @return array
     */
    public function override(array $previousData, array $tsData)
    {
        $validFields = GeneralUtility::trimExplode(',', $tsData['settings']['overrideFlexformSettingsIfEmpty'], true);
        foreach ($validFields as $fieldName) {
            // Multilevel field
            if (strpos($fieldName, '.') !== false) {
                $keyAsArray = explode('.', $fieldName);

                $foundInCurrentTs = $this->getValue($previousData, $keyAsArray);

                if (is_string($foundInCurrentTs) && strlen($foundInCurrentTs) === 0) {
                    $foundInOriginal = $this->getValue($tsData['settings'], $keyAsArray);
                    if ($foundInOriginal) {
                        $previousData = $this->setValue($previousData, $keyAsArray, $foundInOriginal);
                    }
                }
            } else {
                if ($fieldName === 'sortBy' && (($previousData['sortBy'] ?? '') === 'default') && (($tsData['settings']['sortBy'] ?? '') !== '')) {
                    unset($previousData['sortBy']);
                }
                // if flexform setting is empty and value is available in TS
                if (((!isset($previousData[$fieldName]) || (string)$previousData[$fieldName] === '') || (strlen($previousData[$fieldName]) === 0))
                    && isset($tsData['settings'][$fieldName])
                ) {
                    $previousData[$fieldName] = $tsData['settings'][$fieldName];
                }
            }
        }
        return $previousData;
    }

    /**
     * Get value from array by path
     *
     * @param array $data
     * @param array $path
     * @return array|null
     */
    protected function getValue(array $data, array $path)
    {
        $found = true;

        for ($x = 0; $x < count($path) && $found; $x++) {
            $key = $path[$x];

            if (isset($data[$key])) {
                $data = $data[$key];
            } else {
                $found = false;
            }
        }

        if ($found) {
            return $data;
        }
        return null;
    }

    /**
     * Set value in array by path
     *
     * @param array $array
     * @param $path
     * @param $value
     * @return array
     */
    protected function setValue(array $array, $path, $value)
    {
        $this->setValueByReference($array, $path, $value);

        return array_merge_recursive([], $array);
    }

    /**
     * Set value by reference
     *
     * @param array $array
     * @param array $path
     * @param $value
     */
    private function setValueByReference(array &$array, array $path, $value)
    {
        while (count($path) > 1) {
            $key = array_shift($path);
            $array = &$array[$key];
        }

        $key = reset($path);
        $array[$key] = $value;
    }
}
