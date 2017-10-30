<?php
/**
 * User: marcus-campos
 * Date: 24/10/17
 * Time: 12:33.
 */

namespace Maestro\Cache;

use Maestro\Exceptions\PostCachingException;

trait Cache
{
    use CachingGetters;

    /**
     * {number} Time responses will be cached for (if caching is enabled).
     */
    protected $cacheTime = 60;

    /**
     * {string} Used by APCu.
     */
    private $cacheKey = '';

    /**
     * {boolean} Indicates if caching is turned on.
     */
    protected $cachingEnabled = false;

    /**
     * Turns on caching of response body for given time.
     *
     * @param int $time - Shelf-life of cached response in seconds
     *
     * @throws \Maestro\Exceptions\PostCachingException
     *
     * @return $this
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
     * @throws \Maestro\Exceptions\NoUrlException
     * @throws \Maestro\Exceptions\NoMethodException
     *
     * @return mixed
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
    private function makeCacheExpiryTime(): int
    {
        return time() + $this->cacheTime;
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
}
