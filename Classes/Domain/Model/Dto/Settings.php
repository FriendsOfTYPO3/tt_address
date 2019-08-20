<?php

namespace FriendsOfTYPO3\TtAddress\Domain\Model\Dto;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\SingletonInterface;

/**
 * Class Settings
 */
class Settings implements SingletonInterface
{
    /** @var string */
    protected $backwardsCompatFormat = '%1$s %3$s';

    /** @var bool */
    protected $storeBackwardsCompatName = true;

    /** @var bool */
    protected $readOnlyNameField = true;

    /** @var bool */
    protected $activatePiBase = false;

    /** @var string */
    protected $telephoneValidationPatternForPhp = '/[^\d\+\s\-]/';

    /** @var string */
    protected $telephoneValidationPatternForJs = '/[^\d\+\s\-]/g';

    /** @var bool */
    protected $treatGermanUmlautsAsLatinCharacters = false;

    /**
     */
    public function __construct()
    {
        $settings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tt_address']);

        if (\is_array($settings) && !empty($settings)) {
            $this->backwardsCompatFormat = trim((string)$settings['backwardsCompatFormat']);
            $this->storeBackwardsCompatName = (bool)$settings['storeBackwardsCompatName'];
            $this->readOnlyNameField = (bool)$settings['readOnlyNameField'];
            $this->activatePiBase = (bool)$settings['activatePiBase'];
            $this->treatGermanUmlautsAsLatinCharacters = (bool)$settings['treatGermanUmlautsAsLatinCharacters'];

            if ($settings['telephoneValidationPatternForPhp']) {
                $this->telephoneValidationPatternForPhp = (string)$settings['telephoneValidationPatternForPhp'];
            }
            if ($settings['telephoneValidationPatternForJs']) {
                $this->telephoneValidationPatternForJs = (string)$settings['telephoneValidationPatternForJs'];
            }
        }
    }

    /**
     * @return string
     */
    public function getBackwardsCompatFormat(): string
    {
        return $this->backwardsCompatFormat;
    }

    /**
     * @return bool
     */
    public function isStoreBackwardsCompatName(): bool
    {
        return $this->storeBackwardsCompatName;
    }

    /**
     * @return bool
     */
    public function isReadOnlyNameField(): bool
    {
        return $this->readOnlyNameField;
    }

    /**
     * @return bool
     */
    public function isActivatePiBase(): bool
    {
        return $this->activatePiBase;
    }

    /**
     * @return string
     */
    public function getTelephoneValidationPatternForPhp(): string
    {
        return $this->telephoneValidationPatternForPhp;
    }

    /**
     * @return string
     */
    public function getTelephoneValidationPatternForJs(): string
    {
        return $this->telephoneValidationPatternForJs;
    }

    /**
     * @return bool
     */
    public function getTreatGermanUmlautsAsLatinCharacters(): bool
    {
        return $this->treatGermanUmlautsAsLatinCharacters;
    }
}
