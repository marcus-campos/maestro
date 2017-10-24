<?php

namespace Maestro\Test;

/**
 * FakeResponse class for mocking responses from the Guzzle Client.
 */
class FakeResponse
{
    public function __construct($body = '{"foo":"bar"}')
    {
        $this->body = $body;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getStatusCode()
    {
        return 200;
    }
}
