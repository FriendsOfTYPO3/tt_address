<?php
declare(strict_types=1);

namespace FriendsOfTypo3\TtAddress\Tests\Unit\Utility;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Utility\TypoScript;
use TYPO3\TestingFramework\Core\BaseTestCase;

class TypoScriptTest extends BaseTestCase
{
    /**
     * @test
     */
    public function tsIsOverloadedCorrectly()
    {
        $subject = new TypoScript();

        $flexforms = [
            'default1' => 'value',
            'default_as_array' => [
                'sub' => 'value sub',
                'sub_array' => [
                    'sub_sub' => 'sub_sub_value'
                ]
            ],
            'override_empty' => '',
            'override_int' => '0',
            'override_not_empty' => 'content_already_here',
            'override_sub' => [
                'sub_empty' => '',
                'sub_full' => 'sub_value',
                'sub_standalone' => 'standalone'
            ],

        ];
        $tsData = [
            'settings' => [
                'overrideFlexformSettingsIfEmpty' => 'override_empty,override_not_existing,override_int_empty,override_not_empty,override_sub.sub_empty,override_sub.sub_full,override_sub.sub_notexisting',
                'override_empty' => 'a_value',
                'override_not_empty' => 'new_content',
                'override_int' => 'int fallback',
                'override_sub' => [
                    'sub_empty' => 'some_value',
                    'sub_full' => 'sub_value_2',
                    'sub_standalone_2' => 'standalone'
                ]
            ]
        ];
        $expected = [
            'default1' => 'value',
            'default_as_array' => [
                'sub' => 'value sub',
                'sub_array' => [
                    'sub_sub' => 'sub_sub_value'
                ]
            ],
            'override_empty' => 'a_value',
            'override_not_empty' => 'content_already_here',
            'override_int' => '0',
            'override_sub' => [
                'sub_empty' => 'some_value',
                'sub_full' => 'sub_value',
                'sub_standalone' => 'standalone'
            ]
        ];

        $this->assertEquals($expected, $subject->override($flexforms, $tsData));
    }
}
