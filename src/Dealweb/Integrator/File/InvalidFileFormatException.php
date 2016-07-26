<?php
namespace Dealweb\Integrator\File;

use Exception;

class InvalidFileFormatException extends Exception
{
    protected $message = "The provided file format for conversion is invalid.";
}