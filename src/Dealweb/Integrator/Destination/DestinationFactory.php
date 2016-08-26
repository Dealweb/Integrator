<?php
namespace Dealweb\Integrator\Destination;

use Dealweb\Integrator\Destination\Adapter\DummyAdapter;

class DestinationFactory
{
    /**
     * @param $className
     * @return DestinationInterface
     */
    public static function create($className)
    {
        $className = sprintf('\Dealweb\Integrator\Destination\Adapter\%sAdapter', ucfirst($className));
        if (! class_exists($className)) {
            return new DummyAdapter;
        }

        return new $className;
    }
}
