.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _configuration-extension-seo:

SEO
===
This chapter covers all aspects regarding search engine optimization for tt_address.

.. toctree::
    :maxdepth: 2
    :titlesonly:

Routing
-------
.. _configuration-extension-routing:

If routing is required for address records, the following configuration will provide a good start for your configuration.

.. code-block:: yaml

  TtAddress:
    type: Extbase
    limitToPages:
      - 70
    extension: TtAddress
    plugin: ListView
    routes:
      -
        routePath: '/{address_title}'
        _controller: 'Address::show'
        _arguments:
          address_title: address
    defaultController: 'Address::list'
    aspects:
      address_title:
        type: PersistedAliasMapper
        tableName: tt_address
        routeFieldName: slug

Custom Page Title
-----------------
If a detail view is rendered, a custom page title provider changes the page title to a specific one provided by the extension.
The following configuration is set by default:

.. code-block:: typoscript

   plugin.tx_ttaddress.settings.seo {
      pageTitle {
          properties = title,firstName,middleName,lastName
          glue = " "
      }
   }

- The setting `properties` is a comma separated list of properties of the `Address` model which should be taken into account (if not empty).
- The setting `glue` defines how the values are combined together.
