<?php
$settings = \TYPO3\TtAddress\Utility\SettingsUtility::getSettings();

$version7 = \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_branch) >= \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger('7.0');
$version8 = \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_branch) >= \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger('8.0');

$generalLanguageFilePrefix = $version8 ? 'LLL:EXT:lang/Resources/Private/Language/' : 'LLL:EXT:lang/';

return array(
    'ctrl' => array(
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
        'enablecolumns' => array(
            'disabled' => 'hidden'
        ),
        'iconfile' => $version7 ? 'EXT:tt_address/ext_icon.gif' : \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('tt_address') . 'ext_icon.gif',
        'searchFields' => 'name, first_name, middle_name, last_name, email',
        'dividers2tabs' => 1,
    ),
    'interface' => array(
        'showRecordFieldList' => 'first_name,middle_name,last_name,address,building,room,city,zip,region,country,phone,fax,email,www,title,company,image'
    ),
    'columns' => array(
        'pid' => array(
            'label' => 'pid',
            'config' => array(
                'type' => 'passthrough'
            )
        ),
        'crdate' => array(
            'label' => 'crdate',
            'config' => array(
                'type' => 'passthrough',
            )
        ),
        'cruser_id' => array(
            'label' => 'cruser_id',
            'config' => array(
                'type' => 'passthrough'
            )
        ),
        'tstamp' => array(
            'label' => 'tstamp',
            'config' => array(
                'type' => 'passthrough',
            )
        ),
        'hidden' => array(
            'exclude' => 1,
            'label' => $generalLanguageFilePrefix . 'locallang_general.xml:LGL.hidden',
            'config' => array(
                'type' => 'check'
            )
        ),
        'gender' => array(
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.gender',
            'config' => array(
                'type' => 'radio',
                'default' => 'm',
                'items' => array(
                    array('LLL:EXT:tt_address/locallang_tca.xml:tt_address.gender.m', 'm'),
                    array('LLL:EXT:tt_address/locallang_tca.xml:tt_address.gender.f', 'f')
                )
            )
        ),
        'title' => array(
            'exclude' => 1,
            'label' => $generalLanguageFilePrefix . 'locallang_general.xml:LGL.title_person',
            'config' => array(
                'type' => 'input',
                'size' => '8',
                'eval' => 'trim',
                'max' => '255'
            )
        ),
        'name' => array(
            'label' => $generalLanguageFilePrefix . 'locallang_general.xml:LGL.name',
            'config' => array(
                'type' => 'input',
                'readOnly' => $settings->isReadOnlyNameField(),
                'size' => '40',
                'eval' => 'trim',
                'max' => '255'
            )
        ),
        'first_name' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.first_name',
            'config' => array(
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '255'
            )
        ),
        'middle_name' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.middle_name',
            'config' => array(
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '255'
            )
        ),
        'last_name' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.last_name',
            'config' => array(
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '255'
            )
        ),
        'birthday' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.birthday',
            'config' => array(
                'type' => 'input',
                'eval' => 'date',
                'size' => '8',
                'max' => '20'
            )
        ),
        'address' => array(
            'label' => $generalLanguageFilePrefix . 'locallang_general.xml:LGL.address',
            'config' => array(
                'type' => 'text',
                'cols' => '20',
                'rows' => '3'
            )
        ),
        'building' => array(
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.building',
            'config' => array(
                'type' => 'input',
                'eval' => 'trim',
                'size' => '20',
                'max' => '20'
            )
        ),
        'room' => array(
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.room',
            'config' => array(
                'type' => 'input',
                'eval' => 'trim',
                'size' => '5',
                'max' => '15'
            )
        ),
        'phone' => array(
            'label' => $generalLanguageFilePrefix . 'locallang_general.xml:LGL.phone',
            'config' => array(
                'type' => 'input',
                'eval' => 'trim',
                'size' => '20',
                'max' => '30'
            )
        ),
        'fax' => array(
            'exclude' => 1,
            'label' => $generalLanguageFilePrefix . 'locallang_general.xml:LGL.fax',
            'config' => array(
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '30'
            )
        ),
        'mobile' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.mobile',
            'config' => array(
                'type' => 'input',
                'eval' => 'trim',
                'size' => '20',
                'max' => '30'
            )
        ),
        'www' => array(
            'exclude' => 1,
            'label' => $generalLanguageFilePrefix . 'locallang_general.xml:LGL.www',
            'config' => array(
                'type' => 'input',
                'eval' => 'trim',
                'size' => '20',
                'max' => '255',
		'softref' => 'typolink,url',
                'wizards' => array(
                    '_PADDING' => 2,
                    'link' => array(
                        'type' => 'popup',
                        'title' => 'LLL:EXT:cms/locallang_ttc.xml:header_link_formlabel',
                        'icon' => $version7 ? 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_link.gif' : 'link_popup.gif',
                        'module' => array(
                            'name' => $version7 ? 'wizard_link' : 'wizard_element_browser',
                            'urlParameters' => array(
                                'mode' => 'wizard',
                                'act' => 'url|page'
                            )
                        ),
                        'params' => array(
                            'blindLinkOptions' => 'mail,file,spec,folder',
                        ),
                        'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1',
                    ),
                )
            )
        ),
        'email' => array(
            'label' => $generalLanguageFilePrefix . 'locallang_general.xml:LGL.email',
            'config' => array(
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '255',
		'softref' => 'email'
            )
        ),
        'skype' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.skype',
            'config' => array(
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '255',
                'placeholder' => 'johndoe'
            )
        ),
        'twitter' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.twitter',
            'config' => array(
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '255',
                'placeholder' => '@johndoe'
            )
        ),
        'facebook' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.facebook',
            'config' => array(
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '255',
                'placeholder' => '/johndoe'
            )
        ),
        'linkedin' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.linkedin',
            'config' => array(
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '255',
                'placeholder' => 'johndoe'
            )
        ),
        'company' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.organization',
            'config' => array(
                'type' => 'input',
                'eval' => 'trim',
                'size' => '20',
                'max' => '255'
            )
        ),
        'position' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.position',
            'config' => array(
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '255'
            )
        ),
        'city' => array(
            'label' => $generalLanguageFilePrefix . 'locallang_general.xml:LGL.city',
            'config' => array(
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '255'
            )
        ),
        'zip' => array(
            'label' => $generalLanguageFilePrefix . 'locallang_general.xml:LGL.zip',
            'config' => array(
                'type' => 'input',
                'eval' => 'trim',
                'size' => '10',
                'max' => '20'
            )
        ),
        'region' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.region',
            'config' => array(
                'type' => 'input',
                'size' => '10',
                'eval' => 'trim',
                'max' => '255'
            )
        ),
        'country' => array(
            'exclude' => 1,
            'label' => $generalLanguageFilePrefix . 'locallang_general.xml:LGL.country',
            'config' => array(
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
                'max' => '128'
            )
        ),
        'image' => array(
            'exclude' => 1,
            'label' => $generalLanguageFilePrefix . 'locallang_general.xml:LGL.image',
            'config' =>\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'image',
                array(
                    'maxitems' => 6,
                    'minitems' => 0,
                    'appearance' => array(
                        'createNewRelationLinkTitle' => 'LLL:EXT:cms/locallang_ttc.xlf:images.addFileReference'
                    ),
                    'foreign_types' => array(
                        '0' => array(
                            'showitem' => '
								--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
								--palette--;;filePalette'
                        ),
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => array(
                            'showitem' => '
								--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
								--palette--;;filePalette'
                        ),
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => array(
                            'showitem' => '
								--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
								--palette--;;filePalette'
                        ),
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => array(
                            'showitem' => '
								--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
								--palette--;;filePalette'
                        ),
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => array(
                            'showitem' => '
								--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
								--palette--;;filePalette'
                        ),
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => array(
                            'showitem' => '
								--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
								--palette--;;filePalette'
                        )
                    )
                ),
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            )
        ),
        'description' => array(
            'exclude' => 1,
            'label' => $generalLanguageFilePrefix . 'locallang_general.xml:LGL.description',
            'config' => array(
                'type' => 'text',
                'rows' => 5,
                'cols' => 48,
		'softref' => 'typolink_tag,url',
            )
        ),
        'categories' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_tca.xlf:sys_category.categories',
            'config' => \TYPO3\CMS\Core\Category\CategoryRegistry::getTcaFieldConfiguration('tt_address')
        ),
        'latitude' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.latitude',
            'config' => array(
                'type' => 'input',
		'eval' => 'nospace,null',
		'default' => NULL
            )
        ),
        'longitude' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.longitude',
            'config' => array(
                'type' => 'input',
		'eval' => 'nospace,null',
		'default' => NULL
            )
        ),
    ),
    'types' => array(
        '0' => array('showitem' =>
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
			')
    ),
    'palettes' => array(
        'name' => array(
            'showitem' => 'name, --linebreak--,
							gender, title, --linebreak--,
							first_name, middle_name, --linebreak--,
							last_name',
            'canNotCollapse' => 1
        ),
        'organization' => array(
            'showitem' => 'position, company',
            'canNotCollapse' => 1
        ),
        'address' => array(
            'showitem' => 'address, --linebreak--,
							city, zip, region, --linebreak--,
							country,  --linebreak--,
							latitude, --linebreak--,
							longitude',
            'canNotCollapse' => 1
        ),
        'building' => array(
            'showitem' => 'building, room',
            'canNotCollapse' => 1
        ),
        'contact' => array(
            'showitem' => 'email, --linebreak--,
							phone, fax, --linebreak--,
							mobile, --linebreak--,
							www, --linebreak--,
							birthday',
            'canNotCollapse' => 1
        ),
        'social' => array(
            'showitem' => 'skype, --linebreak--,
							twitter, --linebreak--,
							facebook, --linebreak--,
							linkedin',
            'canNotCollapse' => 1
        ),
    )
);
