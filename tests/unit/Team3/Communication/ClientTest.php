<?php
namespace Team3\Communication;

use Buzz\Client\ClientInterface;
use Buzz\Client\Curl;
use Buzz\Exception\RequestException;
use Buzz\Message\Request;
use Buzz\Message\Response;
use Psr\Log\LoggerInterface;
use Team3\Communication\CurlRequestBuilder\CurlRequestBuilderInterface;
use Team3\Configuration\Configuration;
use Team3\Configuration\Credentials\TestCredentials;

/**
 * Class ClientTest
 * @package Team3\Communication
 * @group communication
 */
class ClientTest extends \Codeception\TestCase\Test
{
    const RESPONSE_CONTENT = 'Response content';

    /**
     * @var \UnitTester
     */
    protected $tester;

    public function testResponseContent()
    {
        $client = new ClientAdapter(
            $this->getCurlClient(),
            $this->getCurlRequestBuilder(),
            $this->getLogger()
        );
        $configuration = new Configuration(new TestCredentials());
        $payURequest = $this
            ->getMockBuilder('\Team3\Communication\Request\PayURequestInterface')
            ->getMock();

        $response = $client->sendRequest($configuration, $payURequest);

        $this->assertInstanceOf(
            'Buzz\Message\Response',
            $response
        );

        $this->assertEquals(
            self::RESPONSE_CONTENT,
            $response->getContent()
        );
    }

    /**
     * @expectedException \Team3\Communication\ClientException
     */
    public function testCurlsException()
    {
        $client = new ClientAdapter(
            $this->getCurlClientWithException(),
            $this->getCurlRequestBuilder(),
            $this->getLogger()
        );
        $configuration = new Configuration(new TestCredentials());
        $payURequest = $this
            ->getMockBuilder('\Team3\Communication\Request\PayURequestInterface')
            ->getMock();

        $client->sendRequest($configuration, $payURequest);
    }

    /**
     * @return ClientInterface
     */
    private function getCurlClientWithException()
    {
        $client = $this
            ->getMockBuilder('Buzz\Client\ClientInterface')
            ->getMock();

        $client
            ->expects($this->any())
            ->method('send')
            ->withAnyParameters()
            ->willThrowException(new RequestException());

        return $client;
    }

    /**
     * @return Curl
     */
    private function getCurlClient()
    {
        $client = $this
            ->getMockBuilder('Buzz\Client\ClientInterface')
            ->getMock();

        $client
            ->expects($this->any())
            ->method('send')
            ->willReturnCallback(function (Request $request, Response $response) {
                $response->setContent(self::RESPONSE_CONTENT);
            });

        return $client;
    }

    /**
     * @return CurlRequestBuilderInterface
     */
    private function getCurlRequestBuilder()
    {
        $curlRequest = $this
            ->getMockBuilder('Buzz\Message\Request')
            ->getMock();

        $curlRequestBuilder = $this
            ->getMockBuilder('Team3\Communication\CurlRequestBuilder\CurlRequestBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $curlRequestBuilder
            ->expects($this->any())
            ->method('build')
            ->withAnyParameters()
            ->willReturn($curlRequest);

        return $curlRequestBuilder;
    }

    /**
     * @return LoggerInterface
     */
    private function getLogger()
    {
        return $this
            ->getMockBuilder('Psr\Log\LoggerInterface')
            ->getMock();
    }
}