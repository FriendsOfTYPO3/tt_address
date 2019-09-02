.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _configuration-extension-routing:

Routing
=======

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
