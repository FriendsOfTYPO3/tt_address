<?php
declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\Seo;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Domain\Model\Address;
use TYPO3\CMS\Core\PageTitle\AbstractPageTitleProvider;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Generate page title based on properties of the address model
 */
class AddressTitleProvider extends AbstractPageTitleProvider
{
    private const DEFAULT_PROPERTIES = 'firstName,middleName,lastName';
    private const DEFAULT_GLUE = '" "';

    /**
     * @param Address $address
     * @param array $configuration
     */
    public function setTitle(Address $address, array $configuration = []): void
    {
        $titleFields = [];
        $fields = GeneralUtility::trimExplode(',', $configuration['properties'] ?? self::DEFAULT_PROPERTIES, true);

        foreach ($fields as $field) {
            $getter = 'get' . ucfirst($field);
            $value = $address->$getter();
            if ($value) {
                $titleFields[] = $value;
            }
        }
        if (!empty($titleFields)) {
            $glue = isset($configuration['glue']) && !empty($configuration['glue']) ? $configuration['glue'] : self::DEFAULT_GLUE;
            $glue = str_getcsv($glue, '');
            $this->title = implode($glue[0], $titleFields);
        }
    }
}
