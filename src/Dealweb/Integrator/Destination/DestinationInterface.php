<?php
namespace Dealweb\Integrator\Destination;

/**
 * Interface DestinationInterface
 * @package Dealweb\Integrator\Destination
 */
interface DestinationInterface
{
    /**
     * @param $configArray
     * @param $values
     * @return mixed
     */
    public function batchWrite($configArray, $values);

    /**
     * @param $config
     * @param array $values
     * @return mixed
     */
    public function write($config, $values = []);
}