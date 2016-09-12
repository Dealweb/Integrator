<?php
namespace Dealweb\Integrator\Source;

use Dealweb\Integrator\Exceptions\InvalidFileFormatException;

class SourceFactory
{
    /**
     * @param $className
     * @return SourceInterface
     * @throws InvalidFileFormatException
     */
    public static function create($sourceType)
    {
        $className = sprintf('\Dealweb\Integrator\Source\Adapter\%sAdapter', ucfirst($sourceType));

        if (! class_exists($className)) {
            throw new InvalidFileFormatException(
                sprintf("No converter found for your %s source file", ucfirst($sourceType))
            );
        }

        return new $className;
    }
}
