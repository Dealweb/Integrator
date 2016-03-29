<?php
namespace Dealweb\Integrator\Source;

interface SourceInterface
{
    /**
     * @param $config
     * @return []
     */
    public static function process($config);
}