.. include:: /Includes.rst.txt


.. _users-manual-installation:


Installation
============

+----------+-----+-----+-----+-----+-----+-----+-----+
|          | 9.x | 8.x | 7.x | 6.x | 5.x | 4.x | 3.x |
+----------+-----+-----+-----+-----+-----+-----+-----+
| TYPO3 13 | yes | no  | no  | no  | no  | no  | no  |
+----------+-----+-----+-----+-----+-----+-----+-----+
| TYPO3 12 | yes | yes | no  | no  | no  | no  | no  |
+----------+-----+-----+-----+-----+-----+-----+-----+
| TYPO3 11 | no  | yes | yes | yes | no  | no  | no  |
+----------+-----+-----+-----+-----+-----+-----+-----+
| TYPO3 10 | no  | no  | no  | yes | yes | no  | no  |
+----------+-----+-----+-----+-----+-----+-----+-----+
| TYPO3 9  | no  | no  | no  | no  | yes | yes | no  |
+----------+-----+-----+-----+-----+-----+-----+-----+
| TYPO3 8  | no  | no  | no  | no  | no  | yes | yes |
+----------+-----+-----+-----+-----+-----+-----+-----+
| TYPO3 7  | no  | no  | no  | no  | no  | no  | yes |
+----------+-----+-----+-----+-----+-----+-----+-----+

.. important::

   Active support is only provided for the latest major version.

The extension needs to be installed as any other extension of TYPO3 CMS:

#. **Get it from the Extension Manager:** Press the “Retrieve/Update”
   button and search for the extension key `tt_address` and import the
   extension from the repository.

#. **Get it from typo3.org:** You can always get current version from
   the `TER`_ by downloading the zip file. Upload
   it directly in the Extension Manager or its unpacked version by FTP.

#. **Use composer**: Run

   .. code-block:: bash

      composer require friendsoftypo3/tt-address

.. _TER: https://extensions.typo3.org/extension/tt_address

Settings -> Extension Configuration offers some basic configuration which is explained
:ref:`here <configuration-extension-configuration>`.

Latest version from git
-----------------------
You can get a copy of the Git repository with the latest version by using the git command:

.. code-block:: bash

   git clone git@github.com:FriendsOfTYPO3/tt_address.git

Preparation: Include static TypoScript
--------------------------------------

The extension ships some TypoScript code which needs to be included.

#. Switch to the root page of your site.

#. Switch to the **TypoScript** module, select your master template file and select *Edit TypoScript Record*.

#. Press the link **Edit the whole TypoScript record** and switch to the tab *Advanced Options*.

#. Select **Addresses (Extbase/Fluid)** at the field *Include TypoScript sets:*
