<?php
$settings = \TYPO3\TtAddress\Utility\SettingsUtility::getSettings();

return array(
	'ctrl' => array(
		'label' => 'name',
		'label_alt' => 'email',
		'default_sortby' => 'ORDER BY last_name, first_name, middle_name',
		'tstamp' => 'tstamp',
		'prependAtCopy' => 'LLL:EXT:lang/locallang_general.xml:LGL.prependAtCopy',
		'delete' => 'deleted',
		'title' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address',
		'thumbnail' => 'image',
		'enablecolumns' => array(
			'disabled' => 'hidden'
		),
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('tt_address').'ext_icon.gif',
		'searchFields' => 'name, first_name, middle_name, last_name, email',
		'dividers2tabs' => 1,
	),
	'feInterface' => array(
		'fe_admin_fieldList' => 'pid,hidden,gender,first_name,middle_name,last_name,title,address,building,room,birthday,phone,fax,mobile,www,email,city,zip,company,region,country,image,description'
	),
	'interface' => array(
		'showRecordFieldList' => 'first_name,middle_name,last_name,address,building,room,city,zip,region,country,phone,fax,email,www,title,company,image'
	),
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
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
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.title_person',
			'config' => array(
				'type' => 'input',
				'size' => '8',
				'eval' => 'trim',
				'max' => '255'
			)
		),
		'name' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.name',
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
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.address',
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
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.phone',
			'config' => array(
				'type' => 'input',
				'eval' => 'trim',
				'size' => '20',
				'max' => '30'
			)
		),
		'fax' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.fax',
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
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.www',
			'config' => array(
				'type' => 'input',
				'eval' => 'trim',
				'size' => '20',
				'max' => '255',
				'wizards' => array(
					'_PADDING' => 2,
					'link' => array(
						'type' => 'popup',
						'title' => 'LLL:EXT:cms/locallang_ttc.xml:header_link_formlabel',
						'icon' => 'link_popup.gif',
						'script' => 'browse_links.php?mode=wizard&act=page|url',
						'params' => array(
							'blindLinkOptions' => 'mail,file,spec,folder',
						),
						'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1',
					),
				)
			)
		),
		'email' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.email',
			'config' => array(
				'type' => 'input',
				'size' => '20',
				'eval' => 'trim',
				'max' => '255'
			)
		),
		'skype' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:tt_address/locallang_tca.xml:tt_address.skype',
			'config' => array(
				'type' => 'input',
				'size' => '20',
				'eval' => 'trim',
				'max' => '50',
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
				'max' => '50',
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
				'max' => '50',
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
				'max' => '50',
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
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.city',
			'config' => array(
				'type' => 'input',
				'size' => '20',
				'eval' => 'trim',
				'max' => '255'
			)
		),
		'zip' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.zip',
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
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.country',
			'config' => array(
				'type' => 'input',
				'size' => '20',
				'eval' => 'trim',
				'max' => '128'
			)
		),
		'image' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.image',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'file',
				'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
				'max_size' => '1000',
				'uploadfolder' => 'uploads/pics',
				'show_thumbs' => '1',
				'size' => '3',
				'maxitems' => 6,
				'minitems' => '0'
			)
		),
		'description' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.description',
			'config' => array(
				'type' => 'text',
				'rows' => 5,
				'cols' => 48
			)
		),
		'categories' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_tca.xlf:sys_category.categories',
			'config' => \TYPO3\CMS\Core\Category\CategoryRegistry::getTcaFieldConfiguration('tt_address')
		)
	),
	'types' => array(
		'1' => array('showitem' =>
			'hidden,
			--palette--;LLL:EXT:tt_address/locallang_tca.xml:tt_address_palette.name;name,
			image, description,
			--div--;LLL:EXT:tt_address/locallang_tca.xml:tt_address_tab.contact,
				--palette--;LLL:EXT:tt_address/locallang_tca.xml:tt_address_palette.address;address_usa,
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

		'address_usa' => array(
			'showitem' => 'address, --linebreak--,
							city, zip, region, --linebreak--,
							country',
			'canNotCollapse' => 1
		),

		'address_germany' => array(
			'showitem' => 'address, --linebreak--,
							zip, city, --linebreak--,
							country, region',
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
							www',
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