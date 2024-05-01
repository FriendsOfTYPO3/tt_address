<?php
declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\Tests\UnitDeprecated\FormEngine\FieldControl;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use FriendsOfTYPO3\TtAddress\FormEngine\FieldControl\LocationMapWizard;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\TestingFramework\Core\BaseTestCase;

class LocationMapWizardTest extends BaseTestCase
{
    /**
     * @test
     */
    public function languageServiceIsReturned()
    {
        $languageService = $this->getAccessibleMock(LanguageService::class, null, [], '', false, false);
        $GLOBALS['LANG'] = $languageService;

        $subject = $this->getAccessibleMock(LocationMapWizard::class, null, [], '', false);
        $this->assertEquals($languageService, $subject->_call('getLanguageService'));
    }

    /**
     * @test
     */
    public function properResultArrayIsReturned()
    {
        $languageService = $this->getAccessibleMock(LanguageService::class, ['sL'], [], '', false);
        $languageService->expects($this->any())->method('sL')->willReturn('label');

        $subject = $this->getAccessibleMock(LocationMapWizard::class, ['getLanguageService'], [], '', false);
        $subject->expects($this->any())->method('getLanguageService')->willReturn($languageService);

        $data = [
            'databaseRow' => [
                'latitude' => '12.1212',
                'longitude' => '45.1212',
            ],
            'parameterArray' => [
                'itemFormElName' => 'elName',
            ],
        ];
        $subject->_set('data', $data);

        $result = $subject->render();
        $this->assertEquals('location-map-wizard', $result['iconIdentifier']);
    }
}
