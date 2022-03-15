.. include:: /Includes.rst.txt


.. _tutorial-templates:

Working with the templates
==========================
The following tutorials describe how to work best with the provided templates

Modify the templates
--------------------
If you want to adopt the templates, copy the ones of `tt_address` to your site package extension (or to fileadmin if
you really need to ...) and provide the path in TypoScript.

.. code-block:: typoscript

    # Use either constants or setup

    # constants:
    plugin.tx_ttaddress.view {
        templateRootPath = path/to/your/tt-address-templates/Templates/
        partialRootPath = path/to/your/tt-address-templates/Partials/
        layoutRootPath = path/to/your/tt-address-templates/Layouts/
    }

    # or setup
    plugin.tx_ttaddress.view {
        templateRootPaths.10 = path/to/your/tt-address-templates/Templates/
        partialRootPaths.10 = path/to/your/tt-address-templates/Partials/
        layoutRootPaths.10 = path/to/your/tt-address-templates/Layouts
    }


Access to current content element data
--------------------------------------
The full data of the current content element can be accessed in the view by checking the variable `contentObjectData`.

.. code-block:: html

    <f:debug inline="1">{contentObjectData}</f:debug>

Section in partial Address
--------------------------
To minimize code duplication the partial `Partials/Address` includes often used parts of the template. Currently there are:

-  `address`: The address itself
-  `position`: The position of the address
-  `contact`: All including contacts and the birthday
-  `social`: All social links

To use one of the partial you just need to call

.. code-block:: html

    <f:render section="social" partial="Address" arguments="{_all}"/>

Templates
---------

.. toctree::
	:maxdepth: 5
	:titlesonly:

	GroupAddressRecords/Index


