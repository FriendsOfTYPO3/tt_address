<?php
declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\Command;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Domain\Model\Dto\Settings;
use FriendsOfTYPO3\TtAddress\Service\GeocodeService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Command for geocoding coodinates
 */
class GeocodeCommand extends Command
{

    /** @var GeocodeService */
    protected $geocodeService;

    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->extensionSettings = GeneralUtility::makeInstance(Settings::class);
        $this->geocodeService = GeneralUtility::makeInstance(GeocodeService::class);
    }

    /**
     * Defines the allowed options for this command
     */
    protected function configure()
    {
        $this->setDescription('Geocode tt_address records');
    }

    /**
     * Geocode all records
     *
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = $this->getSymfonyStyle($input, $output);

        if (!$this->extensionSettings->isEnableGeo()) {
            $io->warning('Geo stuff is not enabled for tt_address!');
        } elseif (!$this->extensionSettings->getGoogleMapsKeyGeocoding()) {
            $io->warning('No google maps key configured!');
        } else {
            $this->geocodeService->calculateCoordinatesForAllRecordsInTable();
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return SymfonyStyle
     */
    protected function getSymfonyStyle(InputInterface $input, OutputInterface $output): SymfonyStyle
    {
        return new SymfonyStyle($input, $output);
    }
}
