<?php
namespace Dealweb\Integrator\Source;

use Dealweb\Integrator\Source\Adapter\DummyAdapter;

class SourceFactory
{
    /**
     * @param $className
     * @return SourceInterface
     */
    public static function create($className)
    {
        $className = sprintf('\Dealweb\Integrator\Source\Adapter\%sAdapter', ucfirst($className));
        if (! class_exists($className)) {
            return new DummyAdapter;
        }

        return new $className;
    }
}
