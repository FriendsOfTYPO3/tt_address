.. include:: /Includes.rst.txt


.. _tutorial-overrideDemand:

Override demand object
======================
It is possible to override the flexform configuration by providing additional GET arguments.

.. important::

   This feature needs to be enabled per plugin with the checkbox *Allow override configuration by GET/POST*.

The following properties are allowed:

- `categories`: List of category ids
- `categoryCombination`: Either the value `or` or `and`
- `includeSubCategories`: Either 0 or 1
- `sortOrder`: Either the value `asc` or `desc`

Example
-------

The following example will create a link which filters the result by the category with uid `1`.

.. code-block:: html

   <f:link.page
      additionalParams="{tx_ttaddress_listview:{override:{categories: 1}}}">
         Category 1
   </f:link.page>
