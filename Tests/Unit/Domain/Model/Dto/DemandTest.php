<?php

declare(strict_types=1);

namespace FriendsOfTypo3\TtAddress\Tests\Unit\Domain\Model\Dto;

/*
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Domain\Model\Dto\Demand;
use TYPO3\TestingFramework\Core\BaseTestCase;

class DemandTest extends BaseTestCase
{
    /** @var Demand */
    protected $subject;

    public function setup(): void
    {
        $this->subject = new Demand();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function pagesCanBeSet()
    {
        $value = ['123', '456'];
        $this->subject->setPages($value);
        self::assertEquals($value, $this->subject->getPages());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function sortByCanBeSet()
    {
        $value = 'title';
        $this->subject->setSortBy($value);
        self::assertEquals($value, $this->subject->getSortBy());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function sortOrderCanBeSet()
    {
        $value = 'desc';
        $this->subject->setSortOrder($value);
        self::assertEquals($value, $this->subject->getSortOrder());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function categoriesCanBeSet()
    {
        $value = '12,34,5';
        $this->subject->setCategories($value);
        self::assertEquals($value, $this->subject->getCategories());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function categoryCombinationCanBeSet()
    {
        $value = 'AND';
        $this->subject->setCategoryCombination($value);
        self::assertEquals($value, $this->subject->getCategoryCombination());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function singleRecordsCanBeSet()
    {
        $value = '7,6,1';
        $this->subject->setSingleRecords($value);
        self::assertEquals($value, $this->subject->getSingleRecords());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function includeSubCategoriesCanBeSet()
    {
        $value = true;
        $this->subject->setIncludeSubCategories($value);
        self::assertEquals($value, $this->subject->getIncludeSubCategories());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function ignoreWithoutCoordinatesCanBeSet()
    {
        $value = true;
        $this->subject->setIgnoreWithoutCoordinates($value);
        self::assertEquals($value, $this->subject->getIgnoreWithoutCoordinates());
    }
}
