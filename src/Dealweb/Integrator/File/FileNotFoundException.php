<?php
namespace Dealweb\Integrator\File;

use Exception;

class FileNotFoundException extends Exception
{
    protected $message = "The requested file was not found or is not accessible.";
}