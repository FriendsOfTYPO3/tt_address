.. include:: /Includes.rst.txt

.. _routing:
.. _configuration-extension-routing:

===========================
Use Routing to rewrite URLs
===========================

This section will show you how you can rewrite the URLs for tt\_address using
**Routing Enhancers and Aspects**. TYPO3 Explained has a chapter
:ref:`Introduction to routing <t3coreapi:routing-introduction>` that you can read
if you are not familiar with the concept yet. You will no
longer need third party extensions like RealURL or CoolUri to rewrite and
beautify your URLs.

.. _how_to_rewrite_urls:

How to rewrite URLs with address parameters
============================================

On setting up your page you should already have created a **site configuration**.
You can do this in the backend module :guilabel:`Site Managements > Sites`.

Your site configuration will be stored in
:file:`/config/sites/<your_identifier>/config.yaml`. The following
configurations have to be applied to this file.

Any URL parameters can be rewritten with the Routing Enhancers and Aspects.
These are added manually in the :file:`config.yaml`:

#. Add a section :yaml:`routeEnhancers`, if one does not already exist.
#. Choose an unique identifier for your Routing Enhancer. It doesn't have
   to match any extension key.
#. :yaml:`type`: For tt\_address, the Extbase Plugin Enhancer (:yaml:`Extbase`)
   is used.
#. :yaml:`extension`: the extension key, converted to :code:`UpperCamelCase`.
#. :yaml:`plugin`: the plugin name of address is `ListView`, even for the detail
   page.
#. After that you will configure individual routes and aspects depending on
   your use case.

.. code-block:: yaml
   :linenos:
   :caption: :file:`/config/sites/<your_identifier>/config.yaml`

   Address:
    type: Extbase
    limitToPages:
      - 8
      - 10
      - 11
    extension: TtAddress
    plugin: ListView
    routes:
      - routePath: '/{address-title}'
        _controller: 'Address::show'
        _arguments:
          address-title: address
      - routePath: '/{page-label}-{page}'
        _controller: 'Address::list'
        _arguments:
          page: 'currentPage'
    defaultController: 'Address::list'
    aspects:
      address-title:
        type: PersistedAliasMapper
        tableName: tt_address
        routeFieldName: slug
      page:
        type: StaticRangeMapper
        start: '1'
        end: '100'
      page-label:
        type: LocaleModifier
        default: seite
        localeMap:
          - locale: 'en_.*'
            value: page
          - locale: 'it_.*'
            value: pagina
          - locale: 'fr_.*'
            value: page
          - locale: 'es_.*'
            value: pagina

.. tip::

   If your routing does not work as expected, check the **indentation** of your
   configuration blocks.
   Proper indentation is crucial in YAML.

It is recommended to limit :yaml:`routeEnhancers` to the pages where they are needed.
This will speed up performance for building page routes of all other pages.

References
==========

*  :ref:`TYPO3 Documentation: Routing <t3coreapi:routing-introduction>`
*  :ref:`TYPO3 Documentation: Site Handling <t3coreapi:sitehandling>`
