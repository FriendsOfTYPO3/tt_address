.. include:: /Includes.rst.txt


.. _users-manual-installation:


Installation
============

+----------+-----+-----+-----+-----+-----+-----+
|          | 8.x | 7.x | 6.x | 5.x | 4.x | 3.x |
+----------+-----+-----+-----+-----+-----+-----+
| TYPO3 12 | yes | yes | no  | no  | no  | no  |
+----------+-----+-----+-----+-----+-----+-----+
| TYPO3 11 | yes | yes | yes | no  | no  | no  |
+----------+-----+-----+-----+-----+-----+-----+
| TYPO3 10 | no  | no  | yes | yes | no  | no  |
+----------+-----+-----+-----+-----+-----+-----+
| TYPO3 9  | no  | no  | no  | yes | yes | no  |
+----------+-----+-----+-----+-----+-----+-----+
| TYPO3 8  | no  | no  | no  | no  | yes | yes |
+----------+-----+-----+-----+-----+-----+-----+
| TYPO3 7  | no  | no  | no  | no  | no  | yes |
+----------+-----+-----+-----+-----+-----+-----+

.. important::

   Active support is only provided for the latest major version.

The extension needs to be installed as any other extension of TYPO3 CMS:

#. **Get it from the Extension Manager:** Press the “Retrieve/Update”
   button and search for the extension key `tt_address` and import the
   extension from the repository.

#. **Get it from typo3.org:** You can always get current version from
   the `TER`_ by downloading either the t3x or zip version. Upload
   the file afterwards in the Extension Manager.

#. **Use composer**: Run

   .. code-block:: bash

      composer require friendsoftypo3/tt-address

.. _TER: https://extensions.typo3.org/extension/tt_address

The Extension Manager offers some basic configuration which is explained
:ref:`here <configuration-extension-configuration>`.

Latest version from git
-----------------------
You can get the latest version from git by using the git command:

.. code-block:: bash

   git clone git@github.com:FriendsOfTYPO3/tt_address.git

Preparation: Include static TypoScript
--------------------------------------

The extension ships some TypoScript code which needs to be included.

#. Switch to the root page of your site.

#. Switch to the **Template module** and select *Info/Modify*.

#. Press the link **Edit the whole template record** and switch to the tab *Includes*.

#. Select **Addresses (Extbase/Fluid)** at the field *Include static (from extensions):*
