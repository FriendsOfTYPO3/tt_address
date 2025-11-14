<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\Updates;

use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\AbstractListTypeToCTypeUpdate;

#[UpgradeWizard('ttAddressCTypeMigration')]
final class TtAddressCTypeMigration extends AbstractListTypeToCTypeUpdate
{
    protected function getListTypeToCTypeMapping(): array
    {
        return ['ttaddress_listview' => 'ttaddress_listview'];
    }

    public function getTitle(): string
    {
        return 'Migrate plugins of EXT:tt_address to content elements.';
    }

    public function getDescription(): string
    {
        return 'The "Address" plugin is now registered as content element. Update migrates existing records and backend user permissions.';
    }
}
