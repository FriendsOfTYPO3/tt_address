<?php
$imageSettings =  [
    'maxitems' => 6,
    'minitems' => 0,
    'appearance' => [
        'collapseAll' => true,
        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
    ],
    'behaviour' => [
        'allowLanguageSynchronization' => true,
    ],
    'overrideChildTca' => [
        'types' => [
            '0' => [
                'showitem' => '
                                    --palette--;' . 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
            ],
            \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                'showitem' => '
                                    --palette--;' . 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
            ],
            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                'showitem' => '
                                    --palette--;' . 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
            ],
            \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                'showitem' => '
                                    --palette--;' . 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
            ],
            \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                'showitem' => '
                                    --palette--;' . 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
            ],
            \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                'showitem' => '
                                    --palette--;' . 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
            ],
        ],
    ],
];
$versionInformation = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Information\Typo3Version::class);
if ($versionInformation->getMajorVersion() > 11) {
    $imageConfiguration = [
        'type' => 'file',
        'maxItems' => 6,
        'appearance' => $imageSettings['appearance'],
        'behaviour' => $imageSettings['behaviour'],
        'overrideChildTca' => $imageSettings['overrideChildTca'],
        'allowed' => \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'], true),
    ];
} else {
    $imageConfiguration = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
        'image',
        $imageSettings,
        $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
    );
}

return [
    'ctrl' => [
        'label' => 'name',
        'label_alt' => 'last_name,first_name,email,pid',
        'label_alt_force' => true,
        'label_userFunc' => \FriendsOfTYPO3\TtAddress\Hooks\Tca\Label::class . '->getAddressLabel',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'sortby' => 'sorting',
        'default_sortby' => 'ORDER BY last_name, first_name, middle_name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'prependAtCopy' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.prependAtCopy',
        'delete' => 'deleted',
        'title' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address',
        'versioningWS' => true,
        'origUid' => 't3_origuid',
        'thumbnail' => 'image',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
            'fe_group' => 'fe_group',
        ],
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
        'iconfile' => 'EXT:tt_address/Resources/Public/Icons/tt_address.svg',
        'searchFields' => 'name, first_name, middle_name, last_name, email',
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
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime'
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
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime'
            ]
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check'
            ]
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:starttime_formlabel',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 16,
                'eval' => 'datetime,int',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:endtime_formlabel',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 16,
                'eval' => 'datetime,int',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'fe_group' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.fe_group',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 5,
                'maxitems' => 20,
                'items' => $versionInformation->getMajorVersion() < 12 ? [
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hide_at_login',
                        -1,
                    ],
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.any_login',
                        -2,
                    ],
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.usergroups',
                        '--div--',
                    ],
                ] : [
                    ['label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hide_at_login', 'value' => -1],
                    ['label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.any_login', 'value' => -2],
                    ['label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.usergroups', 'value' => '--div--'],
                ],
                'exclusiveKeys' => '-1,-2',
                'foreign_table' => 'fe_groups',
                'foreign_table_where' => 'ORDER BY fe_groups.title',
            ],
        ],
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
            ]
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => $versionInformation->getMajorVersion() < 12 ? [
                    ['', 0],
                ] : [
                    ['label' => '', 'value' => 0],
                ],
                'default' => 0,
                'foreign_table' => 'tt_address',
                'foreign_table_where' => 'AND tt_address.pid=###CURRENT_PID### AND tt_address.sys_language_uid IN (-1,0)',
            ]
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
                'default' => ''
            ]
        ],
        'gender' => [
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.gender',
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'config' => [
                'type' => 'radio',
                'default' => '',
                'items' => $versionInformation->getMajorVersion() < 12 ? [
                    ['LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.gender.m', 'm'],
                    ['LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.gender.f', 'f'],
                    ['LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.gender.v', 'v'],
                    ['LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.gender.undefined', '']
                ] : [
                    ['label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.gender.m', 'value' => 'm'],
                    ['label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.gender.f', 'value' => 'f'],
                    ['label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.gender.v', 'value' => 'v'],
                    ['label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.gender.undefined', 'value' => '']
                ]
            ]
        ],
        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.title_person',
            'config' => [
                'type' => 'input',
                'size' => 8,
                'eval' => 'trim',
                'max' => 255,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'title_suffix' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.title_suffix',
            'config' => [
                'type' => 'input',
                'size' => 8,
                'eval' => 'trim',
                'max' => 255,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'slug' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:pages.slug',
            'displayCond' => 'VERSION:IS:false',
            'config' => [
                'type' => 'slug',
                'size' => 50,
                'generatorOptions' => [
                    'fields' => ['first_name', 'middle_name', 'last_name'],
                    'fieldSeparator' => '-',
                    'replacements' => [
                        '/' => '-'
                    ],
                ],
                'fallbackCharacter' => '-',
                'eval' => 'unique',
                'default' => ''
            ]
        ],
        'name' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.name',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'eval' => 'trim',
                'max' => 255
            ]
        ],
        'first_name' => [
            'exclude' => false,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.first_name',
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
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
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
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
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
                'max' => 255
            ]
        ],
        'birthday' => [
            'exclude' => true,
            'l10n_display' => 'defaultAsReadonly',
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.birthday',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'date,int',
                'default' => 0
            ]
        ],
        'address' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.address',
            'config' => [
                'type' => 'text',
                'cols' => 20,
                'rows' => 3,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'building' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.building',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'size' => 20,
                'max' => 255,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'room' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.room',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'size' => 15,
                'max' => 255,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'phone' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.phone',
            'config' => [
                'type' => 'input',
                'eval' => \FriendsOfTYPO3\TtAddress\Evaluation\TelephoneEvaluation::class,
                'size' => 20,
                'max' => 30,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'fax' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.fax',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => \FriendsOfTYPO3\TtAddress\Evaluation\TelephoneEvaluation::class,
                'max' => 30,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'mobile' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.mobile',
            'config' => [
                'type' => 'input',
                'eval' => \FriendsOfTYPO3\TtAddress\Evaluation\TelephoneEvaluation::class,
                'size' => 20,
                'max' => 30,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'www' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.www',
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
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'email' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.email',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim,email',
                'max' => 255,
                'softref' => 'email',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
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
                'placeholder' => 'johndoe',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
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
                'placeholder' => '@johndoe',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
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
                'placeholder' => '/johndoe',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'instagram' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.instagram',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
                'max' => 255,
                'placeholder' => '@johndoe',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'tiktok' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.tiktok',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
                'max' => 255,
                'placeholder' => '@johndoe',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
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
                'placeholder' => 'johndoe',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'company' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.organization',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'size' => 20,
                'max' => 255,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'position' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.position',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
                'max' => 255,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'city' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.city',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
                'max' => 255,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'zip' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.zip',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'size' => 10,
                'max' => 20,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'region' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.region',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim',
                'max' => 255,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'country' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.country',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim',
                'max' => 128,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'image' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.image',
            'config' => $imageConfiguration,
        ],
        'description' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.description',
            'config' => [
                'type' => 'text',
                'rows' => 5,
                'cols' => 48,
                'enableRichtext' => true,
                'richtextConfiguration' => 'default',
                'softref' => 'typolink_tag,url',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'categories' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_category.categories',
            'config' => [
                'type' => 'category'
            ]
        ],
        'latitude' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.latitude',
            'config' => [
                'type' => 'input',
                'eval' => \FriendsOfTYPO3\TtAddress\Evaluation\LatitudeEvaluation::class,
                'default' => null,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'longitude' => [
            'exclude' => true,
            'label' => 'LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address.longitude',
            'config' => [
                'type' => 'input',
                'eval' => \FriendsOfTYPO3\TtAddress\Evaluation\LongitudeEvaluation::class,
                'default' => null,
                'fieldControl' => [
                    'locationMap' => [
                        'renderType' => 'locationMapWizard'
                    ]
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => '
                --palette--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_palette.name;name,
                    image, description,
            --div--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_tab.address,
                --palette--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_palette.address;address,
                --palette--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_palette.coordinates;coordinates,

            --div--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_tab.contact,
                --palette--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_palette.contact;contact,
                --palette--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_palette.organization;organization,
                --palette--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_palette.building;building,
                --palette--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_palette.social;social,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                --palette--;;language,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                --palette--;;paletteHidden,
                --palette--;;paletteAccess,
            --div--;' . 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_category.tabs.category, categories
            '
        ]
    ],
    'palettes' => [
        'name' => [
            'showitem' => 'gender, title, title_suffix,--linebreak--,
                            first_name, middle_name, last_name,--linebreak--,name,--linebreak--,slug'
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
                            linkedin, tiktok, --linebreak--,
                            facebook, instagram'
        ],
        'paletteHidden' => [
            'showitem' => 'hidden',
        ],
        'paletteAccess' => [
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access',
            'showitem' => '
                starttime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:starttime_formlabel,
                endtime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:endtime_formlabel,
                --linebreak--,
                fe_group;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:fe_group_formlabel
            ',
        ],
        'language' => ['showitem' => 'sys_language_uid, l10n_parent'],
    ],
];
