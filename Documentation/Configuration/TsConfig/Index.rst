.. include:: /Includes.rst.txt

.. _configuration-extension-tsconfig:

==================
TsConfig Reference
==================

This section covers the configuration options set via Page TsConfig.

Modify record label in backend
==============================

The label of the record is shown in the list module of the backend.
Depending on the use case it can improve the usability to generate the label from
different columns. If `tt_address` is used to collect email addresses,
the email address might be the only filled field. If the records hold information
about contacts of a company, the name should be shown.

By using the configuration `tt_address.label` all use cases can be covered as long
as those are splitted into different pages. A typical example might be:

.. code-block:: typoscript

    tt_address.label = name;last_name,first_name;email

Each label group is divided by `;` and each group can hold a list of field names.

1. In the given example, the value of the field `name` is shown if filled.
2. If the field is empty, the values of the fields `last_name` and `first_name` are shown.
3. If this combination is still empty, the email address is shown
