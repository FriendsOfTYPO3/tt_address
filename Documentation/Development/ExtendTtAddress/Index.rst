.. include:: /Includes.rst.txt


.. _development-extend-ttaddress:


Extend models of tt_address
===========================

If an additional field should be added to the `Address` model, there are several ways to achive this.
This documentation prefers the usage of the extension `extender` (https://extensions.typo3.org/extension/extender/) by Sebastian Fischer.

This documentation is divided into the following sections:

- Install EXT:extender
- Setup extension
- Make the field available in backend
- Configuration of EXT:extender
- Use the field in frontend

Install EXT:extender
--------------------
Install the extension `extender` by either retrieve it from https://extensions.typo3.org/extension/extender or from the Extension Manager or use `composer req evoweb/extender`.

Setup extension
---------------
To extend an extension, you need to create an extension. In this example, the extension key will be called `address_field`
and the vendor name will be `GeorgRinger`.

Create the directory `address_field` inside `typo3conf/ext/address_field`.

Create the following files and install the extension in the extension manager.

ext_emconf.php
~~~~~~~~~~~~~~

.. code-block:: php

   <?php

   $EM_CONF[$_EXTKEY] = [
       'title' => 'Extend tt_address',
       'description' => 'New field in tt_address',
       'category' => 'plugin',
       'state' => 'alpha',
       'clearCacheOnLoad' => true,
       'version' => '1.0.0',
       'constraints' => [
           'depends' => [
               'extender' => '',
               'tt_address' => ''
           ],
           'conflicts' => [],
           'suggests' => [],
       ],
   ];

ext_tables.sql
~~~~~~~~~~~~~~

.. code-block:: sql

   # Extend table structure for table 'tt_address'
   CREATE TABLE tt_address (
    quote text NOT NULL,
   );

.. note::

   If you are using composor, don't forget to add the following entry to the PSR-4 section:
   `"GeorgRinger\\AddressField\\": "web/typo3conf/ext/address_field/Classes",` and use `composer dump-autoload`.



Make the field available in backend
-----------------------------------

Create the following files

Configuration/TCA/Overrides/tt_address.php
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: php

   <?php
   defined('TYPO3_MODE') or die();

   $columns = [
       'quote' => [
           'label' => 'A quote',
           'config' => [
               'default' => '',
               'type' => 'text',
           ]
       ]
   ];
   \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_address', $columns);
   \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_address', 'quote', '', 'after:description');
   // use next line to add it to an existing palette
   // \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('tt_address', 'name', 'quote');

.. important::

    Don't forget to clear the caches (preferred in Install Tool) to make the field available in backend.
    The field should appear after the field *Description*.

Configuration of EXT:extender
-----------------------------

Create the following files

Classes/Extending/Domain/Model/Address.php
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
The extension "extender" merges with the one of `tt_address`. Be aware that it is only possible to extend the model by new properties and methods but not to change existing ones!

.. code-block:: php

   <?php

   namespace GeorgRinger\AddressField\Extending\Domain\Model;

   class Address extends \FriendsOfTYPO3\TtAddress\Domain\Model\Address
   {

       /** @var string */
       protected $quote = '';

       /**
        * @return string
        */
       public function getQuote()
       {
           return $this->quote;
       }

       /**
        * @param string $quote
        */
       public function setQuote($quote)
       {
           $this->quote = $quote;
       }
   }

ext_localconf.php
~~~~~~~~~~~~~~~~~

.. code-block:: php

   <?php

   $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['tt_address']['extender'][\FriendsOfTYPO3\TtAddress\Domain\Model\Address::class]['address_field'] = 'Domain/Model/Address';


Use the field in frontend
-------------------------
After clearing the caches again, the new field is now available in the frontend in any template by using `{address.quote}`;
