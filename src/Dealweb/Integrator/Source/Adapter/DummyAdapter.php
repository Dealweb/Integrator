<?php
namespace Dealweb\Integrator\Source\Adapter;

use Dealweb\Integrator\Source\SourceInterface;

class DummyAdapter implements SourceInterface
{
    public function process($config)
    {
        return false;
    }
}