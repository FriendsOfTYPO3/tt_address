<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\Domain\Model\Dto;

/*
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Settings
{
    protected string $telephoneValidationPatternForPhp = '/[^\d\+\s\-]/';
    protected string $telephoneValidationPatternForJs = '/[^\d\+\s\-]/g';
    protected bool $newPagination = false;

    public function __construct()
    {
        $settings = $this->getSettings();

        if (!empty($settings)) {
            $this->newPagination = (bool) ($settings['newPagination'] ?? false);

            if ($settings['telephoneValidationPatternForPhp'] ?? '') {
                $this->telephoneValidationPatternForPhp = (string) $settings['telephoneValidationPatternForPhp'];
            }
            if ($settings['telephoneValidationPatternForJs'] ?? '') {
                $this->telephoneValidationPatternForJs = (string) $settings['telephoneValidationPatternForJs'];
            }
        }
    }

    public function getTelephoneValidationPatternForPhp(): string
    {
        return $this->telephoneValidationPatternForPhp;
    }

    public function getTelephoneValidationPatternForJs(): string
    {
        return $this->telephoneValidationPatternForJs;
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
