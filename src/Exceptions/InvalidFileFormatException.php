<?php

namespace Dealweb\Integrator\Exceptions;

use Exception;

class InvalidFileFormatException extends Exception
{
    protected $message = 'The provided file format for conversion is invalid.';
}
