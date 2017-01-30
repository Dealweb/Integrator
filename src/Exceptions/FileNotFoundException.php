<?php

namespace Dealweb\Integrator\Exceptions;

use Exception;

class FileNotFoundException extends Exception
{
    protected $message = 'The requested file was not found or is not accessible.';
}
