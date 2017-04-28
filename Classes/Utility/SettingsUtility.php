<?php

namespace TYPO3\TtAddress\Utility;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TtAddress\Domain\Model\Dto\Settings;

/**
 * Class SettingsUtility
 */
class SettingsUtility
{
    /**
     * @var Settings
     */
    protected static $settings;

    /**
     * @return Settings
     */
    public static function getSettings()
    {
        if (!isset(self::$settings)) {
            self::$settings = self::parseSettings();
        }
        return self::$settings;
    }

    /**
     * @return Settings
     */
    protected static function parseSettings()
    {
        $settings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tt_address']);

        if (!is_array($settings)) {
            $settings = array();
        }

        return GeneralUtility::makeInstance('TYPO3\\TtAddress\\Domain\\Model\\Dto\\Settings', $settings);
    }
}
