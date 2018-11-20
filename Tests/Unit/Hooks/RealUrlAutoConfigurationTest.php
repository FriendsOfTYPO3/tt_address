<?php

namespace FriendsOfTYPO3\TtAddress\Tests\Unit\Hooks\DataHandler;

use FriendsOfTYPO3\TtAddress\Hooks\RealUrlAutoConfiguration;
use TYPO3\TestingFramework\Core\BaseTestCase;

class RealUrlAutoConfigurationTest extends BaseTestCase
{
    /**
     * @test
     */
    public function realurlConfigIsMerged()
    {
        $currentConfig = [
            'abc' => '123',
            'config' => [
                'postVarSets' => [
                    '_DEFAULT' => [
                        'sample' => [
                            [
                                'GETvar' => 'sample[id]',
                            ],
                        ],
                    ]

                ]
            ]
        ];

        $expected = [
            'postVarSets' => [
                '_DEFAULT' => [
                    'sample' => [
                        [
                            'GETvar' => 'sample[id]',
                        ],
                    ],
                    'address-results' => [
                        [
                            'GETvar' => 'tx_ttaddress_listview[@widget_0][currentPage]',
                        ],
                    ],
                    'address' => [
                        [
                            'GETvar' => 'tx_ttaddress_listview[action]',
                            'valueMap' => [
                                'show' => '',
                            ],
                            'noMatch' => 'bypass'
                        ],
                        [
                            'GETvar' => 'tx_ttaddress_listview[controller]',
                            'valueMap' => [
                                'Address' => '',
                            ],
                            'noMatch' => 'bypass'
                        ],
                        [
                            'GETvar' => 'tx_ttaddress_listview[address]',
                            'lookUpTable' => [
                                'table' => 'tt_address',
                                'id_field' => 'uid',
                                'alias_field' => "CONCAT(first_name, '-', last_name)",
                                'useUniqueCache' => 1,
                                'useUniqueCache_conf' => [
                                    'strtolower' => 1,
                                    'spaceCharacter' => '-',
                                ],
                                'languageGetVar' => 'L',
                                'languageExceptionUids' => '',
                                'languageField' => 'sys_language_uid',
                                'transOrigPointerField' => 'l10n_parent',
                            ],
                        ],
                    ],
                ]
            ]
        ];

        $subject = new RealUrlAutoConfiguration();
        $this->assertEquals($expected, $subject->addTtAddressConfig($currentConfig));
    }
}