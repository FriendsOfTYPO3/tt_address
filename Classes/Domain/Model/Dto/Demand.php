<?php
declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\Domain\Model\Dto;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
class Demand
{
    /** @var array */
    protected $pages = [];

    /** @var string */
    protected $sortBy = '';

    /** @var string */
    protected $sortOrder = '';

    /** @var string */
    protected $categories = '';

    /** @var bool */
    protected $includeSubCategories = false;

    /** @var string */
    protected $categoryCombination = '';

    /** @var string */
    protected $singleRecords = '';

    /** @var bool */
    protected $ignoreWithoutCoordinates = false;

    /**
     * @return array
     */
    public function getPages(): array
    {
        return $this->pages;
    }

    /**
     * @param array $pages
     */
    public function setPages(array $pages)
    {
        $this->pages = $pages;
    }

    /**
     * @return string
     */
    public function getSortBy(): string
    {
        return $this->sortBy;
    }

    /**
     * @param string $sortBy
     */
    public function setSortBy(string $sortBy)
    {
        $this->sortBy = $sortBy;
    }

    /**
     * @return string
     */
    public function getSortOrder(): string
    {
        return $this->sortOrder;
    }

    /**
     * @param string $sortOrder
     */
    public function setSortOrder(string $sortOrder)
    {
        $this->sortOrder = $sortOrder;
    }

    /**
     * @return string
     */
    public function getCategories(): string
    {
        return $this->categories;
    }

    /**
     * @param string $categories
     */
    public function setCategories(string $categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return bool
     */
    public function getIncludeSubCategories(): bool
    {
        return $this->includeSubCategories;
    }

    /**
     * @param bool $includeSubCategories
     */
    public function setIncludeSubCategories(bool $includeSubCategories)
    {
        $this->includeSubCategories = $includeSubCategories;
    }

    /**
     * @return string
     */
    public function getCategoryCombination(): string
    {
        return $this->categoryCombination;
    }

    /**
     * @param string $categoryCombination
     */
    public function setCategoryCombination(string $categoryCombination)
    {
        $this->categoryCombination = $categoryCombination;
    }

    /**
     * @return string
     */
    public function getSingleRecords(): string
    {
        return $this->singleRecords;
    }

    /**
     * @param string $singleRecords
     */
    public function setSingleRecords(string $singleRecords)
    {
        $this->singleRecords = $singleRecords;
    }

    /**
     * @return bool
     */
    public function getIgnoreWithoutCoordinates(): bool
    {
        return $this->ignoreWithoutCoordinates;
    }

    /**
     * @param bool $ignoreWithoutCoordinates
     */
    public function setIgnoreWithoutCoordinates(bool $ignoreWithoutCoordinates)
    {
        $this->ignoreWithoutCoordinates = $ignoreWithoutCoordinates;
    }
}
