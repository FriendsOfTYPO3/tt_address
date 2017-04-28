<?php
namespace TYPO3\TtAddress\Domain\Model\Dto;

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

/**
 * Class Settings
 */
class Settings
{
    /**
     * @var string
     */
    protected $backwardsCompatFormat = '%1$s %3$s';

    /**
     * @var bool
     */
    protected $storeBackwardsCompatName = true;

    /**
     * @var bool
     */
    protected $readOnlyNameField = true;

    /**
     * @param array $settings extension manager settings
     */
    public function __construct(array $settings)
    {
        foreach ($settings as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        $this->enforceCorrectPropertyTypes();
    }

    /**
     * @return void
     */
    protected function enforceCorrectPropertyTypes()
    {
        $this->backwardsCompatFormat = trim((string)$this->backwardsCompatFormat);
        $this->storeBackwardsCompatName = (bool)$this->storeBackwardsCompatName;
        $this->readOnlyNameField = (bool)$this->readOnlyNameField;
    }

    /**
     * @return string
     */
    public function getBackwardsCompatFormat()
    {
        return $this->backwardsCompatFormat;
    }

    /**
     * @return bool
     */
    public function isStoreBackwardsCompatName()
    {
        return $this->storeBackwardsCompatName;
    }

    /**
     * @return bool
     */
    public function isReadOnlyNameField()
    {
        return $this->readOnlyNameField;
    }
}
