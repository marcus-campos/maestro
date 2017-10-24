<?php

namespace Maestro\Laravel;

use Illuminate\Support\Facades\Facade as IlluminateFacade;
use Maestro\Rest;

class MaestroLaravelFacade extends IlluminateFacade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return Rest::class;
    }
}
