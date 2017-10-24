<?php

namespace Maestro\Laravel;

use Illuminate\Support\Facades\Facade as IlluminateFacade;
use Maestro\Rest;

class Facade extends IlluminateFacade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return Rest::class;
    }
}