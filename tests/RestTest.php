<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 10/18/2017
 * Time: 7:52 PM.
 */

namespace Maestro\Test;

use GuzzleHttp\Client;
use Maestro\Rest;

class RestTest extends TestCase
{
    /**
     * @var
     */
    protected $restClass;

    /**
     * Method setUp.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->restClass = new Rest();
    }

    /**
     * Method testValidRestClass.
     */
    public function testValidRestClass() : void
    {
        $this->assertInstanceOf(Rest::class, $this->restClass);
    }

    /**
     * Method testGetSetUrl.
     */
    public function testGetSetUrl() : void
    {
        $url = 'http://localhost';
        $this->assertInstanceOf(Rest::class, $this->restClass->setUrl($url));
        $this->assertEquals($url, $this->restClass->getUrl());
    }

    /**
     * Method testGetSetEndpoint.
     */
    public function testGetSetEndpoint() : void
    {
        $endpoint = 'endpoint';
        $this->assertInstanceOf(Rest::class, $this->restClass->setEndpoint($endpoint));
        $this->assertEquals($endpoint, $this->restClass->getEndpoint());
    }

    /**
     * Method testHeaders.
     */
    public function testHeaders() : void
    {
        $headers = [
            'http',
        ];

        $this->assertInstanceOf(Rest::class, $this->restClass->headers($headers));
        $this->assertSame($headers, $this->restClass->getHeaders());
    }

    /**
     * Method testBody.
     */
    public function testBody() : void
    {
        $body = [
            'body',
        ];

        $this->assertInstanceOf(Rest::class, $this->restClass->body($body));
    }

    /**
     * Method testSendGet()
     * Assert that the GuzzleClient forwards the request.
     */
    public function testSendGet() : void
    {
        $url = 'https://www.google.com';
        $mock = \Mockery::mock(new Client());
        $mock->shouldReceive('send')
            ->times(1);

        $this->restClass = new Rest($mock);

        $url = 'https://www.google.com';
        $response = $this->restClass
            ->get()
            ->setUrl($url)
            ->send()
            ->getResponse();

        $this->assertSame('GET', $this->restClass->getMethod());
    }

    /**
     * Method testSendNoMethod()
     * Assert that the Rest API raises an exception
     * when no method has been defined.
     */
    public function testSendNoMethod() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->restClass->send();
    }

    /**
     * Method testSendNoUrl()
     * Assert that the Rest API raises an exception
     * when no url has been defined.
     */
    public function testSendNoUrl() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->restClass->get()->send();
    }

    /**
     * Method testSendAsync.
     */
    public function testSendAsync() : void
    {
        $this->markTestIncomplete('To implement');
    }

    /**
     * Method testGetResponse.
     */
    public function testGetResponse() : void
    {
        $expectedReturnValue = 1;
        $mock = \Mockery::mock(new Client());
        $mock->shouldReceive('send')
            ->times(1)
            ->andReturn($expectedReturnValue);

        $this->restClass = new Rest($mock);

        $url = 'https://www.google.com';
        $response = $this->restClass
            ->get()
            ->setUrl($url)
            ->send()
            ->getResponse();

        $this->assertSame($expectedReturnValue, $response);
    }

    /**
     * Method testParse.
     */
    public function testParse() : void
    {
        $mock = \Mockery::mock(new Client());
        $mock->shouldReceive('send')
            ->times(1)
            ->andReturn(new FakeResponse());

        $this->restClass = new Rest($mock);

        $url = 'https://www.google.com';
        $parsedBody = $this->restClass
            ->get()
            ->setUrl($url)
            ->send()
            ->parse();

        $this->assertEquals(json_decode(json_encode(['foo' => 'bar'])), $parsedBody);
    }

    /**
     * Method testAssoc.
     */
    public function testAssoc() : void
    {
        $this->assertInstanceOf(Rest::class, $this->restClass->assoc());
    }
}
