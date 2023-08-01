.. include:: /Includes.rst.txt


.. _tutorial-addressRepository:

Usage of AddressRepository in custom extension
==============================================
If you need to output addresses in your custom extension you can follow this short tutorial.

All you need to do is adopt the following code to your needs:

.. code-block:: php

    <?php

    $addressRepository = GeneralUtility::makeInstance(\FriendsOfTYPO3\TtAddress\Domain\Repository\AddressRepository::class);

    // single selection
    // which means: output addresses in given order
    $demand = new \FriendsOfTYPO3\TtAddress\Domain\Model\Dto\Demand();
    $demand->setSingleRecords('12,34'); // Ids of tt_address records;
    $addresses = $addressRepository->getAddressesByCustomSorting($demand);

    // list action
    $demand = new \FriendsOfTYPO3\TtAddress\Domain\Model\Dto\Demand();
    $demand->setPages(['12']); // list of pages where records are saved
    $demand->setCategories('1,3'); // list of categories desired address records need to be assigned to
    $demand->setCategoryCombination('or'); // combine given categories either by "or" or "and"
    $demand->setSortBy('last_name'); // order field
    $demand->setSortOrder('asc');
    $addresses = $addressRepository->findByDemand($demand);

    $this->view->assign('addresses', $addresses);
