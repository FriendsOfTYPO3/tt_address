.. include:: /Includes.rst.txt
.. _linkhandler:

===========
LinkHandler
===========

The LinkHandler can be configured to create links to the detail view of
tt_address records. If configured editing users can use the
:ref:`LinkBrowser <t3coreapi:LinkBrowser>` in the rich-text-editor and link
fields to link to the detail page of any address record.

Configuration for the backend
=============================

:ref:`Page TSconfig <t3tsconfig:pagetsconfig>` is used to configure the link
browser in the backend. See
:ref:`Setting page TSconfig <t3tsconfig:setting-page-tsconfig>`.

For all available options see :ref:`t3coreapi:linkhandler-pagetsconfig`.

.. code-block:: typoscript
   :caption: EXT:my_sitepackage/Configuration/page.tsconfig

   TCEMAIN.linkHandler {
       # my_address is an identifier, do not change it after links have been created
       my_address {
           handler = TYPO3\CMS\Recordlist\LinkHandler\RecordLinkHandler
           label = Contact
           configuration {
               table = tt_address
               # This storage pid is pre-selected by default
               storagePid = 42
               # Only these folders and their subfolders are displayed in the pagetree
               pageTreeMountPoints = 42, 43, 88
               hidePageTree = 0
           }
           scanAfter = page
           displayBefore = file
       }
   }

Configuration for the frontend
==============================

The links are now stored in the database with the syntax
:html:`<a href="t3://record?identifier=tt_address&amp;uid=456">A link</a>`.
By using TypoScript, these pseudo link is transformed into an actual link.

See :ref:`t3coreapi:linkhandler`.

.. code-block:: typoscript
   :caption: EXT:my_sitepackage/Configuration/TypoScript/setup.typoscript

   config.recordLinks {
       # Use the same identifier as was used in :typoscript:`TCEMAIN.linkHandler`
       my_address {
           typolink {
               # Detail page uid
               parameter = 192
               additionalParams.data = field:uid
               additionalParams.wrap = &tx_ttaddress_listview[action]=show&tx_ttaddress_listview[address]=|&tx_ttaddress_listview[controller]=Address
           }
       }
   }
