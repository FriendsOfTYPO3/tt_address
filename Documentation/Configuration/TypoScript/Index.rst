.. include:: /Includes.rst.txt


.. _configuration-reference:

=========
Reference
=========

View
====

.. confval:: view.templateRootPaths

   :Path: plugin.tx_ttaddress
   :type: array

   Defines the path where the templates are located.

   .. code-block:: typoscript
      :caption: Default value

      templateRootPaths {
         0 = EXT:tt_address/Resources/Private/Templates/
         1 = {$plugin.tx_ttaddress.view.templateRootPath}
      }

   **Example:**

   Override the templates, partials and layouts of tt_adress in your own
   extension:

   .. code-block:: typoscript
      :caption: EXT:my_extension/Configuration/TypoScript/setup.typoscript

      plugin.tx_ttaddress {
          view {
              templateRootPaths {
                  42 = EXT:my_extension/Resources/Private/Templates/Address
              }
              partialRootPaths {
                  42 = EXT:my_extension/Resources/Private/Partials/Address
              }
              layoutRootPaths {
                  42 = EXT:my_extension/Resources/Layouts/Templates/Address
              }
          }
      }

.. confval:: view.partialRootPaths

   :Path: plugin.tx_ttaddress
   :type: array

   Defines the path where the partials are located.

   .. code-block:: typoscript
      :caption: Default value

      partialRootPaths {
         0 = EXT:tt_address/Resources/Private/Partials/
         1 = {$plugin.tx_ttaddress.view.partialRootPath}
      }

   Example: See :confval:`view.templateRootPaths`.

.. confval:: view.layoutRootPaths

   :Path: plugin.tx_ttaddress
   :type: array

   Defines the path where the layouts are located.

   .. code-block:: typoscript
      :caption: Default value

      layoutRootPaths {
         0 = EXT:tt_address/Resources/Private/Layouts/
         1 = {$plugin.tx_ttaddress.view.layoutRootPath}
      }

   Example: See :confval:`view.templateRootPaths`.

Settings
========

.. confval:: settings.overrideFlexformSettingsIfEmpty

   :Path: plugin.tx_ttaddress
   :type: string
   :Default: paginate.itemsPerPage, singlePid, recursive

   A comma separated list of fields which are filled from TypoScript if
   the equivalent flexform field is empty. This makes it possible to define
   default values in TypoScript

.. confval:: settings.recursive

   :Path: plugin.tx_ttaddress
   :type: int

   Defines how many levels to search for tt_address records from the given
   pages in pidList.

   **Example:**

   Search in a depth of 4 by default:

   .. code-block:: typoscript
      :caption: EXT:my_sitepackage/Configuration/TypoScript/setup.typoscript

      plugin.tx_ttaddress {
          settings {
              recursive = 4
          }
      }

.. confval:: settings.paginate

   :Path: plugin.tx_ttaddress
   :type: array

   A comma separated list of fields which are filled from TypoScript if
   the equivalent flexform field is empty. This makes it possible to define
   default values in TypoScript

   .. code-block:: typoscript
      :caption: Default value

      paginate {
         # can be overridden by plugin
         itemsPerPage = 10
         insertAbove = 0
         insertBelow = 1
         maximumNumberOfLinks = 10
      }

   **Example:**

   Show 50 items per page by default and the pagination both above and below:

   .. code-block:: typoscript
      :caption: EXT:my_sitepackage/Configuration/TypoScript/setup.typoscript

      plugin.tx_ttaddress {
		    settings.paginate {
		  	     itemsPerPage = 50
		  	     insertAbove = 1
		  	     insertBelow = 1
		    }
      }

.. confval:: settings.map.rendering

   :Path: plugin.tx_ttaddress
   :type: string
   :Default: leaflet

   Map rendering which is used in the Fronted
   Available: leaflet, googleMaps, staticGoogleMaps

   **Example:**

   .. code-block:: typoscript
      :caption: EXT:my_sitepackage/Configuration/TypoScript/setup.typoscript

      plugin.tx_ttaddress {
         settings {
            map {
               googleMaps.key = ABCDEFG123
               rendering = googleMaps
            }
         }
      }

.. confval:: settings.map.googleMaps.key

   :Path: plugin.tx_ttaddress
   :type: string

   Key for variant **Google Maps**

.. confval:: map.staticGoogleMaps.parameters

   :Path: plugin.tx_ttaddress
   :type: array

   Parameters for Static Google Maps configuration
   See: `official docs <https://developers.google.com/maps/documentation/maps-static/dev-guide>`__


   **Example:**

   .. code-block:: typoscript
      :caption: EXT:my_sitepackage/Configuration/TypoScript/setup.typoscript

      plugin.tx_ttaddress {
         settings {
            map {
               rendering = staticGoogleMaps
               staticGoogleMaps.parameters {
                  center = Emanuel-Leutze-Straße 11, 40547 Düsseldorf
                  zoom = 14
                  size = 400x400
                  key = ABCDEFG123
               }
            }
         }
      }
