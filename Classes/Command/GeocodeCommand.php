<?php
declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\Command;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Service\GeocodeService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Command for geocoding coordinates
 */
class GeocodeCommand extends Command
{
    /**
     * Defines the allowed options for this command
     *
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setDescription('Geocode tt_address records')
            ->addArgument(
                'key',
                InputArgument::REQUIRED,
                'Google Maps key for geocoding'
            );
    }

    /**
     * Geocode all records
     *
     * @inheritdoc
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->getGeocodeService($input->getArgument('key'))->calculateCoordinatesForAllRecordsInTable();
        return 0;
    }

    /**
     * @param string $key Google Maps key
     * @return GeocodeService
     */
    protected function getGeocodeService(string $key)
    {
        return GeneralUtility::makeInstance(GeocodeService::class, $key);
    }
}
