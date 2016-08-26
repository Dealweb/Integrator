<?php
namespace Dealweb\Integrator\Exceptions;

use Exception;

class InvalidFileTypeException extends Exception
{
    protected $message = "The provided file type for conversion is not available.";
}
