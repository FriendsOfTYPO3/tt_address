<?php
declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\Tests\Unit\Seo;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Domain\Model\Address;
use FriendsOfTYPO3\TtAddress\Seo\AddressTitleProvider;
use TYPO3\TestingFramework\Core\BaseTestCase;

class AddressTitleProviderTest extends BaseTestCase
{
    /**
     * @test
     * @dataProvider addressTitleProvider
     * @param string $expected
     * @param string[] $addressFields
     * @param array $configuration
     */
    public function correctTitleIsGenerated(string $expected, array $addressFields, array $configuration): void
    {
        $address = new Address();
        foreach ($addressFields as $fieldName => $value) {
            $setter = 'set' . ucfirst($fieldName);
            $address->$setter($value);
        }

        $mockedProvider = $this->getAccessibleMock(AddressTitleProvider::class, null, [], '', false);
        $mockedProvider->setTitle($address, $configuration);

        $this->assertEquals($expected, $mockedProvider->getTitle());
    }

    public function addressTitleProvider(): array
    {
        return [
            'basic example' => [
                'Max Mustermann',
                [
                    'firstName' => 'Max',
                    'middleName' => '',
                    'lastName' => 'Mustermann'
                ],
                [
                    'properties' => 'firstName,middleName,lastName'
                ]
            ],
            'custom clue' => [
                'Max - M. - Mustermann',
                [
                    'firstName' => 'Max',
                    'middleName' => 'M.',
                    'lastName' => 'Mustermann'
                ],
                [
                    'properties' => 'firstName,middleName,lastName',
                    'glue' => '" - "'
                ]
            ],
            'empty custom clue' => [
                'Max M. Mustermann',
                [
                    'firstName' => 'Max',
                    'middleName' => 'M.',
                    'lastName' => 'Mustermann'
                ],
                [
                    'properties' => 'firstName,middleName,lastName',
                    'glue' => ''
                ]
            ]
        ];
    }
}
