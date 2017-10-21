<?php
/**
 * User: marcus-campos
 * Date: 02/10/17
 * Time: 10:31.
 */

namespace Maestro;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlMultiHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use Maestro\Exceptions\NoMethodException;
use Maestro\Exceptions\NoUrlException;
use Maestro\Exceptions\PostCachingException;
use Maestro\Http\Methods;

class Rest
{
    use Methods, CachingGetters;

    protected $url;

    protected $assoc = false;

    protected $headers = [];

    private $body = [];

    private $endPoint;

    /** @var \Psr\Http\Message\ResponseInterface */
    private $response;

    /** @var Client */
    private $client;

    /** {boolean} Indicates if caching is turned on */
    protected $cachingEnabled = false;

    /** {string} The response body as a string to make it cachable */
    protected $responseBody;

    /** {number} Time responses will be cached for (if caching is enabled) */
    protected $cacheTime = 60;

    /** {string} Used by APCu */
    private $cacheKey = '';

    public function __construct($client = null)
    {
        $this->client = $client;

        if (!$client) {
            $this->setClient(new Client());
        }
    }

    /**
     * @param $client
     */
    private function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param string $url
     *
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
     *
     * @return $this
     */
    public function setEndPoint(string $endPoint)
    {
        $this->endPoint = $endPoint;

        return $this;
    }
    
    /**
     * @param array $headers
     *
     * @return $this
     */
    public function headers(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }
    
    /**
     * @param array $body
     *
     * @return $this
     */
    public function body(array $body)
    {
        try {
            $this->body = \GuzzleHttp\json_encode($body);
        } catch (\InvalidArgumentException $e) {
            $this->body = $body;
        }

        return $this;
    }
    
    /**
     * Turns on caching of response body for given time.
     *
     * @param int $time - Shelf-life of cached response in seconds
     *
     * @return $this
     * @throws \Maestro\Exceptions\PostCachingException
     */
    public function cachable(int $time = 60)
    {
        if ($this->method === 'POST') {
            throw new PostCachingException();
        }
        $this->cachingEnabled = true;
        $this->cacheTime = $time;

        return $this;
    }
    
    /**
     * Either sends the request or fetches a cached response body dependent on if caching is enabled.
     *
     * @return $this
     * @throws \Maestro\Exceptions\NoUrlException
     * @throws \Maestro\Exceptions\NoMethodException
     */
    public function send()
    {
        if ($this->cachingEnabled) {
            return $this->fetchCachedIfExists();
        }

        // Set the response from a Client Request
        return $this->sendRequest();
    }
    
    /**
     * @return mixed
     * @throws \Maestro\Exceptions\NoUrlException
     * @throws \Maestro\Exceptions\NoMethodException
     */
    private function fetchCachedIfExists()
    {
        // Generate a key to use for caching
        $this->cacheKey = md5($this->url.$this->endPoint);

        // Set the response from APCu cache
        if (apcu_exists($this->cacheKey)) {
            $batch = apcu_fetch($this->cacheKey);
            // Check that expiry date is after now but also check that it is before our current cache time
            // just incase a cache has been created by a previous request with a longer cache time.
            if ($batch['expires'] > time() && $batch['expires'] < $this->makeCacheExpiryTime()) {
                $this->responseBody = $batch['responseBody'];

                return $this;
            }
        }

        return $this->sendRequest();
    }

    /**
     * @return int
     */
    private function makeCacheExpiryTime() : int
    {
        return time() + $this->cacheTime;
    }
    
    /**
     * Sends the request and caches the response is caching is enabled.
     *
     * @return $this
     * @throws \Maestro\Exceptions\NoUrlException
     * @throws \Maestro\Exceptions\NoMethodException
     */
    private function sendRequest()
    {
        if (!$this->method) {
            throw new NoMethodException();
        }
    
        if (!$this->url) {
            throw new NoUrlException();
        }
    
        // GET method doesn't send a BODY
        $paramsToSend = [$this->method, $this->url.$this->endPoint];
        
        if ($this->method !== 'GET') {
            $paramsToSend[] = $this->body;
        }
        
        $request = new Request(...$paramsToSend);

        $this->response = $this->client->send($request);

        $this->cacheResponseBody();

        return $this;
    }

    private function cacheResponseBody()
    {
        if (method_exists($this->response, 'getBody')) {
            $this->responseBody = (string) $this->response->getBody();
        }

        if ($this->cachingEnabled && $this->response->getReasonPhrase() === 'OK') {
            $batch = [
                'expires'      => $this->makeCacheExpiryTime(),
                'responseBody' => $this->responseBody,
            ];
            apcu_store($this->cacheKey, $batch);
        }
    }

    /**
     * @return $this
     */
    public function sendAsync()
    {
        $curl = new CurlMultiHandler();
        $handler = HandlerStack::create($curl);
        $this->setClient(new Client(['handler' => $handler]));

        $request = new Request(
            $this->method,
            $this->url.$this->endPoint,
            $this->headers,
            $this->body
        );

        $this->response = $this->client->sendAsync($request)->then(function ($response) {
            return $response;
        });

        $curl->tick();

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
        if ($this->assoc === true) {
            return json_decode($this->responseBody, true);
        }

        return json_decode($this->responseBody);
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
