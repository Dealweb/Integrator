<?php
namespace Dealweb\Integrator\Exceptions;

use Exception;

class MissingOptionsException extends Exception
{
    protected $message = 'Missing option on configuration.';

    public static function forOption($option)
    {
        return new self("Missing option on configuration: {$option}");
    }
}
