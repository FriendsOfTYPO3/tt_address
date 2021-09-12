<?php
declare(strict_types=1);

namespace FriendsOfTypo3\TtAddress\Tests\Unit\ViewHelpers;

/**
 * This file is part of the "tt_address" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use FriendsOfTYPO3\TtAddress\Domain\Model\Address;
use FriendsOfTYPO3\TtAddress\ViewHelpers\StaticGoogleMapsViewHelper;
use Prophecy\PhpUnit\ProphecyTrait;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\TestingFramework\Core\BaseTestCase;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

class StaticGoogleMapsViewHelperTest extends BaseTestCase
{
    use ProphecyTrait;

    /**
     * @var StaticGoogleMapsViewHelper
     */
    protected $viewHelper;

    protected function setUp():void
    {
        parent::setUp();
        $this->viewHelper = new StaticGoogleMapsViewHelper();
        $this->viewHelper->initializeArguments();
    }

    /**
     * @test
     * @dataProvider staticGoogleMapsViewHelpersIsCalledDataProvider
     */
    public function staticGoogleMapsViewHelpersIsCalled(array $parameters, $result)
    {
        $actualResult = $this->viewHelper->renderStatic(
            $parameters,
            function () {
            },
            $this->prophesize(RenderingContextInterface::class)->reveal()
        );

        $this->assertEquals($result, $actualResult);
    }

    public function staticGoogleMapsViewHelpersIsCalledDataProvider(): array
    {
        $address1 = new Address();
        $address1->setLatitude(1.1);
        $address1->setLongitude(1.2);

        $address2 = new Address();
        $address2->setLatitude(2.1);
        $address2->setLongitude(2.2);

        $addresses1 = new ObjectStorage();
        $addresses1->attach($address1);

        $addresses2 = new ObjectStorage();
        $addresses2->attach($address1);
        $addresses2->attach($address2);

        return [
            '1 address' => [
                [
                    'parameters' => [
                        'key' => 'abcdefgh',
                        'size' => '300x400',
                    ],
                    'addresses' => $addresses1
                ],
                'https://maps.googleapis.com/maps/api/staticmap?&key=abcdefgh&size=300x400&zoom=13&markers=1.1,1.2'
            ],
            '2 addresses' => [
                [
                    'parameters' => [
                        'key' => 'abcdefgh',
                        'size' => '300x400',
                    ],
                    'addresses' => $addresses2
                ],
                'https://maps.googleapis.com/maps/api/staticmap?&key=abcdefgh&size=300x400&markers=1.1,1.2&markers=2.1,2.2'
            ]
        ];
    }
}
