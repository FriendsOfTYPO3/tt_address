<?php

namespace FriendsOfTYPO3\TtAddress\Domain\Model\Dto;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class Settings
 */
class Settings
{
    /** @var string */
    protected $backwardsCompatFormat = '%1$s %3$s';

    /** @var bool */
    protected $storeBackwardsCompatName = true;

    /** @var bool */
    protected $readOnlyNameField = true;

    /** @var string */
    protected $telephoneValidationPatternForPhp = '/[^\d\+\s\-]/';

    /** @var string */
    protected $telephoneValidationPatternForJs = '/[^\d\+\s\-]/g';

    /** @var bool */
    protected $newPagination = false;

    /**
     */
    public function __construct()
    {
        $settings = $this->getSettings();

        if (!empty($settings)) {
            $this->backwardsCompatFormat = trim((string)$settings['backwardsCompatFormat']);
            $this->storeBackwardsCompatName = (bool)$settings['storeBackwardsCompatName'];
            $this->readOnlyNameField = (bool)$settings['readOnlyNameField'];
            $this->newPagination = (bool)($settings['newPagination'] ?? false);

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
    public function getNewPagination(): bool
    {
        return $this->newPagination;
    }

    protected function getSettings(): array
    {
        try {
            return GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('tt_address');
        } catch (\Exception $e) {
            return [];
        }
    }
}
