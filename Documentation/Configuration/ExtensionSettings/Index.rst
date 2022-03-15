.. include:: /Includes.rst.txt


.. _configuration-extension-configuration:

Global extension configuration
==============================

After the installation has been completed, some global configuration can be defined.

If you are using TYPO3 8.7, this configuration can be found in the Extension Manager.
With version 9.5 this has been moved to the Install Tool.

Settings
--------

The following settings are available:

======================================  ==========  =============================================================  =========================
Property:                               Data type:  Description:                                                   Default:
======================================  ==========  =============================================================  =========================
storeBackwardsCompatName                boolean     If set, the field `name` is populated with the values of the   1
                                                    fields `first_name`, `middle_name` and `last_name`.
--------------------------------------  ----------  -------------------------------------------------------------  -------------------------
backwardsCompatFormat                   string      Define the format how the name field is populated.             %1$s %3$s
                                                    Use `%1$s` for the first name, `%2$s` for the middle name
                                                    and `%3$s` for the last name.
--------------------------------------  ----------  -------------------------------------------------------------  -------------------------
readOnlyNameField                       boolean     If set, the name field is set to read only which makes         1
                                                    absolutely sense if the value of the field is populated
                                                    automatically.
======================================  ==========  =============================================================  =========================
