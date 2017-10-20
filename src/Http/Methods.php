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
}
