<?php

use PHPUnit\Framework\TestCase;
use Dealweb\Integrator\Source\Adapter\CsvFileInput;

class CsvFileAdapterTest extends TestCase
{
    /**
     * @test
     */
    public function it_throws_exception_if_no_file_path_is_defined()
    {
        // TODO: expect exception Dealweb\Integrator\Exceptions\InvalidFilePathException
        $csvFileAdapter = new CsvFileInput;

        $csvFileAdapter->process([]);
    }
}
