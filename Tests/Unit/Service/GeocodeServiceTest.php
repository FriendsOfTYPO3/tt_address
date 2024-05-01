<?php
declare(strict_types=1);

namespace FriendsOfTYPO3\TtAddress\Tests\Unit\Service;

use FriendsOfTYPO3\TtAddress\Service\GeocodeService;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\BaseTestCase;

class GeocodeServiceTest extends BaseTestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function validAPiResultIsReturned()
    {
        $content = ['status' => 200, 'CONTENT' => 123];
        $stream = $this->prophesize(StreamInterface::class);
        $stream->getContents()->willReturn(json_encode($content));
        $response = $this->prophesize(ResponseInterface::class);
        $response->getBody()->willReturn($stream);
        $requestFactory = $this->prophesize(RequestFactory::class);
        $requestFactory->request(Argument::cetera())->willReturn($response);

        GeneralUtility::addInstance(RequestFactory::class, $requestFactory->reveal());

        $subject = $this->getAccessibleMock(GeocodeService::class, null, [], '', false);
        $apiResponse = $subject->_call('getApiCallResult', 'http://dummy.com');
        $this->assertEquals($content, $apiResponse);
    }

    /**
     * @test
     */
    public function invalidAPiResultReturnsEmptyArray()
    {
        $content = ['status' => 'OVER_QUERY_LIMIT', 'CONTENT' => 123];
        $stream = $this->prophesize(StreamInterface::class);
        $stream->getContents()->willReturn(json_encode($content));
        $response = $this->prophesize(ResponseInterface::class);
        $response->getBody()->willReturn($stream);
        $requestFactory = $this->prophesize(RequestFactory::class);
        $requestFactory->request(Argument::cetera())->willReturn($response);

        GeneralUtility::addInstance(RequestFactory::class, $requestFactory->reveal());

        $subject = $this->getAccessibleMock(GeocodeService::class, null, [], '', false);
        $apiResponse = $subject->_call('getApiCallResult', 'http://dummy.com');
        $this->assertEquals([], $apiResponse);
    }

    /**
     * @test
     */
    public function wrongCacheThrowsException()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionCode(1548785854);
        $subject = $this->getAccessibleMock(GeocodeService::class, null, [], '', false);
        $subject->_call('initializeCache', 'notExisting');
    }
}
