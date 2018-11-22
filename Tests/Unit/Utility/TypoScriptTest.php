<?php

namespace FriendsOfTypo3\TtAddress\Tests\Unit\Utility;

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

        $previousData = [
            'default1' => 'value',
            'default_as_array' => [
                'sub' => 'value sub',
                'sub_array' => [
                    'sub_sub' => 'sub_sub_value'
                ]
            ],
            'override_empty' => '',
            'override_not_empty' => 'content_already_here',
            'override_sub' => [
                'sub_empty' => '',
                'sub_full' => 'sub_value',
                'sub_standalone' => 'standalone'
            ],

        ];
        $tsData = [
            'settings' => [
                'overrideFlexformSettingsIfEmpty' => 'override_empty,override_not_existing,override_not_empty,override_sub.sub_empty,override_sub.sub_full,override_sub.sub_notexisting',
                'override_empty' => 'a_value',
                'override_not_empty' => 'new_content',
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
            'override_sub' => [
                'sub_empty' => 'some_value',
                'sub_full' => 'sub_value',
                'sub_standalone' => 'standalone'
            ]
        ];

        $this->assertEquals($expected, $subject->override($previousData, $tsData));
    }
}
