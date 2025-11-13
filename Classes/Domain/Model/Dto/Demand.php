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
    protected array $pages = [];

    protected string $sortBy = '';
    protected string $sortOrder = '';
    protected string $categories = '';
    protected bool $includeSubCategories = false;
    protected string $categoryCombination = '';
    protected string $singleRecords = '';
    protected bool $ignoreWithoutCoordinates = false;

    public function getPages(): array
    {
        return $this->pages;
    }

    public function setPages(array $pages)
    {
        $this->pages = $pages;
    }

    public function getSortBy(): string
    {
        return $this->sortBy;
    }

    public function setSortBy(string $sortBy)
    {
        $this->sortBy = $sortBy;
    }

    public function getSortOrder(): string
    {
        return $this->sortOrder;
    }

    public function setSortOrder(string $sortOrder)
    {
        $this->sortOrder = $sortOrder;
    }

    public function getCategories(): string
    {
        return $this->categories;
    }

    public function setCategories(string $categories)
    {
        $this->categories = $categories;
    }

    public function getIncludeSubCategories(): bool
    {
        return $this->includeSubCategories;
    }

    public function setIncludeSubCategories(bool $includeSubCategories)
    {
        $this->includeSubCategories = $includeSubCategories;
    }

    public function getCategoryCombination(): string
    {
        return $this->categoryCombination;
    }

    public function setCategoryCombination(string $categoryCombination)
    {
        $this->categoryCombination = $categoryCombination;
    }

    public function getSingleRecords(): string
    {
        return $this->singleRecords;
    }

    public function setSingleRecords(string $singleRecords)
    {
        $this->singleRecords = $singleRecords;
    }

    public function getIgnoreWithoutCoordinates(): bool
    {
        return $this->ignoreWithoutCoordinates;
    }

    public function setIgnoreWithoutCoordinates(bool $ignoreWithoutCoordinates)
    {
        $this->ignoreWithoutCoordinates = $ignoreWithoutCoordinates;
    }
}
