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
    private $headers = [];
    private $body = [];

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
        $this->method = 'PATCH';
        return $this;
    }

    /**
     * @return $this
     */
    public function copy()
    {
        $this->method = 'COPY';
        return $this;
    }

    /**
     * @return $this
     */
    public function head()
    {
        $this->method = 'HEAD';
        return $this;
    }

    /**
     * @return $this
     */
    public function options()
    {
        $this->method = 'OPTIONS';
        return $this;
    }    
    
    /**
     * @return $this
     */
    public function link()
    {
        $this->method = 'LINK';
        return $this;
    }

    /**
     * @return $this
     */
    public function unlink()
    {
        $this->method = 'UNLINK';
        return $this;
    }

    /**
     * @return $this
     */
    public function purge()
    {
        $this->method = 'PURGE';
        return $this;
    }

    /**
     * @return $this
     */
    public function lock()
    {
        $this->method = 'LOCK';
        return $this;
    }

    /**
     * @return $this
     */
    public function unlock()
    {
        $this->method = 'UNLOCK';
        return $this;
    }

    /**
     * @return $this
     */
    public function propfind()
    {
        $this->method = 'PROPFIND';
        return $this;
    }

    /**
     * @return $this
     */
    public function view()
    {
        $this->method = 'VIEW';
        return $this;
    }

    /**
     * @return $this
     */
    public function headers(array $headers)
    {
        $this->headers = $headers;
        return $this;
    }
    
    /**
     * @return $this
     */
    public function body(array $body)
    {
        $this->body = $body;
        return $this;
    }
    
    /**
     * @return $this
     */
    public function send()
    {
        $client = new Client();
        $request = new Request(
            $this->method,
            $this->url.$this->endPoint,
            $this->headers,
            json_encode($this->body)
        );
        $this->response = $client->send($request);
        return $this;
    }

    /**
     * @return $this
     */
    public function sendAsync()
    {
        $client = new Client();
        $request = new Request($this->method, $this->url.$this->endPoint, $this->headers, $this->body);
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
