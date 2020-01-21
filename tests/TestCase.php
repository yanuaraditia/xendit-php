<?php

namespace Xendit;

//require 'vendor\autoload.php';

/**
 * Class TestCase
 *
 * @package Xendit
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    protected $oriApiBase;
    protected $oriApiKey;
    protected $oriApiVersion;
    protected $clientMock;

    /**
     * Setting up PHPUnit
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->oriApiBase = Xendit::$apiBase;
        //        $this->oriApiKey = Xendit::getApiKey();
        $this->oriApiKey = 'xnd_development_prHUBDfVuOQTxyWTQSNkpj'
            . 'n9OwX9ZSUjdqgF9GenZ6hwhUQkc3NZ9WVexdH';
        $this->oriApiVersion = Xendit::$libVersion;

        $this->clientMock = $this->createMock('\Xendit\HttpClient\ClientInterface');

        ApiRequestor::setGuzzleClient(HttpClient\GuzzleClient::instance());
    }

    /**
     * Restore original values after tests
     *
     * @return void
     */
    protected function tearDown(): void
    {
        Xendit::$apiBase = $this->oriApiBase;
        Xendit::setApiKey($this->oriApiKey);
        Xendit::$libVersion = $this->oriApiVersion;
    }

    /**
     * Request expectations
     *
     * @param string $method  HTTP method
     * @param string $path    relative url
     * @param array  $params  user params
     * @param array  $headers request headers

     * @return void
     */
    protected function expectsRequest(
        $method,
        $path,
        $params = [],
        $headers = []
    ) {
        $this->_prepareRequestMock($method, $path, $params, $headers)
            ->will(
                $this->returnCallback(
                    function ($method, $url, $headers, $params) {
                        $guzzleClient = HttpClient\GuzzleClient::instance();
                        ApiRequestor::setGuzzleClient($guzzleClient);
                        return $guzzleClient->sendRequest(
                            $method,
                            $url,
                            $headers,
                            $params
                        );
                    }
                )
            );
    }

    /**
     * Set up request expectations without actually being emitted
     *
     * @param string $method   HTTP method
     * @param string $path     relative url
     * @param array  $params   user params
     * @param array  $headers  request headers
     * @param array  $response response
     * @param int    $rcode    response code
     *
     * @return void
     */
    protected function stubRequest(
        $method,
        $path,
        $params = [],
        $headers = [],
        $response = [],
        $rcode = 200
    ) {
        $this->_prepareRequestMock($method, $path, $params, $headers)
            ->willReturn([json_encode($response), $rcode, []]);
    }

    /**
     * Prepares client mocker
     *
     * @param string $method  HTTP method
     * @param string $path    relative path
     * @param array  $params  user params
     * @param array  $headers request headers
     *
     * @return \PHPUnit\Framework\MockObject\Builder\InvocationMocker
     */
    private function _prepareRequestMock($method, $path, $params, $headers)
    {
        ApiRequestor::setGuzzleClient($this->clientMock);

        Xendit::setApiKey(
            <<<TAG
xnd_development_prHUBDfVuOQTxyWTQSNkpjn9OwX9ZSUjdqgF9GenZ6hwhUQkc3NZ9WVexdH
TAG
        );

        $url = $path;

        return $this->clientMock
            ->expects($this->once())
            ->method('sendRequest')
            ->with(
                $this->identicalTo(strtoupper($method)),
                $this->identicalTo($url),
                $headers === [] ? $this->anything() : $this->callback(
                    function ($array) use ($headers) {
                        foreach ($headers as $header) {
                            if (!in_array($header, $array)) {
                                return false;
                            }
                        }
                        return true;
                    }
                ),
                $params === [] ? $this->anything() : $this->identicalTo($params)
            );
    }
}
