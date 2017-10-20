<?php
/**
 * User: marcus-campos
 * Date: 02/10/17
 * Time: 10:31
 */

namespace Maestro;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlMultiHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use Maestro\Http\Methods;

class Rest
{
    use Methods;

    protected $url;

    protected $assoc = false;

    protected $headers = [];

    private $body = [];

    private $endPoint;

    private $client;

    private $response;


    public function __construct($client = null)
    {
        $this->client = $client;

        if (!$client) {
            $this->setClient((new Client()));
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
        try {
            $this->body = json_encode($body);
        } catch (\Exception $e) {
            $this->body = $body;
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function send()
    {
        if (!$this->method) {
            throw new \InvalidArgumentException('No method defined');
        } elseif (!$this->url) {
            throw new \InvalidArgumentException('No url defined');
        }

        // GET method doesn't send a BODY
        switch ($this->method) {
            case ('GET'):
                $request = new Request(
                    $this->method,
                    $this->url.$this->endPoint,
                    $this->headers
                );
                break;
            default:
                $request = new Request(
                    $this->method,
                    $this->url.$this->endPoint,
                    $this->headers,
                    $this->body
                );
        }

        $this->response = $this->client->send($request);
        return $this;
    }

    /**
     * @return $this
     */
    public function sendAsync()
    {
        $curl = new CurlMultiHandler();
        $handler = HandlerStack::create($curl);
        $this->setClient((new Client(['handler' => $handler])));

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
        if ($this->assoc == true) {
            return json_decode($this->response->getBody(), true);
        }

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
