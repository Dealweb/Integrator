<?php
namespace Dealweb\Integrator\Destination\Adapter;

use Dealweb\Integrator\Destination\DestinationInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DummyAdapter implements DestinationInterface
{
    public function setConfig($config)
    {
        return true;
    }

    public function start(OutputInterface $output)
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
