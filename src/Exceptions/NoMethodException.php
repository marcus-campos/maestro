<?php
/**
 * Created by PhpStorm.
 * User: iwex
 * Date: 10/21/17
 * Time: 5:39 PM
 */

namespace Maestro\Exceptions;

class NoMethodException extends MaestroException
{
    public function __construct()
    {
        parent::__construct('No method defined');
    }
}
