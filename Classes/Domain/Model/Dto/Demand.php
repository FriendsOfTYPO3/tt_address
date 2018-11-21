<?php

namespace FriendsOfTYPO3\TtAddress\Domain\Model\Dto;

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

    /** @var string */
    protected $categoryCombination = '';

    /** @var string */
    protected $singleRecords = '';

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

}