<?php
defined('TYPO3_MODE') or die();

/* ===========================================================================
  Custom cache, done with the caching framework
=========================================================================== */
if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cache_ttaddress_category'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cache_ttaddress_category'] = [];
}
if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['ttaddress_geocoding'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['ttaddress_geocoding'] = [
        'frontend' => \TYPO3\CMS\Core\Cache\Frontend\VariableFrontend::class,
        'backend'  => \TYPO3\CMS\Core\Cache\Backend\Typo3DatabaseBackend::class,
    ];
}

/* ===========================================================================
  Hooks
=========================================================================== */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['tt_address'] = \FriendsOfTYPO3\TtAddress\Hooks\DataHandler\BackwardsCompatibilityNameFormat::class;

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('realurl')) {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/realurl/class.tx_realurl_autoconfgen.php']['extensionConfiguration']['tt_address'] =
        \FriendsOfTYPO3\TtAddress\Hooks\RealUrlAutoConfiguration::class . '->addTtAddressConfig';
}

/* ===========================================================================
 BEGIN: Add old legacy plugin
=========================================================================== */
$settings = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\FriendsOfTYPO3\TtAddress\Domain\Model\Dto\Settings::class);

if ($settings->isActivatePiBase()) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43(
        'tt_address',
        'Classes/Controller/LegacyPluginController.php',
        '_pi1',
        'list_type',
        true
    );
    // Adds the old legacy plugin to New Content Element wizard
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:tt_address/Configuration/TSconfig/AddLegacyPluginToNewCEWizard.typoscript">');
}

/* ===========================================================================
 END: Add old legacy plugin
=========================================================================== */

// Add wizard with map for setting geo location
$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1546531781] = [
   'nodeName' => 'locationMapWizard',
   'priority' => 30,
   'class' => \FriendsOfTYPO3\TtAddress\FormEngine\FieldControl\LocationMapWizard::class
];

// Adds the new fluid/extbase-plugin to New Content Element wizard
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:tt_address/Configuration/TSconfig/NewContentElementWizard.typoscript">');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'FriendsOfTYPO3.tt_address',
    'ListView',
    [
        'Address' => 'list,show'
    ],
    [
        'Address' => ''
    ]
);

// Register evaluations for TCA
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][\FriendsOfTYPO3\TtAddress\Evaluation\TelephoneEvaluation::class] = '';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][\FriendsOfTYPO3\TtAddress\Evaluation\LatitudeEvaluation::class] = '';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][\FriendsOfTYPO3\TtAddress\Evaluation\LongitudeEvaluation::class] = '';

// Update scripts
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['tt_address_legacyplugintyposcript'] = \FriendsOfTYPO3\TtAddress\Updates\TypoScriptTemplateLocation::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['tt_address_legacyplugintoextbase'] = \FriendsOfTYPO3\TtAddress\Updates\MigrateLegacyPlugin::class;

// Register icons
\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class)->registerIcon(
    'location-map-wizard',
    \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
    ['source' => 'EXT:tt_address/Resources/Public/Contrib/layers-2x.png']
);

$icons = [
    'apps-pagetree-folder-contains-tt-address' => 'page-tree-module.svg',
    'tt-address-plugin' => 'ContentElementWizard.svg',
];
$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
foreach ($icons as $identifier => $path) {
    $iconRegistry->registerIcon(
        $identifier,
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:tt_address/Resources/Public/Icons/' . $path]
    );
}
