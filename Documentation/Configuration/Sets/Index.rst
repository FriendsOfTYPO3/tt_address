:navigation-title: Site sets
..  include:: /Includes.rst.txt

..  _configuration-extension-sets:

====================================
Site sets provided by EXT:tt_address
====================================

..  versionchanged:: tt_address 9.1.0 /  TYPO3 v13.3
    Site sets were added.

The extension :composer:`friendsoftypo3/tt-address` offers a site set
called "Addresses (Extbase/Fluid)" and has the key `friendsoftypo3/tt-address`.

..  _configuration-extension-sets-usage:

Using the site set "Addresses (Extbase/Fluid)"
==============================================

You can include this :ref:`Site set <t3coreapi:site-sets>` in your site configuration:

*   Got to :guilabel:`Site Management > Sites`.
*   Edit your site configuration.
*   In :guilabel:`General > Sets for this Site` add the site set called
    "Addresses (Extbase/Fluid)".

This changes the YAML file representing your site configuration as follows:

..  literalinclude:: _siteconfig.diff
    :caption: config/sites/my_site/config.yaml (diff)

If you are using a :ref:`custom site package extension <t3sitepackage:start>`
providing a custom Set you can include `friendsoftypo3/tt-address` into your
site packages set:

..  literalinclude:: _setconfig.yaml
    :caption: EXT:my_sitepackage/Configuration/Sets/MySitepackage/config.yaml

..  _configuration-extension-sets-settings:

Settings for site set "Addresses (Extbase/Fluid)"
=================================================

The following settings are available:

..  typo3:site-set-settings:: PROJECT:/Configuration/Sets/Address/settings.definitions.yaml
    :name: address-settings
    :type:
    :Label: Settings of the site set of EXT:tt_address
