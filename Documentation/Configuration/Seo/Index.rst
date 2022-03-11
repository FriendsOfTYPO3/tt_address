.. include:: /Includes.rst.txt


.. _configuration-extension-seo:

SEO
===
This chapter covers all aspects regarding search engine optimization for tt_address.

.. toctree::
    :maxdepth: 2
    :titlesonly:

Routing
-------

Routing can be used to rewrite the URLs for tt\_address. The chapter
:ref:`Use Routing to rewrite URLs <configuration-extension-routing>` is a good
starting point.

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

Meta Tags
---------
Meta Tags are rendered by using the ViewHelper `MetaTagViewHelper`.
.. code-block:: html

    <!-- Example can be found in Partials/MetaTags.html
    <ttaddr:metaTag property="description">{address}</ttaddr:metaTag>

By default the meta tags for `description` and `og:description` are set.
