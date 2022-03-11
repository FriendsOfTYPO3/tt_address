.. include:: /Includes.rst.txt


.. _development-tests:


Tests
=====

The extension is fully covered by tests which run automatically for each commit and pull requests.
You can find the output of those at https://travis-ci.org/FriendsOfTYPO3/tt_address or also as badge on the https://github.com/FriendsOfTYPO3/tt_address page.

If you want to provide a code change (which is awesome), you can also run those tests on your local environment.

.. note::

    Detailed docs for writing tests for TYPO3 can be found online at https://docs.typo3.org/typo3cms/CoreApiReference/Testing/Index.html

**Requirements**

It is important to know that the site must be set up with composer to make tests working! Use

.. code-block:: bash

    composer require typo3/testing-framework

Unit Tests
----------
Unit tests can be called by the following code:

.. code-block:: bash

    cd typo3conf/ext/tt_address
    ../../../../phpunit -c ./Build/Local/phpunit.xml

    # for specific tests
    ../../../../phpunit -c ./Build/Local/phpunit.xml Tests/Unit/Controller/AddressControllerTest.php

    # Generating coverage (xdebug is required)
    # the report can be found at typo3conf/ext/tt_address/Build/Local/report
    ../../../../phpunit -c ./Build/Local/phpunit.xml --coverage-text --coverage-html=./Build/Local/report

Functional Tests
----------------
Functional tests can be called by the following code:

.. code-block:: bash

    cd typo3conf/ext/tt_address
    export typo3DatabaseName="functional";export typo3DatabaseHost="mysql";export typo3DatabaseUsername="root";export typo3DatabasePassword="dev";../../../../phpunit   -c ./Build/Local/FunctionalTests.xml Tests/Functional

