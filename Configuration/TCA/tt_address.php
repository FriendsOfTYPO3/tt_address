<?php
$settings = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\FriendsOfTYPO3\TtAddress\Domain\Model\Dto\Settings::class);

$version9 = \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_branch) >= \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger('9.3');

$generalLanguageFilePrefix = $version9 ? 'LLL:EXT:core/Resources/Private/Language/' : 'LLL:EXT:lang/Resources/Private/Language/';

return [
    'ctrl' => [
        'label' => 'name',
        'label_alt' => 'email',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'sortby' => 'sorting',
        'default_sortby' => 'ORDER BY last_name, first_name, middle_name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'prependAtCopy' => $generalLanguageFilePrefix . 'locallang_general.xlf:LGL.prependAtCopy',
        'delete' => 'deleted',
        'title' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address',
        'versioningWS' => true,
        'origUid' => 't3_origuid',
        'thumbnail' => 'image',
        'enablecolumns' => [
            'disabled' => 'hidden'
        ],
        'iconfile' => 'EXT:tt_address/Resources/Public/Icons/tt_address.svg',
        'searchFields' => 'name, first_name, middle_name, last_name, email',
    ],
    'interface' => [
        'showRecordFieldList' => 'first_name,middle_name,last_name,address,building,room,city,zip,region,country,phone,fax,email,www,title,company,image'
    ],
    'columns' => [
        'pid' => [
            'label' => 'pid',
            'config' => [
                'type' => 'passthrough'
            ]
        ],
        'crdate' => [
            'label' => 'crdate',
            'config' => [
                'type' => 'passthrough',
            ]
        ],
        'cruser_id' => [
            'label' => 'cruser_id',
            'config' => [
                'type' => 'passthrough'
            ]
        ],
        'tstamp' => [
            'label' => 'tstamp',
            'config' => [
                'type' => 'passthrough',
            ]
        ],
        'hidden' => [
            'exclude' => true,
            'label' => $generalLanguageFilePrefix . 'locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check'
            ]
        ],
        'sys_language_uid' => [
            'exclude' => true,
            'label' => $generalLanguageFilePrefix . 'locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => [
                    [$generalLanguageFilePrefix . 'locallang_general.xlf:LGL.allLanguages', -1],
                    [$generalLanguageFilePrefix . 'locallang_general.xlf:LGL.default_value', 0],
                ]
            ]
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => true,
            'label' => $generalLanguageFilePrefix . 'locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tt_address',
                'foreign_table_where' => 'AND tt_address.pid=###CURRENT_PID### AND tt_address.sys_language_uid IN (-1,0)',
            ]
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough'
            ]
        ],
        'gender' => [
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.gender',
            'config' => [
                'type' => 'radio',
                'default' => '',
                'items' => [
                    ['LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.gender.m', 'm'],
                    ['LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.gender.f', 'f'],
                    ['LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.gender.undefined', '']
                ]
            ]
        ],
        'title' => [
            'exclude' => true,
            'label' => $generalLanguageFilePrefix . 'locallang_general.xlf:LGL.title_person',
            'config' => [
                'type' => 'input',
                'size' => 8,
                'eval' => 'trim',
                'max' => 255
            ]
        ],
        'name' => [
            'label' => $generalLanguageFilePrefix . 'locallang_general.xlf:LGL.name',
            'config' => [
                'type' => 'input',
                'readOnly' => $settings->isReadOnlyNameField(),
                'size' => 40,
                'eval' => 'trim',
                'max' => 255
            ]
        ],
        'first_name' => [
            'exclude' => false,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.first_name',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
                'max' => 255
            ]
        ],
        'middle_name' => [
            'exclude' => false,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.middle_name',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
                'max' => 255
            ]
        ],
        'last_name' => [
            'exclude' => false,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.last_name',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
                'max' => 255
            ]
        ],
        'birthday' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.birthday',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'date,int',
                'default' => 0
            ]
        ],
        'address' => [
            'label' => $generalLanguageFilePrefix . 'locallang_general.xlf:LGL.address',
            'config' => [
                'type' => 'text',
                'cols' => 20,
                'rows' => 3
            ]
        ],
        'building' => [
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.building',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'size' => 20,
                'max' => 20
            ]
        ],
        'room' => [
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.room',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'size' => 5,
                'max' => 15
            ]
        ],
        'phone' => [
            'label' => $generalLanguageFilePrefix . 'locallang_general.xlf:LGL.phone',
            'config' => [
                'type' => 'input',
                'eval' => \FriendsOfTYPO3\TtAddress\Evaluation\TelephoneEvaluation::class,
                'size' => 20,
                'max' => 30
            ]
        ],
        'fax' => [
            'exclude' => true,
            'label' => $generalLanguageFilePrefix . 'locallang_general.xlf:LGL.fax',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => \FriendsOfTYPO3\TtAddress\Evaluation\TelephoneEvaluation::class,
                'max' => 30
            ]
        ],
        'mobile' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.mobile',
            'config' => [
                'type' => 'input',
                'eval' => \FriendsOfTYPO3\TtAddress\Evaluation\TelephoneEvaluation::class,
                'size' => 20,
                'max' => 30
            ]
        ],
        'www' => [
            'exclude' => true,
            'label' => $generalLanguageFilePrefix . 'locallang_general.xlf:LGL.www',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'fieldControl' => [
                    'linkPopup' => [
                        'options' => [
                            'blindLinkOptions' => 'mail,file,spec,folder',
                        ],
                    ],
                ],
                'eval' => 'trim',
                'size' => 20,
                'max' => 255,
                'softref' => 'typolink,url',
            ],
        ],
        'email' => [
            'label' => $generalLanguageFilePrefix . 'locallang_general.xlf:LGL.email',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'email',
                'max' => 255,
                'softref' => 'email'
            ]
        ],
        'skype' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.skype',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
                'max' => 255,
                'placeholder' => 'johndoe'
            ]
        ],
        'twitter' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.twitter',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
                'max' => 255,
                'placeholder' => '@johndoe'
            ]
        ],
        'facebook' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.facebook',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
                'max' => 255,
                'placeholder' => '/johndoe'
            ]
        ],
        'linkedin' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.linkedin',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
                'max' => 255,
                'placeholder' => 'johndoe'
            ]
        ],
        'company' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.organization',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'size' => 20,
                'max' => 255
            ]
        ],
        'position' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.position',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
                'max' => 255
            ]
        ],
        'city' => [
            'label' => $generalLanguageFilePrefix . 'locallang_general.xlf:LGL.city',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
                'max' => 255
            ]
        ],
        'zip' => [
            'label' => $generalLanguageFilePrefix . 'locallang_general.xlf:LGL.zip',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'size' => 10,
                'max' => 20
            ]
        ],
        'region' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.region',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim',
                'max' => 255
            ]
        ],
        'country' => [
            'exclude' => true,
            'label' => $generalLanguageFilePrefix . 'locallang_general.xlf:LGL.country',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
                'max' => 128
            ]
        ],
        'image' => [
            'exclude' => true,
            'label' => $generalLanguageFilePrefix . 'locallang_general.xlf:LGL.image',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'image',
                [
                    'maxitems' => 6,
                    'minitems' => 0,
                    'appearance' => [
                        'collapseAll' => true,
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
                    ],
                    'overrideChildTca' => [
                        'types' => [
                            '0' => [
                                'showitem' => '
                                    --palette--;' . $generalLanguageFilePrefix . 'locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                                'showitem' => '
                                    --palette--;' . $generalLanguageFilePrefix . 'locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                                'showitem' => '
                                    --palette--;' . $generalLanguageFilePrefix . 'locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                                'showitem' => '
                                    --palette--;' . $generalLanguageFilePrefix . 'locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                                'showitem' => '
                                    --palette--;' . $generalLanguageFilePrefix . 'locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                                'showitem' => '
                                    --palette--;' . $generalLanguageFilePrefix . 'locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
                            ],
                        ],
                    ],
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            )
        ],
        'description' => [
            'exclude' => true,
            'label' => $generalLanguageFilePrefix . 'locallang_general.xlf:LGL.description',
            'config' => [
                'type' => 'text',
                'rows' => 5,
                'cols' => 48,
                'softref' => 'typolink_tag,url',
            ]
        ],
        'categories' => [
            'exclude' => true,
            'label' => $generalLanguageFilePrefix . 'locallang_tca.xlf:sys_category.categories',
            'config' => \TYPO3\CMS\Core\Category\CategoryRegistry::getTcaFieldConfiguration('tt_address')
        ],
        'latitude' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.latitude',
            'config' => [
                'type' => 'input',
                'eval' => 'null,' . \FriendsOfTYPO3\TtAddress\Evaluation\LatitudeEvaluation::class,
                'default' => null
            ]
        ],
        'longitude' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.longitude',
            'config' => [
                'type' => 'input',
                'eval' => 'null,' . \FriendsOfTYPO3\TtAddress\Evaluation\LongitudeEvaluation::class,
                'default' => null
            ]
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => '
                --palette--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_palette.name;name,
                    image, description,
            --div--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_palette.address,
                --palette--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_palette.address;address,
            
            --div--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_tab.contact,
                --palette--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_palette.contact;contact,
                --palette--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_palette.organization;organization,
                --palette--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_palette.building;building,
                --palette--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_palette.social;social,
                --palette--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_palette.coordinates;coordinates,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                --palette--;;paletteHidden,
            --div--;' . $generalLanguageFilePrefix . 'locallang_tca.xlf:sys_category.tabs.category, categories
            '
        ]
    ],
    'palettes' => [
        'name' => [
            'showitem' => 'gender, title, --linebreak--,
                            first_name, middle_name, last_name,--linebreak--,name'
        ],
        'organization' => [
            'showitem' => 'position, company'
        ],
        'address' => [
            'showitem' => 'address, --linebreak--,
                            city, zip, region, --linebreak--,
                            country,  --linebreak--,'
        ],
        'building' => [
            'showitem' => 'building, room'
        ],
        'coordinates' => [
            'showitem' => 'latitude,longitude'
        ],
        'contact' => [
            'showitem' => 'email, --linebreak--,
                            phone, mobile, fax, --linebreak--,
                            www, --linebreak--,
                            birthday'
        ],
        'social' => [
            'showitem' => 'skype, twitter, --linebreak--,
                            facebook, linkedin'
        ],
        'paletteHidden' => [
            'showitem' => '
                hidden
            ',
        ],
    ],
];
