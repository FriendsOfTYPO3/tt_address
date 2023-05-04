<?php
declare(strict_types=1);

namespace FriendsOfTypo3\TtAddress\Tests\Unit\Domain\Model\Dto;

/**
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

    public function setup():void
    {
        $this->subject = new Demand();
    }

    /**
     * @test
     */
    public function pagesCanBeSet()
    {
        $value = ['123', '456'];
        $this->subject->setPages($value);
        $this->assertEquals($value, $this->subject->getPages());
    }

    /**
     * @test
     */
    public function sortByCanBeSet()
    {
        $value = 'title';
        $this->subject->setSortBy($value);
        $this->assertEquals($value, $this->subject->getSortBy());
    }

    /**
     * @test
     */
    public function sortOrderCanBeSet()
    {
        $value = 'desc';
        $this->subject->setSortOrder($value);
        $this->assertEquals($value, $this->subject->getSortOrder());
    }

    /**
     * @test
     */
    public function categoriesCanBeSet()
    {
        $value = '12,34,5';
        $this->subject->setCategories($value);
        $this->assertEquals($value, $this->subject->getCategories());
    }

    /**
     * @test
     */
    public function categoryCombinationCanBeSet()
    {
        $value = 'AND';
        $this->subject->setCategoryCombination($value);
        $this->assertEquals($value, $this->subject->getCategoryCombination());
    }

    /**
     * @test
     */
    public function singleRecordsCanBeSet()
    {
        $value = '7,6,1';
        $this->subject->setSingleRecords($value);
        $this->assertEquals($value, $this->subject->getSingleRecords());
    }

    /**
     * @test
     */
    public function includeSubCategoriesCanBeSet()
    {
        $value = true;
        $this->subject->setIncludeSubCategories($value);
        $this->assertEquals($value, $this->subject->getIncludeSubCategories());
    }

    /**
     * @test
     */
    public function ignoreWithoutCoordinatesCanBeSet()
    {
        $value = true;
        $this->subject->setIgnoreWithoutCoordinates($value);
        $this->assertEquals($value, $this->subject->getIgnoreWithoutCoordinates());
    }
}
