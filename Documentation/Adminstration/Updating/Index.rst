.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _adminstration-updating:

Updating to tt_address versions 3.0.0
----------------------------------------------

In version 3.0.0, the tt_address group is obsolete. tt_address is now using
sys_category to categorize the tt_address records.

The update script will convert all tt_address groups and creates a corresponding group record
in sys_category table.
