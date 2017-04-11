<?php

namespace Dealweb\Integrator\Destination;

use Dealweb\Integrator\Exceptions\InvalidFileFormatException;

class DestinationFactory
{
    /**
     * Creates an adapter for the destination type.
     *
     * @param $destinationType
     *
     * @throws InvalidFileFormatException
     *
     * @return DestinationInterface
     */
    public static function create($destinationType)
    {
        $className = sprintf('\Dealweb\Integrator\Destination\Adapter\%sOutput', ucfirst($destinationType));

        if (!class_exists($className)) {
            throw new InvalidFileFormatException(
                sprintf('No converter found for your %s destination file', ucfirst($destinationType))
            );
        }

        return new $className();
    }
}
