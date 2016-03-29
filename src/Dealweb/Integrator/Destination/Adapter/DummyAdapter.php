<?php
namespace Dealweb\Integrator\Destination\Adapter;

use Dealweb\Integrator\Destination\DestinationInterface;

class DummyAdapter implements DestinationInterface
{
    public function batchWrite($configArray, $values)
    {
        return true;
    }

    public function write($config, $values = [])
    {
        return true;
    }
}