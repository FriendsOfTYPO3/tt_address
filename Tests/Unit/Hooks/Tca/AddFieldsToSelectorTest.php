<?php
declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\Tests\Unit\Hooks\Tca;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Hooks\Tca\AddFieldsToSelector;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\TestingFramework\Core\BaseTestCase;

class AddFieldsToSelectorTest extends BaseTestCase
{
    /**
     * @test
     */
    public function constructorIsCalled()
    {
        $languageService = $this->getAccessibleMock(LanguageService::class, null, [], '', false, false);
        $GLOBALS['LANG'] = $languageService;

        $subject = $this->getAccessibleMock(AddFieldsToSelector::class, null, [], '', true);
        $this->assertEquals($languageService, $subject->_get('languageService'));
    }

    /**
     * @test
     */
    public function optionsAreFilled()
    {
        foreach (AddFieldsToSelector::sortFields as $sortField) {
            $GLOBALS['TCA']['tt_address']['columns'][$sortField]['label'] = 'label_' . $sortField;
        }

        $mockedLanguageService = $this->getAccessibleMock(LanguageService::class, ['sL'], [], '', false);
        $mockedLanguageService->expects($this->any())
            ->method('sL')
            ->will($this->returnCallback(function ($o) {
                return $o;
            }));
        $subject = $this->getAccessibleMock(AddFieldsToSelector::class, null, [], '', false);
        $subject->_set('languageService', $mockedLanguageService);

        $items = [];
        $subject->main($items);

        $expected = [
            'items' => [
                ['label_address', 'address'],
                ['label_birthday', 'birthday'],
                ['label_building', 'building'],
                ['label_city', 'city'],
                ['label_company', 'company'],
                ['label_country', 'country'],
                ['label_email', 'email'],
                ['label_fax', 'fax'],
                ['label_first_name', 'first_name'],
                ['label_gender', 'gender'],
                ['label_last_name', 'last_name'],
                ['label_middle_name', 'middle_name'],
                ['label_mobile', 'mobile'],
                ['label_name', 'name'],
                ['label_phone', 'phone'],
                ['label_region', 'region'],
                ['label_room', 'room'],
                ['label_title', 'title'],
                ['label_www', 'www'],
                ['label_zip', 'zip'],
                ['LLL:EXT:tt_address/Resources/Private/Language/ff/locallang_ff.xlf:pi1_flexform.sortBy.singleSelection', 'singleSelection'],
            ]
        ];

        $this->assertEquals($expected, $items);
    }
}
