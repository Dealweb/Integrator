<?php

namespace Dealweb\Integrator\Destination\Adapter;

use Dealweb\Integrator\Destination\DestinationInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixedWidthFileAdapter implements DestinationInterface
{
    /**
     * @param $config
     * @return bool
     */
    public function setConfig($config)
    {
        // TODO: Implement setConfig() method.
    }

    /**
     * @param OutputInterface $output
     * @return mixed
     */
    public function start(OutputInterface $output)
    {
        // TODO: Implement start() method.
    }

    /**
     * @param $values
     * @return mixed
     */
    public function write($values)
    {
        // TODO: Implement write() method.
    }

    /**
     * @return mixed
     */
    public function finish()
    {
        // TODO: Implement finish() method.
    }
}
