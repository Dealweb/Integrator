<?php
namespace Dealweb\Integrator\File;

use Exception;

class InvalidFileTypeException extends Exception
{
    protected $message = "The provided file type for conversion is not available.";
}