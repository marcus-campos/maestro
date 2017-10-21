<?php
/**
 * Created by PhpStorm.
 * User: iwex
 * Date: 10/21/17
 * Time: 5:39 PM.
 */

namespace Maestro\Exceptions;

class PostCachingException extends MaestroException
{
    public function __construct()
    {
        parent::__construct('Enabling caching is disabled for POST requests');
    }
}
