<?php
namespace Dealweb\Integrator\Source;

interface SourceInterface
{
    /**
     * @param $config
     * @return []
     */
    public function process($config);
}