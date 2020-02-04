.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _administration-updating:

Updating from 4.x to 5.0.0
--------------------------

.. warning::

	The upgrade wizard to migrate from pibase to extbase plugins have been removed. If you need those, please install versionn 4.x first and upgrade afterwards.

With the version 5.0.0 the TYPO3 versions 9 and 10 are supported which also means that the support for version 8 has been dropped.


Updating from 3.x to 4.0.0
--------------------------
With the version 4.0.0 the code base changed a lot to support TYPO3 8.7 LTS and 9.5 LTS.

The most important changes are:

- Changing the vendor name from `TYPO3` to `FriendsOfTYPO3`
- Usage of Extbase & Fluid. The previous plugin is deprecated and will be removed with version 5.0.0
- Changing the location of TypoScript files of old plugin

An upgrade wizard in the Install Tool makes it possible to upgrade the TypoScript usages.
It migrates `EXT:tt_address/static/pi1` to `EXT:tt_address/Configuration/TypoScript/LegacyPlugin`.

.. warning::

	The upgrade wizard does not update any TS inclusion done in your site package.

Another upgrade wizard migrates old piBase plugins to extbase plugins. The `Template` option is converted to
`Display mode`. So for example your `Template` is `my_template.html`, then

- Add TSconfig `TCEFORM.tt_content.pi_flexform.ttaddress_listview.sDISPLAY.settings\.displayMode.addItems.my_template = My Template`
- Add a section named `displayMode_my_template` in your custom `List.html` fluid template

After execution (via cli) you get a list of custom templates you used in your installation.

Updating from 2.x to 4.0.0
--------------------------

It is **not** possible to upgrade from version 2.x to 4.0 directly! Update first to the latest 3.x version and use
the upgrade wizards to migrate from custom category records to sys_category records and to FAL elements.

