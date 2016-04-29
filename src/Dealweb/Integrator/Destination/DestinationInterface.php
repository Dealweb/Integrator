<?php
namespace Dealweb\Integrator\Destination;

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
    public function start();

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