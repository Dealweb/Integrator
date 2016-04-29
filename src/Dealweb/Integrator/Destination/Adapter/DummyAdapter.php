<?php
namespace Dealweb\Integrator\Destination\Adapter;

use Dealweb\Integrator\Destination\DestinationInterface;

class DummyAdapter implements DestinationInterface
{
    public function setConfig($config)
    {
        return true;
    }

    public function start()
    {
        return true;
    }

    public function write($values)
    {
        return true;
    }

    public function finish()
    {
        return true;
    }
}