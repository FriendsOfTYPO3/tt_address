<?php
$settings = \TYPO3\TtAddress\Utility\SettingsUtility::getSettings();

$version8 = \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_branch) >= \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger('8.0');
$version9 = \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_branch) >= \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger('9.3');

$generalLanguageFilePrefix = $version9 ? 'LLL:EXT:core/Resources/Private/Language/' : ($version8 ? 'LLL:EXT:lang/Resources/Private/Language/' : 'LLL:EXT:lang/');

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
        'iconfile' => 'EXT:tt_address/ext_icon.gif',
        'searchFields' => 'name, first_name, middle_name, last_name, email',
        'dividers2tabs' => 1,
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
            'exclude' => 1,
            'label' => $generalLanguageFilePrefix . 'locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check'
            ]
        ],
        'sys_language_uid' => [
            'exclude' => 1,
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
            'exclude' => 1,
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
                'default' => 'm',
                'items' => [
                    ['LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.gender.m', 'm'],
                    ['LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.gender.f', 'f']
                ]
            ]
        ],
        'title' => [
            'exclude' => 1,
            'label' => $generalLanguageFilePrefix . 'locallang_general.xlf:LGL.title_person',
            'config' => [
                'type' => 'input',
                'size' => '8',
                'eval' => 'trim',
                'max' => '255'
            ]
        ],
        'name' => [
            'label' => $generalLanguageFilePrefix . 'locallang_general.xlf:LGL.name',
            'config' => [
                'type' => 'input',
                'readOnly' => $settings->isReadOnlyNameField(),
                'size' => '40',
                'eval' => 'trim',
                'max' => '255'
            ]
        ],
        'first_name' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.first_name',
            'config' => [
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '255'
            ]
        ],
        'middle_name' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.middle_name',
            'config' => [
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '255'
            ]
        ],
        'last_name' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.last_name',
            'config' => [
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '255'
            ]
        ],
        'birthday' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.birthday',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'size' => '8',
                'default' => 0
            ]
        ],
        'address' => [
            'label' => $generalLanguageFilePrefix . 'locallang_general.xlf:LGL.address',
            'config' => [
                'type' => 'text',
                'cols' => '20',
                'rows' => '3'
            ]
        ],
        'building' => [
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.building',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'size' => '20',
                'max' => '20'
            ]
        ],
        'room' => [
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.room',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'size' => '5',
                'max' => '15'
            ]
        ],
        'phone' => [
            'label' => $generalLanguageFilePrefix . 'locallang_general.xlf:LGL.phone',
            'config' => [
                'type' => 'input',
                'eval' => 'TYPO3\\TtAddress\\Evaluation\\TelephoneEvaluation',
                'size' => '20',
                'max' => '30'
            ]
        ],
        'fax' => [
            'exclude' => 1,
            'label' => $generalLanguageFilePrefix . 'locallang_general.xlf:LGL.fax',
            'config' => [
                'type' => 'input',
                'size' => '20',
                'eval' => 'TYPO3\\TtAddress\\Evaluation\\TelephoneEvaluation',
                'max' => '30'
            ]
        ],
        'mobile' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.mobile',
            'config' => [
                'type' => 'input',
                'eval' => 'TYPO3\\TtAddress\\Evaluation\\TelephoneEvaluation',
                'size' => '20',
                'max' => '30'
            ]
        ],
        'www' => [
            'exclude' => 1,
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
                'size' => '20',
                'max' => '255',
                'softref' => 'typolink,url',
            ],
        ],
        'email' => [
            'label' => $generalLanguageFilePrefix . 'locallang_general.xlf:LGL.email',
            'config' => [
                'type' => 'input',
                'size' => '20',
                'eval' => 'email',
                'max' => '255',
                'softref' => 'email'
            ]
        ],
        'skype' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.skype',
            'config' => [
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '255',
                'placeholder' => 'johndoe'
            ]
        ],
        'twitter' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.twitter',
            'config' => [
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '255',
                'placeholder' => '@johndoe'
            ]
        ],
        'facebook' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.facebook',
            'config' => [
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '255',
                'placeholder' => '/johndoe'
            ]
        ],
        'linkedin' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.linkedin',
            'config' => [
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '255',
                'placeholder' => 'johndoe'
            ]
        ],
        'company' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.organization',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'size' => '20',
                'max' => '255'
            ]
        ],
        'position' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.position',
            'config' => [
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '255'
            ]
        ],
        'city' => [
            'label' => $generalLanguageFilePrefix . 'locallang_general.xlf:LGL.city',
            'config' => [
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '255'
            ]
        ],
        'zip' => [
            'label' => $generalLanguageFilePrefix . 'locallang_general.xlf:LGL.zip',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'size' => '10',
                'max' => '20'
            ]
        ],
        'region' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.region',
            'config' => [
                'type' => 'input',
                'size' => '10',
                'eval' => 'trim',
                'max' => '255'
            ]
        ],
        'country' => [
            'exclude' => 1,
            'label' => $generalLanguageFilePrefix . 'locallang_general.xlf:LGL.country',
            'config' => [
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '128'
            ]
        ],
        'image' => [
            'exclude' => 1,
            'label' => $generalLanguageFilePrefix . 'locallang_general.xlf:LGL.image',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'image',
                [
                    'maxitems' => 6,
                    'minitems' => 0,
                    'appearance' => [
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
            'exclude' => 1,
            'label' => $generalLanguageFilePrefix . 'locallang_general.xlf:LGL.description',
            'config' => [
                'type' => 'text',
                'rows' => 5,
                'cols' => 48,
                'softref' => 'typolink_tag,url',
            ]
        ],
        'categories' => [
            'exclude' => 1,
            'label' => $generalLanguageFilePrefix . 'locallang_tca.xlf:sys_category.categories',
            'config' => \TYPO3\CMS\Core\Category\CategoryRegistry::getTcaFieldConfiguration('tt_address')
        ],
        'latitude' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.latitude',
            'config' => [
                'type' => 'input',
                'eval' => 'null,TYPO3\\TtAddress\\Evaluation\\LatitudeEvaluation',
                'default' => null
            ]
        ],
        'longitude' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.longitude',
            'config' => [
                'type' => 'input',
                'eval' => 'null,TYPO3\\TtAddress\\Evaluation\\LongitudeEvaluation',
                'default' => null
            ]
        ],
    ],
    'types' => [
        '0' => [
            'showitem' =>
                'hidden,
            --palette--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_palette.name;name,
            image, description,
            --div--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_tab.contact,
                --palette--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_palette.address;address,
                --palette--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_palette.building;building,
                --palette--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_palette.organization;organization,
                --palette--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_palette.contact;contact,
                --palette--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_palette.social;social,
            --div--;' . $generalLanguageFilePrefix . 'locallang_tca.xlf:sys_category.tabs.category, categories
            '
        ]
    ],
    'palettes' => [
        'name' => [
            'showitem' => 'name, --linebreak--,
                            gender, title, --linebreak--,
                            first_name, middle_name, --linebreak--,
                            last_name'
        ],
        'organization' => [
            'showitem' => 'position, company'
        ],
        'address' => [
            'showitem' => 'address, --linebreak--,
                            city, zip, region, --linebreak--,
                            country,  --linebreak--,
                            latitude, --linebreak--,
                            longitude'
        ],
        'building' => [
            'showitem' => 'building, room'
        ],
        'contact' => [
            'showitem' => 'email, --linebreak--,
                            phone, fax, --linebreak--,
                            mobile, --linebreak--,
                            www, --linebreak--,
                            birthday'
        ],
        'social' => [
            'showitem' => 'skype, --linebreak--,
                            twitter, --linebreak--,
                            facebook, --linebreak--,
                            linkedin'
        ],
    ],
];
