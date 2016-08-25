<?php

use PHPUnit\Framework\TestCase;
use Dealweb\Integrator\Source\Adapter\CsvFileAdapter;

class CsvFileAdapterTest extends TestCase
{
    /**
     * @test
     * @expectedException Dealweb\Integrator\Exceptions\InvalidArgumentException
     */
    public function it_throws_exception_if_no_file_path_config_is_defined()
    {
        $csvFileAdapter = new CsvFileAdapter;

        $csvFileAdapterReturn = $csvFileAdapter->process([]);
    }
}