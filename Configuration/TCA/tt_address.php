<?php
$settings = \TYPO3\TtAddress\Utility\SettingsUtility::getSettings();

$version8 = \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_branch) >= \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger('8.0');

$generalLanguageFilePrefix = $version8 ? 'LLL:EXT:lang/Resources/Private/Language/' : 'LLL:EXT:lang/';

return [
    'ctrl' => [
        'label' => 'name',
        'label_alt' => 'email',
        'default_sortby' => 'ORDER BY last_name, first_name, middle_name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'prependAtCopy' => $generalLanguageFilePrefix . 'locallang_general.xml:LGL.prependAtCopy',
        'delete' => 'deleted',
        'title' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address',
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
            'label' => $generalLanguageFilePrefix . 'locallang_general.xml:LGL.hidden',
            'config' => [
                'type' => 'check'
            ]
        ],
        'gender' => [
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.gender',
            'config' => [
                'type' => 'radio',
                'default' => 'm',
                'items' => [
                    ['LLL:EXT:tt_address/locallang_tca.xml:tt_address.gender.m', 'm'],
                    ['LLL:EXT:tt_address/locallang_tca.xml:tt_address.gender.f', 'f']
                ]
            ]
        ],
        'title' => [
            'exclude' => 1,
            'label' => $generalLanguageFilePrefix . 'locallang_general.xml:LGL.title_person',
            'config' => [
                'type' => 'input',
                'size' => '8',
                'eval' => 'trim',
                'max' => '255'
            ]
        ],
        'name' => [
            'label' => $generalLanguageFilePrefix . 'locallang_general.xml:LGL.name',
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
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.first_name',
            'config' => [
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '255'
            ]
        ],
        'middle_name' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.middle_name',
            'config' => [
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '255'
            ]
        ],
        'last_name' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.last_name',
            'config' => [
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '255'
            ]
        ],
        'birthday' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.birthday',
            'config' => [
                'type' => 'input',
                'eval' => 'date',
                'size' => '8',
                'max' => '20'
            ]
        ],
        'address' => [
            'label' => $generalLanguageFilePrefix . 'locallang_general.xml:LGL.address',
            'config' => [
                'type' => 'text',
                'cols' => '20',
                'rows' => '3'
            ]
        ],
        'building' => [
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.building',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'size' => '20',
                'max' => '20'
            ]
        ],
        'room' => [
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.room',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'size' => '5',
                'max' => '15'
            ]
        ],
        'phone' => [
            'label' => $generalLanguageFilePrefix . 'locallang_general.xml:LGL.phone',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'size' => '20',
                'max' => '30'
            ]
        ],
        'fax' => [
            'exclude' => 1,
            'label' => $generalLanguageFilePrefix . 'locallang_general.xml:LGL.fax',
            'config' => [
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '30'
            ]
        ],
        'mobile' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.mobile',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'size' => '20',
                'max' => '30'
            ]
        ],
        'www' => [
            'exclude' => 1,
            'label' => $generalLanguageFilePrefix . 'locallang_general.xml:LGL.www',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'size' => '20',
                'max' => '255',
        'softref' => 'typolink,url',
                'wizards' => [
                    '_PADDING' => 2,
                    'link' => [
                        'type' => 'popup',
                        'title' => 'LLL:EXT:cms/locallang_ttc.xml:header_link_formlabel',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_link.gif',
                        'module' => [
                            'name' => 'wizard_link',
                            'urlParameters' => [
                                'mode' => 'wizard',
                                'act' => 'url|page'
                            ]
                        ],
                        'params' => [
                            'blindLinkOptions' => 'mail,file,spec,folder',
                        ],
                        'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1',
                    ],
                ]
            ]
        ],
        'email' => [
            'label' => $generalLanguageFilePrefix . 'locallang_general.xml:LGL.email',
            'config' => [
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '255',
        'softref' => 'email'
            ]
        ],
        'skype' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.skype',
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
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.twitter',
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
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.facebook',
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
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.linkedin',
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
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.organization',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'size' => '20',
                'max' => '255'
            ]
        ],
        'position' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.position',
            'config' => [
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '255'
            ]
        ],
        'city' => [
            'label' => $generalLanguageFilePrefix . 'locallang_general.xml:LGL.city',
            'config' => [
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '255'
            ]
        ],
        'zip' => [
            'label' => $generalLanguageFilePrefix . 'locallang_general.xml:LGL.zip',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'size' => '10',
                'max' => '20'
            ]
        ],
        'region' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.region',
            'config' => [
                'type' => 'input',
                'size' => '10',
                'eval' => 'trim',
                'max' => '255'
            ]
        ],
        'country' => [
            'exclude' => 1,
            'label' => $generalLanguageFilePrefix . 'locallang_general.xml:LGL.country',
            'config' => [
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '128'
            ]
        ],
        'image' => [
            'exclude' => 1,
            'label' => $generalLanguageFilePrefix . 'locallang_general.xml:LGL.image',
            'config' =>\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'image',
                [
                    'maxitems' => 6,
                    'minitems' => 0,
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:cms/locallang_ttc.xlf:images.addFileReference'
                    ],
                    'foreign_types' => [
                        '0' => [
                            'showitem' => '
								--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
								--palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                            'showitem' => '
								--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
								--palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                            'showitem' => '
								--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
								--palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                            'showitem' => '
								--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
								--palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                            'showitem' => '
								--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
								--palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                            'showitem' => '
								--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
								--palette--;;filePalette'
                        ]
                    ]
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            )
        ],
        'description' => [
            'exclude' => 1,
            'label' => $generalLanguageFilePrefix . 'locallang_general.xml:LGL.description',
            'config' => [
                'type' => 'text',
                'rows' => 5,
                'cols' => 48,
        'softref' => 'typolink_tag,url',
            ]
        ],
        'categories' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_tca.xlf:sys_category.categories',
            'config' => \TYPO3\CMS\Core\Category\CategoryRegistry::getTcaFieldConfiguration('tt_address')
        ],
        'latitude' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.latitude',
            'config' => [
                'type' => 'input',
        'eval' => 'nospace,null',
        'default' => null
            ]
        ],
        'longitude' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.longitude',
            'config' => [
                'type' => 'input',
        'eval' => 'nospace,null',
        'default' => null
            ]
        ],
    ],
    'types' => [
        '0' => ['showitem' =>
            'hidden,
			--palette--;LLL:EXT:tt_address/locallang_tca.xml:tt_address_palette.name;name,
			image, description,
			--div--;LLL:EXT:tt_address/locallang_tca.xml:tt_address_tab.contact,
				--palette--;LLL:EXT:tt_address/locallang_tca.xml:tt_address_palette.address;address,
				--palette--;LLL:EXT:tt_address/locallang_tca.xml:tt_address_palette.building;building,
				--palette--;LLL:EXT:tt_address/locallang_tca.xml:tt_address_palette.organization;organization,
				--palette--;LLL:EXT:tt_address/locallang_tca.xml:tt_address_palette.contact;contact,
				--palette--;LLL:EXT:tt_address/locallang_tca.xml:tt_address_palette.social;social,
			--div--;LLL:EXT:lang/locallang_tca.xlf:sys_category.tabs.category, categories
			']
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
    ]
];
