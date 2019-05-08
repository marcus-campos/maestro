<?php

/**
 * User: marcus-campos
 * Date: 18/10/17
 * Time: 14:24.
 */

namespace Maestro\Http;

trait Methods
{
    private $method;
    
    private $endPoint;

    /**
     * @return $this
     */
    public function get(string $endPoint)
    {
        $this->method = 'GET';
        $this->endPoint = $endPoint;
        return $this;
    }

    /**
     * @return $this
     */
    public function post(string $endPoint)
    {
        $this->method = 'POST';
        $this->endPoint = $endPoint;
        return $this;
    }

    /**
     * @return $this
     */
    public function put(string $endPoint)
    {
        $this->method = 'PUT';
        $this->endPoint = $endPoint;
        return $this;
    }

    /**
     * @return $this
     */
    public function delete(string $endPoint)
    {
        $this->method = 'DELETE';
        $this->endPoint = $endPoint;
        return $this;
    }

    /**
     * @return $this
     */
    public function patch(string $endPoint)
    {
        $this->method = 'PATCH';
        $this->endPoint = $endPoint;
        return $this;
    }

    /**
     * @return $this
     */
    public function copy(string $endPoint)
    {
        $this->method = 'COPY';
        $this->endPoint = $endPoint;
        return $this;
    }

    /**
     * @return $this
     */
    public function head(string $endPoint)
    {
        $this->method = 'HEAD';
        $this->endPoint = $endPoint;
        return $this;
    }

    /**
     * @return $this
     */
    public function options(string $endPoint)
    {
        $this->method = 'OPTIONS';
        $this->endPoint = $endPoint;
        return $this;
    }

    /**
     * @return $this
     */
    public function link(string $endPoint)
    {
        $this->method = 'LINK';
        $this->endPoint = $endPoint;
        return $this;
    }

    /**
     * @return $this
     */
    public function unlink(string $endPoint)
    {
        $this->method = 'UNLINK';
        $this->endPoint = $endPoint;
        return $this;
    }

    /**
     * @return $this
     */
    public function purge(string $endPoint)
    {
        $this->method = 'PURGE';
        $this->endPoint = $endPoint;
        return $this;
    }

    /**
     * @return $this
     */
    public function lock(string $endPoint)
    {
        $this->method = 'LOCK';
        $this->endPoint = $endPoint;
        return $this;
    }

    /**
     * @return $this
     */
    public function unlock(string $endPoint)
    {
        $this->method = 'UNLOCK';
        $this->endPoint = $endPoint;
        return $this;
    }

    /**
     * @return $this
     */
    public function propfind(string $endPoint)
    {
        $this->method = 'PROPFIND';
        $this->endPoint = $endPoint;
        return $this;
    }

    /**
     * @return $this
     */
    public function view(string $endPoint)
    {
        $this->method = 'VIEW';
        $this->endPoint = $endPoint;
        return $this;
    }
}
