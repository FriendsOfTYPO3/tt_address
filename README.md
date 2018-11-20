# TYPO3 Extension `tt_address: Address storage and display

[![Build Status](https://travis-ci.org/FriendsOfTYPO3/tt_address.svg?branch=master)](https://travis-ci.org/FriendsOfTYPO3/tt_address)

The basic aim of tt_address is to store your address data. The extension itself
is shipped with a basic fluid/extbase plugin.

The aim of tt_address is not to solve sophisticated address-data problems for you, but
tt_address could be your basic domain-model which you can reuse and extend.
For example if you want nesting of address-records (e.g. "Home" and "Business"), you can do your
own extension, which just references address records 1:n or n:m, whatever you need.
Therefore you can focus on your special address-related problem to solve and have not to think about
the general domain-model for storing an address-record with all its related fields.

The fluid/extbase part comes with a list view and a detail view.
List view is able to select by single records, categories or 'from pages' by waterfall principle.

You can set the sorting order (manual ordering is also possible), deactivate pagination and there is
also a limit option you can set.

Furthermore the category-based selection can be set to logical AND or logical OR.

### Further Notes

At the moment we also ship some ancient code, you might know: tt_address is nearly as old as TYPO3 itself.

For version 4.0.0 we decided to ship the old pibase plugin, but keep in mind you need to actively activate it in
the extensions settings, as we decided to deactivate it by default.

You are encouraged to move along to the new extbase/fluid based plugin, in case you used the pibase part before.


## License

GPL v2+

## Maintenance

The extension is currently maintained by @BastianBalthasarBux and @bmack.
