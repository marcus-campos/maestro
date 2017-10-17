<?php
/**
 * User: marcus-campos
 * Date: 02/10/17
 * Time: 10:31
 */

namespace Maestro;


use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class Rest
{
    protected $url;
    private $endPoint;
    private $method;
    private $response;
    private $assoc;

    /**
     * @return string
     */
    public function getUrl() :string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndPoint(): string
    {
        return $this->endPoint;
    }

    /**
     * @param string $endPoint
     * @return $this
     */
    public function setEndPoint(string $endPoint)
    {
        $this->endPoint = $endPoint;
        return $this;
    }

    /**
     * @return $this
     */
    public function get()
    {
        $this->method = 'GET';
        return $this;
    }

    /**
     * @return $this
     */
    public function post()
    {
        $this->method = 'POST';
        return $this;
    }

    /**
     * @return $this
     */
    public function put()
    {
        $this->method = 'PUT';
        return $this;
    }

    /**
     * @return $this
     */
    public function delete()
    {
        $this->method = 'DELETE';
        return $this;
    }

    /**
     * @return $this
     */
    public function patch()
    {
        $this->method = 'PATH';
        return $this;
    }

    public function send(array $headers = [], array $body = [])
    {
        $client = new Client();
        $request = new Request(
            $this->method,
            $this->url.$this->endPoint,
            $headers,
            json_encode($body)
        );
        $this->response = $client->send($request);
        return $this;
    }

    /**
     * @param array $headers
     * @param array $body
     * @return $this
     */
    public function sendAsync(array $headers = [], array $body = [])
    {
        $client = new Client();
        $request = new Request($this->method, $this->url.$this->endPoint, $headers, $body);
        $this->response = $promise = $client->sendAsync($request)->then(function ($response) {
            return $response;
        });
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return mixed
     */
    public function parse()
    {
        if($this->assoc == true)
            return json_decode($this->response->getBody(), true);
        else
            return json_decode($this->response->getBody());
    }
    
    /**
     * @return $this
     */
    public function assoc()
    {
        $this->assoc = true;
        return $this;
    }
}
