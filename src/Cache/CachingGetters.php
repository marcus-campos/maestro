<?php

namespace Maestro\Cache;

trait CachingGetters
{
    /**
    * Get if cache is enabled
    */
    public function getCachingEnabled()
    {
        return $this->cachingEnabled;
    }

    public function getCacheTime()
    {
        return $this->cacheTime;
    }
}
