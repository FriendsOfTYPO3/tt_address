.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _configuration-backend-configuration:

Backend configuration
---------------------

The single address selector features a suggest wizard in order to find addresses quickly. In
installations with a lot of tt_address records (e.g. your team members, but also newsletter
recipients) you might want to limit the wizard's search results to certain pages or to a maximum
number of records. You can use Page TSconfig to configure the search results of the wizard:

::

    TCEFORM.suggest.tt_address {  maxItemsInResultList = 5  pidList = 1 }
