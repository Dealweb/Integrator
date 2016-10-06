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
     * Sets the configuration for the destination service.
     *
     * @param $config
     * @return bool
     */
    public function setConfig($config);

    /**
     * Starts the destination service.
     *
     * @param OutputInterface $output
     * @return mixed
     */
    public function start(OutputInterface $output);

    /**
     * Writes the load to the destination service.
     *
     * @param $values
     * @return mixed
     */
    public function write($values);

    /**
     * Finishes the destination process.
     *
     * @return bool
     */
    public function finish();
}
