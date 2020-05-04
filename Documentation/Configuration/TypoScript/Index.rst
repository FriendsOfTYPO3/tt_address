.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _configuration-reference:

Reference
=========

View
----


======================================  ==========  =============================================================  ======================================================
Property:                               Data type:  Description:                                                   Default:
======================================  ==========  =============================================================  ======================================================
view.templateRootPaths                  array       Defines the path where the templates are located.              templateRootPaths {
                                                                                                                    0 = EXT:tt_address/Resources/Private/Templates/
                                                                                                                    1 = {$plugin.tx_ttaddress.view.templateRootPath}
                                                                                                                   }
--------------------------------------  ----------  -------------------------------------------------------------  ------------------------------------------------------
view.partialRootPaths                   array       Defines the path where the partials are located.               partialRootPaths {
                                                                                                                    0 = EXT:tt_address/Resources/Private/Partials/
                                                                                                                    1 = {$plugin.tx_ttaddress.view.partialRootPath}
                                                                                                                   }
--------------------------------------  ----------  -------------------------------------------------------------  ------------------------------------------------------
view.layoutRootPaths                    array       Defines the path where the layouts are located.                layoutRootPaths {
                                                                                                                    0 = EXT:tt_address/Resources/Private/Layouts/
                                                                                                                    1 = {$plugin.tx_ttaddress.view.layoutRootPath}
                                                                                                                   }
======================================  ==========  =============================================================  ======================================================

Settings
--------

======================================  ==========  =============================================================  ======================================================
Property:                               Data type:  Description:                                                   Default:
======================================  ==========  =============================================================  ======================================================
overrideFlexformSettingsIfEmpty         string      A comma separated list of fields which are filled from         paginate.itemsPerPage, singlePid, recursive
                                                    TypoScript if the equivalent flexform field is empty.
                                                    This makes it possible to define default values in TypoScript
--------------------------------------  ----------  -------------------------------------------------------------  ------------------------------------------------------
recursive                               int         Defines how many levels to search for tt_address
                                                    records from the given pages in pidList.
--------------------------------------  ----------  -------------------------------------------------------------  ------------------------------------------------------
paginate                                array       Configuration of the pagination ViewHelper                     paginate {
                                                    which is provided by the core                                    itemsPerPage = 10
                                                                                                                     insertAbove = 0
                                                                                                                     insertBelow = 1
                                                                                                                     maximumNumberOfLinks = 10
                                                                                                                   }
--------------------------------------  ----------  -------------------------------------------------------------  ------------------------------------------------------
map.rendering                           string      Map rendering which is used in the Fronted                     leaflet
                                                    Available: leaflet, googleMaps, staticGoogleMaps                                                               }
--------------------------------------  ----------  -------------------------------------------------------------  ------------------------------------------------------
map.googleMaps.key                      string      Key for variant **Google Maps**
--------------------------------------  ----------  -------------------------------------------------------------  ------------------------------------------------------
map.staticGoogleMaps.parameters         array       Parameters for Static Google Maps configuration
                                                    See: `official docs https://developers.google.com/maps/documentation/maps-static/dev-guide>`_

======================================  ==========  =============================================================  ======================================================

