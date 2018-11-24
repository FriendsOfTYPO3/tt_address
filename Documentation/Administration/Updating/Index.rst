.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _administration-updating:

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

Updating from 2.x to 4.0.0
--------------------------

It is **not** possible to upgrade from version 2.x to 4.0 directly! Update first to the latest 3.x version and use
the upgrade wizards to migrate from custom category records to sys_category records and to FAL elements.

