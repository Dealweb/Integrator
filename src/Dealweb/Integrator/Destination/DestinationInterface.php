<?php
namespace Dealweb\Integrator\Destination;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Interface DestinationInterface
 * @package Dealweb\Integrator\Destination
 */
interface DestinationInterface
{
    /**
     * @param $config
     * @return bool
     */
    public function setConfig($config);

    /**
     * @return mixed
     */
    public function start(OutputInterface $output);

    /**
     * @param $values
     * @return mixed
     */
    public function write($values);

    /**
     * @return mixed
     */
    public function finish();
}