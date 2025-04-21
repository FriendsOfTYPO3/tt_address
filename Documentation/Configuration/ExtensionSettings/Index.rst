.. include:: /Includes.rst.txt


.. _configuration-extension-configuration:

Global extension configuration
==============================

After the installation has been completed, some global configuration can be defined.

The configuration is in the Admin Tool -> Settings -> Extension Configuration.

Settings
--------

The following settings are available:

======================================  ==========  =============================================================  =========================
Property:                               Data type:  Description:                                                   Default:
======================================  ==========  =============================================================  =========================
storeBackwardsCompatName                boolean     If set, the field `name` is populated with the values of the   1
                                                    fields `first_name`, `middle_name` and `last_name`.
--------------------------------------  ----------  -------------------------------------------------------------  -------------------------
readOnlyNameField                       boolean     If set, the name field is set to read only which makes         1
                                                    absolutely sense if the value of the field is populated
                                                    automatically.
======================================  ==========  =============================================================  =========================
