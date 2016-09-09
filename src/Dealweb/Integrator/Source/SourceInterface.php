<?php
namespace Dealweb\Integrator\Source;

interface SourceInterface
{
    /**
     * Process the source input.
     *
     * @param $config
     * @return []
     */
    public function process($config);
}
